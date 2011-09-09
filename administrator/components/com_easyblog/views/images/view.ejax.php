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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport( 'joomla.filesystem.file' );
jimport( 'joomla.filesystem.folder' );

class EasyBlogViewImages extends EasyBlogView
{
	public function deleteImage( $fileName )
	{
		$ajax		= new Ejax();
		$my			= JFactory::getUser();
		$config     = EasyBlogHelper::getConfig();
		$acl		= EasyBlogACLHelper::getRuleSet();

		// @rule: Only allowed users are allowed to upload images.
		if( $my->id == 0 || empty( $acl->rules->upload_image ) )
		{
			echo JText::_( 'COM_EASYBLOG_NOT_ALLOWED' );
			exit;
		}

		$imagePath	= rtrim( $config->get('main_image_path') , '/' );
		$path		= JPATH_ROOT . DS . $imagePath . DS . $my->id;
		$file		= $path . DS . $fileName;
		
		// @rule: Do not continue if folder doesn't even exists.
		if( !JFile::exists( $file ) )
		{
			echo JText::_( 'COM_EASYBLOG_NOT_ALLOWED' );
			exit;
		}

		if( !JFile::delete( $file ) )
		{
			echo JText::_( 'Error deleting file. It could be caused by permission issues. Please contact site administrator.' );
			exit;
		}

		$ajax->callback( array('test', 'data') );

		return $ajax->send();
	}
}
