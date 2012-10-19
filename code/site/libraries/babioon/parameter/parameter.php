<?php
/**
* @version $Id: parameter.php 6 2010-01-23 16:01:49Z deutz $
* @copyright * 2007 * Robert Deutz Business Solution * www.rdbs.de *
*/

/** ensure this file is being included by a parent file */
defined('BABIOON') or die('Restricted access');

jimport('joomla.html.parameter');

//legacy
class RdbsParameter extends BabioonParameter {}

/**
 * class BabioonParameter
 *
 * @package BABIOON
 * @subpackage joomla
 *
 */
class BabioonParameter extends JParameter
{

	function loadSetupString($str)
	{
		$result = false;

		if ($str)
		{
			$xml = & JFactory::getXMLParser('Simple');

			if ($xml->loadString($str))
			{
				if ($params = & $xml->document->params) {
					foreach ($params as $param)
					{
						$this->setXML( $param );
						$result = true;
					}
				}
			}
		}
		else
		{
			$result = true;
		}

		return $result;
	}


}