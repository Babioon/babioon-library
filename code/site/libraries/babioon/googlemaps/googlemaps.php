<?php
/**
* 
*/

/** ensure this file is being included by a parent file */
defined('BABIOON') or die('Restricted access');

/**
 * BabioonGooglemaps
 * 
 * @uses map API v3
 * 
 */
class BabioonGooglemaps
{
	private static $instances = null;

	private $url 	= '';

	private $returncode = null;

	private $msg = '';
	
	private $debugon = false;
	
	private $logobj = null;
	
	/**
	 * protect the constructor
	 */
	protected function __construct()
	{
		$this->init();
	}

	/**
	 * cloning is not allowed
	 */
	private function __clone() { }
	
	
	private function init() 
	{
		/* load the script tag once */
		static $loaded = false;
		
		if($loaded) return true;
		
		JHTML::_('behavior.framework');
		$doc=JFactory::getDocument();
		$doc->addScript("http://maps.google.com/maps/api/js?sensor=false");
		$loaded=true;
	}
	
	function getInstance()
	{
		if (!isset(self::$instances))
		{
			self::$instances = new BabioonGooglemaps();
		}
		return self::$instances;
	}

	function getGeoKoordinaten($street,$pcode,$city,$state=null,$country=null)
	{
		if (!isset(self::$instances)) return false;
		$this->setDebugOn();
		$info 		= array();
		$search		= array(' ','-',',',';','ä','ö','ü','Ä','Ö','Ü','ß','++');
		$replace	= array('+','+','+','+','ae','oe','ue','Ae','Oe','Ue','ss','+');
		$info[]		= str_replace($search,$replace,$street);
		$info[]		= str_replace($search,$replace,$pcode);
		$info[]		= str_replace($search,$replace,$city);
		if (!is_null($state))
		{
			$info[] 	= str_replace($search,$replace,$state);
		}	
		if (!is_null($country))
		{
			$info[] 	= str_replace($search,$replace,$country);
		}	
		
		$istr			= implode( '+', $info );
		$this->url		= 'http://maps.google.com/maps/api/geocode/json?address='.$istr.'&sensor=false';
		
		$starttime	= time();
		$ch = curl_init();
		// set url
		curl_setopt($ch, CURLOPT_URL, $this->url);
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $output contains the output string
		$data = curl_exec($ch);
		if ($data === false)
		{
			$error=curl_error($ch);
			$this->msg='ERROR:'.$error;
		}
		// close curl resource to free up system resources
		curl_close($ch);
		$this->debug('requesttime: '.time()-$starttime);
		
		$data = json_decode($data);

		if($data->status == 'OK')
		{
			$this->msg='Geo-Koordinaten OK! ';
			$location=$data->results[0]->geometry->location;
			$geo_b=$location->lat;
			$geo_l=$location->lng;
		}
		else 
		{
			$this->msg='Geo-Koordinaten FAIL! ('.$data->code.')';
			return false;
		}
		return (array($geo_l,$geo_b));
	}
	
	function debug($msg)
	{
		if ($this->debugon)
		{
			$msg = (string) $msg;
			//if ($this->logobj instanceof RdbsLog)
			//{
				//JLog::sl($msg);
			//}
		}
	}
	
	function setDebugOff()
	{
		$this->debugon=false;
	}

	function setDebugOn()
	{
		$this->debugon=true;
	}
	
	function setLogger($obj)
	{
		$this->logobj=$obj;
	}
	
}
