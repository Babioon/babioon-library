<?php
/**
 * RDBS 
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @version $Id: timeselect.php 9 2010-04-26 18:46:55Z deutz $
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );

if ($this->elm ['error'] == 1) {
	echo '<div class="formelm fail">';
	echo '<p class="unsichtbar">' . RdbsText::_ ( 'RDBSLLSP_FORMELMERROR' ) . '</p>';
	echo '<a name="error' . $this->errorcounter . '"></a>';
	$this->errorcounter++;
} else {
	echo '<div class="formelm">';
}

$elm1=$this->elm ['name'].'_h';
$elm2=$this->elm ['name'].'_m';

if (array_key_exists('labletag',$this->elm))
{
	$namedesc1 = strtoupper ( $this->elm ['labletag'].'_h' );
	$namedesc2 = strtoupper ( $this->elm ['labletag'].'_m' );
} else {
	$namedesc1 = strtoupper ( $elm1 ) . 'DESC';
	$namedesc2 = strtoupper ( $elm2 ) . 'DESC';
}

echo '<label for="', $elm1, '" class="time" >', RdbsText::_ ( $namedesc1 );
echo ' </label>';
echo '<label for="', $elm2, '" class="time" >', RdbsText::_ ( $namedesc2 );
if ($this->elm['mandatory'] != '' ) echo ' *';
echo ' </label>';

$hh=ltrim(substr($this->elm['value'],0,2),'0');
$mm=ltrim(substr($this->elm['value'],3,2),'0');
$txt = array ('--', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23' );
echo '<select class="time" name="', $elm1, '" id="', $elm1, '" size="1">';
foreach ( $txt as $e ) {
	echo '<option';
	echo $hh == $e ? ' selected="selected"' : '';
	echo ' value="' . $e . '">' . $e . '</option>';
}
echo '</select>';

$txt = array ('--', '0', '5', '10', '11', '15', '20', '25', '30', '35', '40', '45', '50', '55' );
echo '<select class="time" name="', $elm2 , '" id="', $elm2, '" size="1">';
foreach ( $txt as $e ) {
	echo '<option';
	echo $mm == $e ? ' selected="selected"' : '';
	echo ' value="' . $e . '">' . $e . '</option>';
}
echo '</select>';

if ($this->elm ['error'] == 1 and $this->errorcounter < $this->errorcount) {
	echo '<a class="unsichtbar" href="#error' . $this->errorcounter . '">' . RdbsText::_ ( 'RDBSLLSP_JUMPTONEXTERROR' ) . '</a>';
}
echo '<div class="wrap">&nbsp;</div> ';
echo '</div>';
