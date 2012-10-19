<?php
/**
* @version $Id: table.php 10 2010-04-26 18:48:13Z deutz $
* @copyright * 2007 * Robert Deutz Business Solution * www.rdbs.de *
*/

/** ensure this file is being included by a parent file */
defined('RDBS_LLSP') or die('Restricted access');

jimport( 'joomla.database.table' );

/**
 * class RdController
 *
 * @package adapter
 * @subpackage joomla
 *
 */
class RdbsTable extends JTable
{
	
	/**
	 * Returns a reference to the a Table object, always creating it
	 *
	 * @param type $type The table type to instantiate
	 * @param string A prefix for the table class name
	 * @return database A database object
	 * @since 1.5
	*/
	function &getInstanceAutofields( $type, $prefix, $table, $key='id',$defaults=array() )
	{
		$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
		$tableClass = $prefix.ucfirst($type);

		if (!class_exists( $tableClass ))
		{
			jimport('joomla.filesystem.path');
			if($path = JPath::find(RdbsTable::addIncludePath(), strtolower($type).'.php'))
			{
				require_once $path;

				if (!class_exists( $tableClass ))
				{
					$tableClass='RdbsTable';
				}
			}
			else
			{
				$tableClass='RdbsTable';
			}
		}

		$db =& RdbsFactory::getDBO();

		$instance = new $tableClass($table, $key,$db);
		$instance->setDBO($db);
		// get the table properties
		$result=$db->getTableFields( array($table) );
		$fields=$result[$table];
		foreach ($fields as $key => $val)
		{
			if (count($defaults) != 0 AND array_key_exists($key,$defaults))
			{
				$instance->set($key,$defaults[$key]);
			}
			else
			{
				if ( strpos($val,'int') === false AND strpos($val,'float') === false  )
				{
					$instance->set($key,'');
				}
				else
				{
					$instance->set($key,0);
				}
			}
		}
		return $instance;
	}
}
