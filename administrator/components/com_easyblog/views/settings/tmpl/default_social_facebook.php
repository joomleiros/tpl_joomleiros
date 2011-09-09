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

<script type="text/javascript">
function insertFacebookCentralizeId( id , name )
{
	ej('#integrations_facebook_centralized_userid').val(id);
	ej('#integrations_facebook_centralized_username').html(name);

	<?php
	if($this->joomlaversion >= '1.6')
	{
	?>
		window.parent.SqueezeBox.close();
	<?php
	}
	else
	{
	?>
		window.parent.document.getElementById('sbox-window').close();
	<?php
	}
	?>
	
	ej('input:text').each( function() {
		ej(this).addClass('inputbox');
	});
}
</script>

<table class="noshow">
	<tr>
		<td width="50%" valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_ENABLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_ENABLE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_ENABLE' ); ?>
						</span>
					</td>
					<td>
						<?php echo $this->renderCheckbox( 'integrations_facebook' , $this->config->get( 'integrations_facebook' ) );?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_APP_ID' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_APP_ID_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_APP_ID' ); ?>
						</span>
					</td>
					<td>
						<input type="text" name="integrations_facebook_api_key" class="inputbox" value="<?php echo $this->config->get('integrations_facebook_api_key');?>" size="60" />
						<a href="http://stackideas.com/docs.html?view=mediawiki&article=Twitter_OAuth" target="_blank" style="margin-left:5px;"><?php echo JText::_('COM_EASYBLOG_WHAT_IS_THIS'); ?></a>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_SECRET_KEY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_SECRET_KEY_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_SECRET_KEY' ); ?>
						</span>
					</td>
					<td>
						<input type="text" name="integrations_facebook_secret_key" class="inputbox" value="<?php echo $this->config->get('integrations_facebook_secret_key');?>" size="60" />
						<a href="http://stackideas.com/docs.html?view=mediawiki&article=Twitter_OAuth" target="_blank" style="margin-left:5px;"><?php echo JText::_('COM_EASYBLOG_WHAT_IS_THIS'); ?></a>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_ADMIN_ID' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_ADMIN_ID_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_ADMIN_ID' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" name="main_facebook_like_admin" class="inputbox" value="<?php echo $this->config->get('main_facebook_like_admin');?>" size="60" />
						<a href="http://stackideas.com/docs.html?view=mediawiki&article=Facebook_Like_Admin_/_Application_ID" target="_BLANK" style="margin-left:5px;"><?php echo JText::_('COM_EASYBLOG_WHAT_IS_THIS'); ?></a>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_APP_ID' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_APP_ID_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_APP_ID' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" name="main_facebook_like_appid" class="inputbox" value="<?php echo $this->config->get('main_facebook_like_appid');?>" size="60" />
						<a href="http://stackideas.com/docs.html?view=mediawiki&article=Facebook_Like_Admin_/_Application_ID" target="_BLANK" style="margin-left:5px;"><?php echo JText::_('COM_EASYBLOG_WHAT_IS_THIS'); ?></a>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_ENABLE_LIKES' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_ENABLE_LIKES_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_ENABLE_LIKES' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'main_facebook_like' , $this->config->get( 'main_facebook_like' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_SHOW_SEND' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_SHOW_SEND_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_SHOW_SEND' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'main_facebook_like_send' , $this->config->get( 'main_facebook_like_send' ) );?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_FRONTPAGE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_FRONTPAGE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_FRONTPAGE' ); ?>
						</span>
					</td>
					<td>
						<?php echo $this->renderCheckbox( 'integrations_facebook_show_in_listing' , $this->config->get( 'integrations_facebook_show_in_listing' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_SHOW_FACES' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_SHOW_FACES_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_SHOW_FACES' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'main_facebook_like_faces' , $this->config->get( 'main_facebook_like_faces' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key" style="vertical-align: top !important;">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_LAYOUT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_LAYOUT_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_LAYOUT' ); ?>
						</span>
					</td>
					<td valign="top">
						<table width="70%">
							<tr>
								<td valign="top">
									<div>
										<input type="radio" name="main_facebook_like_layout" id="standard" value="standard"<?php echo $this->config->get('main_facebook_like_layout') == 'standard' ? ' checked="checked"' : '';?> />
										<label for="standard"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_BUTTON_STANDARD');?></label>
									</div>
									<div><img src="<?php echo JURI::root() . 'administrator/components/com_easyblog/assets/images/facebook/standard.png';?>" /></div>
								</td>
							</tr>
							<tr>
								<td valign="top">
									<div>
										<input type="radio" name="main_facebook_like_layout" id="button_count" value="button_count"<?php echo $this->config->get('main_facebook_like_layout') == 'button_count' ? ' checked="checked"' : '';?> />
										<label for="button_count"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_BUTTON_COUNT');?></label>
									</div>
									<div><img src="<?php echo JURI::root() . 'administrator/components/com_easyblog/assets/images/facebook/button_count.png';?>" /></div>
								</td>
							</tr>
							<tr>
								<td valign="top">
									<div>
										<input type="radio" name="main_facebook_like_layout" id="box_count" value="box_count"<?php echo $this->config->get('main_facebook_like_layout') == 'box_count' ? ' checked="checked"' : '';?> />
										<label for="box_count"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_BUTTON_BOX_COUNT');?></label>
									</div>
									<div><img src="<?php echo JURI::root() . 'administrator/components/com_easyblog/assets/images/facebook/box_count.png';?>" /></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_WIDTH' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_WIDTH_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_WIDTH' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" name="main_facebook_like_width" class="inputbox" style="width: 50px;" value="<?php echo $this->config->get('main_facebook_like_width');?>" size="5" /> <span class="extra-text"><?php echo JText::_('COM_EASYBLOG_PIXELS');?></span>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_VERB' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_VERB_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_VERB' ); ?>
						</span>
					</td>
					<td valign="top">
						<select name="main_facebook_like_verb" class="inputbox">
							<option<?php echo $this->config->get( 'main_facebook_like_verb' ) == 'like' ? ' selected="selected"' : ''; ?> value="like"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_VERB_LIKES');?></option>
							<option<?php echo $this->config->get( 'main_facebook_like_verb' ) == 'recommend' ? ' selected="selected"' : ''; ?> value="recommend"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_VERB_RECOMMENDS');?></option>
						</select>
					</td>
				</tr>
	
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_THEMES' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_THEMES_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_THEMES' ); ?>
						</span>
					</td>
					<td valign="top">
						<select name="main_facebook_like_theme" class="inputbox">
							<option<?php echo $this->config->get( 'main_facebook_like_theme' ) == 'light' ? ' selected="selected"' : ''; ?> value="light"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_THEMES_LIGHT');?></option>
							<option<?php echo $this->config->get( 'main_facebook_like_theme' ) == 'dark' ? ' selected="selected"' : ''; ?> value="dark"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_LIKE_THEMES_DARK');?></option>
						</select>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
		</td>
		<td width="50%" valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_ADVANCED_SETTINGS_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::sprintf( 'COM_EASYBLOG_INTEGRATIONS_CENTRALIZED', 'Facebook'); ?>::<?php echo JText::sprintf('COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_DESC', 'Facebook'); ?>">
							<?php echo JText::sprintf( 'COM_EASYBLOG_INTEGRATIONS_CENTRALIZED', 'Facebook' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'integrations_facebook_centralized' , $this->config->get( 'integrations_facebook_centralized', false ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_SELECT_USER' ); ?>::<?php echo JText::sprintf('COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_SELECT_USER_DESC', 'Facebook'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_SELECT_USER' ); ?>
						</span>
					</td>
					<td valign="top">
		    			<div style="margin-right: 10px; float: left;" class="half-width">
							<input type="hidden" name="integrations_facebook_centralized_userid" id="integrations_facebook_centralized_userid" value="<?php echo empty($this->centralizedSocialAcount->facebook->user->id)? '0' : $this->centralizedSocialAcount->facebook->user->id; ?>" />
		    				<div style="float: left; margin: 5px 5px 0 0;" id="integrations_facebook_centralized_username"><?php echo $this->centralizedSocialAcount->facebook->user->name; ?></div> 
							<div style="float: left; margin-top: 5px;">[ <a class="modal" rel="{handler: 'iframe', size: {x: 650, y: 375}}" href="index.php?option=com_easyblog&view=users&tmpl=component&browse=1&browsefunction=insertFacebookCentralizeId"><?php echo JText::_('COM_EASYBLOG_BROWSE_USERS');?></a> ]</div>
							<div style="clear:both;"></div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="key"><span class="hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_PAGE_ID' );?>::<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_PAGE_ID_DESC');?>">
					<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_PAGE_ID' ); ?></span></td>
					<td valign="top">
						<input type="text" name="integrations_facebook_page_id" class="inputbox" value="<?php echo $this->config->get('integrations_facebook_page_id');?>" size="30" />
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_PAGE_IMPERSONATION' ); ?>::<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_PAGE_IMPERSONATION_DESC' ); ?>">
							<?php echo JText::sprintf( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_PAGE_IMPERSONATION' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'integrations_facebook_impersonate_page' , $this->config->get( 'integrations_facebook_impersonate_page' ) );?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_OAUTH_ALLOW_ACCESS' ); ?>::<?php echo JText::sprintf('COM_EASYBLOG_OAUTH_ALLOW_ACCESS_DESC', 'Facebook'); ?>">
							<?php echo JText::_('COM_EASYBLOG_OAUTH_ALLOW_ACCESS'); ?>
						</span>
					</td>
					<td class="paramlist_value">
						<?php if(!empty($this->centralizedSocialAcount->facebook->user->id)): ?>
							<?php if( $this->centralizedSocialAcount->facebook->settings->id && $this->centralizedSocialAcount->facebook->settings->request_token && $this->centralizedSocialAcount->facebook->settings->access_token): ?>
							<div>
								<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&c=oauth&task=revoke&type=' . EBLOG_OAUTH_FACEBOOK . '&return=settings&activechild=facebook&id=' . $this->centralizedSocialAcount->facebook->user->id);?>"><?php echo JText::_( 'COM_EASYBLOG_OAUTH_REVOKE_ACCESS' ); ?></a>
							</div>
							<?php else: ?>
							<div><?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_FACEBOOK_ACCESS_DESC');?></div>
							<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&c=oauth&task=request&type=' . EBLOG_OAUTH_FACEBOOK . '&return=settings&activechild=facebook&id=' . $this->centralizedSocialAcount->facebook->user->id);?>">
								<img src="<?php echo JURI::root();?>components/com_easyblog/assets/images/facebook_signon.png" border="0" alt="here" />
							</a>
				    		<?php endif; ?>
			    		<?php else : ?>
						<div>
							<?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_AUTHENTICATION_REQUIRE_USER'); ?>
						</div>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_OAUTH_ENABLE_AUTO_UPDATES' ); ?>::<?php echo JText::sprintf('COM_EASYBLOG_OAUTH_ENABLE_AUTO_UPDATES_DESC', 'Facebook'); ?>">
							<?php echo JText::_('COM_EASYBLOG_OAUTH_ENABLE_AUTO_UPDATES'); ?>
						</span>
					</td>
					<?php if(!empty($this->centralizedSocialAcount->facebook->user->id)): ?>
					    <td class="paramlist_value">
							<?php echo $this->renderCheckbox( 'integrations_facebook_centralized_auto_post' , $this->config->get('integrations_facebook_centralized_auto_post', false) );?>
						</td>
		    		<?php else : ?>
					<td>
						<?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_OPTION_REQUIRE_USER'); ?>
					</td>
					<?php endif; ?>
				</tr>

				<tr>
					<td class="key"><span class="hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_CONTENT_LENGTH' );?>::<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_CONTENT_LENGTH_DESC');?>">
					<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_CONTENT_LENGTH' ); ?></span></td>
					<td valign="top">
						<input type="text" name="integrations_facebook_blogs_length" class="inputbox" value="<?php echo $this->config->get('integrations_facebook_blogs_length');?>" size="5" />
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
		</td>
	</tr>
</table>