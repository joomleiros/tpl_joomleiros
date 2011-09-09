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

function migrateSmartBlog()
{
	ej( "#migrator-submit2" ).attr('disabled' , 'true');
	ej( "#migrator-submit2" ).html('Updating ...');
	
	ej( "#no-progress2" ).css( 'display' , 'none' );
	ej( "#icon-wait2" ).css( 'display' , 'block' );
	ej( "#progress-status2" ).html( '' );
	
	//form the query string so that we can use ejax to submit.
	finalData	= ejax.getFormVal('#adminForm2');
	//process the migration	
	ejax.load('migrators','migrateArticle',finalData);		
}

function divSrolltoBottomSmartBlog()
{
	var objDiv = document.getElementById("progress-status2");
    objDiv.scrollTop = objDiv.scrollHeight;
    
	var objDiv2 = document.getElementById("stat-status2");
    objDiv2.scrollTop = objDiv2.scrollHeight;
}
</script>
<?php if(! $this->smartblogInstalled) { ?>
<?php echo JText::_('COM_EASYBLOG_MIGRATOR_SMARTBLOG_COMPONENT_NOT_FOUND');?>
<?php
}else{ ?>
<form action="" method="post" name="adminForm2" id="adminForm2">
<table class="noshow">
	<tr>		
		<td width="50%" valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_MIGRATOR_SMARTBLOG' ); ?></legend>
			<div>
				<?php echo JText::_('COM_EASYBLOG_MIGRATOR_SMARTBLOG_DESC'); ?>
			</div>
			<div>
				<?php echo JText::_('COM_EASYBLOG_MIGRATOR_SMARTBLOG_NOTICE'); ?>
			</div> 			
						
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_MIGRATOR_SMARTBLOG_MIGRATE_COMMENT' ); ?>::<?php echo JText::_('COM_EASYBLOG_MIGRATOR_SMARTBLOG_MIGRATE_COMMENT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_MIGRATOR_SMARTBLOG_MIGRATE_COMMENT' ); ?>:
					</span>
					</td>
					<td valign="top">
						 <?php echo JHTML::_('select.booleanlist', 'smartblog_comment', 'class="inputbox"', true ); ?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_MIGRATOR_SMARTBLOG_MIGRATE_IMAGE' ); ?>::<?php echo JText::_('COM_EASYBLOG_MIGRATOR_SMARTBLOG_MIGRATE_IMAGE_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_MIGRATOR_SMARTBLOG_MIGRATE_IMAGE' ); ?>:
					</span>
					</td>
					<td valign="top">
						 <?php echo JHTML::_('select.booleanlist', 'smartblog_image', 'class="inputbox"', true ); ?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_MIGRATOR_SMARTBLOG_IMAGE_PATH' ); ?>::<?php echo JText::_('COM_EASYBLOG_MIGRATOR_SMARTBLOG_IMAGE_PATH_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_MIGRATOR_SMARTBLOG_IMAGE_PATH' ); ?>:
					</span>
					</td>
					<td valign="top">
						 <?php echo DS; ?> images <?php echo DS; ?> <input class="inputbox" type="text" value="blogs" name="smartblog_imagepath" id="smartblog_imagepath" size="50"/>
					</td>
				</tr>
				</tbody>
			</table>

			<div style="padding-top:20px;">
				<button id="migrator-submit2" class="button" onclick="migrateSmartBlog();return false;"><?php echo JText::_('COM_EASYBLOG_MIGRATOR_RUN_NOW'); ?></button>
			</div>

			<div id="icon-wait2" style="display:none;">
				<img src="<?php echo JURI::root() . 'administrator/components/com_easyblog/assets/images/wait.gif'; ?>" />&nbsp;&nbsp;<?php echo JText::_('COM_EASYBLOG_MIGRATOR_PLEASE_WAIT'); ?>
			</div>
			
			</fieldset>
		</td>				
		<td>
			<fieldset class="adminform" style="height:200px;">
				<legend><?php echo JText::_( 'COM_EASYBLOG_MIGRATOR_PROGRESS' ); ?></legend>
				<div id="no-progress2"><?php echo JText::_('COM_EASYBLOG_MIGRATOR_NO_PROGRESS_YET'); ?></div>
				<div id="progress-status2"  style="overflow:auto; height:98%;"></div>
			</fieldset>
			
			<fieldset class="adminform" style="height:65px;">
				<legend><?php echo JText::_( 'COM_EASYBLOG_MIGRATOR_STATISTIC' ); ?></legend>
				<div id="stat-status2"  style="overflow:auto; height:98%;"></div>
			</fieldset>
		</td>
	</tr>		
</table>		

<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="com_type" value="com_blog" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="c" value="migrators" />
</form>
<?php } ?>