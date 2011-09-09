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
		<td valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_AVATARS_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_AVATARS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_AVATARS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_AVATARS' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_avatar' , $this->config->get( 'layout_avatar' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_SHOW_AVATAR_IN_ENTRY_PAGE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_SHOW_AVATAR_IN_ENTRY_PAGE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_SHOW_AVATAR_IN_ENTRY_PAGE' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_avatar_in_read_blog' , $this->config->get( 'layout_avatar_in_read_blog' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_AVATAR_INTEGRATIONS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_AVATAR_INTEGRATIONS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_AVATAR_INTEGRATIONS' ); ?>
					</span>
					</td>
					<td valign="top">
					<?php
  						$nameFormat = array();
						$avatarIntegration[] = JHTML::_('select.option', 'default', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_AVATAR_INTEGRATIONS_DEFAULT' ) );
						$avatarIntegration[] = JHTML::_('select.option', 'communitybuilder', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_AVATAR_INTEGRATIONS_CB' ) );
						$avatarIntegration[] = JHTML::_('select.option', 'gravatar', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_AVATAR_INTEGRATIONS_GRAVATAR' ) );
						$avatarIntegration[] = JHTML::_('select.option', 'jomsocial', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_AVATAR_INTEGRATIONS_JOMSOCIAL' ) );
						$avatarIntegration[] = JHTML::_('select.option', 'kunena', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_AVATAR_INTEGRATIONS_KUNENA' ) );
						$avatarIntegration[] = JHTML::_('select.option', 'phpbb', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_AVATAR_INTEGRATIONS_PHPBB' ) );
						echo JHTML::_('select.genericlist', $avatarIntegration, 'layout_avatarIntegration', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('layout_avatarIntegration' , 'default' ) );
					?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PHPBB_PATH' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_PHPBB_PATH_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PHPBB_PATH' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" name="layout_phpbb_path" class="inputbox full-width" value="<?php echo $this->config->get('layout_phpbb_path', '' );?>" />
					</td>
				</tr>
				
				</tbody>
			</table>
			</fieldset>
		</td>
		<td>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_CATEGORY_AVATAR_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_CATEGORY_AVATARS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_CATEGORY_AVATARS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_CATEGORY_AVATARS' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_categoryavatar' , $this->config->get( 'layout_categoryavatar' ) );?>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_TEAMBLOGS_AVATARS_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_TEAMBLOG_AVATARS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_TEAMBLOG_AVATARS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_TEAMBLOG_AVATARS' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_teamavatar' , $this->config->get( 'layout_teamavatar' ) );?>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
		</td>
	</tr>
</table>