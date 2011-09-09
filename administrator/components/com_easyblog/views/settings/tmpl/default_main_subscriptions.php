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
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="top" width="50%">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_SUBSCRIPTIONS_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_SITE_SUBSCRIPTIONS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_SITE_SUBSCRIPTIONS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_SITE_SUBSCRIPTIONS' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'main_sitesubscription' , $this->config->get( 'main_sitesubscription' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_BLOG_SUBSCRIPTIONS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_BLOG_SUBSCRIPTIONS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_BLOG_SUBSCRIPTIONS' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'main_subscription' , $this->config->get( 'main_subscription' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_BLOGGER_SUBSCRIPTIONS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_BLOGGER_SUBSCRIPTIONS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_BLOGGER_SUBSCRIPTIONS' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'main_bloggersubscription' , $this->config->get( 'main_bloggersubscription' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_CATEGORY_SUBSCRIPTIONS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_CATEGORY_SUBSCRIPTIONS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_CATEGORY_SUBSCRIPTIONS' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'main_categorysubscription' , $this->config->get( 'main_categorysubscription' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_TEAM_SUBSCRIPTIONS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_TEAM_SUBSCRIPTIONS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_TEAM_SUBSCRIPTIONS' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'main_teamsubscription' , $this->config->get( 'main_teamsubscription' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_GUEST_TO_SUBSCRIBE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_GUEST_TO_SUBSCRIBE_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_GUEST_TO_SUBSCRIBE' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'main_allowguestsubscribe' , $this->config->get( 'main_allowguestsubscribe' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_GUEST_REGISTRATION_DURING_SUBSCRIBE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_GUEST_REGISTRATION_DURING_SUBSCRIBE_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_GUEST_REGISTRATION_DURING_SUBSCRIBE' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'main_registeronsubscribe' , $this->config->get( 'main_registeronsubscribe' ) );?>
					</td>
				</tr>
                </tbody>
			</table>
			</fieldset>
		</td>
		<td valign="top">&nbsp;</td>
	</tr>
</table>