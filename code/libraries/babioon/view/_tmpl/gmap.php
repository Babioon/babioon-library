<?php
/**
 * RD REGISTER
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @version $Id: gmap.php 9 2010-04-26 18:46:55Z deutz $
 * @package RD_REGISTER
 * @copyright Copyright (C) 2008 Robert Deutz Business Solution
 * @license Commercial Licence
 **/
if (!defined('RDEVENT')) die( 'Restricted access1' );


// apikey pr-journal.de key=ABQIAAAAX9uuFjIcmqRH4GMy_b5a1RQj0wH8U3Ifp_c5clJXMcjVM0SJchQAp5mRSwiK1bl-YCmOtlZJkz0fPw 
// apikey datenbanken.pr-journal.de key=ABQIAAAAX9uuFjIcmqRH4GMy_b5a1RSD23nydTvfmFHHPcm_bwvjyM6z3RQLcDrUlrbAhGrCja7byDTjTX4Iag

$row=$this->element;

if ($row->geo_b != '' && $row->geo_l != '')
{

	$doc = RdbsFactory::getDocument();
	$surl = "http://www.google.com/jsapi?key=".$this->sconf->get('gmapappkey');
	$doc->addScript($surl);
	$script='
	  google.load("maps", "2");
	   
	  // Diese Funktion aufrufen, wenn die Seite geladen ist
	  function initialize() {
	    var map = new google.maps.Map2(document.getElementById("map"));
	    map.setCenter(new google.maps.LatLng( '.$row->geo_b.', '.$row->geo_l.'), '.$this->sconf->get('gmapdefzoom',14).');
	    
		var point = new GLatLng('.$row->geo_b.', '.$row->geo_l.');
	    map.addOverlay(new GMarker(point));    
	    
	  }
	  google.setOnLoadCallback(initialize);
	
	';
	$doc->addScriptDeclaration($script);
}
