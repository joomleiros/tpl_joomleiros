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

<form name="adminForm" method="post" action="index.php">
<table id="easyblog_panel">
	<tr>
		<td valign="top" width="60%">
			<table width="100%" class="cpanel-items" cellpadding="7" cellspacing="3">
				<tr>
					<td width="50%">
					<?php $this->addButton( JRoute::_('index.php?option=com_easyblog&view=settings') , 'settings.png' , JText::_('COM_EASYBLOG_HOME_SETTINGS') , JText::_('COM_EASYBLOG_HOME_SETTINGS_DESC')); ?>
					</td>
					<td>
						<?php $this->addButton( JRoute::_('index.php?option=com_easyblog&view=blogs') , 'blogs.png' , JText::_('COM_EASYBLOG_HOME_BLOG_ENTRIES') , JText::_('COM_EASYBLOG_HOME_BLOG_ENTRIES_DESC')); ?>
					</td>
				</tr>
				<tr>
					<td width="50%">
						<?php $this->addButton( JRoute::_('index.php?option=com_easyblog&view=categories') , 'categories.png' , JText::_('COM_EASYBLOG_HOME_CATEGORIES') , JText::_('COM_EASYBLOG_HOME_CATEGORIES_DESC')); ?>
					</td>
					<td>
						<?php $this->addButton( JRoute::_('index.php?option=com_easyblog&view=pending') , 'pending.png' , JText::_('COM_EASYBLOG_HOME_PENDING_POSTS') , JText::_('COM_EASYBLOG_HOME_PENDING_POSTS_DESC')); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php $this->addButton( JRoute::_('index.php?option=com_easyblog&view=tags') , 'tags.png' , JText::_('COM_EASYBLOG_HOME_TAGS') , JText::_('COM_EASYBLOG_HOME_TAGS_DESC')); ?>
						
					</td>
					<td>
						<?php $this->addButton( JRoute::_('index.php?option=com_easyblog&view=comments') , 'comments.png' , JText::_('COM_EASYBLOG_HOME_COMMENTS'), JText::_('COM_EASYBLOG_HOME_COMMENTS_DESC')); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php $this->addButton( JRoute::_('index.php?option=com_easyblog&view=trackbacks') , 'trackback.png' , JText::_('COM_EASYBLOG_HOME_TRACKBACKS'), JText::_('COM_EASYBLOG_HOME_TRACKBACKS_DESC')); ?>
					</td>
					<td>
						<?php $this->addButton( JRoute::_('index.php?option=com_easyblog&view=users') , 'users.png' , JText::_('COM_EASYBLOG_HOME_BLOGGERS'), JText::_('COM_EASYBLOG_HOME_BLOGGERS_DESC')); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php $this->addButton( JRoute::_('index.php?option=com_easyblog&view=teamblogs') , 'teamblogs.png' , JText::_('COM_EASYBLOG_HOME_TEAM_BLOGS') , JText::_('COM_EASYBLOG_HOME_TEAM_BLOGS_DESC')); ?>
					</td>
					<td>
						<?php $this->addButton( JRoute::_('index.php?option=com_easyblog&view=acls') , 'acls.png' , JText::_('COM_EASYBLOG_HOME_ACL') , JText::_('COM_EASYBLOG_HOME_ACL_DESC')); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php $this->addButton( JRoute::_('index.php?option=com_easyblog&view=metas') , 'meta.png' , JText::_('COM_EASYBLOG_HOME_META_TAGS') , JText::_('COM_EASYBLOG_HOME_META_TAGS_DESC')); ?>
					</td>
					<td>
						<?php $this->addButton( JRoute::_('index.php?option=com_easyblog&view=subscriptions') , 'subscriptions.png' , JText::_('COM_EASYBLOG_HOME_SUBSCRIPTIONS') , JText::_('COM_EASYBLOG_HOME_SUBSCRIPTIONS_DESC')); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php $this->addButton( JRoute::_('index.php?option=com_easyblog&view=migrators') , 'migrators.png' , JText::_('COM_EASYBLOG_HOME_MIGRATORS') , JText::_('COM_EASYBLOG_HOME_MIGRATORS_DESC')); ?>
					</td>
				</tr>
			</table>
			<div class="clr"></div>	
			<div id="easyblog_tips"></div>
		</td>
		<td valign="top" width="38%" style="padding: 7px 5px 0 5px;">
			<?php echo $this->loadTemplate('rightcol'); ?>
		</td>
	</tr>
</table>
<div style="text-align: right;">
	<?php echo JText::_('Developed and brought to you by <a href="http://stackideas.com" target="_blank">Stack Ideas Private Limited</a>');?>
</div>
</form>

<div id="versionTracker" style="display: none;">
	<?php echo $this->getVersion(); ?>
</div>