<?php
/**
 * @version		$Id: file.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

require_once( JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_media' . DS . 'helpers' . DS. 'media.php' );
require_once( EBLOG_HELPERS . DS . 'image.php' );
require_once( EBLOG_CLASSES . DS . 'simpleimage.php' );

/**
 * Weblinks Weblink Controller
 *
 * @package		Joomla
 * @subpackage	Weblinks
 * @since 1.5
 */
//class MediaControllerFile extends MediaController
class EasyBlogControllerImages extends JController
{
	function ajaxupload()
	{
		$mainframe	=& JFactory::getApplication();
		$my         =& JFactory::getUser();
		$config     =& EasyBlogHelper::getConfig();
		$acl		= EasyBlogACLHelper::getRuleSet();
		
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );
		
		// @rule: Only allowed users are allowed to upload images.
		if( $my->id == 0 || empty( $acl->rules->upload_image ) )
		{
			echo JText::_( 'COM_EASYBLOG_NOT_ALLOWED' );
			exit;
		}
		
		
		// check if the blogger_id is given or not
		$blogger_id = JRequest::getInt( 'bloggger_id', $my->id );
		
	    if( $my->id != $blogger_id)
	    {
	        // now we knwo this two is different person.
			// lets check if the current user have the power to upload photos into other ppl folder or not.
			if( ! EasyBlogHelper::isSiteAdmin( $my->id ) )
			{
				echo JText::_( 'COM_EASYBLOG_NOT_ALLOWED' );
				exit;
			}
	    }

		$file 		= JRequest::getVar( 'file', '', 'files', 'array' );
		$err		= null;
		
		$main_image_path	= $config->get('main_image_path');
        $main_image_path 	= rtrim($main_image_path, '/');
		
		$userUploadPath    	= JPATH_ROOT . DS . str_replace('/', DS, $main_image_path . DS . $blogger_id);
		$folder     		= JPath::clean( $userUploadPath );

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Make the filename safe
		jimport('joomla.filesystem.file');
		$file['name']	= JFile::makeSafe($file['name']);
		
		$filename	= strtolower( $file['name'] );
		$filepath 	= JPath::clean( $folder . DS . $filename );
		
		// @task: try to rename the file if another image with the same name exists
		if( JFile::exists( $filepath ) )
		{
			$i	= 1;
			while( JFile::exists( $filepath ) )
			{
				$tmpName	= $i . '_' . $filename;
				$filepath	= JPath::clean( $folder . DS . $tmpName );
				$i++;
			}
			$filename	= $tmpName;
		}
		
		if (isset($file['name']))
		{
			if (!EasyImageHelper::canUploadImage( $file, $err ))
			{
				$response = new stdClass();
				$response->type = 'error';
				$response->message = 'Error. Unsupported media type!';
			}

			if($config->get('main_resize_image_on_upload' ))
			{
				$image = new SimpleImage();
				$image->load($file['tmp_name']);
				
				$fileWidth	= $image->getWidth();
				$fileHeight	= $image->getHeight();
				
				$maxWidth	= $config->get('main_maximum_image_width');
				$maxHeight	= $config->get('main_maximum_image_height');
				
				if(!empty($maxWidth) && $fileWidth > $maxWidth)
				{
					$image->resizeToWidth($maxWidth);
					$fileWidth	= $image->getWidth();
					$fileHeight	= $image->getHeight();
				}
				
				if(!empty($maxWidth) && $fileHeight > $maxHeight)
				{
					$image->resizeToHeight($maxHeight);
				}

				$uploadStatus = $image->save( $filepath , $image->image_type);
			}
			else
			{
				$uploadStatus = JFile::upload($file['tmp_name'], $filepath);
			}
			
			if (!$uploadStatus)
			{
				$response = new stdClass();
				$response->type = 'error';
				$response->message = JText::_( 'COM_EASYBLOG_IMAGE_MANAGER_UPLOAD_ERROR' );
			}
			else
			{
			    // file uploaded. Now we test if the index.html was there or not.
			    // if not, copy from easyblog root into this folder
			    if(! JFile::exists( $folder . DS . 'index.html' ) )
			    {
			        $targetFile = JPATH_ROOT . DS . 'components' . DS . 'com_easyblog' . DS . 'index.html';
			        $destFile   = $folder . DS .'index.html';
			        
			        if( JFile::exists( $targetFile ) )
			        {
			        	JFile::copy( $targetFile, $destFile );
			        }
			    }
			    
				$response = new stdClass();
				$response->type 			= 'success';
				$response->message 			= JText::_( 'COM_EASYBLOG_IMAGE_MANAGER_UPLOAD_SUCCESS' );

				// TODO: Return image object identical to the $image variable in dashboard.images.list.php
				//       in $response->image. See EasyBlogViewImages::display() @ controllers/images.php.
				$response->image			= EasyBlogHelper::getHelper( 'ImageData' )->getObject( $folder , $filename );
			}
		}
		else
		{
			$response = new stdClass();
			$response->type = 'error';
			$response->message = JText::_( 'COM_EASYBLOG_IMAGE_MANAGER_UPLOAD_ERROR' );
		}
		include_once( EBLOG_CLASSES . DS . 'json.php' );
		$json	= new Services_JSON();
		echo $json->encode( $response );
		exit;
	}

	
	/**
	 * Deletes paths from the current path
	 *
	 * @param string $listFolder The image directory to delete a file from
	 * @since 1.5
	 */
	function delete()
	{
		$mainframe	=& JFactory::getApplication();
		$my         =& JFactory::getUser();
		$config     =& EasyBlogHelper::getConfig();

		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Get some data from the request
		$tmpl	= JRequest::getCmd( 'tmpl' );
		$f_url	= JRequest::getVar( 'f_file', '');
		$msg 	= '';
		
		if(! empty($f_url))
		{
		
			$main_image_path	= $config->get('main_image_path');
	        $main_image_path 	= rtrim($main_image_path, '/');

			$userUploadPath    	= JPATH_ROOT . DS . str_replace('/', DS, $main_image_path . DS . $my->id);
			$folder     		= JPath::clean($userUploadPath);

			$fileDes            = JPath::clean(JPATH_ROOT . DS . str_replace('/', DS, $f_url));

			if(JFile::exists($fileDes))
			{
			    if(! JFile::delete($fileDes))
			    {
			        $msg    = 'COM_EASYBLOG_IMAGE_UPLOADER_FILE_FAILED_TO_DELETE';
			    }
			    else
			    {
			        $msg    = 'COM_EASYBLOG_IMAGE_UPLOADER_FILE_SUCCESSFULLY_DELETED';
			    }
			}
			else
			{
			    $msg    = 'COM_EASYBLOG_IMAGE_UPLOADER_FILE_NOT_FOUND';
			}
			
			$mainframe->enqueueMessage(JText::_($msg));
		}

		$mainframe->redirect('index.php?option=com_easyblog&view=images&folder='.$folder.'&tmpl=component');
		return;
	}

}
