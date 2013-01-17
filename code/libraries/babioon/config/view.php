<?php
/**
 * Robert Deutz Business Solution
 * @author Robert Deutz (email contact@rdbs.net / site www.rdbs.de)
 * @version $Id: view.php 6 2010-01-23 16:01:49Z deutz $
 * @package RDBS_Config
 * @copyright Copyright (C) 2007 Robert Deutz Business Solution
 * 
 **/
defined('BABIOON') or die('Restricted access');

class RdbsViewConfig extends BabioonViewConfig {}

class BabioonViewConfig extends BabioonView
{
	/**
	 * Display the view
	 */
	function display()
	{
		$task=BabioonRequest::getVar('task','system');
		$conf=BabioonConfig::getInstance($task);
		
		$params=$conf->getParamsObj();
		
		$document = & BabioonFactory::getDocument();
		$document->setTitle( BabioonText::_('Edit Preferences') );
		BabioonHTML::_('behavior.tooltip');
?>
	<form action="index.php" method="post" name="adminForm" autocomplete="off">
		<fieldset>
			<div style="float: right">
				<button type="button" onclick="submitbutton('save');window.top.setTimeout('window.parent.document.getElementById(\'sbox-window\').close()', 700);">
					<?php echo BabioonText::_( 'Save' );?></button>
				<button type="button" onclick="window.parent.document.getElementById('sbox-window').close();">
					<?php echo BabioonText::_( 'Cancel' );?></button>
			</div>
			<div class="configuration" >
				<?php 
					$task	= BabioonRequest::getVar('task','');
					echo BabioonText::_('Config'.$task) ?>
			</div>
		</fieldset>

		<fieldset>
			<legend>
				<?php echo BabioonText::_( 'Configuration' );?>
			</legend>
			<?php echo $params->render();?>
		</fieldset>

		<input type="hidden" name="id" value="<?php echo $params->get('id',0);?>" />
		<input type="hidden" name="control" value="<?php echo $task;?>" />

		<input type="hidden" name="section" value="config" />
		<input type="hidden" name="option" value="<?php echo $conf->getComponent(); ?>" />
		<input type="hidden" name="task" value="" />
	</form>
<?php
	}
}