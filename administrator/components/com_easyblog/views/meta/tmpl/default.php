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
<div id="config-document">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_EASYBLOG_META_TAG_EDIT'); ?></legend>
		<table cellspacing="0" cellpadding="0" border="0" width="50%">
			<tr>
				<td valign="top">
					<table class="admintable">
						<tr>
							<td class="key">
								<label for="keywords">
									<?php echo JText::_( 'Item Title' ); ?>
								</label>
							</td>
							<td>
								<span><strong><?php echo $this->meta->title; ?></strong></span>
							</td>
						</tr>
						
						<tr>
							<td class="key" style="vertical-align:top;">
								<label for="keywords">
									<?php echo JText::_( 'COM_EASYBLOG_META_TAG_EDIT_KEYWORDS' ); ?>
								</label>
							</td>
							<td>
								<textarea id="keywords" name="keywords" class="inputbox" style="width: 300px;height:150px"><?php echo $this->meta->keywords; ?></textarea>
							</td>
						</tr>
						<tr>
							<td class="key" style="vertical-align:top;">
								<label for="description">
									<?php echo JText::_( 'COM_EASYBLOG_META_TAG_EDIT_DESCRIPTION' ); ?>
								</label>
							</td>
							<td>
								<textarea id="description" name="description" class="inputbox" style="width: 300px;height:150px"><?php echo $this->meta->description; ?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php echo JHTML::_( 'form.token' ); ?>
		<input type="hidden" name="option" value="com_easyblog" />
		<input type="hidden" name="c" value="meta" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="id" value="<?php echo $this->meta->id;?>" />		
	</fieldset>
</form>
</div>