<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *  
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');

?>
<style type="text/css">
iframe.image-manager {
	width: 100%;
	height: 100%;
	border: none;
}
</style>

<script type="text/javascript">
(function($)
{
eblog.showImageManager = function(url)
{
	ejax.dialog({
		width: 700,
		height: 500,
		title: '<?php echo JText::_( 'COM_EASYBLOG_IMAGE_MANAGER_DIALOG_TITLE' );?>',
		content: '',
		beforeDisplay: function()
		{
			var dialog = $(this);
			// Remove padding from dialog
			dialog.find('.dialog-middle').css('padding', 0);
		},
		afterDisplay: function()
		{
			var dialog = $(this);


			// Add image manager iframe
			$('<iframe class="image-manager" frameborder="0">')
				.attr('src', url)
				.appendTo(dialog.find('.dialog-middle-content'));
		},
		afterClose: function()
		{
			var dialog = $(this);
			// Remove padding from dialog
			dialog.find('.dialog-middle').css('padding', '8px 10px');			
		}
	})
}
})(sQuery);
</script>

<a class="ir fleft ico-dimage mrs" href="javascript:void(0);" onclick="eblog.showImageManager('<?php echo JURI::base() . 'index.php?option=com_easyblog&view=images&tmpl=component&e_name=' . $this->editorName . '&blogger_id=' . $this->blogger_id ?>');"><?php echo JText::_( 'COM_EASYBLOG_DASHBOARD_WRITE_INSERT_IMAGE' );?></a>