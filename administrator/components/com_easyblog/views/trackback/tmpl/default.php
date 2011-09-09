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
		<td class="key"><label class="label" for="blog_name"><?php echo JText::_('COM_EASYBLOG_TRACKBACKS_TRACKBACK_NAME'); ?></label></td>
		<td class="input" valign="top"><input class="inputbox" type="text" id="blog_name" name="blog_name" size="45" value="<?php echo $this->trackback->blog_name;?>" /></td>
	</tr>
	<tr>
		<td width="30%" class="key">
			<label class="label" for="title"><?php echo JText::_('COM_EASYBLOG_TRACKBACKS_TRACKBACK_TITLE'); ?></label>
		</td>
		<td class="input" valign="top" class="value">
			<input class="inputbox" type="text" id="title" name="title" size="45" value="<?php echo $this->trackback->title;?>" />
		</td>
	</tr>
	<tr>
		<td class="key" style="vertical-align: top;">
			<label class="label" for="excerpt"><?php echo JText::_('COM_EASYBLOG_TRACKBACKS_TRACKBACK_EXCERPT'); ?></label><br />
		</td>
		<td>
			<textarea name="excerpt" id="excerpt" class="inputbox" style="height: 150px; width: 250px;" ><?php echo $this->trackback->excerpt;?></textarea>
		</td>
	</tr>
	<tr>
		<td class="key">
			<label class="label" for="url"><?php echo JText::_('COM_EASYBLOG_TRACKBACKS_TRACKBACK_URL'); ?></label><br />
		</td>
		<td class="input" valign="top">
			<input class="inputbox" type="text" id="url" name="url" size="45" value="<?php echo $this->trackback->url;?>" />
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="created"><?php echo JText::_( 'COM_EASYBLOG_TRACKBACKS_TRACKBACK_CREATED' ); ?></label>
		</td>
		<td><?php echo JHTML::_('calendar', $this->trackback->created , "created", "created", '%Y-%m-%d %H:%M:%S', array('size'=>'30')); ?></td>
	</tr>
	<tr>
		<td class="key"><label for="published"><?php echo JText::_( 'COM_EASYBLOG_PUBLISHED' ); ?></label></td>
		<td><?php echo JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $this->trackback->published ); ?></td>
	</tr>
</table>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="c" value="trackback" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="id" value="<?php echo $this->trackback->id;?>" />
</form>