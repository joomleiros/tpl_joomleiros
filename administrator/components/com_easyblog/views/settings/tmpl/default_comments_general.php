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
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>				
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_COMMENT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_COMMENT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_COMMENT' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'main_comment' , $this->config->get( 'main_comment' ) );?> 
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_BBCODE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_BBCODE_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_BBCODE' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'comment_bbcode' , $this->config->get( 'comment_bbcode' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_LIKES' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_LIKES_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_LIKES' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'comment_likes' , $this->config->get( 'comment_likes' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_GUEST_COMMENT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_GUEST_COMMENT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_GUEST_COMMENT' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'main_allowguestcomment' , $this->config->get( 'main_allowguestcomment' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_GUEST_REGISTRATION_WHEN_COMMENTING' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_GUEST_REGISTRATION_WHEN_COMMENTING_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_GUEST_REGISTRATION_WHEN_COMMENTING' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'comment_registeroncomment' , $this->config->get( 'comment_registeroncomment' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_AUTO_TITLE_IN_REPLY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_AUTO_TITLE_IN_REPLY_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_AUTO_TITLE_IN_REPLY' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'comment_autotitle' , $this->config->get( 'comment_autotitle' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_REQUIRE_TITLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_REQUIRE_TITLE_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_REQUIRE_TITLE' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'comment_requiretitle' , $this->config->get( 'comment_requiretitle' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_TERMS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_TERMS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_ENABLE_TERMS' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'comment_tnc' , $this->config->get( 'comment_tnc' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_THREADED_LEVEL' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_THREADED_LEVEL_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_THREADED_LEVEL' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" class="inputbox" name="comment_maxthreadedlevel" value="<?php echo $this->config->get( 'comment_maxthreadedlevel' );?>" size="10" />
					</td>
				</tr>
				<tr>
					<td width="300" class="key" style="vertical-align: top;">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_TERMS_TEXT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_TERMS_TEXT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_TERMS_TEXT' ); ?>
					</span>
					</td>
					<td valign="top">						
						<textarea name="comment_tnctext" class="inputbox full-width" cols="25" rows="15"><?php echo str_replace('<br />', "\n", $this->config->get('comment_tnctext' )); ?></textarea>
					</td>
				</tr>
			</tbody>
			</table>
			</fieldset>
		</td>
		<td valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_MODERATION_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_MODERATE_NEW_COMMENT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_MODERATE_NEW_COMMENT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_MODERATE_NEW_COMMENT' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'comment_moderatecomment' , $this->config->get( 'comment_moderatecomment' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_MODERATE_BLOG_AUTHORS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_MODERATE_BLOG_AUTHORS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_MODERATE_BLOG_AUTHORS' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'comment_moderateauthorcomment' , $this->config->get( 'comment_moderateauthorcomment' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_MODERATE_GUEST_COMMENTS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_MODERATE_GUEST_COMMENTS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_MODERATE_GUEST_COMMENTS' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'comment_moderateguestcomment' , $this->config->get( 'comment_moderateguestcomment' ) );?>
					</td>
				</tr>
			</table>
			</fieldset>
		</td>
	</tr>
</table>