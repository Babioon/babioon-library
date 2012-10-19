<?php
/**
 * RDBS 
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @version $Id: passwordbox.php 9 2010-04-26 18:46:55Z deutz $
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );

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
echo '<label for="', $this->elm ['name'], '" class="lib" >', RdbsText::_ ( $namedesc );
echo $this->elm ['mandatory'] ? ' *' : '';
echo ' </label>';

echo '<div class="field">';
echo '<input type="password" name="', $this->elm ['name'], '" id="', $this->elm ['name'], '" size="30" maxlength="100" class="inputbox" value="', $this->escape($this->elm ['value']), '" />';
echo '</div>';
if ($this->elm ['error'] == 1 and $this->errorcounter < $this->errorcount) {
	echo '<a class="unsichtbar" href="#error' . $this->errorcounter . '">' . RdbsText::_ ( 'RDBSLLSP_JUMPTONEXTERROR' ) . '</a>';
}
echo '<div class="wrap">&nbsp;</div> ';
echo '</div>';