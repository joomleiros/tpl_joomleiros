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
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_EASYBLOG_CATEGORIES_EDIT_FORM_TITLE'); ?></legend>
		<table class="admintable">
			<tr>
				<td class="key">
					<label for="catname"><?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_EDIT_CATEGORY_NAME' ); ?></label>
				</td>
				<td>
					<input class="inputbox" id="catname" name="title" size="55" maxlength="255" value="<?php echo $this->cat->title;?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="alias"><?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_EDIT_CATEGORY_ALIAS' ); ?></label>
				</td>
				<td>
					<input class="inputbox" id="alias" name="alias" size="55" maxlength="255" value="<?php echo $this->cat->alias;?>" />
				</td>
			</tr>
			<tr>
			    <td class="key"><label for="parent_id"><?php echo JText::_('COM_EASYBLOG_PARENT'); ?></label></td>
				<td><?php echo $this->parentList; ?></td>
			</tr>
			<tr>
	        	<td class="key"><label for="private"><?php echo JText::_('COM_EASYBLOG_CATEGORIES_EDIT_IS_PRIVATE'); ?></label></td>
				<td><?php echo JHTML::_('select.booleanlist', 'private', 'class="inputbox checkbox"', $this->cat->private ); ?></td>
			</tr>
			<?php if($this->config->get('layout_categoryavatar', true)) : ?>
			<tr>
	        	<td class="key"><label for="Filedata"><?php echo JText::_('COM_EASYBLOG_CATEGORIES_EDIT_AVATAR'); ?></label></td>
				<td>
				    <?php if(! empty($this->cat->avatar)) { ?>
						<img style="border-style:solid; float:none;" src="<?php echo $this->cat->getAvatar(); ?>" width="60" height="60"/><br />
				    <?php }//end if ?>
					<?php if ($this->acl->rules->upload_cavatar) : ?>
						<input id="file-upload" type="file" name="Filedata" class="inputbox" size="33"/>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif; ?>
			<tr>
				<td class="key"><label for="published"><?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_EDIT_CATEGORY_PUBLISHED' ); ?></label></td>
				<td><?php echo JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $this->cat->published ); ?></td>						
			</tr>
			<tr style="display: none;">
				<td class="key"><label for="created"><?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_EDIT_CATEGORY_CREATED' ); ?></label></td>
				<td><?php echo JHTML::_('calendar', $this->cat->created , "created", "created"); ?></td>
			</tr>
		</table>
		<?php echo JHTML::_( 'form.token' ); ?>
		<input type="hidden" name="option" value="com_easyblog" />
		<input type="hidden" name="c" value="category" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="catid" value="<?php echo $this->cat->id;?>" />		
	</fieldset>
</form>