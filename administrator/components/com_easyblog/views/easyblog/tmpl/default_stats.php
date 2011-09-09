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
<table class="adminlist">
	<tr>
		<td width="30%">
			<?php echo JText::_( 'COM_EASYBLOG_HOME_TOTAL_BLOG_ENTRIES' ); ?>
		</td>
		<td>
			<strong><?php echo JText::sprintf( 'COM_EASYBLOG_HOME_TOTAL_BLOG_ENTRIES_DESC' , $this->getTotalEntries() ); ?></strong>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_( 'COM_EASYBLOG_HOME_UNPUBLISHED_BLOG_ENTRIES' ); ?>
		</td>
		<td>
			<strong><?php echo JText::sprintf( 'COM_EASYBLOG_HOME_UNPUBLISHED_BLOG_ENTRIES_DESC' , $this->getTotalUnpublishedEntries() ); ?></strong>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_( 'COM_EASYBLOG_HOME_TOTAL_TAGS' ); ?>
		</td>
		<td>
			<strong><?php echo JText::sprintf( 'COM_EASYBLOG_HOME_TOTAL_TAGS_DESC' , $this->getTotalTags() ); ?></strong>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_( 'COM_EASYBLOG_HOME_TOTAL_CATEGORIES' ); ?>
		</td>
		<td>
			<strong><?php echo JText::sprintf( 'COM_EASYBLOG_HOME_TOTAL_CATEGORIES_DESC' , $this->getTotalCategories() ); ?></strong>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_( 'COM_EASYBLOG_HOME_TOTAL_COMMENTS' ); ?>
		</td>
		<td>
			<strong><?php echo JText::sprintf( 'COM_EASYBLOG_HOME_TOTAL_COMMENTS_DESC' , $this->getTotalComments() ); ?></strong>
		</td>
	</tr>
</table>
