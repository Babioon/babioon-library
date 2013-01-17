<?php
/**
* @version $Id: factory.php 28 2011-10-19 15:51:56Z deutz $
* @copyright * 2007 * Robert Deutz Business Solution * www.rdbs.de *
*/

/** ensure this file is being included by a parent file */
defined('BABIOON') or die('Restricted access');

jimport( 'joomla.factory' );

// legacy

class RdbsFactory extends BabioonFactory {}

/**
 * class BabioonFactory
 *
 * @package BABIOON
 * @subpackage joomla
 *
 */
class BabioonFactory extends JFactory
{
    public static $params = null;
    
    
	public static function getParams()
	{

		if (!is_object(self::$params))
		{
			jimport('joomla.application.component.helper');
		    self::$params = JComponentHelper::getParams(self::getApplication()->get('scope'));
		}
		return self::$params;
	}
    

}