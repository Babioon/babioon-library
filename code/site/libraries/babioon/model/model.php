<?php
/**
* @version $Id: model.php 30 2011-10-19 15:53:49Z deutz $
* @copyright * 2007 * Robert Deutz Business Solution * www.rdbs.de *
*/

/** ensure this file is being included by a parent file */
defined('RDBS_LLSP') or die('Restricted access');

jimport( 'joomla.application.component.model' );

/**
 * class RdbsModel
 *
 * @package adapter
 * @subpackage joomla
 *
 */
class RdbsModel extends JModel
{
	/**
	 * name of the table assiotated with the model
	 * 
	 * @var string
	 */
	protected $table = null;
	
	/**
	 * name of the object assiotated with this model
	 * 
	 * @var string
	 */
	protected $objname = null;
	
	/**
	 * content for the data saved in session or registry
	 * 
	 * @var string
	 */
	protected $context = null;
	
	/**
	 * the data
	 * 
	 * @var mixed
	 */
	protected $data = null;
	
	/**
	 * Pagination object
	 * 
	 * @var object
	 */
	protected $page = null;
	
	/**
	 * Lists and form elements
	 * 
	 * @var array
	 */
	protected $lists = null;
	
	/**
	 * database handle
	 * 
	 * @var object
	 */
	protected $db = null; 
	
	/**
	 * the component name we are working at the moment
	 * 
	 * @var unknown_type
	 */
	protected $scope = null;
	
	/**
	 * application object
	 * 
	 * @var unknown_type
	 */
	protected $app = null;
	
	/**
	 * tableobject
	 *
	 * @var object
	 */
	protected $tableobject = null;	
	
	/**
	 * Constructor
	 * @param $args
	 * @return unknown_type
	 */
	function __construct($args=array())
	{
		parent::__construct($args);
		// get the database handle from the model
		if(array_key_exists('db', $args))
		{
		    $this->db = $args['db'];
		}
		else
		{
		    $this->db = $this->getDBO();    
		}
		// get the objname from the model
		if(array_key_exists('objname', $args))
		{
		    $this->objname = $args['objname'];
		}
		else
		{
		    $this->objname 	= $this->getName();    
		}
		// get a appplication object
	    $this->app 	= RdbsFactory::getApplication();    
		// get application name
		if(array_key_exists('scope', $args))
		{
		    $this->scope = $args['scope'];
		}
		else
		{
		    $this->scope	= $this->app->scope;    
		}
		// set content 
		if(array_key_exists('context', $args))
		{
		    $this->context = $args['context'];
		}
		else
		{
		    $this->context 	= $this->scope.'.'.$this->objname.'.';    
		}
		// set tablename for model
		if(array_key_exists('table', $args))
		{
		    $this->table = $args['table'];
		}
		else
		{
		    $this->table 	= '#__'.substr($this->scope,4).'_'.$this->objname;    
		}
		
	}

	function getData()
	{
		if ( !is_null($this->data) )
		{
			return $this->data;
		}

		$limitstart 		= RdbsRequest::getVar('limitstart', '0', '', 'int');
		$limit				= $this->getUserStateFromRequest('limit', 50);
		
		$this->db->setQuery("SELECT count(*) FROM $this->table ");
		$rc = $this->db->loadResult();
		if ($this->db->getErrorNum()) {
			return RdbsError::raiseError(500, $this->db->stderr());
		}
		
		$query = "SELECT * FROM $this->table ";
		$this->db->setQuery( $query,$limitstart,$limit );

		$this->data = $this->db->loadObjectList();

		if ($this->db->getErrorNum()) {
			return RdbsError::raiseError(500, $this->db->stderr());
		}
		$this->page = new RdbsPagination($rc, $limitstart, $limit);
		$this->lists=array();
		return $this->data;		
	}

	function getList()
	{
		if ( is_null($this->data) )
		{
			$this->getData();
		}
		return $this->lists;
		
	}
	
	function getPagination()
	{
		if ( is_null($this->data) )
		{
			$this->getData();
		}
		return $this->page;
	}
	
	
	function getTable($type=null, $prefix=null, $table=null, $key='id')
	{
		if (is_null($this->tableobject))
		{ 
			
			if (is_null($type)) 	$type 	= $this->objname;
			if (is_null($prefix)) 	$prefix = 'Table';
			if (is_null($table)) 	$table 	= $this->table;
			
			$this->tableobject	=& RdbsTable::getInstanceAutofields($type, $prefix, $table, $key);
		}	
		return $this->tableobject;
	}
	

	/**
	 * get the userstate value from the context
	 * 
	 * @param $key
	 * @return unknown_type
	 */
	function getUserState($key)
	{
		return $this->app->getUserState($this->context .$key);
	}
	
	/**
	 * get a session value from request or context 
	 * allow masking for the request content
	 * 
	 * @param $key
	 * @param $default
	 * @param $type
	 * @param $mask
	 * @return mixed
	 */
	function getUserStateFromRequest( $key, $default = null, $type = 'none', $mask=0 )
	{
		$old_state = $this->app->getUserState( $this->context . $key );
		$cur_state = (!is_null($old_state)) ? $old_state : $default;
		$new_state = RdbsRequest::getVar($key, null, 'default', $type,$mask);

		// Save the new value only if it was set in this request
		if ($new_state !== null) {
			$this->setUserState($key, $new_state);
		} else {
			$new_state = $cur_state;
		}

		return $new_state;
	}
	
	/**
	 * set a session value in context
	 * 
	 * @param $key
	 * @param $value
	 * @return unknown_type
	 */
	function setUserState($key, $value)
	{
		$this->app->setUserState($this->context .$key, $value);
	}
	
}