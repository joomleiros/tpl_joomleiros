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

class EasyBlogControllerCategory extends EasyBlogController
{	
	function __construct()
	{
		parent::__construct();
		
		$this->registerTask( 'add' , 'edit' );
	}
	
	function orderdown()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
	    EasyBlogControllerCategory::orderCategory(1);
	}
	
	function orderup()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
	    EasyBlogControllerCategory::orderCategory(-1);
	}
	
	function orderCategory( $direction )
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$mainframe  =& JFactory::getApplication();

		// Initialize variables
		$db		= & JFactory::getDBO();
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		
		if (isset( $cid[0] ))
		{
			$row =& JTable::getInstance('ECategory', 'Table');
			$row->load( (int) $cid[0] );
			$row->move($direction);
		}
		
		$mainframe->redirect( 'index.php?option=com_easyblog&view=categories');
		exit;
	}
	
	function saveOrder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
	
	    $mainframe  =& JFactory::getApplication();
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array (0), 'post', 'array' );
		$total		= count($cid);
		$conditions	= array ();
		
		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));
		
		$row =& JTable::getInstance('ECategory', 'Table');
		
		// Update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++)
		{
			$row->load( (int) $cid[$i] );
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError( 500, $db->getErrorMsg() );
					return false;
				}
				// remember to updateOrder this group
				$condition = 'id = '.(int) $row->id;
				$found = false;
				foreach ($conditions as $cond)
					if ($cond[1] == $condition) {
						$found = true;
						break;
					}
				if (!$found)
					$conditions[] = array ($row->id, $condition);
			}
		}
		
		// execute updateOrder for each group
		foreach ($conditions as $cond)
		{
			$row->load($cond[0]);
			$row->reorder($cond[1]);
		}
		
		$message	= JText::_('COM_EASYBLOG_CATEGORIES_ORDERING_SAVED');
		$type       = 'message';
		
		$mainframe->redirect( 'index.php?option=com_easyblog&view=categories' , $message , $type );
		exit;
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
				$mainframe->enqueueMessage(JText::_('COM_EASYBLOG_CATEGORIES_INVALID_CATEGORY'), 'error');
				
				$url  = 'index.php?option=com_easyblog&view=categories';
				$mainframe->redirect(JRoute::_($url, false));
				return;
			}
			
			$category			=& JTable::getInstance( 'ECategory', 'Table' );
			$user				=& JFactory::getUser();
			$post['created_by']	= $user->id;
			$catId				= JRequest::getVar( 'catid' , '' );
			
			$isNew				= (empty($catId)) ? true : false;
			
			if( !empty( $catId ) )
			{
				$category->load( $catId );
			}

			$category->bind( $post );
						
			if (!$category->store()) 
			{
	        	JError::raiseError(500, $cat->getError() );
			}
			else
			{
				// AlphaUserPoints
				// since 1.2
				if ( $isNew && EasyBlogHelper::isAUPEnabled() )
				{
					AlphaUserPointsHelper::newpoints( 'plgaup_easyblog_add_category', '', 'easyblog_add_category_' . $category->id, JText::sprintf('AUP NEW CATEGORY CREATED', $post['title']) );
				} 				
				
				$file = JRequest::getVar( 'Filedata', '', 'files', 'array' );
				if(! empty($file['name']))
				{
					$newAvatar  		= EasyBlogHelper::uploadCategoryAvatar($category, true);
					$category->avatar   = $newAvatar;
					$category->store(); //now update the avatar.
				}
			
				$message	= JText::_( 'COM_EASYBLOG_CATEGORIES_SAVED_SUCCESS' );
			}
		}
		else
		{
			$message	= JText::_('COM_EASYBLOG_INVALID_REQUEST');
			$type		= 'error';
		}
		
		$mainframe->redirect( 'index.php?option=com_easyblog&view=categories' , $message , $type );
	}

	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_easyblog&view=categories' );
		
		return;
	}

	function edit()
	{
		JRequest::setVar( 'view', 'category' );
		JRequest::setVar( 'catid' , JRequest::getVar( 'catid' , '' , 'REQUEST' ) );
		
		parent::display();
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$categories	= JRequest::getVar( 'cid' , '' , 'POST' );
		
		$message	= '';
		$type		= 'info';
		
		if( empty( $categories ) )
		{
			$message	= JText::_('COM_EASYBLOG_CATEGORIES_INVALID_CATEGORY');
			$type		= 'error';
		}
		else
		{
			$table		=& JTable::getInstance( 'ECategory' , 'Table' );
			foreach( $categories as $category )
			{
				$table->load( $category );
				
				if($table->getPostCount())
				{
					$message	= JText::sprintf('COM_EASYBLOG_CATEGORIES_DELETE_ERROR_POST_NOT_EMPTY', $table->title);
					$type		= 'error';
					$this->setRedirect( 'index.php?option=com_easyblog&view=categories' , $message , $type );
					return;
				}

				if($table->getChildCount())
				{
					$message	= JText::sprintf('COM_EASYBLOG_CATEGORIES_DELETE_ERROR_CHILD_NOT_EMPTY', $table->title);
					$type		= 'error';
					$this->setRedirect( 'index.php?option=com_easyblog&view=categories' , $message , $type );
					return;
				}

				if( !$table->delete() )
				{
					$message	= JText::_( 'COM_EASYBLOG_CATEGORIES_DELETE_ERROR' );
					$type		= 'error';
					$this->setRedirect( 'index.php?option=com_easyblog&view=categories' , $message , $type );
					return;
				}
				else
				{
					// AlphaUserPoints
					// since 1.2
					if ( EasyBlogHelper::isAUPEnabled() )
					{
					    $aupid = AlphaUserPointsHelper::getAnyUserReferreID( $table->created_by );
						AlphaUserPointsHelper::newpoints( 'plgaup_easyblog_delete_category', $aupid, '', JText::sprintf('AUP CATEGORY DELETED', $table->title) );
					} 					
				}
			}
			$message	= JText::_('COM_EASYBLOG_CATEGORIES_DELETE_SUCCESS');
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=categories' , $message , $type );
	}

	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$categories	= JRequest::getVar( 'cid' , array(0) , 'POST' );

		$message	= '';
		$type		= 'message';
		
		if( count( $categories ) <= 0 )
		{
			$message	= JText::_('COM_EASYBLOG_CATEGORIES_INVALID_CATEGORY');
			$type		= 'error';
		}
		else
		{
			$model		=& $this->getModel( 'Categories' );
			
			if( $model->publish( $categories , 1 ) )
			{
				$message	= JText::_('COM_EASYBLOG_CATEGORIES_PUBLISHED_SUCCESS');
			}
			else
			{
				$message	= JText::_('COM_EASYBLOG_CATEGORIES_PUBLISHED_ERROR');
				$type		= 'error';
			}
			
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=categories' , $message , $type );
	}

	function unpublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$categories	= JRequest::getVar( 'cid' , array(0) , 'POST' );
		
		$message	= '';
		$type		= 'message';
		
		if( count( $categories ) <= 0 )
		{
			$message	= JText::_('COM_EASYBLOG_CATEGORIES_INVALID_CATEGORY');
			$type		= 'error';
		}
		else
		{
			$model		=& $this->getModel( 'Categories' );
			
			if( $model->publish( $categories , 0 ) )
			{
				$message	= JText::_('COM_EASYBLOG_CATEGORIES_UNPUBLISHED_SUCCESS');
			}
			else
			{
				$message	= JText::_('COM_EASYBLOG_CATEGORIES_UNPUBLISHED_ERROR');
				$type		= 'error';
			}
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=categories' , $message , $type );
	}
}