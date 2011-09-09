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
<table class="noshow">
	<tr>
		<td width="50%" valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_SETTINGS_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_EMAILS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_EMAILS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_EMAILS' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" class="inputbox full-width" name="notification_email" value="<?php echo $this->config->get('notification_email');?>" />
						<div class="small"><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_EMAILS_EXAMPLE' ); ?></div>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_BLOGS_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_BLOGS_ADMIN' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_BLOGS_ADMIN_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_BLOGS_ADMIN' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'notification_blogadmin' , $this->config->get( 'notification_blogadmin' ) );?>
					</td>
				</tr>
				
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_ALL_MEMBERS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_ALL_MEMBERS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_ALL_MEMBERS' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'notification_allmembers' , $this->config->get( 'notification_allmembers' ) );?>
					</td>
				</tr>
				
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_BLOGS_SUBSCRIBERS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_BLOGS_SUBSCRIBERS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_BLOGS_SUBSCRIBERS' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'notification_blogsubscriber' , $this->config->get( 'notification_blogsubscriber' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_BLOGS_CATEGORIES' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_BLOGS_CATEGORIES_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_BLOGS_CATEGORIES' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'notification_categorysubscriber' , $this->config->get( 'notification_categorysubscriber' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_SITE_SUBSCRIBERS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_SITE_SUBSCRIBERS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_SITE_SUBSCRIBERS' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'notification_sitesubscriber' , $this->config->get( 'notification_sitesubscriber' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_TEAM_SUBSCRIBERS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_TEAM_SUBSCRIBERS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_TEAM_SUBSCRIBERS' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'notification_teamsubscriber' , $this->config->get( 'notification_teamsubscriber' ) );?>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
		</td>
		<td valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_COMMENTS_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_COMMENTS_ADMIN' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_COMMENTS_ADMIN_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_COMMENTS_ADMIN' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'notification_commentadmin' , $this->config->get( 'notification_commentadmin' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_COMMENTS_AUTHOR' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_COMMENTS_AUTHOR_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_COMMENTS_AUTHOR' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'notification_commentauthor' , $this->config->get( 'notification_commentauthor' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_COMMENTS_SUBSCRIBERS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_COMMENTS_SUBSCRIBERS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_FOR_COMMENTS_SUBSCRIBERS' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'notification_commentsubscriber' , $this->config->get( 'notification_commentsubscriber' ) );?>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
			
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_EMAIL_TEMPLATES' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td class="key" valign="top" style="vertical-align: top !important;">
						<label for="email_templates"><?php echo JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_EMAIL_TEMPLATES_FILENAME'); ?></label>
					</td>
					<td valign="top">
						<?php echo $this->getEmailsTemplate(); ?>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
			
		</td>
	</tr>
</table>