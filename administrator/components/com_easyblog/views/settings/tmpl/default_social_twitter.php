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
function insertTwitterCentralizeId( id , name )
{
	ej('#integrations_twitter_centralized_userid').val(id);
	ej('#integrations_twitter_centralized_username').html(name);
	
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
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_INTEGRATIONS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_INTEGRATIONS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_INTEGRATIONS' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'integrations_twitter' , $this->config->get( 'integrations_twitter' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key" style="vertical-align: top !important;">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_DEFAULT_MESSAGE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_DEFAULT_MESSAGE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_DEFAULT_MESSAGE' ); ?>
						</span>
					</td>
					<td valign="top">
						<textarea name="main_twitter_message" class="inputbox half-width" style="margin-bottom: 10px;height: 75px;"><?php echo $this->config->get('main_twitter_message', JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_DEFAULT_MESSAGE_STRING'));?></textarea>
						<div class="notice half-width" style="text-align: left !important;"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_MESSAGE_DESC');?></div>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_API_KEY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_API_KEY_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_API_KEY' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" name="integrations_twitter_api_key" class="inputbox" value="<?php echo $this->config->get('integrations_twitter_api_key');?>" size="60" />
						<a href="http://stackideas.com/docs.html?view=mediawiki&article=Twitter_OAuth" target="_BLANK" style="margin-left:5px;"><?php echo JText::_('COM_EASYBLOG_WHAT_IS_THIS'); ?></a>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_SECRET_KEY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_SECRET_KEY_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_SECRET_KEY' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" name="integrations_twitter_secret_key" class="inputbox" value="<?php echo $this->config->get('integrations_twitter_secret_key');?>" size="60" />
						<a href="http://stackideas.com/docs.html?view=mediawiki&article=Twitter_OAuth" target="_BLANK" style="margin-left:5px;"><?php echo JText::_('COM_EASYBLOG_WHAT_IS_THIS'); ?></a>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BITLY_SHORTEN_URL' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_BITLY_SHORTEN_URL_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BITLY_SHORTEN_URL' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'main_twitter_shorten_url' , $this->config->get( 'main_twitter_shorten_url' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BITLY_LOGIN' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_BITLY_LOGIN_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BITLY_LOGIN' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" name="main_twitter_urlshortener_login" class="inputbox" value="<?php echo $this->config->get('main_twitter_urlshortener_login');?>" size="60" />
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BITLY_APIKEY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_BITLY_APIKEY_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BITLY_APIKEY' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" name="main_twitter_urlshortener_apikey" class="inputbox" value="<?php echo $this->config->get('main_twitter_urlshortener_apikey');?>" size="60" />
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_USE_TWITTER_BUTTON' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_USE_TWITTER_BUTTON_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_USE_TWITTER_BUTTON' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'main_twitter_button' , $this->config->get( 'main_twitter_button' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key" style="vertical-align: top !important;">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_BUTTON_STYLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_BUTTON_STYLE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_BUTTON_STYLE' ); ?>
						</span>
					</td>
					<td valign="top">
						<table width="70%">
							<tr>
								<td valign="top">
									<div>
										<input type="radio" name="main_twitter_button_style" id="tweet_vertical" value="vertical"<?php echo $this->config->get('main_twitter_button_style') == 'vertical' ? ' checked="checked"' : '';?> />
										<label for="tweet_vertical"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_BUTTON_VERTICAL');?></label>
									</div>
									<div style="text-align: center;margin-top: 5px;"><img src="<?php echo JURI::root() . 'administrator/components/com_easyblog/assets/images/tweet/button_vertical.png';?>" /></div>
								</td>
								<td valign="top">
									<div>
										<input type="radio" name="main_twitter_button_style" id="tweet_horizontal" value="horizontal"<?php echo $this->config->get('main_twitter_button_style') == 'horizontal' ? ' checked="checked"' : '';?> />
										<label for="tweet_horizontal"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_BUTTON_HORIZONTAL');?></label>
									</div>
									<div style="text-align: center;margin-top: 5px;"><img src="<?php echo JURI::root() . 'administrator/components/com_easyblog/assets/images/tweet/button_horizontal.png';?>" /></div>
								</td>
								<td valign="top">
									<div>
										<input type="radio" name="main_twitter_button_style" id="tweet_button" value="none"<?php echo $this->config->get('main_twitter_button_style') == 'none' ? ' checked="checked"' : '';?> />
										<label for="tweet_button"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_BUTTON_NOCOUNT');?></label>
									</div>
									<div style="text-align: center;margin-top: 5px;"><img src="<?php echo JURI::root() . 'administrator/components/com_easyblog/assets/images/tweet/button.png';?>" /></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
		</td>
		<td valign="top" width="50%">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_ADVANCED_SETTINGS_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::sprintf( 'COM_EASYBLOG_INTEGRATIONS_CENTRALIZED', 'Twitter' ); ?>::<?php echo JText::sprintf('COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_DESC', 'Twitter'); ?>">
							<?php echo JText::sprintf( 'COM_EASYBLOG_INTEGRATIONS_CENTRALIZED', 'Twitter' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'integrations_twitter_centralized' , $this->config->get( 'integrations_twitter_centralized', false ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_SELECT_USER' ); ?>::<?php echo JText::sprintf('COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_SELECT_USER_DESC', 'Twitter'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_SELECT_USER' ); ?>
						</span>
					</td>
					<td valign="top">
		    			<div style="margin-right: 10px; float: left;" class="half-width">
							<input type="hidden" name="integrations_twitter_centralized_userid" id="integrations_twitter_centralized_userid" value="<?php echo empty($this->centralizedSocialAcount->twitter->user->id)? '0' : $this->centralizedSocialAcount->twitter->user->id; ?>" />
		    				<div style="float: left; margin: 5px 5px 0 0;" id="integrations_twitter_centralized_username"><?php echo $this->centralizedSocialAcount->twitter->user->name; ?></div> 
							<div style="float: left; margin-top: 5px;">[ <a class="modal" rel="{handler: 'iframe', size: {x: 650, y: 375}}" href="index.php?option=com_easyblog&view=users&tmpl=component&browse=1&browsefunction=insertTwitterCentralizeId"><?php echo JText::_('COM_EASYBLOG_BROWSE_USERS');?></a> ]</div>
							<div style="clear:both;"></div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_OAUTH_ALLOW_ACCESS' ); ?>::<?php echo JText::sprintf('COM_EASYBLOG_OAUTH_ALLOW_ACCESS_DESC', 'Twitter'); ?>">
							<?php echo JText::_('COM_EASYBLOG_OAUTH_ALLOW_ACCESS'); ?>
						</span>
					</td>
					<td class="paramlist_value">
						<?php if(!empty($this->centralizedSocialAcount->twitter->user->id)): ?>
							<?php if( $this->centralizedSocialAcount->twitter->settings->id && $this->centralizedSocialAcount->twitter->settings->request_token && $this->centralizedSocialAcount->twitter->settings->access_token): ?>
							<div>
								<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&c=oauth&task=revoke&type=' . EBLOG_OAUTH_TWITTER . '&return=settings&activechild=twitter&id=' . $this->centralizedSocialAcount->twitter->user->id);?>"><?php echo JText::_( 'COM_EASYBLOG_OAUTH_REVOKE_ACCESS' ); ?></a>
							</div>
							<?php else: ?>
							<div><?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_TWITTER_ACCESS_DESC');?></div>
							<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&c=oauth&task=request&type=' . EBLOG_OAUTH_TWITTER . '&return=settings&activechild=twitter&id=' . $this->centralizedSocialAcount->twitter->user->id);?>">
								<img src="<?php echo JURI::root();?>components/com_easyblog/assets/images/twitter_signon.png" border="0" alt="here" />
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
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_OAUTH_ENABLE_AUTO_UPDATES' ); ?>::<?php echo JText::sprintf('COM_EASYBLOG_OAUTH_ENABLE_AUTO_UPDATES_DESC', 'Twitter'); ?>">
							<?php echo JText::_('COM_EASYBLOG_OAUTH_ENABLE_AUTO_UPDATES'); ?>
						</span>
					</td>
					<?php if(!empty($this->centralizedSocialAcount->twitter->user->id)): ?>
					    <td class="paramlist_value">
							<?php echo $this->renderCheckbox( 'integrations_twitter_centralized_auto_post' , $this->config->get('integrations_twitter_centralized_auto_post', false) );?>
						</td>
		    		<?php else : ?>
					<td>
						<?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_OPTION_REQUIRE_USER'); ?>
					</td>
					<?php endif; ?>
				</tr>			
				</tbody>
			</table>
			</fieldset>
		</td>
	</tr>
</table>	