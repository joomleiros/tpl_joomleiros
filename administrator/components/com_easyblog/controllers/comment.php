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

class EasyBlogControllerComment extends EasyBlogController
{	
	function __construct()
	{
		parent::__construct();
		
		$this->registerTask( 'add' , 'edit' );
	}

	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_easyblog&view=comments' );
		
		return;
	}
	
	function edit()
	{
		JRequest::setVar( 'view', 'comment' );
		JRequest::setVar( 'commentid' , JRequest::getVar( 'commentid' , '' , 'REQUEST' ) );

		parent::display();
	}
	
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$comments	= JRequest::getVar( 'cid' , '' , 'POST' );
		$message	= '';
		$type		= 'message';
		
		if( empty( $comments ) )
		{
			$message	= JText::_('Invalid comment id');
			$type		= 'error';
		}
		else
		{
			$table		=& JTable::getInstance( 'Comment' , 'Table' );
			foreach( $comments as $comment )
			{
				$table->load( $comment );
				
				// AlphaUserPoints
				// since 1.2
				if ( !empty($table->created_by) && EasyBlogHelper::isAUPEnabled() )
				{
					$aupid = AlphaUserPointsHelper::getAnyUserReferreID( $table->created_by );
					AlphaUserPointsHelper::newpoints( 'plgaup_easyblog_delete_comment', $aupid, '', JText::_('COM_EASYBLOG_AUP_COMMENT_DELETED') );
				}
				
				if( !$table->delete() )
				{
					$message	= JText::_( 'COM_EASYBLOG_COMMENTS_COMMENT_REMOVE_ERROR' );
					$type		= 'error';
					$this->setRedirect( 'index.php?option=com_easyblog&view=comments' , $message , $type );
					return;
				}
				
				$message	= JText::_('COM_EASYBLOG_COMMENTS_COMMENT_REMOVED');
			}
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=comments' , $message , $type );
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
			$user				=& JFactory::getUser();
			$post['created_by']	= $user->id;
			$commentId				= JRequest::getVar( 'commentid' , '' );
			$comment				=& JTable::getInstance( 'Comment', 'Table' );

			if( !empty( $commentId ) )
			{
				$comment->load( $commentId );
				$post['created_by']	= $comment->created_by;
			}

			$comment->bind( $post );
			$comment->comment	= EasyBlogStringHelper::url2link( $comment->comment );

			if (!$comment->store())
			{
	        	JError::raiseError(500, $comment->getError() );
			}
			else
			{
			    if($comment->published && !$comment->sent)
			    {
					$comment->comment   = EasyBlogCommentHelper::parseBBCode($comment->comment);
					$comment->comment   = nl2br($comment->comment);

					//add notification to mailq
					EasyBlogCommentHelper::addNotification($comment, true);

					//update the sent flag to sent
					$comment->updateSent();
				}
			
			
				$message	= JText::_( 'COM_EASYBLOG_COMMENTS_SAVED' );
			}
		}
		else
		{
			$message	= JText::_('Invalid request method. This form needs to be submitted through a "POST" request.');
			$type		= 'error';
		}

		$mainframe->redirect( 'index.php?option=com_easyblog&view=comments' , $message , $type );
	}

	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$comments	= JRequest::getVar( 'cid' , array(0) , 'POST' );
		
		$message	= '';
		$type		= 'message';
		
		if( count( $comments ) <= 0 )
		{
			$message	= JText::_('Invalid comment id');
			$type		= 'error';
		}
		else
		{
			$model		=& $this->getModel( 'Comments' );
			
			if( $model->publish( $comments , 1 ) )
			{
				$message	= JText::_('COM_EASYBLOG_COMMENTS_COMMENT_PUBLISHED');
				
				foreach($comments as $row)
				{
					$comment	=& JTable::getInstance( 'Comment', 'Table' );
		    		$comment->load($row);

					$comment->comment   = EasyBlogCommentHelper::parseBBCode($comment->comment);
					$comment->comment   = nl2br($comment->comment);
					
					//add notification to mailq
					EasyBlogCommentHelper::addNotification($comment, true);

					//update the sent flag to sent
					$comment->updateSent();
				}
			}
			else
			{
				$message	= JText::_('COM_EASYBLOG_COMMENTS_COMMENT_PUBLISH_ERROR');
				$type		= 'error';
			}
			
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=comments' , $message , $type );
	}

	function unpublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$comments	= JRequest::getVar( 'cid' , array(0) , 'POST' );
		
		$message	= '';
		$type		= 'message';
		
		if( count( $comments ) <= 0 )
		{
			$message	= JText::_('Invalid comment id');
			$type		= 'error';
		}
		else
		{
			$model		=& $this->getModel( 'Comments' );
			
			if( $model->publish( $comments , 0 ) )
			{
				$message	= JText::_('COM_EASYBLOG_COMMENTS_COMMENT_UNPUBLISHED');
			}
			else
			{
				$message	= JText::_('COM_EASYBLOG_COMMENTS_COMMENT_UNPUBLISH_ERROR');
				$type		= 'error';
			}
			
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=comments' , $message , $type );
	}
}