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
		<?php if( $this->linkedin->id ): ?>
			<div>
				<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&c=oauth&task=revoke&type=' . EBLOG_OAUTH_LINKEDIN . '&id=' . $this->user->id );?>"><?php echo JText::_( 'COM_EASYBLOG_OAUTH_REVOKE_ACCESS' ); ?></a>
			</div>
		<?php else: ?>
			<div><?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_LINKEDIN_ACCESS_DESC');?></div>
			<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&c=oauth&task=request&type=' . EBLOG_OAUTH_LINKEDIN . '&id=' . $this->user->id );?>">
				<img src="<?php echo JURI::root();?>components/com_easyblog/assets/images/linkedin_signon.png" border="0" alt="here" />
			</a>
    	<?php endif; ?>
	</td>
</tr>
<?php 
}
?>
<tr>
    <td class="key"><label for="integrations_facebook_auto"><?php echo JText::_('COM_EASYBLOG_OAUTH_ENABLE_AUTO_UPDATES'); ?></label></td>
    <td class="paramlist_value">
		<select name="integrations_linkedin_auto" id="integrations_linkedin_auto">
			<option value="1"<?php echo ($this->linkedin->auto == true)? ' selected="selected"' : ''; ?>><?php echo JText::_('COM_EASYBLOG_YES_OPTION'); ?></option>
			<option value="0"<?php echo ($this->linkedin->auto == false)? ' selected="selected"' : ''; ?>><?php echo JText::_('COM_EASYBLOG_NO_OPTION'); ?></option>
		</select>
	</td>
</tr>
<tr>
    <td class="key"><label for="integrations_linkedin_private"><?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_LINKEDIN_PROTECTED_MODE'); ?></label></td>
    <td class="paramlist_value">
    	<input type="checkbox" name="integrations_linkedin_private" id="integrations_linkedin_private" value="1"<?php echo $this->linkedin->private ? ' checked="checked"' : '';?> />
		<span class="small"><?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_LINKEDIN_PROTECTED_MODE_DESC' );?></span>
	</td>
</tr>
</table>