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
<form action="index.php" method="post" name="adminForm" id="adminForm">
<fieldset class="adminform">
<legend><?php echo JText::_('COM_EASYBLOG_CATEGORIES_EDIT_FORM_TITLE'); ?></legend>
<table class="admintable">
	<tr>
		<td width="30%" class="key">
			<label class="label" for="title"><?php echo JText::_('COM_EASYBLOG_COMMENTS_COMMENT_TITLE'); ?></label>
			<small>(<?php echo JText::_('COM_EASYBLOG_COMMENTS_COMMENT_REQUIRED'); ?>)</small>
		</td>
		<td class="input" valign="top" class="value">
			<input class="inputbox" type="text" id="title" name="title" size="45" value="<?php echo $this->comment->title;?>" />
		</td>
	</tr>
	<tr>
		<td class="key">
			<label class="label" for="name"><?php echo JText::_('COM_EASYBLOG_COMMENTS_COMMENT_AUTHOR_NAME'); ?></label><br />
			<small>(<?php echo JText::_('COM_EASYBLOG_COMMENTS_COMMENT_REQUIRED'); ?>)</small>
		</td>
		<td>
			<input class="inputbox" type="text" id="name" name="name" size="45" value="<?php echo $this->comment->name;?>" />
		</td>
	</tr>
	<tr>
		<td class="key">
			<label class="label" for="email"><?php echo JText::_('COM_EASYBLOG_EMAIL'); ?></label><br />
			<small>(<?php echo JText::_('COM_EASYBLOG_COMMENTS_COMMENT_REQUIRED'); ?>)</small>
		</td>
		<td class="input" valign="top">
			<input class="inputbox" type="text" id="email" name="email" size="45" value="<?php echo $this->comment->email;?>" />
		</td>
	</tr>
	<tr>
		<td class="key"><label class="label" for="url"><?php echo JText::_('COM_EASYBLOG_COMMENTS_COMMENT_AUTHOR_WEBSITE'); ?></label></td>
		<td class="input" valign="top"><input class="inputbox" type="text" id="url" name="url" size="45" value="<?php echo $this->comment->url;?>" /></td>
	</tr>

	<tr>
		<td class="key">
			<label class="label" for="comment"><?php echo JText::_('COM_EASYBLOG_COMMENTS_COMMENT'); ?></label><br />
			<small>(<?php echo JText::_('COM_EASYBLOG_COMMENTS_COMMENT_REQUIRED'); ?>)</small>
		</td>
		<td class="input" valign="top">
			<textarea id="comment" name="comment" class="inputbox" cols="50" rows="5"><?php echo $this->comment->comment;?></textarea>
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="created"><?php echo JText::_( 'COM_EASYBLOG_COMMENTS_COMMENT_CREATED' ); ?></label>
		</td>
		<td><?php echo JHTML::_('calendar', $this->comment->created , "created", "created", '%Y-%m-%d %H:%M:%S', array('size'=>'30')); ?></td>
	</tr>
	<tr>
		<td class="key"><label for="published"><?php echo JText::_( 'COM_EASYBLOG_PUBLISHED' ); ?></label></td>
		<td><?php echo JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $this->comment->published ); ?></td>
	</tr>
</table>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="c" value="comment" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="commentid" value="<?php echo $this->comment->id;?>" />
</form>