<?php
/**
 * Robert Deutz Business Solution
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @version $Id: config.php 6 2010-01-23 16:01:49Z deutz $
 * @package RDBS_Config
 * @copyright Copyright (C) 2007 Robert Deutz Business Solution
 * 
 **/

/** ensure this file is being included by a parent file */
defined('BABIOON') or die('Restricted access');

require_once(dirname(__FILE__).DS.'controller.php');

// leagcy
class RdbsConfig extends BabioonConfig {};

/**
 * BabioonConfig
 */
class BabioonConfig
{
	private static $instances = array();
	
	private static $controller = array();

	private $type 	= '';
	
	private $table 	= null;
	
	private $params = '';
	
	private $component = null;
	

	/**
	 * protect the constructor
	 */
	protected function __construct($type,$table,$scope)
	{
		$this->type 		= $type;
		$this->table 		= $table;
		$this->component 	= $scope;
		$this->db			= BabioonFactory::getDBO();
		$this->params 		= $this->loadParams($type);
	}

	/**
	 * cloning is not allowed
	 */
	private function __clone() { }

	function get($key, $default = '', $group = '_default')
	{
		return $this->params->get($key,$default,$group);
	}

	function def($key, $default = '', $group = '_default')
	{
		return $this->params->def($key, $default,$group);
	}
	
	function getComponent()
	{
		return 'com_'.$this->component;		
	}
	
	function getController()
	{
		if (!isset($this->controller[$this->component.'_'.$this->type]))
		{
			$this->controller[$this->component.'_'.$this->type] = new BabioonControllerConfig();
		}
		return $this->controller[$this->component.'_'.$this->type];
		
	}
	
	function getInstance($type='system',$table=null,$component=null)
	{
		if (is_null($component))
		{
			$app 	= BabioonFactory::getApplication();
			$scope	= substr($app->scope,4);
		}
		else
		{
			if (strpos($component,'com_') !== false)
			{
				$scope	= substr($component,4);	
			}
			else 
			{
				$scope = $component;
			}
		}
		$index	= $scope.'_'.$type;
		if (!isset(self::$instances[$index]))
		{
			if (is_null($table))
			{
				$table = '#__'.$scope;
			}
			self::$instances[$index] = new BabioonConfig($type,$table,$scope);
		}
		return self::$instances[$index];
	}

	function getParamsObj()
	{
		return $this->params;
	}

	function getIcons($link=null)
	{
		$html='';
		$this->db->setQuery("SELECT * FROM $this->table ORDER BY control");
		
		$result	= $this->db->loadObjectList();
		$rc		= count($result);
		if( $rc != 0 )
		{
			if (is_null($link))
			{
				$link = 'index.php?option='.$this->getComponent().'&amp;section=config&amp;task=';
			}
			$base = JURI::base(true). '/components/'.$this->getComponent().'/libraries/images/cpanel';
			for($i=0;$i<$rc;$i++)
			{
				$elm	= $result[$i];
				$sizex	= '500';
				$sizey	= '400'; 
				if(property_exists($elm,'sizex') && trim($elm->sizex) != '')
				{
					$sizex	= $elm->sizex;
				}
				if(property_exists($elm,'sizey') && trim($elm->sizey) != '')
				{
					$sizey	= $elm->sizey;
				}
				
				$name	 = strtolower(substr($elm->control,6));
				$html	.= '<div style="float:left;"><div class="icon">';
				$html	.= '<a class="modal" href="'.$link.$name.'" rel="{handler:'." 'iframe', ";	
				$html	.= "size: {x: $sizex, y: $sizey}}\">";
				$image	 = 'config';
				if(property_exists($elm,'image') && trim($elm->image) != '')
				{
					$image	= $elm->image;
				}
				$html	.= '<img src="'.$base.'/icon-48-'.$image.'.png" alt="'.BabioonText::_($elm->control).'" /><span>'.BabioonText::_($elm->control).'</span></a></div></div>'; 
			}
		}
		return $html;
	}
	
	
	private function loadParams($type)
	{
		$xmlfile=SITEROOTDIR.DS.'administrator'.DS.'components'.DS.$this->getComponent().DS.'configuration'.DS.$type.'.xml';
		if ( is_readable($xmlfile) )
		{
			// Get Data
			$control	= 'Config'.ucfirst($type);
			$query		= "SELECT params FROM $this->table WHERE control = '$control';";
			$this->db->setQuery($query);
			$result=$this->db->loadResult();
			if (trim($result) != '')
			{
				// create a parameter object
				$instance = new BabioonParameter( $result, $xmlfile );
				return $instance;
			}
			return new BabioonParameter('', $xmlfile);
		}
		return BabioonError::raiseWarning( 500, BabioonText::_( "Configfile: $xmlfile does not exists" ) );
	}

	function save()
	{
		$post		= BabioonRequest::get( 'post' );
		$params		= $post['params'];
		$control	= 'Config'.ucfirst($this->type);
		if (is_array( $params ))
		{
			$txt = array();
			foreach ( $params as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$paramsdata = implode( "\n", $txt );
		}
		$query	= "UPDATE $this->table set params='$paramsdata' WHERE control = '$control';";
		$this->db->setQuery($query);
		if (!$this->db->query()) {
			BabioonError::raiseWarning( 500, $this->db->getError() );
		}
		
	}
	
	function set($key, $value = '', $group = '_default')
	{
		return $this->params->setValue($group.'.'.$key, (string) $value);
	}

}

