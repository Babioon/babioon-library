<?php
/**
 * RDBS 
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @version $Id: checkbox.php 9 2010-04-26 18:46:55Z deutz $
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );

$class = '';
if (array_key_exists('class',$this->elm)) $class=' '.$this->elm ['class'];


if ($this->elm ['error'] == 1) {
	echo '<div class="formelm fail',$class,'">';
	echo '<p class="unsichtbar">' . RdbsText::_ ( 'RDBSLLSP_FORMELMERROR' ) . '</p>';
	echo '<a name="error' . $this->errorcounter . '"></a>';
	$this->errorcounter ++;
} else {
	echo '<div class="formelm',$class,'">';
}
if (array_key_exists('labletag',$this->elm))
{
	$namedesc = strtoupper ( $this->elm ['labletag'] );
} else {
	$namedesc = strtoupper ( $this->elm ['name'] ) . 'DESC';
}


$checked = '';
if ($this->elm ['value'] == 1) {
	$checked = ' checked="checked"';
}
echo '<div class="checkboxfield',$class,'">';
echo '<input type="checkbox" name="', $this->elm ['name'], '" id="', $this->elm ['name'], '" class="cb',$class,'" value="1"', $checked, ' />';
echo '</div>';

echo '<label for="', $this->elm ['name'], '" class="lcb',$class,'" >', RdbsText::_ ( $namedesc );
echo $this->elm ['mandatory'] ? ' *' : '';
echo '</label>';

if ($this->elm ['error'] == 1 and $this->errorcounter < $this->errorcount) {
	echo '<a class="unsichtbar" href="#error' . $this->errorcounter . '">' . RdbsText::_ ( 'RDBSLLSP_JUMPTONEXTERROR' ) . '</a>';
}
echo '<div class="wrap">&nbsp;</div> ';
echo '</div>';