<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript" language="javascript">

function migrateMyBlog()
{
	ej( "#migrator-submit4" ).attr('disabled' , 'true');
	ej( "#migrator-submit4" ).html('Updating ...');

	ej( "#no-progress4" ).css( 'display' , 'none' );
	ej( "#icon-wait4" ).css( 'display' , 'block' );
	ej( "#progress-status4" ).html( '' );

	//form the query string so that we can use ejax to submit.
	finalData	= ejax.getFormVal('#adminForm4');
	//process the migration
	ejax.load('migrators','migrateArticle',finalData);
}

function divSrolltoBottomMyblog()
{
	var objDiv = document.getElementById("progress-status4");
    objDiv.scrollTop = objDiv.scrollHeight;

	var objDiv2 = document.getElementById("stat-status4");
    objDiv2.scrollTop = objDiv2.scrollHeight;
}
</script>

<?php if( !$this->myblogInstalled ) { ?>
<?php echo JText::_('COM_EASYBLOG_MIGRATOR_MYBLOG_COMPONENT_NOT_FOUND');?>
<?php } else { ?>

<form action="" method="post" name="adminForm4" id="adminForm4">
<table class="noshow">
	<tr>
		<td width="50%" valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_MIGRATOR_MYBLOG' ); ?></legend>
			<div>
				<?php echo JText::_('COM_EASYBLOG_MIGRATOR_MYBLOG_DESC'); ?>
			</div>
			<div>
				<?php echo JText::_('COM_EASYBLOG_MIGRATOR_MYBLOG_NOTICE'); ?>
			</div>

			<div style="padding-top:20px;">
				<button id="migrator-submit4" class="button" onclick="migrateMyBlog();return false;"><?php echo JText::_('COM_EASYBLOG_MIGRATOR_RUN_NOW'); ?></button>
			</div>

			<div id="icon-wait4" style="display:none;">
				<img src="<?php echo JURI::root() . 'administrator/components/com_easyblog/assets/images/wait.gif'; ?>" />&nbsp;&nbsp;<?php echo JText::_('COM_EASYBLOG_MIGRATOR_PLEASE_WAIT'); ?>
			</div>

			</fieldset>
		</td>
		<td>
			<fieldset class="adminform" style="height:200px;">
				<legend><?php echo JText::_( 'COM_EASYBLOG_MIGRATOR_PROGRESS' ); ?></legend>
				<div id="no-progress4"><?php echo JText::_('COM_EASYBLOG_MIGRATOR_NO_PROGRESS_YET'); ?></div>
				<div id="progress-status4"  style="overflow:auto; height:98%;"></div>
			</fieldset>

			<fieldset class="adminform" style="height:65px;">
				<legend><?php echo JText::_( 'COM_EASYBLOG_MIGRATOR_STATISTIC' ); ?></legend>
				<div id="stat-status4"  style="overflow:auto; height:98%;"></div>
			</fieldset>
		</td>
	</tr>
</table>

<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="com_type" value="com_myblog" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="c" value="migrators" />
</form>
<?php } ?>