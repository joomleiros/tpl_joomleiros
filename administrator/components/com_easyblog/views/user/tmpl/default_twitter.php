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
<table  width="100%" class="paramlist admintable paramstable">
<?php 
if(!($this->isNew))
{
?>
<tr>
	<td class="key">
		<?php echo JText::_('COM_EASYBLOG_OAUTH_ALLOW_ACCESS'); ?>
	</td>
	<td class="paramlist_value">
		<?php if( $this->twitter->id && $this->twitter->request_token && $this->twitter->access_token): ?>
		<div>
			<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&c=oauth&task=revoke&type=' . EBLOG_OAUTH_TWITTER . '&id=' . $this->user->id);?>"><?php echo JText::_( 'COM_EASYBLOG_OAUTH_REVOKE_ACCESS' ); ?></a>
		</div>
		<?php else: ?>
		<div><?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_TWITTER_ACCESS_DESC');?></div>
		<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&c=oauth&task=request&type=' . EBLOG_OAUTH_TWITTER . '&id=' . $this->user->id);?>">
			<img src="<?php echo JURI::root();?>components/com_easyblog/assets/images/twitter_signon.png" border="0" alt="here" />
		</a>
  		<?php endif; ?>
	</td>
</tr>
<?php 
}
?>
<tr>
	<td class="key">
		<span for="twitter_passwd"><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_TWITTER_MESSAGE'); ?></span>
	</td>
	<td class="paramlist_value">
		<textarea id="integrations_twitter_message" name="integrations_twitter_message" class="inputbox fullwidth"><?php echo (empty($this->twitter->message)) ? $this->config->get('main_twitter_message', 'Published a new blog entry title:{title} under category:{category}. {link}') : $this->twitter->message; ?></textarea>
	</td>
</tr>
<tr>
    <td class="key"><span><?php echo JText::_('COM_EASYBLOG_OAUTH_ENABLE_AUTO_UPDATES'); ?></span></td>
    <td class="paramlist_value">
		<select name="integrations_twitter_auto">
			<option value="1"<?php echo ($this->twitter->auto == true)? ' selected="selected"' : ''; ?>><?php echo JText::_('COM_EASYBLOG_YES_OPTION'); ?></option>
			<option value="0"<?php echo ($this->twitter->auto == false)? ' selected="selected"' : ''; ?>><?php echo JText::_('COM_EASYBLOG_NO_OPTION'); ?></option>
		</select>
	</td>
</tr>
</table>