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
		<td class="key"><label for="feedburner_url"><?php echo JText::_('COM_EASYBLOG_BLOGGERS_EDIT_FEEDBURNER_URL'); ?></label></td>
		<td class="paramlist_value"><input class="inputbox full-width" type="text" id="feedburner_url" name="feedburner_url" value="<?php echo $this->feedburner->url;?>" /></td>
	</tr>
</table>	