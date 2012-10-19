<?php
/**
 * RDBS 
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @version $Id: textbox.php 9 2010-04-26 18:46:55Z deutz $
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );

$class='inputbox';
$lclass='lib';
$rows=5;
$cols=30;

if (array_key_exists('class', $this->elm)) $class = $this->elm ['class']; 
if (array_key_exists('rows', $this->elm)) $rows = $this->elm ['rows']; 
if (array_key_exists('cols', $this->elm)) $cols = $this->elm ['cols'];
if (array_key_exists('lclass', $this->elm)) $lclass = $this->elm ['lclass']; 
 
if ($this->elm ['error'] == 1) {
	echo '<div class="formelm fail">';
	echo '<p class="unsichtbar">' . RdbsText::_ ( 'RDBSLLSP_FORMELMERROR' ) . '</p>';
	echo '<a name="error' . $this->errorcounter . '"></a>';
	$this->errorcounter ++;
} else {
	echo '<div class="formelm">';
}
if (array_key_exists('labletag',$this->elm))
{
	$namedesc = strtoupper ( $this->elm ['labletag'] );
} else {
	$namedesc = strtoupper ( $this->elm ['name'] ) . 'DESC';
}

echo '<label for="', $this->elm ['name'], '" class="'.$lclass.'" >', RdbsText::_ ( $namedesc );
echo $this->elm ['mandatory'] ? ' *' : '';
echo '</label>';
echo '<textarea name="', $this->elm ['name'], '" id="', $this->elm ['name'], '" cols="'.$cols.'" rows="'.$rows.'"';
echo ' class="'.$class.'">';
echo $this->elm ['value'], '</textarea>';
if ($this->elm ['error'] == 1 and $this->errorcounter < $this->errorcount) {
	echo '<a class="unsichtbar" href="#error' . $this->errorcounter . '">' . RdbsText::_ ( 'RDBSLLSP_JUMPTONEXTERROR' ) . '</a>';
}
echo '<div class="wrap">&nbsp;</div> ';
echo '</div>';