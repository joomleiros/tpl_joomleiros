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
function insertLinkedinCentralizeId( id , name )
{
	ej('#integrations_linkedin_centralized_userid').val(id);
	ej('#integrations_linkedin_centralized_username').html(name);

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
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_LINKEDIN_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_LINKEDIN_ENABLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_LINKEDIN_ENABLE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_LINKEDIN_ENABLE' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'integrations_linkedin' , $this->config->get( 'integrations_linkedin' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key" style="vertical-align: top !important;">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_LINKEDIN_DEFAULT_MESSAGE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_LINKEDIN_DEFAULT_MESSAGE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_LINKEDIN_DEFAULT_MESSAGE' ); ?>
						</span>
					</td>
					<td valign="top">
						<textarea name="main_linkedin_message" class="inputbox half-width" style="margin-bottom: 10px;height: 75px;"><?php echo $this->config->get('main_linkedin_message', JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_DEFAULT_MESSAGE_STRING'));?></textarea>
						<div class="notice half-width" style="text-align: left !important;"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_MESSAGE_DESC');?></div>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_LINKEDIN_API_KEY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_LINKEDIN_API_KEY_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_LINKEDIN_API_KEY' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" name="integrations_linkedin_api_key" class="inputbox" value="<?php echo $this->config->get('integrations_linkedin_api_key');?>" size="60" />
						<a href="http://stackideas.com/docs.html?view=mediawiki&article=Twitter_OAuth" target="_blank" style="margin-left:5px;"><?php echo JText::_('COM_EASYBLOG_WHAT_IS_THIS'); ?></a>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_LINKEDIN_SECRET_KEY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_LINKEDIN_SECRET_KEY_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_LINKEDIN_SECRET_KEY' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" name="integrations_linkedin_secret_key" class="inputbox" value="<?php echo $this->config->get('integrations_linkedin_secret_key');?>" size="60" />
						<a href="http://stackideas.com/docs.html?view=mediawiki&article=Twitter_OAuth" target="_BLANK" style="margin-left:5px;"><?php echo JText::_('COM_EASYBLOG_WHAT_IS_THIS'); ?></a>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
		</td>
		<td width="50%" valign="top">
			<a name="linked_centralized_config" id="linked_centralized_config"></a>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_ADVANCED_SETTINGS_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::sprintf( 'COM_EASYBLOG_INTEGRATIONS_CENTRALIZED', 'LinkedIn'); ?>::<?php echo JText::sprintf('COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_DESC', 'LinkedIn'); ?>">
							<?php echo JText::sprintf( 'COM_EASYBLOG_INTEGRATIONS_CENTRALIZED', 'LinkedIn' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'integrations_linkedin_centralized' , $this->config->get( 'integrations_linkedin_centralized', false ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_SELECT_USER' ); ?>::<?php echo JText::sprintf('COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_SELECT_USER_DESC', 'LinkedIn'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_SELECT_USER' ); ?>
						</span>
					</td>
					<td valign="top">
		    			<div style="margin-right: 10px; float: left;" class="half-width">
							<input type="hidden" name="integrations_linkedin_centralized_userid" id="integrations_linkedin_centralized_userid" value="<?php echo empty($this->centralizedSocialAcount->linkedin->user->id)? '0' : $this->centralizedSocialAcount->linkedin->user->id; ?>" />
		    				<div style="float: left; margin: 5px 5px 0 0;" id="integrations_linkedin_centralized_username"><?php echo $this->centralizedSocialAcount->linkedin->user->name; ?></div> 
							<div style="float: left; margin-top: 5px;">[ <a class="modal" rel="{handler: 'iframe', size: {x: 650, y: 375}}" href="index.php?option=com_easyblog&view=users&tmpl=component&browse=1&browsefunction=insertLinkedinCentralizeId"><?php echo JText::_('COM_EASYBLOG_BROWSE_USERS');?></a> ]</div>
							<div style="clear:both;"></div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_OAUTH_ALLOW_ACCESS' ); ?>::<?php echo JText::sprintf('COM_EASYBLOG_OAUTH_ALLOW_ACCESS_DESC', 'LinkedIn'); ?>">
							<?php echo JText::_('COM_EASYBLOG_OAUTH_ALLOW_ACCESS'); ?>
						</span>
					</td>
					<td class="paramlist_value">
						<?php if(!empty($this->centralizedSocialAcount->linkedin->user->id)): ?>
							<?php if( $this->centralizedSocialAcount->linkedin->settings->id && $this->centralizedSocialAcount->linkedin->settings->request_token && $this->centralizedSocialAcount->linkedin->settings->access_token): ?>
							<div>
								<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&c=oauth&task=revoke&type=' . EBLOG_OAUTH_LINKEDIN . '&return=settings&activechild=linkedin&id=' . $this->centralizedSocialAcount->linkedin->user->id);?>"><?php echo JText::_( 'COM_EASYBLOG_OAUTH_REVOKE_ACCESS' ); ?></a>
							</div>
							<?php else: ?>
							<div><?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_LINKEDIN_ACCESS_DESC');?></div>
							<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&c=oauth&task=request&type=' . EBLOG_OAUTH_LINKEDIN . '&return=settings&activechild=linkedin&id=' . $this->centralizedSocialAcount->linkedin->user->id);?>">
								<img src="<?php echo JURI::root();?>components/com_easyblog/assets/images/linkedin_signon.png" border="0" alt="here" />
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
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_OAUTH_ENABLE_AUTO_UPDATES' ); ?>::<?php echo JText::sprintf('COM_EASYBLOG_OAUTH_ENABLE_AUTO_UPDATES_DESC', 'LinkedIn'); ?>">
							<?php echo JText::_('COM_EASYBLOG_OAUTH_ENABLE_AUTO_UPDATES'); ?>
						</span>
					</td>
					<?php if(!empty($this->centralizedSocialAcount->linkedin->user->id)): ?>
					    <td class="paramlist_value">
							<?php echo $this->renderCheckbox( 'integrations_linkedin_centralized_auto_post' , $this->config->get('integrations_linkedin_centralized_auto_post', false) );?>
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