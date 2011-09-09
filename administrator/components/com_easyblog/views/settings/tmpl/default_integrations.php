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
<script language="javascript" type="text/javascript">
	function syncPosition(ele)
	{
	    var position = ele.value;
	    
	    ej('#main_tweetmeme_position').val(position);
	    ej('#main_googlebuzz_position').val(position);
	}
</script>
<table class="noshow">
	<tr>
		<td width="50%" valign="top">
			<table class="noshow">
				<tr>
					<td>			
						<a name="main_config" id="aup_config"></a>
						<fieldset class="adminform">
						<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_AUP_TITLE' ); ?></legend>
						<p><?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_AUP_INSTRUCTIONS');?></p>
						<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_AUP_ENABLE_INTEGRATIONS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_AUP_ENABLE_INTEGRATIONS_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_AUP_ENABLE_INTEGRATIONS' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'main_alpha_userpoint' , $this->config->get( 'main_alpha_userpoint' ) );?>
								</td>
							</tr>
							</tbody>
						</table>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td>			
						<a name="main_config" id="feedburner_config"></a>
						<fieldset class="adminform">
						<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_FEEDBURNER_TITLE' ); ?></legend>
						
						<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_ENABLE_FEEDBURNER_INTEGRATIONS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_ENABLE_FEEDBURNER_INTEGRATIONS_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_ENABLE_FEEDBURNER_INTEGRATIONS' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'main_feedburner' , $this->config->get( 'main_feedburner' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_ALLOW_BLOGGERS_TO_USE_FEEDBURNER' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_ALLOW_BLOGGERS_TO_USE_FEEDBURNER_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_ALLOW_BLOGGERS_TO_USE_FEEDBURNER' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'main_feedburnerblogger' , $this->config->get( 'main_feedburnerblogger' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_FEEDBURNER_URL' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_FEEDBURNER_URL_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_FEEDBURNER_URL' ); ?>
								</span>
								</td>
								<td valign="top">
									<input type="text" name="main_feedburner_url" class="inputbox full-width" value="<?php echo $this->config->get('main_feedburner_url');?>" size="60" />
								</td>
							</tr>
							</tbody>
						</table>
						</fieldset>		
					</td>
				</tr>
				
				<tr>
					<td>
						<a name="main_config" id="feedburner_config"></a>
						<fieldset class="adminform">
						<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE' ); ?></legend>

						<table class="admintable" cellspacing="1">
							<tbody>
							
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_ENABLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_ENABLE_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_ENABLE' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'integration_google_adsense_enable' , $this->config->get( 'integration_google_adsense_enable' ) );?>
								</td>
							</tr>
							
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_USE_CENTRALIZED' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_USE_CENTRALIZED_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_USE_CENTRALIZED' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'integration_google_adsense_centralized' , $this->config->get( 'integration_google_adsense_centralized' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key" valign="top">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_CODE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_CODE_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_CODE' ); ?>
								</span>
								</td>
								<td valign="top">
									<textarea name="integration_google_adsense_code" class="inputbox full-width" style="margin-bottom: 10px;height: 75px;"><?php echo $this->config->get('integration_google_adsense_code');?></textarea>
									<div class="notice full-width" style="text-align: left !important;"><?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_CODE_EXAMPLE');?></div>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_DISPLAY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_DISPLAY_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_DISPLAY' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php
									$display = array();
									$display[] = JHTML::_('select.option', 'both', JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_BOTH' ) );
									$display[] = JHTML::_('select.option', 'header', JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_HEADER' ) );
									$display[] = JHTML::_('select.option', 'footer', JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_FOOTER' ) );
									$display[] = JHTML::_('select.option', 'beforecomments', JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_GOOGLE_ADSENSE_BEFORE_COMMENT' ) );
									$showOption = JHTML::_('select.genericlist', $display, 'integration_google_adsense_display', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('integration_google_adsense_display' , 'both' ) );
									echo $showOption;
									?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_ADSENSE_DISPLAY_ACCESS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_ADSENSE_DISPLAY_ACCESS_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_ADSENSE_DISPLAY_ACCESS' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php
									$display = array();
									$display[] = JHTML::_('select.option', 'both', JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_ADSENSE_DISPLAY_ALL' ) );
									$display[] = JHTML::_('select.option', 'members', JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_ADSENSE_DISPLAY_MEMBERS' ) );
									$display[] = JHTML::_('select.option', 'guests', JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_ADSENSE_DISPLAY_GUESTS' ) );
									$showOption = JHTML::_('select.genericlist', $display, 'integration_google_adsense_display_access', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('integration_google_adsense_display_access' , 'both' ) );
									echo $showOption;
									?>
								</td>
							</tr>
							</tbody>
						</table>
						</fieldset>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<table class="noshow">
				<tr>
					<td>			
						<a name="main_config" id="jomsocial_config"></a>
						<fieldset class="adminform">
						<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_TITLE' ); ?></legend>
						<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_PRIVACY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_PRIVACY_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_PRIVACY' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'main_jomsocial_privacy' , $this->config->get( 'main_jomsocial_privacy' ) );?>
									<div class="small"><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_PRIVACY_INFO' );?></div>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_TOOLBAR' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_TOOLBAR_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_TOOLBAR' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'integrations_jomsocial_toolbar' , $this->config->get( 'integrations_jomsocial_toolbar' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_FRIEND' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_FRIEND_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_FRIEND' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'main_jomsocial_friends' , $this->config->get( 'main_jomsocial_friends' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_JOMSOCIAL_PROFILE_LINK' ); ?>::<?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_JOMSOCIAL_PROFILE_LINK_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_JOMSOCIAL_PROFILE_LINK' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'integrations_jomsocial_profile_link' , $this->config->get( 'integrations_jomsocial_profile_link' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_MESSAGING' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_MESSAGING_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_MESSAGING' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'main_jomsocial_messaging' , $this->config->get( 'main_jomsocial_messaging' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_USERPOINT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_USERPOINT_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_USERPOINT' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'main_jomsocial_userpoint' , $this->config->get( 'main_jomsocial_userpoint' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_BLOGGER_STATUS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_BLOGGER_STATUS_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_BLOGGER_STATUS' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'integrations_jomsocial_blogger_status' , $this->config->get( 'integrations_jomsocial_blogger_status' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_NEW_POST_ACTIVITY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_NEW_POST_ACTIVITY_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_NEW_POST_ACTIVITY' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'integrations_jomsocial_blog_new_activity' , $this->config->get( 'integrations_jomsocial_blog_new_activity' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_UPDATE_POST_ACTIVITY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_UPDATE_POST_ACTIVITY_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_UPDATE_POST_ACTIVITY' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'integrations_jomsocial_blog_update_activity' , $this->config->get( 'integrations_jomsocial_blog_update_activity' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_NEW_COMMENT_ACTIVITY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_NEW_COMMENT_ACTIVITY_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_NEW_COMMENT_ACTIVITY' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'integrations_jomsocial_comment_new_activity' , $this->config->get( 'integrations_jomsocial_comment_new_activity' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_ACTIVITY_SUBMIT_CONTENT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_ACTIVITY_SUBMIT_CONTENT_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_ACTIVITY_SUBMIT_CONTENT' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'integrations_jomsocial_submit_content' , $this->config->get( 'integrations_jomsocial_submit_content' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_ACTIVITY_DISPLAY_CATEGORY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_ACTIVITY_DISPLAY_CATEGORY_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_ACTIVITY_DISPLAY_CATEGORY' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'integrations_jomsocial_show_category' , $this->config->get( 'integrations_jomsocial_show_category' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_ACTIVITY_BLOG_LENGTH' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_ACTIVITY_BLOG_LENGTH_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_ACTIVITY_BLOG_LENGTH' ); ?>
								</span>
								</td>
								<td valign="top">
									<input type="text" name="integrations_jomsocial_blogs_length" class="inputbox" style="width: 50px;" value="<?php echo $this->config->get('integrations_jomsocial_blogs_length');?>" size="5" /> <span class="extra-text"><?php echo JText::_('COM_EASYBLOG_CHARACTERS');?></span>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_ACTIVITY_COMMENT_LENGTH' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_ACTIVITY_COMMENT_LENGTH_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_JOMSOCIAL_ACTIVITY_COMMENT_LENGTH' ); ?>
								</span>
								</td>
								<td valign="top">
									<input type="text" name="integrations_jomsocial_comments_length" class="inputbox" style="width: 50px;" value="<?php echo $this->config->get('integrations_jomsocial_comments_length');?>" size="5" /> <span class="extra-text"><?php echo JText::_('COM_EASYBLOG_CHARACTERS');?></span>
								</td>
							</tr>
							</tbody>
						</table>
						</fieldset>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>