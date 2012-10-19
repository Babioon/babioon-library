<?php /**
 * Robert Deutz Business Solution
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @version $Id: controller.php 6 2010-01-23 16:01:49Z deutz $
 * @package RDBS_Config
 * @copyright Copyright (C) 2007 Robert Deutz Business Solution
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 **/
defined('BABIOON') or die('Restricted access');

// leagcy
class RdbsControllerConfig extends BabioonControllerConfig {};

/**
 * class BabioonControllerConfig
 *
 *  @package Babioon_Config
 */
class BabioonControllerConfig extends BabioonController
{

	/**
	 * Constructor
	 */
	function __construct( $config = array() )
	{
		parent::__construct( $config );
	}

	/**
	 * Display the config screen
	 */
	function display()
	{
		//force the component template
		BabioonRequest::setVar('tmpl', 'component');
		require_once (dirname(__FILE__).DS.'view.php');
		BabioonRequest::setVar('view','config');
		BabioonViewConfig::display();
	}

	function save()
	{
		
		$control 	= BabioonRequest::getVar('control');
		$conf		= BabioonConfig::getInstance($control);
		$conf->save();
	}

}