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
<table  width="100%" class="paramlist admintable" cellspacing="1">
	<tr>
		<td class="key"><span><?php echo JText::_('COM_EASYBLOG_ID'); ?></span></td>
		<td class="paramlist_value"><?php echo  $this->user->get('id'); ?></td>
	</tr>
	<tr>
		<td class="key"><label for="name"><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_NAME'); ?></label></td>
		<td class="paramlist_value"><input class="inputbox" type="text" id="name" name="name" value="<?php echo $this->escape( $this->user->get('name') );?>" style="width: 150px;" /></td>
	</tr>
	<tr>
		<td class="key"><label for="username"><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_USERNAME'); ?></label></td>
		<td class="paramlist_value"><input class="inputbox" type="text" name="username" value="<?php echo $this->user->get('username');?>" id="username" style="width: 150px;" /></td>
	</tr>
	<tr>
		<td class="key"><label for="email"><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_EMAIL'); ?></label></td>
		<td class="paramlist_value"><input type="text" class="inputbox" id="email" name="email" value="<?php echo $this->user->get('email');?>"  style="width: 350px;" /></td>
	</tr>
	<tr>
		<td class="key"><label for="password"><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_NEW_PASSWORD'); ?></label></td>
		<td class="paramlist_value"><input id="password" name="password" class="inputbox" type="password" value="<?php echo isset( $this->post['password'] ) ?  $this->post['password'] : '' ;?>"  style="width: 350px;" /></td>
	</tr>
	<tr>
		<td class="key"><label for="password2"><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_VERIFY_PASSWORD'); ?></label></td>
		<td class="paramlist_value"><input id="password2" name="password2" class="inputbox" type="password" value="" size="40"  style="width: 350px;" /></td>
	</tr>
	<tr>
		<td valign="top" class="key"><label for="gid"><?php echo JText::_( 'COM_EASYBLOG_BLOGGERS_EDIT_USER_GROUP' ); ?></span></td>
		<td><?php echo $this->getGroupsHTML($this->user->id); ?></td>
	</tr>
	<tr>
		<td class="key"><span><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_BLOCK_USER'); ?></span></td>
		<td class="paramlist_value"><?php echo JHTML::_('select.booleanlist',  'block', 'class="inputbox" size="1"', $this->user->get('block') ); ?></td>
	</tr>
	<tr>
		<td class="key"><span><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_RECEIVE_SYSTEM_EMAILS'); ?></span></td>
		<td><?php echo JHTML::_('select.booleanlist',  'sendEmail', 'class="inputbox" size="1"', $this->user->get('sendEmail') ); ?></td>
	</tr>
	<tr>
		<td class="key"><span><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_REGISTER_DATE'); ?></span></td>
		<td><?php echo JHTML::_('date', $this->user->get('registerDate'), $this->dateFormat );?></td>
	</tr>
	<tr>
		<td class="key"><span><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_LAST_VISIT_DATE'); ?></span></td>
		<td><?php echo ($this->user->get('lastvisitDate') == "0000-00-00 00:00:00") ? JText::_('NEVER') : JHTML::_('date', $this->user->get('lastvisitDate'), $this->dateFormat); ?></td>
	</tr>
</table>
<?php
if(EasyBlogHelper::getJoomlaVersion() <= '1.5')
{
	echo $this->params->render( 'params' );
}
else
{
?>
	<table  width="100%" class="paramlist admintable">
	<?php
	foreach($this->form->getFieldset('settings') as $field)
	{
	?>
	<tr>
		<td class="key"><span><?php echo $field->label; ?></span></td>
		<td><?php echo $field->input; ?></td>
	</tr>
	<?php
	}
	?>
	</table>
<?php } ?>