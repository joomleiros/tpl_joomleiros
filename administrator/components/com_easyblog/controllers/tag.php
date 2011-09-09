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

class EasyBlogControllerTag extends EasyBlogController
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
			
			if(empty($post['title']))
			{
				$mainframe->enqueueMessage(JText::_('COM_EASYBLOG_INVALID_TAG'), 'error');
				
				$url  = 'index.php?option=com_easyblog&view=tags';
				$mainframe->redirect(JRoute::_($url, false));
				return;
			}
			
			$user				=& JFactory::getUser();
			$post['created_by']	= $user->id;
			$tagId				= JRequest::getVar( 'tagid' , '' );
			$tag				=& JTable::getInstance( 'tag', 'Table' );
			
			$isNew              = (empty($tagId)) ? true : false;

			if( !empty( $tagId ) )
			{
				$tag->load( $tagId );
			}
			else
			{
				$tagModel =& $this->getModel( 'Tags' );
				$result = $tagModel->searchTag($title);

				if(!empty($result))
				{
					$message	= JText::_('COM_EASYBLOG_TAGS_TAG_EXISTS');
					$type		= 'error';
					$mainframe->redirect( 'index.php?option=com_easyblog&view=tags' , $message , $type );
				}
			}
			
			$tag->bind( $post );
			
			$tag->title = JString::trim($tag->title);
			$tag->alias = JString::trim($tag->alias);
			
			if (!$tag->store()) 
			{
	        	JError::raiseError(500, $tag->getError() );
			}
			else
			{
				// AlphaUserPoints
				// since 1.2				
				if ( $isNew && EasyBlogHelper::isAUPEnabled() )
				{
					AlphaUserPointsHelper::newpoints( 'plgaup_easyblog_add_tag', '', 'easyblog_add_blog_' . $tag->id, JText::sprintf('AUP TAG ADDED', $tag->title) );
				}
			
				$message	= JText::_( 'COM_EASYBLOG_TAGS_TAG_SAVED' );
			}
		}
		else
		{
			$message	= JText::_('Invalid request method. This form needs to be submitted through a "POST" request.');
			$type		= 'error';
		}
		
		$mainframe->redirect( 'index.php?option=com_easyblog&view=tags' , $message , $type );
	}

	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_easyblog&view=tags' );
		
		return;
	}

	function edit()
	{
		JRequest::setVar( 'view', 'tag' );
		JRequest::setVar( 'tagid' , JRequest::getVar( 'tagid' , '' , 'REQUEST' ) );
		
		parent::display();
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$tags	= JRequest::getVar( 'cid' , '' , 'POST' );
		
		$message	= '';
		$type		= 'message';
		
		if( empty( $tags ) )
		{
			$message	= JText::_('Invalid tag id');
			$type		= 'error';
		}
		else
		{
			$table		=& JTable::getInstance( 'Tag' , 'Table' );
			foreach( $tags as $tag )
			{
				$table->load( $tag );
				
				// AlphaUserPoints
				// since 1.2
				if ( EasyBlogHelper::isAUPEnabled() )
				{
					$aupid = AlphaUserPointsHelper::getAnyUserReferreID( $table->created_by );
					AlphaUserPointsHelper::newpoints( 'plgaup_easyblog_delete_tag', $aupid, '', JText::sprintf('AUP TAG DELETED', $table->title) );
				}
				
				if( !$table->delete() )
				{
					$message	= JText::_( 'COM_EASYBLOG_TAGS_REMOVE_ERROR' );
					$type		= 'error';
					$this->setRedirect( 'index.php?option=com_easyblog&view=tags' , $message , $type );
					return;
				}
			}
			
			$message	= JText::_('COM_EASYBLOG_TAGS_TAG_REMOVED');
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=tags' , $message , $type );
	}

	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$tags	= JRequest::getVar( 'cid' , array(0) , 'POST' );
		
		$message	= '';
		$type		= 'message';
		
		if( count( $tags ) <= 0 )
		{
			$message	= JText::_('Invalid tag id');
			$type		= 'error';
		}
		else
		{
			$model		=& $this->getModel( 'Tags' );
			
			if( $model->publish( $tags , 1 ) )
			{
				$message	= JText::_('COM_EASYBLOG_TAGS_TAG_PUBLISHED');
			}
			else
			{
				$message	= JText::_('COM_EASYBLOG_TAGS_TAG_PUBLISH_ERROR');
				$type		= 'error';
			}
			
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=tags' , $message , $type );
	}

	function unpublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
			
		$tags	= JRequest::getVar( 'cid' , array(0) , 'POST' );
		
		$message	= '';
		$type		= 'message';
		
		if( count( $tags ) <= 0 )
		{
			$message	= JText::_('Invalid tag id');
			$type		= 'error';
		}
		else
		{
			$model		=& $this->getModel( 'Tags' );
			
			if( $model->publish( $tags , 0 ) )
			{
				$message	= JText::_('COM_EASYBLOG_TAGS_TAG_UNPUBLISHED');
			}
			else
			{
				$message	= JText::_('COM_EASYBLOG_TAGS_TAG_UNPUBLISH_ERROR');
				$type		= 'error';
			}
			
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=tags' , $message , $type );
	}
	
	function setDefault()
	{
		// Check for request forgeries
		JRequest::checkToken('default') or die(JText::_('JINVALID_TOKEN'));

		// Get items to publish from the request.
		$cid	= JRequest::getVar('cid', array(), '', 'array');
		
		if (!empty($cid))
		{
			$model	= $this->getModel('Tags');
			JArrayHelper::toInteger($cid);
			
			if (!$model->setDefault($cid))
			{
				$message	= JText::_('COM_EASYBLOG_TAGS_TAG_SET_DEFAULT_ERROR');
				$type		= 'error';
			} else {
				$message	= JText::_('COM_EASYBLOG_TAGS_TAG_SET_DEFAULT_SUCCESS');
				$type		= 'success';
			}
		}
		
		$this->setRedirect( 'index.php?option=com_easyblog&view=tags' , $message , $type );
	}
	
	function unsetDefault()
	{
			// Check for request forgeries
		JRequest::checkToken('default') or die(JText::_('JINVALID_TOKEN'));

		// Get items to publish from the request.
		$cid	= JRequest::getVar('cid', array(), '', 'array');
		
		if (!empty($cid))
		{
			$model	= $this->getModel('Tags');
			JArrayHelper::toInteger($cid);
			
			if (!$model->unsetDefault($cid))
			{
				$message	= JText::_('COM_EASYBLOG_TAGS_TAG_UNSET_DEFAULT_ERROR');
				$type		= 'error';
			} else {
				$message	= JText::_('COM_EASYBLOG_TAGS_TAG_UNSET_DEFAULT_SUCCESS');
				$type		= 'success';
			}
		}
		
		$this->setRedirect( 'index.php?option=com_easyblog&view=tags' , $message , $type );
	}
}