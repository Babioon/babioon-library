<?php
/**
* @version $Id: toolbar.php 6 2010-01-23 16:01:49Z deutz $
* @copyright * 2007 * Robert Deutz Business Solution * www.rdbs.de *
*/

/** ensure this file is being included by a parent file */
defined('RDBS_LLSP') or die('Restricted access');
	
/**
 * class RdbsToolbar
 * @package RDBS_LLSP
 */
class RdbsToolbar
{

	protected static $instance = null;

	protected $toolbars = array();

	protected $path = array();

	protected $iconset = array('apply','cancel','copy','edit','new','publish','save','unpublish');

	protected function __construct($config=array())
	{
		if (array_key_exists('basePath',$config)) 
		{
			$this->addIconPath($config['basePath']);
		}
		else
		{
			$this->addIconPath(SITEROOTDIR.DS.'media'.DS.'plg_rdbs'.DS.'images'.DS.'toolbar');
		}
	}

	public function addApply($task='apply',$name='Apply',$listSelect=false,$confirm=false,$disableMenu=false, $type='default')
	{
		$this->addElement($task,'apply.png','apply.png',$name,$listSelect,$confirm,$disableMenu,$type);
	}

	public function addCancel($task='cancel',$name='Cancel',$listSelect=false,$confirm=false,$disableMenu=false, $type='default')
	{
		$this->addElement($task,'cancel.png','cancel.png',$name,$listSelect,$confirm,$disableMenu,$type);
	}

	public function addCust($task='cust',$name='Cust',$listSelect=false,$confirm=false,$disableMenu=false, $type='default')
	{
		$this->addElement($task,$task.'.png',$task.'.png',$name,$listSelect,$confirm,$disableMenu,$type);
	}

	public function addDelete($task='remove',$name='Delete',$listSelect=true,$confirm=true,$disableMenu=false, $type='default')
	{
		$this->addElement($task,'delete.png','delete.png',$name,$listSelect,$confirm,$disableMenu,$type);
	}

	public function addEdit($task='edit',$name='Edit',$listSelect=true,$confirm=false,$disableMenu=false,$type='default')
	{
		$this->addElement($task,'edit.png','edit.png',$name,$listSelect,$confirm,$disableMenu,$type);
	}

	/**
	 * Adds an element to the toolbar
	 */
	public function addElement($task,$image,$imagehover,$name,$listSelect=true,$confirm=false,$disableMenu=false, $type='default')
	{
		if (!array_key_exists($type,$this->toolbars))
		{
			$this->toolbars[$type]= array();
		}
		$tb=$this->toolbars[$type];

		$element = new stdClass();
		$element->task = $task ;
		$element->image = $image ;
		$element->imagehover = $imagehover ;
		$element->name = $name ;
		$element->listSelect = $listSelect ;
		$element->confirm = $confirm ;
		$element->disableMenu = $disableMenu ;

		$tb[]=$element;
		$this->toolbars[$type]=$tb;
	}

	public function addNew($task='add',$name='New',$listSelect=false,$confirm=false,$disableMenu=false, $type='default')
	{
		$this->addElement($task,'new.png','new.png',$name,$listSelect,$confirm,$disableMenu,$type);
	}

	public function addIconPath($path)
	{
		if(is_array($path))
		{
			foreach ($path as $p)
			{
				$p = trim($p);
				if (!in_array($p,$this->path))
				{
					array_unshift($this->path,$p);
				}
			}
		}
		else
		{
			//trim string
			$p = trim($path);
			if (!in_array($p,$this->path))
			{
				array_unshift($this->path,$p);
			}
		}
	}

	public function addPrP($task='prp',$name='Delete',$listSelect=true,$confirm=false,$disableMenu=false, $type='default')
	{
		$this->addElement($task,'delete.png','delete.png',$name,$listSelect,$confirm,$disableMenu,$type);
	}
	
	public function addPublish($task='publish',$name='Publish',$listSelect=true,$confirm=false,$disableMenu=false, $type='default')
	{
		$this->addElement($task,'publish.png','publish.png',$name,$listSelect,$confirm,$disableMenu,$type);
	}

	public function addSave($task='save',$name='Save',$listSelect=false,$confirm=false,$disableMenu=false, $type='default')
	{
		$this->addElement($task,'save.png','save.png',$name,$listSelect,$confirm,$disableMenu,$type);
	}

	public function addUnpublish($task='unpublish',$name='Unpublish',$listSelect=true,$confirm=false,$disableMenu=false, $type='default')
	{
		$this->addElement($task,'unpublish.png','unpublish.png',$name,$listSelect,$confirm,$disableMenu,$type);
	}

	protected function getIconPath($icon)
	{
		$found = false;
		foreach($this->path as $p)
		{
			$check = $p .DS .$icon;
			if (file_exists($check))
			{
				$found = true;
				break;
			}
		}
		if ($found)
		{
			$rootpath=explode(DS,SITEROOTDIR);
			$check = explode(DS,$check);
			$rootlenght=count($rootpath);
			$result=array_slice($check,$rootlenght);
			$result=implode(DS,$result);
			return $result;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Return an instance, create if it is not allready created
	 *
	 * @return 	object access class object
	 */
	public static function getInstance($config=array())
	{

		if (self::$instance === null)
		{
			self::$instance = new RdbsToolbar($config);
		}
		return self::$instance;
	}

	/**
	 * render the toolbar
	 */
	public function render($type='default')
	{
		if(array_key_exists($type,$this->toolbars))
		{
			$tb=$this->toolbars[$type];
			$c=count($tb);
			if ($c != 0)
			{
				echo '<div id="toolbar-'.$type.'">';
				echo '<ul>';
				for($i=0;$i<$c;$i++)
				{
					$elm=$tb[$i];
					$name = substr($elm->image,0,strpos($elm->image,'.'));

					if (in_array($name,$this->iconset))
					{
						echo '<li class="icon-32-'.$name.'">';
						//echo '<li>';

					}
					else
					{
						$ipath=$this->getIconPath('icon-32-'.$elm->image);
						if ($ipath === null)
						{
							// standard icon
							echo '<li class="icon-32-default">';
							//echo '<li>';
						}
						else
						{
							echo '<li style="background-image:url('.$ipath.');background-repeat:no-repeat;">';
							//echo '<li>';

						}
					}
					echo "\n".'<a href="javascript:';
					if ($elm->listSelect)
					{
						$seltext = RdbsText::_('RDBS_SPLL_TOOLBARSEL'.strtoupper($elm->task));
						echo "if(document.adminForm.boxchecked.value == 0){ alert('".$seltext."'); } else";
						if ($elm->confirm)
						{
							$confirmtext = RdbsText::_('RDBS_SPLL_TOOLBARCONFIRM'.strtoupper($elm->task));
							echo " if (confirm('".$confirmtext."')) ";
						}
						echo "{ submitbutton('".$elm->task."');}\"";
					}
					else
					{
						echo 'submitbutton(\''.$elm->task.'\');"';
					}
					echo '>';
					echo RdbsText::_($elm->name);
					echo '</a></li>';
				}
				echo '</ul>';
				echo '</div>';
			}
		}
	}
}