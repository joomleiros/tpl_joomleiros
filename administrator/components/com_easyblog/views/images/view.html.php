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

class EasyBlogViewImages extends JView
{
	function display($tpl = null)
	{
	    $lang		= JFactory::getLanguage();
	    $lang->load( 'com_easyblog' , JPATH_ROOT );
	    
		$config     = EasyBlogHelper::getConfig();
		
		$document	= JFactory::getDocument();
		$my         = JFactory::getUser();
		$user		= JFactory::getUser(JRequest::getInt('blogger_id', $my->id));

		$app 	= JFactory::getApplication();
		
		if( $my->id <= 0 )
		{
			echo JText::_( 'COM_EASYBLOG_NOT_ALLOWED' );
			exit;
		}

		$document	=& JFactory::getDocument();
		$document->addScript (rtrim( JURI::root() , '/' ) . '/components/com_easyblog/assets/vendors/plupload/plupload.js' );
		$document->addScript( rtrim( JURI::root() , '/' ) . '/components/com_easyblog/assets/vendors/plupload/plupload.html4.js' );
		$document->addScript( rtrim( JURI::root() , '/' ) . '/components/com_easyblog/assets/vendors/plupload/plupload.flash.js' );
		
		$document->addStyleSheet( rtrim( JURI::root() , '/' ) . '/components/com_easyblog/assets/css/common.css' );
		$document->addStyleSheet( rtrim( JURI::root() , '/' ) . '/components/com_easyblog/themes/dashboard/system/css/styles.css' );
		$document->addStyleSheet( rtrim( JURI::root() , '/' ) . '/components/com_easyblog/assets/css/imagemanager.css' );

		$main_image_path	= $config->get('main_image_path');
        $main_image_path 	= rtrim($main_image_path, '/');
		$userImagePath  	= JPATH_ROOT . DS . str_replace('/', DS, $main_image_path . DS . $user->id);
		$userImageBasePath  = rtrim( JURI::root() , '/' ) . '/' . $main_image_path . '/' . $user->id;
  		$userUploadPath     = JPATH_ROOT . DS . str_replace('/', DS, $main_image_path . DS . $user->id);
  		$userUploadPathBase = rtrim( JURI::root() , '/' ) . '/' . $main_image_path . '/' . $user->id;

  		if(! JFolder::exists($userUploadPath))
  		{
  		    JFolder::create( $userUploadPath );
	        $source 		= JPATH_ROOT . DS . 'components' . DS . 'com_easyblog' . DS . 'index.html';
			$destination	= $userUploadPath . DS .'index.html';
        	JFile::copy( $source , $destination );
  		}

		$images = $this->getImages( $userUploadPath , $userImageBasePath );

		require_once( EBLOG_CLASSES . DS . 'json.php' );
		$json   = new Services_JSON();
		$config = EasyBlogHelper::getConfig();
		
		$this->assign( 'acl'	, EasyBlogACLHelper::getRuleSet() );
		$this->assign( 'json'	, $json );
		$this->assign( 'config'	, $config );
		$this->assign( 'baseURL', $userImageBasePath );
		$this->assign( 'session', JFactory::getSession() );
		$this->assign( 'images' , $images );
		$this->assign( 'blogger_id' , $user->id );

		parent::display( $tpl );
	}
	
	function setFolder($index = 0)
	{
		if (isset($this->folders[$index])) {
			$this->_tmp_folder = &$this->folders[$index];
		} else {
			$this->_tmp_folder = new JObject;
		}
	}
	
	function setImage($index = 0)
	{
		if (isset($this->images[$index])) {
			$this->_tmp_img = &$this->images[$index];
		} else {
			$this->_tmp_img = new JObject;
		}
	}
	
	public function getImages( $folder )
	{
		static $list;

		if (is_array($list))
		{
			return $list;
		}
		
		// Retrieve files
		$files		= JFolder::files( $folder , '.' , false , true );
		$data		= array();

		array_multisort(
    		array_map( 'filectime', $files ),
    		SORT_NUMERIC,
    		SORT_DESC,
    		$files
		);

		if( $files )
		{
			foreach( $files as $file )
			{
			
				// fixed for path in window enviroment in joomla 1.7.
			    $file   = str_ireplace( '/' , DS , $file );
				$file	= str_ireplace( $folder . DS , '' , $file );

				if( is_file( $folder . DS . $file ) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html')
				{
					$data[] = EasyBlogHelper::getHelper( 'ImageData' )->getObject( $folder , $file );
				}
			}
		}
		krsort( $data );

		return $data;
	}


}
