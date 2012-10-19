<?php
/**
 * BABIOON Library
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @version $Id: rdbs.php 6 2010-01-23 16:01:49Z deutz $
 * @package BABIOON
 * @copyright Copyright (C) 2005-2012 Robert Deutz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *
 * This is free software
 **/

/**
 * Developing components for Joomla! is sometimes a pain, 
 * you have to copy many times the same lines of code. 
 * To make my live easier I use this library and try develop with the DWTSCMT principle :-)
 */


/**
 * RDBS/BABIOON is active
 */
define('RDBS_LLSP', 1);
define('BABIOON', 1);

/**
 * DS is a shortcut for DIRECTORY_SEPARATOR
 */
if(!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

/**
 * BABIOON class
 *
 * Loads classes and files, and provides metadata for BABIOON such as version info
 * 
 * inspired by the nooku framework (see nooku.org)
 *
 * @author		Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @package 	BABIOON
 */
class babioon
{
    /**
     * Babioon version
     */
    const BABIOONVERSION = '0.9';

    /**
     * Path to BABIOON libraries
     */
    protected static $path;

    /**
     * Get the version of the BABIOON library
     */
    public static function getVersion()
    {
   	    return self::BABIOONVERSION;
    }

    /**
     * Get path to BABIOON libraries
     */
    public static function getPath()
    {
    	if(!isset(self::$path)) {
        	self::$path = dirname(__FILE__);
        }

        return self::$path;
    }

	/**
 	 * Intelligent file importer
	 *
 	 * @param string $path A dot syntax path
 	 */
	public static function import( $path, $basepath = '')
	{
		$parts = explode( '.', $path );

		$result = '';
		switch($parts[0])
		{
			case 'lib' :
			{
				if($parts[1] == 'joomla') 
				{
					unset($parts[0]);
					$path   = implode('.', $parts);
					$result = JLoader::import($path, null, 'libraries.' );
				} 
				
				if($parts[1] == 'babioon') 
				{
					unset($parts[0]);
					unset($parts[1]);
					$path   = implode('.', $parts);
					$result = JLoader::import($path, self::getPath());
				}
				
			} break;
			
			default :
			{
				if(strpos($parts[0], '::') !== false) {
					$app  = explode( '::', $parts[0] );	
					$name =  $app[0];
				} else {
					$app  = JFactory::getApplication();
					$name = $app->getName(); 
				}
				
				$app = ($name == 'site') ? JPATH_SITE : JPATH_ADMINISTRATOR;
				$com = $parts[1];

				unset($parts[0]);
				unset($parts[1]);

				$base   = $app.DS.'components'.DS.'com_'.$com;
				$path   = implode('.', $parts);
					
				$result = JLoader::import($path, $base, $com.'.' );
				
			} break;
		}

		return $result;
	}
}