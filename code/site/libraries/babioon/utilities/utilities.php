<?php
/**
* @version $Id: utilities.php 29 2011-10-19 15:53:03Z deutz $
* @copyright * 2007 * Robert Deutz Business Solution * www.rdbs.de *
*/

/** ensure this file is being included by a parent file */
defined('BABIOON') or die('Restricted access');

/**
 * utility class
 *
 */
class BabioonUtilities
{

	function checkFileInTemplate($filename,$type='css')
	{
		$mainframe = RdbsFactory::getApplication();
		$template = $mainframe->getTemplate();
		$comp = $mainframe->scope;
		$path = 'templates'.DS.$template.DS.'html'.DS.$comp.DS;
		$file = basename($filename);
		switch ($type)
		{
			case 'css':
				if (file_exists(SITEROOTDIR.DS.$path.'css'.DS.$file)) {
					$filename='templates/'.$template.'/html/'.$comp.'/css/'.$file;
				}
				break;
				
			case 'php':
				$filename=false;
				$view = RdbsRequest::getVar('view','');
				if ($view != '')
				{
					if (file_exists(SITEROOTDIR.DS.$path.$view.DS.$file)) {
						$filename=true;
					}
				}	
				break;	
				
		}
		return $filename;
	}

	function checkOverride($filename,$type='css',$prefix='rdbs')
	{
		$mainframe = RdbsFactory::getApplication();
		$template = $mainframe->getTemplate();
		if (file_exists(SITEROOTDIR.DS.'templates'.DS.$template.DS.$prefix.DS.$type.DS.$filename)) 
		{
		    return 'templates'.'/'.$template.'/'.$prefix.'/'.$type.'/'.$filename;
		}
		else if (SITEROOTDIR.DS.'media'.DS.$prefix.DS.$type.DS.$filename) 
		{
		    return 'media'.'/'.$prefix.'/'.$type.'/'.$filename;
		}
		return false;
	}
	
	
	

	/**
	 * Create a uniq filename, returns false if not found
	 *
	 * @param unknown_type $adir
	 * @param unknown_type $prefix
	 * @param unknown_type $suffix
	 * @param unknown_type $year
	 * @param unknown_type $month
	 * @param unknown_type $day
	 * @param unknown_type $hour
	 * @param unknown_type $minutes
	 * @param unknown_type $seconds
	 * @param unknown_type $maxloops
	 * @return unknown
	 */
	function getFilename($adir, $prefix, $suffix, $year = false,$month=false,$day=true,$hour=true,$minutes=true,$seconds=true,$maxloops=10)
	{
		
		$filename = '';
		$format = '';
		if ($year) $format .= 'Y';
		if ($month) $format .= 'm';
		if ($day) $format .= 'd';
		if ($hour) $format .= 'H';
		if ($minutes) $format .= 'i';
		if ($seconds) $format .= 's';
		
		$loop = 0;
		$found = false;
		while (!$found  AND $loop < $maxloops)
		{
			$part = date($format);
			$filename = $prefix.'-'.$part.'.'.$suffix;
			$found = file_exists($adir.DS.$filename) === false;
			$loop++;
		}
		if ($found)
		{
			return $filename;		
		}
		return false;
	}
	
	function getList($listname,$withunpublished=true )
	{
		$r=array();
		if (trim( $listname ) != '')
		{
			$pf=$withunpublished ? '':' AND e.published = "1" '; 
			
			$db		= RdbsFactory::getDBO();
			$query = 'SELECT e.name as text, e.value, defaultvalue ' .
					 ' FROM #__rd_mc_listelements as e, #__rd_mc_lists as l ' .
					 ' WHERE l.name = "'.$listname.'" AND l.id = e.listid ' .$pf .
					 ' ORDER BY e.defaultvalue  DESC, e.name';
			$db->setQuery($query);
			$r=$db->loadObjectList();
		}
		return $r;
	}

	function getListElement($listname,$element )
	{
		$r='';
		if (trim( $listname ) != '' && trim($element) != '')
		{
			
			$db		= RdbsFactory::getDBO();
			$query = 'SELECT e.name ' .
					 ' FROM #__rd_mc_listelements as e, #__rd_mc_lists as l ' .
					 ' WHERE l.name = "'.$listname.'" AND l.id = e.listid AND e.published = 1 AND e.value = "'.$element .'"';
			$db->setQuery($query);
			$r=$db->loadResult();
		}
		return $r;
	}
	
	
	function checkoutProcessing($row,$rownum=0,$resulttype='boolean',$overlib = 1,$checkoutfield='checked_out',$identifier='id',$formfield='cid')
	{
		$user   =& RdbsFactory::getUser();
		$userid = $user->get('id');

		$checkout=false;
		// check member
		if ( property_exists($row,$checkoutfield) )
		{
			if (!($row->$checkoutfield == $userid || $row->$checkoutfield == 0))
			{
				$checkout=true;
			}
		}

		if ($resulttype=='boolean')
		{
			return $checkout;
		}
		else
		{
			// return html code to display
			if ($checkout)
			{
				$checkoutByUserId = $row->$checkoutfield;

				$user->load($checkoutByUserId);
				$name = $user->name;

				$hover = '';
				if ( $overlib )
				{
					$text = ':: '. addslashes(htmlspecialchars($name));
					$date = '';
					$time = '';
					if ( property_exists($row,'checked_out_time') )
					{
						$date 	= RdbsHTML::_('date',  $row->checked_out_time, '%A, %d %B %Y' ).' ';
						$time	= RdbsHTML::_('date',  $row->checked_out_time, '%H:%M' );
					}
					$hover = '<span class="editlinktip hasTip" title="'. RdbsText::_( 'Checked Out' ) . $text.' ' . $date . $time .'">';
				}
				$hover = $hover .'<img src="images/checked_out.png"/></span>';
				return $hover;
			}
			else
			{
				return '<input type="checkbox" id="cb'.$rownum.'" name="'.$formfield.'[]" value="'.$row->$identifier.'" onclick="isChecked(this.checked);" />';
			}
		}

	}

	function html2txt( $html, $keeplinks=false  ) {

		// uebernommen von php.net
		//
		// $dokument sollte ein HTML-Dokument enthalten.
		// Folgendes entfernt HTML-Tags, JavaScript-Abschnitte
		// und Leerraeume. Ausserdem wandelt es ein paar gaengige
		// HTML-Entitaeten in ihr Text-Aequivalent um.

		$suche1 = array ('@<script[^>]*?>.*?</script>@si',   // JavaScript entfernen
		               '@([\r\n])[\s]+@',                   // Leerraeume entfernen
					   '@<br />@i',							// Zeilunbrueche
					   '@</p>@i',							// Absaetze
					   '@</tr>@i',							// Tabellenzeilen
					   '@</td>@i');							// Tabellenspalten

		$suche2 = array('');
		
		$suche3 = array ('@<[\/\!]*?[^<>]*?>@si',             // HTML-Tags entfernen
		               '@&(quot|#34);@i',                   // HTML-Entitaeten ersetzen
		               '@&(amp|#38);@i',
		               '@&(lt|#60);@i',
		               '@&(gt|#62);@i',
		               '@&(nbsp|#160);@i',
		               '@&(iexcl|#161);@i',
		               '@&(cent|#162);@i',
		               '@&(pound|#163);@i',
		               '@&(copy|#169);@i',
		               '@&#(\d+);@e');                      // als PHP auswerten

		$ersetze1 = array ('',
		                 '\1',
						 chr(13),
						 chr(13),
						 chr(13),
						 ' | ');
		
		$ersetze3 = array('');
						 
		$ersetze3 = array ('',
		                 '"',
		                 '&',
		                 '<',
		                 '>',
		                 ' ',
		                 chr(161),
		                 chr(162),
		                 chr(163),
		                 chr(169),
		                 'chr(\1)');
		if ($keeplinks)
		{
			$suche =array_merge($suche1,$suche2,$suche3);
			$ersetze =array_merge($ersetze1,$ersetze2,$ersetze3);
		}
		else 
		{
			$suche =array_merge($suche1,$suche3);
			$ersetze =array_merge($ersetze1,$ersetze3);
		}
		$text = preg_replace($suche, $ersetze, $html);
		return $text;

	}
	
	/**
	* teilt eine htmlstring an einer bestimmten stelle und schliesst die 
	* dann noch offenen HTML-Tags um das htmlstring weiterhin valide zu 
	* halten.
	*/
	function cutHTMLTextSave($htmltext,$maxlenght)
	{
		/*
		* Der htmltext darf keine kommentare und scripte enthalten, auch
		* duerfen die elemente keine classen, id, style oder sonstige attribute haben
		*/
	    $listen = array('<ul>','<ol>','<dl>');
	   	$search = array('</p>','<br>','<br />');
	   	$replace = array(' ',' ',' ');
	
	   	$nurtext = strip_tags(str_replace($search,$replace,$htmltext));
	   	if (strlen($nurtext) > $maxlenght)
	   	{
	   		//text zerlegen
			preg_match_all("@(<[\/\!]*?[^<>]*?>)|([^<> ]+)@si", $htmltext,$treffer);
			// leerzeichen entfernen
			$htmla=array();
			$treffer=$treffer[0];
			for ($k=0;$k<count($treffer);$k++)
			{
				if (trim($treffer[$k]) != '') $htmla[]=trim($treffer[$k]); 
			}
	   		$t = '';
			$rcount=count($htmla);
	   		for ($i=0; ($i< $rcount) AND (strlen($t) < $maxlenght) ;$i++)
	   		{
	   			$e=$htmla[$i]; 
	 			// was ist es fuer ein element html oder text	
	            if (preg_match("@<[\/\!]*?[^<>]*?>@si",$e) == 1)
				{
					// es ist ein html element
					continue;
				}	
	  			$t .= $e;
	   		} 
	        $elmnumber=$i-1;
	   		// wenn das letzte wort erst zum ueberlauf gefuert hat dann koennen wir wieder alles nehmen
	   		if (($i+1) ==  $rcount) 
			{
				$result=$htmltext;
			}
			else
			{
				/* 
				* das array noch mal durchlaufen und den text wieder incl. html zusammenbauen
				* dabei merken wir uns, ob ein tag geoeffnet wurde
				*/
				$opentags=array();
				$rtext = '';
				for($j=0;$j < $elmnumber;$j++)
				{
					$e=$htmla[$j];
		            if (preg_match("@<[\/\!]*?[^<>]*?>@si",$e) == 1)
					{
						// es ist ein html element, ist es ein oeffnendes ?
						if (strpos($e,'/') === false AND preg_match("@<[ ]*[br].[ ]*>@i",$e) == 0)
						{
							$opentags[] = $e;
						} else {
							// es ist ein slash im element, aber wo oder es ist ein '<br>'?
							if(preg_match("@<[ ]*[/].*>@i",$e) == 1)
							{
								/*
								* am anfang, es ist also ein schiessendes element
								* wir ueberpruefen hier nicht die semantik des gelieferten html
								* daher gehen wir davon aus das dieses element zu dem letzten
								* geoeffentem element passt
								*/
								array_pop($opentags);
							}
							else
							{
								// nicht am Anfang also wird es ein in sich geschlossenes element sein.
							}
						}
						$rtext .= $e;
					}
					else
					{
						// es ist text
						$rtext .= $e.' ';
					}	
				}
				/* nun haben wir den gekuerzeten Text, ggf. noch zu schliessende tags stehen in opentags
				* wir nehmen uns nun die tags und schliessen diese
				* vorher bekommt der text aber noch ein paar punkte verpasst, das wird genau dann zu einem 
				* Problem, wenn das naechste zu schliessnende Element z.B. eine ul ist, dann werden die Punkte
				* am Ende angefuegt
				*/
				$points = '.....';
				if (is_array($opentags) && count($opentags) != 0)
				{
					$nc= $opentags[count($opentags)-1];
					
					if (!in_array($nc,$listen))
					{
						$rtext .= $points;
						$points = '';
					}
					$ropentags = array_reverse(str_replace('<','</',$opentags));
					$close = implode('',$ropentags);	
					$result=$rtext.$close.$points;	
				} else {
					$result=$rtext.$points ;
				}
		    }
	   	} else {
	   		$result=$htmltext;
	   	}
		return $result;
	}   	
	
	/**
	 * convert an array of objects to an array of ids
	 *
	 * @param array $a
	 * @return array
	 */
	function aoo2aoi($a)
	{
		$nresult=array();
		$rc=count($a);
		if (is_array($a) && $rc != 0)
		{
			for ($i=0;$i<count($a);$i++)
			{
				$n = $a[$i];
				$nresult[] = $n->id;
			}
		}
		return $nresult;
	}
	
	function getPassword($lenghtpass, $casesensitv=true)
	{
		$base	= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$len	= strlen($base);
		$pass	= '';
		for($i=0;$i<$lenghtpass;$i++)
		{
			$index = rand(0,$len-1);
			$c = $base{$index};
			$pass .= $c;
		}
		if (!$casesensitv)
		{
			$pass=strtolower($pass);
		}
		return $pass;
	}			
}

// leagcy
class RdbsUtilities extends BabioonUtilities {}
