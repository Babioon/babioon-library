<?php
/**
* @version $Id: loader.php 6 2010-01-23 16:01:49Z deutz $
* @copyright * 2007 * Robert Deutz Business Solution * www.rdbs.de *
*/

/** ensure this file is being included by a parent file */
defined('BABIOON') or die('Restricted access');


//Initialise the loader
BabioonLoader::initialize();


/**
 * RdbsLoader
 *
 */
class BabioonLoader
{

	/**
	 * path to the class files
	 *
	 * @var string
	 */
	protected static $classDirectory = '';
	
	
	/**
	 * Constructor
	 * 
	 * Prevent creating instances of this class by making the contructor protected
	 */
	protected function __construct() { }
	
	/**
	 * Clone
	 * 
	 * do not allow cloning 
	 */
	private function __clone() { }
	
	
	/**
	 * Initialize
	 * 
	 * @return void
	 */	
	public static function initialize()
	{	
		// Register the autoloader in a way to play well with as many configurations as possible.
		spl_autoload_register(array(__CLASS__, 'load'));
		
		if (function_exists('__autoload')) {		
			spl_autoload_register('__autoload');
		}
		
		// get the path for loading classes
		$path=explode(DS,dirname(__FILE__));
		// one directory back
		array_pop($path);
		self::$classDirectory=BABIOON::getPath();
		// save value
		self::$classDirectory=implode(DS,$path);		
	}
	
	
	/**
	 * Load the class
	 * 
	 * @param 	string  	The class name
	 * @return 	boolean		true on success FALSE on failure
	 */
	public static function load($class) 
	{	

		// pre-empt further searching for the named class or interface.
		// do not use autoload, because this method is registered with
		// spl_autoload already.
		if (class_exists($class, false) || interface_exists($class, false)) {
			return true;
		}
		
		// If class start with  'Babioon' it is a Babioon class and we try to load the file
		if(strtoupper(substr($class, 0, 7)) == 'BABIOON' || strtoupper(substr($class, 0, 4)) == 'RDBS')
		{
			// name: BabioonTralala  -> dir = tralala;file = tralala.php
			$offset=7;
			if(strtoupper($class[0]) == 'R') $offset=4; 
			$part= strtolower(substr($class, $offset));
			if (file_exists(self::$classDirectory.DS.$part.DS.$part.'.php'))
			{
				include self::$classDirectory.DS.$part.DS.$part.'.php';
				return true;				
			}
		}

        return false;
	}
	
}
