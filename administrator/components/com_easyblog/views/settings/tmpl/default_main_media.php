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
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="top" width="50%">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_TITLE' ); ?></legend>

			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_ENABLE_IMAGE_MANAGER' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_ENABLE_IMAGE_MANAGER_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_ENABLE_IMAGE_MANAGER' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<?php echo $this->renderCheckbox( 'main_imagemanager' , $this->config->get( 'main_imagemanager' ) );?>
					</td>
				</tr>

				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_RESIZE_IMAGE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_RESIZE_IMAGE_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_RESIZE_IMAGE' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'main_resize_image_on_upload' , $this->config->get( 'main_resize_image_on_upload' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_MAXIMUM_IMAGE_WIDTH' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_MAXIMUM_IMAGE_WIDTH_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_MAXIMUM_IMAGE_WIDTH' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="main_maximum_image_width" class="inputbox" style="width: 100px;" value="<?php echo $this->config->get('main_maximum_image_width');?>" />
						<?php echo JText::_( 'COM_EASYBLOG_PIXELS' ); ?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_MAXIMUM_IMAGE_HEIGHT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_MAXIMUM_IMAGE_HEIGHT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_MAXIMUM_IMAGE_HEIGHT' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="main_maximum_image_height" class="inputbox" style="width: 100px;" value="<?php echo $this->config->get('main_maximum_image_height');?>" />
						<?php echo JText::_( 'COM_EASYBLOG_PIXELS' ); ?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_IMAGE_MAX_FILESIZE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_IMAGE_MAX_FILESIZE_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_IMAGE_MAX_FILESIZE' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="main_upload_image_size" class="inputbox" style="width: 100px;" value="<?php echo $this->config->get('main_upload_image_size', '0' );?>" />
						<?php echo JText::_( 'COM_EASYBLOG_MEGABYTES' );?>
						<div><?php echo JText::sprintf( 'COM_EASYBLOG_SETTINGS_WORKFLOW_IMAGE_UPLOAD_PHP_MAXSIZE' , ini_get( 'upload_max_filesize') ); ?></div>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_IMAGE_UPLOADER_TYPE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_IMAGE_UPLOADER_TYPE_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_IMAGE_UPLOADER_TYPE' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php
	  						$options = array();
  							$options[]	= JHTML::_('select.option', 'html4' , JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_IMAGE_UPLOADER_TYPE_HTML' ) );
  							$options[]	= JHTML::_('select.option', 'flash' , JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_IMAGE_UPLOADER_TYPE_FLASH' ) );
							echo JHTML::_('select.genericlist', $options, 'main_upload_client', 'size="1" class="inputbox"', 'value', 'text' , $this->config->get('main_upload_client' ) );
						?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_IMAGE_UPLOAD_QUALITY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_IMAGE_UPLOAD_QUALITY_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_IMAGE_UPLOAD_QUALITY' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php
	  						$options = array();

	  						for( $i = 0; $i <= 100; $i += 10 )
	  						{
	  							$message	= $i;
	  							$message	= $i == 0 ? JText::sprintf( 'COM_EASYBLOG_LOWEST_QUALITY_OPTION' , $i ) : $message;
	  							$message	= $i == 50 ? JText::sprintf( 'COM_EASYBLOG_MEDIUM_QUALITY_OPTION' , $i ) : $message;
	  							$message	= $i == 100 ? JText::sprintf( 'COM_EASYBLOG_HIGHEST_QUALITY_OPTION' , $i ) : $message;
	  							$options[]	= JHTML::_('select.option', $i , $message );
	  						}

							echo JHTML::_('select.genericlist', $options, 'main_upload_quality', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('main_upload_quality' ) );
						?>
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_IMAGE_UPLOAD_QUALITY_HINTS' );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_AVATAR_PATH' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_AVATAR_PATH_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_AVATAR_PATH' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="main_avatarpath" class="inputbox" style="width: 260px;" value="<?php echo $this->config->get('main_avatarpath', 'images/eblog_avatar/' );?>" />
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_CATEGORY_PATH' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_CATEGORY_PATH_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_CATEGORY_PATH' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="main_categoryavatarpath" class="inputbox" style="width: 260px;" value="<?php echo $this->config->get('main_categoryavatarpath', 'images/eblog_cavatar/' );?>" />
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_TEAMBLOG_PATH' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_TEAMBLOG_PATH_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_TEAMBLOG_PATH' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="main_teamavatarpath" class="inputbox" style="width: 260px;" value="<?php echo $this->config->get('main_teamavatarpath', 'images/eblog_tavatar/' );?>" />
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_IMAGE_PATH' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_IMAGE_PATH_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MEDIA_IMAGE_PATH' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="main_image_path" class="inputbox" style="width: 260px;" value="<?php echo $this->config->get('main_image_path', 'images/easyblog_images/' );?>" />
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_IMAGE_DEFAULT_ALIGNMENT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_IMAGE_DEFAULT_ALIGNMENT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_IMAGE_DEFAULT_ALIGNMENT' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php
	  						$options = array();

  							$options[]	= JHTML::_('select.option', 'none' , JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_IMAGE_ALIGN_NOT_SET' ) );
  							$options[]	= JHTML::_('select.option', 'left' , JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_IMAGE_ALIGN_LEFT' ) );
  							$options[]	= JHTML::_('select.option', 'right' , JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_IMAGE_ALIGN_RIGHT' ) );
							echo JHTML::_('select.genericlist', $options, 'main_image_alignment', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('main_image_alignment' ) );
						?>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
		</td>
		<td valign="top">&nbsp;</td>
	</tr>
</table>