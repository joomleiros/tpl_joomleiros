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

class EasyBlogControllerUsers extends EasyBlogController
{	
	function __construct()
	{
		parent::__construct();
		
		$this->registerTask( 'add' , 'edit' );
	}
	
	function save()
	{
	}

	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_easyblog&view=users' );
		
		return;
	}

	function edit()
	{
		JRequest::setVar( 'view', 'user' );
		JRequest::setVar( 'id' , JRequest::getVar( 'id' , '' , 'REQUEST' ) );
		
		parent::display();
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid 			= JRequest::getVar( 'cid', array(), '', 'array' );

		JArrayHelper::toInteger( $cid );

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select a User to delete', true ) );
		}

        $result = null;

		foreach ($cid as $id)
		{
		    $result = null;
			if(EasyBlogHelper::getJoomlaVersion() >= '1.6')
			{
			    $result	= $this->_removeUser16($id);
			}
			else
			{
				$result	= $this->_removeUser($id);
			}
		
			if(! $result['success'])
			    $this->setRedirect( 'index.php?option=com_easyblog&view=users', $result['msg']);
			
		}

        $this->setRedirect( 'index.php?option=com_easyblog&view=users', $result['msg']);
	}
	
	function _removeUser16($id)
	{
		$db 			=& JFactory::getDBO();
		$currentUser 	=& JFactory::getUser();
		
		$user           =& JFactory::getUser($id);
		$isUserSA       = $user->authorise('core.admin');
		
		if($isUserSA)
		{
		    $msg = JText::_( 'You cannot delete a Super Administrator' );
		}
		else if($id == $currentUser->get( 'id' ))
		{
		    $msg = JText::_( 'You cannot delete Yourself!' );
		}
		else
		{
		    $count = 2;
		
		    if($isUserSA)
		    {
		        $saUsers    = EasyBlogHelper::getSAUsersIds();
		        $count		= count($saUsers);
		    }
		    
			if ( $count <= 1 && $isUserSA)
			{
				// cannot delete Super Admin where it is the only one that exists
				$msg = "You cannot delete this Super Administrator as it is the only active Super Administrator for your site";
			}
			else
			{
				// delete user
				$user->delete();
				$msg = JText::_('User Deleted.');

				JRequest::setVar( 'task', 'remove' );
				JRequest::setVar( 'cid', $id );

				// delete user acounts active sessions
				$this->logout();
				$success    = true;
			}
		
		}
		
		$result['success'] 	= $success;
		$result['msg'] 		= $msg;

		return $result;
		
	}
	
	function _removeUser($id)
	{
		$db 			=& JFactory::getDBO();
		$currentUser 	=& JFactory::getUser();
		$acl			=& JFactory::getACL();
	
		// check for a super admin ... can't delete them
		$objectID 	= $acl->get_object_id( 'users', $id, 'ARO' );
		$groups 	= $acl->get_object_groups( $objectID, 'ARO' );
		$this_group = strtolower( $acl->get_group_name( $groups[0], 'ARO' ) );

		$success 	= false;
		$msg        = '';
		
		if ( $this_group == 'super administrator' )
		{
			$msg = JText::_( 'You cannot delete a Super Administrator' );
		}
		else if ( $id == $currentUser->get( 'id' ) )
		{
			$msg = JText::_( 'You cannot delete Yourself!' );
		}
		else if ( ( $this_group == 'administrator' ) && ( $currentUser->get( 'gid' ) == 24 ) )
		{
			$msg = JText::_( 'WARNDELETE' );
		}
		else
		{
			$user =& JUser::getInstance((int)$id);
			$count = 2;

			if ( $user->get( 'gid' ) == 25 )
			{
				// count number of active super admins
				$query = 'SELECT COUNT( id )'
					. ' FROM #__users'
					. ' WHERE gid = 25'
					. ' AND block = 0'
				;
				$db->setQuery( $query );
				$count = $db->loadResult();
			}

			if ( $count <= 1 && $user->get( 'gid' ) == 25 )
			{
				// cannot delete Super Admin where it is the only one that exists
				$msg = "You cannot delete this Super Administrator as it is the only active Super Administrator for your site";
			}
			else
			{
				// delete user
				$user->delete();
				$msg = JText::_('User Deleted.');

				JRequest::setVar( 'task', 'remove' );
				JRequest::setVar( 'cid', $id );

				// delete user acounts active sessions
				$this->logout();
				$success    = true;
			}
		}
		
		$result['success'] 	= $success;
		$result['msg'] 		= $msg;
		
		return $result;
	}
	
	
	/**
	 * Force log out a user
	 */
	function logout( )
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$mainframe	=& JFactory::getApplication();

		$db		=& JFactory::getDBO();
		$task 	= $this->getTask();
		$cids 	= JRequest::getVar( 'cid', array(), '', 'array' );
		$client = JRequest::getVar( 'client', 0, '', 'int' );
		$id 	= JRequest::getVar( 'id', 0, '', 'int' );

		JArrayHelper::toInteger($cids);

		if ( count( $cids ) < 1 ) {
			$this->setRedirect( 'index.php?option=com_easyblog&view=users', JText::_( 'User Deleted' ) );
			return false;
		}

		foreach($cids as $cid)
		{
			$options = array();

			if ($task == 'logout' || $task == 'block') {
				$options['clientid'][] = 0; //site
				$options['clientid'][] = 1; //administrator
			} else if ($task == 'flogout') {
				$options['clientid'][] = $client;
			}

			$mainframe->logout((int)$cid, $options);
		}


		$msg = JText::_( 'User Session Ended' );
		switch ( $task )
		{
			case 'flogout':
				$this->setRedirect( 'index.php', $msg );
				break;

			case 'remove':
			case 'block':
				return;
				break;

			default:
				$this->setRedirect( 'index.php?option=com_easyblog&view=users', $msg );
				break;
		}
	}
	
}