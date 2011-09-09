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

class EasyBlogControllerAcl extends EasyBlogController
{
	function apply()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$mainframe	=& JFactory::getApplication();
		
		$cid = JRequest::getVar( 'cid' , '' , 'POST' );
		$type = JRequest::getVar( 'type' , '' , 'POST' );
		$add = JRequest::getVar( 'add' , '' , 'POST' );
		
		$result		= $this->_store();
		
		$task = 'edit';
		if($result['type']=='error')
		{
			$task = empty($add)? 'edit':'add';
		}
		
		$mainframe->redirect( 'index.php?option=com_easyblog&c=acl&task='.$task.'&cid='.$cid.'&type='.$type , $result['message'] , $result['type'] );
	}
	
	function cancel()
	{
		$mainframe	=& JFactory::getApplication();		
		$mainframe->redirect( 'index.php?option=com_easyblog&view=acls' , $result['message'] , $result['type'] );
	}
	
	function edit()
	{
		JRequest::setVar( 'view', 'acl' );
		JRequest::setVar( 'cid' , JRequest::getVar( 'cid' , '' , 'REQUEST' ) );
		JRequest::setVar( 'type' , JRequest::getVar( 'type' , '' , 'REQUEST' ) );
		
		parent::display();
	}
	
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$mainframe	=& JFactory::getApplication();
		
		$result		= $this->_store();
		$mainframe->redirect( 'index.php?option=com_easyblog&view=acls', $result['message'], $result['type'] );
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
			$cid 		= JRequest::getVar( 'cid' , '' , 'POST' );
			$acltype	= JRequest::getVar( 'type' , '' , 'POST' );
			$name 		= JRequest::getVar( 'name' , '' , 'POST' );
						
			if(!empty($cid) || !empty($acltype))
			{
				$model =& $this->getModel( 'Acl' );
							
				$db =& JFactory::getDBO();
				
				if($model->deleteRuleset($cid, $acltype))
				{
					$postArray	= JRequest::get( 'post' );
					$saveData	= array();
						
					// Unset unecessary data.
					unset( $postArray['task'] );
					unset( $postArray['option'] );
					unset( $postArray['c'] );
					unset( $postArray['cid'] );
					unset( $postArray['name'] );
					unset( $postArray['type'] );
					
					foreach( $postArray as $index => $value )
					{
						if( $index != 'task' );
						{
							$saveData[ $index ]	= $value;
						}		
					}
					
					if( $model->insertRuleset( $cid, $acltype, $saveData ) )
					{
						$message	= JText::_( 'ACL settings successfully saved.' );
					}
					else
					{
						$message	= JText::_( 'There was an error while trying to save the ACL settings.' );
						$type		= 'error';
					}
				}
				else
				{
					$message	= JText::_( 'There was an error while trying to update the ACL.' );
					$type		= 'error';
				}
			}
			else
			{
				$message	= JText::_( 'Invalid ID or ACL type, please try again.' );
				$type		= 'error';
			}
		}
		else
		{
			$message	= JText::_('Invalid request method. This form needs to be submitted through a "POST" request.');
			$type		= 'error';
		}
		
		return array( 'message' => $message , 'type' => $type);
	}
	
	function add()
	{
		JRequest::setVar( 'view', 'acl' );		
		JRequest::setVar( 'add' , true );
		JRequest::setVar( 'type' , 'assigned' );
		
		parent::display();
	}
	
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$mainframe	=& JFactory::getApplication();
		
		$bloggers	= JRequest::getVar( 'cid' , '' , 'POST' );
		
		$message	= '';
		$type		= 'message';
		
		if( empty( $bloggers ) )
		{
			$message	= JText::_('Invalid blogger id');
			$type		= 'error';
		}
		else
		{
			$model =& $this->getModel( 'Acl' );
			foreach( $bloggers as $id )
			{
				$ruleset = $model->getRuleSet('assigned', $id);
				
				if(!empty($ruleset->id))
				{
					if( !$model->deleteRuleset($id, 'assigned') )
					{
						$message	= JText::_( 'Error removing blogger, ' . $ruleset->name );
						$type		= 'error';
						$mainframe->redirect( 'index.php?option=com_easyblog&view=acls' , $message , $type );
						return;
					}
				}
			}
			
			$message	= JText::_('Blogger(s) deleted');
		}

		$mainframe->redirect( 'index.php?option=com_easyblog&view=acls', $message , $type );
	}
}