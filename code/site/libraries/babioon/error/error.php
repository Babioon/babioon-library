<?php
/**
* @version $Id: error.php 6 2010-01-23 16:01:49Z deutz $
* @copyright * 2007 * Robert Deutz Business Solution * www.rdbs.de *
*/

/** ensure this file is being included by a parent file */
defined('BABIOON') or die('Restricted access2');

jimport( 'joomla.utilities.error' );

// legacy
class RdbsError extends BabioonError {}

/**
 * class BabioonError
 *
 * @package BABIOON
 * @subpackage joomla
 *
 */
class BabioonError extends JError
{

}

