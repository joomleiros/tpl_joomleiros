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

jimport('joomla.application.component.controller');

require_once( EBLOG_HELPERS . DS . 'string.php' );

class EasyBlogControllerSettings extends JController
{
	function apply()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$mainframe	=& JFactory::getApplication();
		
		$result		= $this->_store();
		$layout		= JRequest::getString( 'active' , '' );
		$child		= strtolower(JRequest::getString( 'activechild' , '' ));
		$mainframe->redirect( 'index.php?option=com_easyblog&view=settings&active=' . $layout . '&activechild=' . $child , $result['message'] , $result['type'] );
	}
	
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$mainframe	=& JFactory::getApplication();
		
		$result		= $this->_store();
		$mainframe->redirect( 'index.php?option=com_easyblog' , $result['message'] , $result['type'] );
	}
	
	function _store()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$mainframe	=& JFactory::getApplication();
		
		$message	= '';
		$type		= 'message';
		
		if( JRequest::getMethod() == 'POST' )
		{
			$model		=& $this->getModel( 'Settings' );
			//$model->save( $key , $values );

			$postArray	= JRequest::get( 'post' );
			$saveData	= array();
			
			// Unset unecessary data.
			unset( $postArray['task'] );
			unset( $postArray['option'] );
			unset( $postArray['c'] );
			
			foreach( $postArray as $index => $temp )
			{
				if(is_array($temp))
				{
					$value = implode('|', $temp);
				}
				else
				{
					$value = $temp;
				}
				
				if( $index == 'integration_google_adsense_code' )
				{
					$value	= str_ireplace( ';"' , '' , $value );
				}
				
				if( $index != 'task' );
				{
					$saveData[ $index ]	= $value;
				}

			}
			
			//overwrite the main blog description value by using getVar to preserve the html tag
			$saveData['main_description']	= JRequest::getVar( 'main_description', '', 'post', 'string', JREQUEST_ALLOWRAW );
			
			//overwrite the addthis custom code value by using getVar to preserve the html tag
			$saveData['social_addthis_customcode']	= JRequest::getVar( 'social_addthis_customcode', '', 'post', 'string', JREQUEST_ALLOWRAW );

			if( $model->save( $saveData ) )
			{
				$message	= JText::_( 'COM_EASYBLOG_SETTINGS_STORE_SUCCESS' );
			}
			else
			{
				$message	= JText::_( 'COM_EASYBLOG_SETTINGS_STORE_ERROR' );
				$type		= 'error';
			}
		}
		else
		{
			$message	= JText::_('COM_EASYBLOG_SETTINGS_STORE_INVALID_REQUEST');
			$type		= 'error';
		}
		
		return array( 'message' => $message , 'type' => $type);
	}

	/**
	* Cancels an edit operation
	*/
	function cancel()
	{
		$mainframe =& JFactory::getApplication();

		$mainframe->redirect('index.php?option=com_easyblog');
	}
	
	/**
	* Save the Email Template.
	*/
	function saveEmailTemplate()
	{
		$mainframe 	=& JFactory::getApplication();
		$file 		= JRequest::getVar('file', '', 'POST' );
		$filepath	= JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'themes'.DS.'default'.DS.$file;
		$content	= JRequest::getVar( 'content' , '' , 'POST' , '' , JREQUEST_ALLOWRAW );
		$msg		= '';
		$msgType	= '';

		$status 	= JFile::write($filepath, $content);
		if(!empty($status))
		{
			$msg = JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_EMAIL_TEMPLATES_SAVE_SUCCESS');
			$msgType = 'info';
		}
		else
		{
			$msg = JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_EMAIL_TEMPLATES_SAVE_FAIL');
			$msgType = 'error';
		}

		$mainframe->enqueueMessage($msg);
		$mainframe->redirect('index.php?option=com_easyblog&view=settings&layout=editEmailTemplate&file='.$file.'&msg='.$msg.'&msgtype='.$msgType.'&tmpl=component&browse=1');
	}
	
	/**
	* Save the Email Template.
	*/
	function saveEmailTemplateOld()
	{
		$mainframe 	=& JFactory::getApplication();

		$langCode   	= EasyBlogStringHelper::getLangCode();
		$adminLangPath  = JPATH_ROOT.DS.'administrator'.DS.'language'.DS.$langCode.DS.$langCode.'.com_easyblog.ini';
		$siteLangPath	= JPATH_ROOT.DS.'language'.DS.$langCode.DS.$langCode.'.com_easyblog.ini';
		
		//check if the above lang ini file exist. if not, use default instead.
		if(! (JFile::exists($adminLangPath) && JFile::exists($siteLangPath)))
		{
		    $langCode       = 'en-GB';
			$adminLangPath  = JPATH_ROOT.DS.'administrator'.DS.'language'.DS.$langCode.DS.$langCode.'.com_easyblog.ini';
			$siteLangPath	= JPATH_ROOT.DS.'language'.DS.$langCode.DS.$langCode.'.com_easyblog.ini';
		}

		$file 		= JRequest::getVar('file', '', 'POST' );
		$content	= JRequest::getVar( 'content' , '' , 'POST' , '' , JREQUEST_ALLOWRAW );
		$msg		= '';
		$msgType	= '';


		$file_jstring	= JString::str_ireplace('.', '_', $file);
		$file_jstring   = strtoupper($file_jstring);

		$currentAdmin   = JFile::read($adminLangPath);
		$currentSite    = JFile::read($siteLangPath);
		

        $pattern    = array('/(?:(?:\r\n)\s*){2}/',
					        '/(?:(?:\r)\s*){1}/',
					        '/(?:(?:\n)\s*){1}/',
							'/\"/');

        $replace    = array('\n\n',
							'\n',
        					'\n',
							'\'');

							
//         $pattern    = array('/(?:(?:\r)\s*){1}/s',
// 					        '/(?:(?:\n)\s*){1}/s',
// 							'/\"/');

        
		$content 	= preg_replace($pattern, $replace, $content);
		
		
		//EMAIL_BLOG_NEW_PHP=".*"[(\r|\n|\r\n){2,}]
		
		$pattern		= '/'.$file_jstring.'=".*"[(\r|\n|\r\n){2,}]/';
		$replace        = $file_jstring.'="'.$content.'"'."\n";
		
		//$pattern		= '/'.$file_jstring.'=".*"+\s$/';
		//$replace        = $file_jstring.'="'.$content.'"'."\r\n";
		//$replace        = $content;
		
 		$newContentAdmin 	= preg_replace($pattern, $replace, $currentAdmin);
 		$newContentSite 	= preg_replace($pattern, $replace, $currentSite);

		$statusAdmin 	= JFile::write($adminLangPath, $newContentAdmin);
		$statusSite 	= JFile::write($siteLangPath, $newContentSite);
		
		if(!empty($statusAdmin) && !empty($statusSite))
		{
			$msg = JText::_('COM_EASYBLOG_EMAILTEMPLATESAVESUCCESS');
			$msgType = 'info';
		}
		else
		{
			$msg = JText::_('COM_EASYBLOG_EMAILTEMPLATESAVEFAIL');
			$msgType = 'error';
		}

		$mainframe->enqueueMessage($msg);
		$mainframe->redirect('index.php?option=com_easyblog&view=settings&layout=editEmailTemplate&file='.$file.'&msg='.$msg.'&msgtype='.$msgType.'&tmpl=component&browse=1');
	}
	
	
	
	
}