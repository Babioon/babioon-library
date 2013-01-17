<?php
/**
* @version $Id: application.php 6 2010-01-23 16:01:49Z deutz $
* @copyright * 2007 * Robert Deutz Business Solution * www.rdbs.de *
*/

/** ensure this file is being included by a parent file */
defined('BABIOON') or die('Restricted access');

jimport('joomla.application.application');

// leagcy
class RdbsApplication extends BabioonApplication {}


/**
 * class BabioonApplication
 *
 * @package BABIOON
 * @subpackage joomla
 *
 */
class BabioonApplication extends JApplication
{

}