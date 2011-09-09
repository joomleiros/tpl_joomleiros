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

jimport('joomla.application.component.controller');

class EasyBlogControllerMeta extends JController
{
	function __construct()
	{
		parent::__construct();
		
		$this->registerTask( 'add' , 'edit' );
	}
	
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$mainframe	=& JFactory::getApplication();		
				
		$message	= '';
		$type		= 'message';
		
		if( JRequest::getMethod() == 'POST' )
		{
			$post				= JRequest::get( 'post' );
			
			if(empty($post['id']))
			{
				$mainframe->enqueueMessage(JText::_('COM_EASYBLOG_INVALID_META_TAG_ID'), 'error');
				
				$url  = 'index.php?option=com_easyblog&view=metas';
				$mainframe->redirect(JRoute::_($url, false));
				return;
			}
			
			$meta		=& JTable::getInstance( 'meta', 'Table' );
			$user		=& JFactory::getUser();
			$metaId		= JRequest::getVar( 'id' , '' );
			
			if( !empty( $metaId ) )
			{
				$meta->load( $metaId );
			}

			$meta->bind( $post );
						
			if (!$meta->store()) 
			{
	        	JError::raiseError(500, $meta->getError() );
			}
			else
			{
				$message	= JText::_( 'COM_EASYBLOG_META_SAVED' );
			}
		}
		else
		{
			$message	= JText::_('Invalid request method. This form needs to be submitted through a "POST" request.');
			$type		= 'error';
		}
		
		$mainframe->redirect( 'index.php?option=com_easyblog&view=metas' , $message , $type );
	}	

	/**
	* Cancels an edit operation
	*/
	function cancel()
	{
		$mainframe =& JFactory::getApplication();

		$mainframe->redirect('index.php?option=com_easyblog&view=metas');
	}	
}