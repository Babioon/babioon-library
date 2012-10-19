<?php
/**
 * RDBS 
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @version $Id: categorycheckboxes.php 9 2010-04-26 18:46:55Z deutz $
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );

if ($this->elm ['error'] == 1) {
	echo '<div class="formelm fail">';
	echo '<p class="unsichtbar">' . RdbsText::_ ( 'RDBSLLSP_FORMELMERROR' ) . '</p>';
	echo '<a name="error' . $this->errorcounter . '"></a>';
	$this->errorcounter ++;
} else {
	echo '<div class="formelm kategorie">';
}
if (array_key_exists('labletag',$this->elm))
{
	$namedesc = strtoupper ( $this->elm ['labletag'] );
} else {
	$namedesc = strtoupper ( $this->elm ['name'] ) . 'DESC';
}

echo RdbsText::_ ( $namedesc );

echo '<div class="radio">';

foreach ( $this->elm['list'] as $c ) {
	echo '<input type="checkbox" name="'.$this->elm['name'].'[]" id="'.$this->elm['name'].'c' . $c->value . '" value="' . $c->value . '" ';
	if ($this->showerror == 0)
	{
		$checked = array_search($c->value, $this->defaultvalue) !== false;
	}
	else
	{
		$checked=$c->value;		
	}
	echo $checked ? 'checked="checked" ' : '';
	echo ' class="cb"/> <label for="c'.$c->value.'" class="lrb">' . $c->text . '</label><br /><br />' . "\n";
}
echo '</div>';

if ($this->elm ['error'] == 1 and $this->errorcounter < $this->errorcount) {
	echo '<a class="unsichtbar" href="#error' . $this->errorcounter . '">' . RdbsText::_ ( 'RDBSLLSP_JUMPTONEXTERROR' ) . '</a>';
}
echo '<div class="wrap">&nbsp;</div> ';
echo '</div>';