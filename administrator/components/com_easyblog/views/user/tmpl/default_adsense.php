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
<?php if( !$this->config->get( 'integration_google_adsense_enable' ) ) : ?>
<tr>
    <td valign="top"><span for="adsense_disabled"><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_ADSENSE_DISABLED_BY_ADMIN'); ?></span></td>
</tr>
<?php else: ?>
<tr>
    <td valign="top" class="key"><span for="adsense_published"><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_ADSENSE_ENABLE'); ?></span></td>
    <td class="paramlist_value">
		<select name="adsense_published">
			<option value="1"<?php echo ($this->adsense->published)? ' selected="selected"' : ''; ?>><?php echo JText::_('COM_EASYBLOG_YES_OPTION'); ?></option>
			<option value="0"<?php echo empty($this->adsense->published)? ' selected="selected"' : ''; ?>><?php echo JText::_('COM_EASYBLOG_NO_OPTION'); ?></option>
		</select>
	</td>
</tr>
<tr>
    <td width="25%" valign="top" class="key"><span for="adsense_code"><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_ADSENSE_CODE'); ?></span></td>
    <td class="paramlist_value"><textarea id="adsense_code" name="adsense_code" class="inputbox fullwidth"><?php echo $this->adsense->code; ?></textarea></td>
</tr>
<tr>
    <td valign="top" class="key"><span><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_ADSENSE_DISPLAY_IN'); ?></span></td>
    <td class="paramlist_value">
		<select name="adsense_display">
			<option value="both"<?php echo ($this->adsense->display == 'both')? ' selected="selected"' : ''; ?>><?php echo JText::_('COM_EASYBLOG_BOTH_HEADER_AND_FOOTER_OPTION'); ?></option>
			<option value="header"<?php echo ($this->adsense->display == 'header')? ' selected="selected"' : ''; ?>><?php echo JText::_('COM_EASYBLOG_HEADER_OPTION'); ?></option>
			<option value="footer"<?php echo ($this->adsense->display == 'footer')? ' selected="selected"' : ''; ?>><?php echo JText::_('COM_EASYBLOG_FOOTER_OPTION'); ?></option>
			<option value="beforecomments"<?php echo ($this->adsense->display == ' beforecomments')? 'selected="selected"' : ''; ?>><?php echo JText::_('COM_EASYBLOG_FOOTER_OPTION'); ?></option>
		</select>
	</td>
</tr>
<?php endif; ?>
</table>	