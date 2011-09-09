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
require_once( EBLOG_HELPERS . DS . 'comment.php' );

class EasyBlogControllerTrackback extends EasyBlogController
{	
	function __construct()
	{
		parent::__construct();
		
		$this->registerTask( 'add' , 'edit' );
		$this->registerTask( 'publish' , 'togglePublish' );
		$this->registerTask( 'unpublish' , 'togglePublish' );
	}

	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_easyblog&view=comments' );
		
		return;
	}
	
	function edit()
	{
		JRequest::setVar( 'view', 'trackback' );
		JRequest::setVar( 'id' , JRequest::getVar( 'id' , '' , 'REQUEST' ) );

		parent::display();
	}
	
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$trackbacks	= JRequest::getVar( 'cid' , '' , 'POST' );
		$message	= '';
		$type		= 'message';
		
		if( empty( $trackbacks ) )
		{
			$message	= JText::_('COM_EASYBLOG_TRACKBACKS_INVALID_ID');
			$type		= 'error';
		}
		else
		{
			$trackback		=& JTable::getInstance( 'Trackback' , 'Table' );
			
			foreach( $trackbacks as $id )
			{
				$trackback->load( $id );
				
				if( !$trackback->delete() )
				{
					$message	= JText::_( 'COM_EASYBLOG_TRACKBACKS_DELETE_ERROR' );
					$type		= 'error';
					$this->setRedirect( 'index.php?option=com_easyblog&view=trackbacks' , $message , $type );
					return;
				}
				
				$message	= JText::_('COM_EASYBLOG_TRACKBACKS_DELETE_SUCCESS');
			}
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=trackbacks' , $message , $type );
	}
	
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$mainframe	=& JFactory::getApplication();

		$message	= '';
		$type		= 'message';

		
		if( JRequest::getMethod() != 'POST' )
		{
			$mainframe->redirect( 'index.php?option=com_easyblog&view=trackbacks' , JText::_('COM_EASYBLOG_TRACKBACKS_INVALID_REQUEST') , 'error' );
			$mainframe->close();
		}
		
		$post		= JRequest::get( 'POST' );
		$my			= JFactory::getUser();
		$id			= JRequest::getInt( 'id' , 0 );
		
		JTable::addIncludePath( EBLOG_TABLES );
		$trackback	= JTable::getInstance( 'Trackback' , 'Table' );
		$trackback->load( $id );
		$trackback->bind( $post );

		if( !$trackback->store() )
		{
			$mainframe->redirect( 'index.php?option=com_easyblog&view=trackbacks' , JText::_('COM_EASYBLOG_TRACKBACKS_SAVE_ERROR') , 'error' );
			$mainframe->close();
		}
		
		$mainframe->redirect( 'index.php?option=com_easyblog&view=trackbacks' , JText::_( 'COM_EASYBLOG_TRACKBACKS_SAVE_SUCCESS' ) );
		$mainframe->close();
	}

	public function togglePublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$trackbacks	= JRequest::getVar( 'cid' , array(0) , 'POST' );
		$status		= JRequest::getVar( 'task' ) == 'publish' ? 1 : 0;
		
		$message	= '';
		$type		= 'message';
		
		if( count( $trackbacks ) <= 0 )
		{
			$message	= JText::_('COM_EASYBLOG_TRACKBACKS_INVALID_ID');
			$type		= 'error';
		}
		else
		{
			$model		=& $this->getModel( 'Trackbacks' );
			
			if( $model->publish( $trackbacks , $status ) )
			{
				$message	= $status == 1 ? JText::_('COM_EASYBLOG_TRACKBACKS_PUBLISHED_SUCCESS') : JText::_('COM_EASYBLOG_TRACKBACKS_UNPUBLISHED_SUCCESS');
			}
			else
			{
				$message	= $status == 1 ? JText::_( 'COM_EASYBLOG_TRACKBACKS_PUBLISHED_ERROR' ) : JText::_('COM_EASYBLOG_TRACKBACKS_UNPUBLISHED_ERROR');
				$type		= 'error';
			}
			
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=trackbacks' , $message , $type );
	}
}