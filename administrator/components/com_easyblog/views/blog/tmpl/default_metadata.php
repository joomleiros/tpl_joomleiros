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
<table class="admintable" cellspacing="0" cellpadding="5">
	<tr>
		<td class="paramlist_key" width="10%"><label for="description" class="label label-title"><?php echo JText::_('COM_EASYBLOG_BLOGS_META_DESCRIPTION'); ?></label></td>
		<td class="paramlist_value"><textarea name="description" id="description" class="inputbox full-width"><?php echo $this->meta->description; ?></textarea></td>
	</tr>
	<tr>
		<td class="paramlist_key" width="10%"><label for="keywords" class="label label-title"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_META_KEYWORDS' ); ?></label></td>
		<td class="paramlist_value">
			<textarea name="keywords" id="keywords" class="inputbox full-width"><?php echo $this->meta->keywords; ?></textarea>
			<div><small>( <?php echo JText::_('COM_EASYBLOG_BLOGS_META_KEYWORDS_INSTRUCTIONS'); ?> )</small></div>
		</td>
	</tr>
</table>