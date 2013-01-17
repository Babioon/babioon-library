<?php
/**
* @version $Id: menu.php 6 2010-01-23 16:01:49Z deutz $
* @copyright * 2007 * Robert Deutz Business Solution * www.rdbs.de *
*/

/** ensure this file is being included by a parent file */
defined('BABIOON') or die('Restricted access');

jimport( 'joomla.application.menu' );

// legacy
class RdbsMenu extends BabioonMenu {}

/**
 * class BabioonMenu
 *
 * @package BABIOON
 * @subpackage joomla
 *
 */
class BabioonMenu extends JMenu
{

}