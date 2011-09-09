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

require_once( EBLOG_HELPERS . DS . 'helper.php' );

class EasyBlogControllerUser extends EasyBlogController
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

		$db			= & JFactory::getDBO();
		$my			= & JFactory::getUser();
		$acl		=& JFactory::getACL();
		$config     =& EasyBlogHelper::getConfig();

 		// Create a new JUser object
		$user = new JUser(JRequest::getVar( 'id', 0, 'post', 'int'));
		$original_gid = $user->get('gid');

		$post = JRequest::get('post');
		
		$post['username']	= JRequest::getVar('username', '', 'post', 'username');
		$post['password']	= JRequest::getVar('password', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['password2']	= JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		
		if(EasyBlogHelper::getJoomlaVersion() >= '1.6')
		{
			$jformPost = JRequest::getVar('jform', array(), 'post', 'array');
			$post['params'] = $jformPost['params'];
		}
		
		if (!$user->bind($post))
		{
			EasyBlogHelper::storeSession($post, 'EASYBLOG_REGISTRATION_POST');
			$mainframe->enqueueMessage($user->getError(), 'error');
			$this->_saveError( $user->id );
		}
		
		if(EasyBlogHelper::getJoomlaVersion() >= '1.6')
		{	
			if( $user->get('id') == $my->get( 'id' ) && $user->get('block') == 1 )
			{
				$mainframe->enqueueMessage( JText::_( 'You cannot block Yourself!' ) , 'error');
				$this->_saveError( $user->id );
			}
			else if ( ( $user->authorise('core.admin') ) && $user->get('block') == 1 )
			{
				$mainframe->enqueueMessage( JText::_( 'You cannot block a Super User' ) , 'error');
				$this->_saveError( $user->id );
			}
			else if ( ( $user->authorise('core.admin') ) && !( $my->authorise('core.admin') ) )
			{
				$mainframe->enqueueMessage( JText::_( 'You cannot edit a Super User account' ) , 'message');
				$this->_saveError( $user->id );
			}
			
			//replacing thr group name with group id so it is save correctly into the Joomla group table.
			$jformPost = JRequest::getVar('jform', array(), 'post', 'array');
			if(!empty($jformPost['groups']))
			{
				$user->groups = array();
				
				foreach($jformPost['groups'] as $groupid)
				{
					$user->groups[$groupid] = $groupid;
				}
			}
			
		}
		else
		{
			$objectID 	= $acl->get_object_id( 'users', $user->get('id'), 'ARO' );
			$groups 	= $acl->get_object_groups( $objectID, 'ARO' );
			$this_group = strtolower( $acl->get_group_name( $groups[0], 'ARO' ) );
		
			if( $user->get('id') == $my->get( 'id' ) && $user->get('block') == 1 )
			{
				$mainframe->enqueueMessage( JText::_( 'You cannot block Yourself!' ) , 'error');
				$this->_saveError( $user->id );
			}
			else if ( ( $this_group == 'super administrator' ) && $user->get('block') == 1 )
			{
				$mainframe->enqueueMessage( JText::_( 'You cannot block a Super Administrator' ) , 'error');
				$this->_saveError( $user->id );
			}
			else if ( ( $this_group == 'administrator' ) && ( $my->get( 'gid' ) == 24 ) && $user->get('block') == 1 )
			{
				$mainframe->enqueueMessage( JText::_( 'WARNBLOCK' ) , 'error');
				$this->_saveError( $user->id );
			}
			else if ( ( $this_group == 'super administrator' ) && ( $my->get( 'gid' ) != 25 ) )
			{
				$mainframe->enqueueMessage( JText::_( 'You cannot edit a super administrator account' ) , 'message');
				$this->_saveError( $user->id );
			}
		}

		// Are we dealing with a new user which we need to create?
		$isNew 	= ($user->get('id') < 1);
		
        if(EasyBlogHelper::getJoomlaVersion() <= '1.5')
		{
			// do this step only for J1.5
			if (!$isNew)
			{
				// if group has been changed and where original group was a Super Admin
				if ( $user->get('gid') != $original_gid && $original_gid == 25 )
				{
					// count number of active super admins
					$query = 'SELECT COUNT( id )'
						. ' FROM #__users'
						. ' WHERE gid = 25'
						. ' AND block = 0'
					;
					$db->setQuery( $query );
					$count = $db->loadResult();

					if ( $count <= 1 )
					{
						// disallow change if only one Super Admin exists
						$this->setRedirect( 'index.php?option=com_users', JText::_('WARN_ONLY_SUPER') );
						return false;
					}
				}
			}
		}


		if (!$user->save())
		{
			$mainframe->enqueueMessage(JText::_('COM_EASYBLOG_BLOGGERS_EDIT_CANNOT_SAVE_THE_USER_INFORMATION'), 'message');
			$mainframe->enqueueMessage($user->getError(), 'error');
			return $this->execute('edit');
		}

		// If updating self, load the new user object into the session
		if(EasyBlogHelper::getJoomlaVersion() <= '1.5')
		{
			if ($user->get('id') == $my->get('id'))
			{
				// Get an ACL object
				$acl = &JFactory::getACL();

				// Get the user group from the ACL
				$grp = $acl->getAroGroup($user->get('id'));

				// Mark the user as logged in
				$user->set('guest', 0);
				$user->set('aid', 1);

				// Fudge Authors, Editors, Publishers and Super Administrators into the special access group
				if ($acl->is_group_child_of($grp->name, 'Registered')      ||
				    $acl->is_group_child_of($grp->name, 'Public Backend'))    {
					$user->set('aid', 2);
				}

				// Set the usertype based on the ACL group name
				$user->set('usertype', $grp->name);

				$session = &JFactory::getSession();
				$session->set('user', $user);
			}
		}

		$post	= JRequest::get( 'post' );
		
		
		if($isNew)
		{
		    unset($post['id']);
		}

		$blogger	=& JTable::getInstance( 'Profile' , 'Table' );
		$blogger->load( $user->id );
		$blogger->bind($post);
		
		$file 				= JRequest::getVar( 'Filedata', '', 'Files', 'array' );
		if(! empty($file['name']))
		{
			$newAvatar			= EasyBlogHelper::uploadAvatar($blogger, true);
			$blogger->avatar    = $newAvatar;
		}
        $blogger->store();
		
		
		JTable::addIncludePath( EBLOG_TABLES );
		
		//save twitter info.
		$twitter	= JTable::getInstance( 'Oauth' , 'Table' );
		$twitter->loadByUser( $user->id , EBLOG_OAUTH_TWITTER );
		$twitter->auto		= JRequest::getVar( 'integrations_twitter_auto' );
		$twitter->message	= JRequest::getVar( 'integrations_twitter_message' );
		if( !$twitter->store() )
		{
			$mainframe->enqueueMessage( JText::_('COM_EASYBLOG_INTEGRATIONS_TWITTER_ERROR') , 'error');
		}

		// Map linkedin items
		$linkedin	= JTable::getInstance( 'Oauth' , 'Table' );
		$linkedin->loadByUser( $user->id , EBLOG_OAUTH_LINKEDIN );
		$linkedin->auto		= JRequest::getVar( 'integrations_linkedin_auto' );
		$linkedin->message	= JRequest::getVar( 'integrations_linkedin_message' );
		$linkedin->private	= JRequest::getVar( 'integrations_linkedin_private' );
		if( !$linkedin->store() )
		{
			$mainframe->enqueueMessage( JText::_('COM_EASYBLOG_INTEGRATIONS_LINKEDIN_ERROR') , 'error');
		}
	
		// store faebook info
		$facebook	= JTable::getInstance( 'Oauth' , 'Table' );
		$facebook->loadByUser( $user->id , EBLOG_OAUTH_FACEBOOK );
		$facebook->auto		= JRequest::getVar( 'integrations_facebook_auto' );
		$facebook->message	= '';
		if( !$facebook->store() )
		{
			$mainframe->enqueueMessage( JText::_('COM_EASYBLOG_INTEGRATIONS_FACEBOOK_FAILED_UPDATE_INFO_ERROR') , 'error');
		}

		if( $config->get( 'integration_google_adsense_enable' ) )
		{
			// Store adsense data
			$adsense =& JTable::getInstance( 'Adsense', 'Table' );
	    	$adsense->load( $user->id );

			$adsense->code 		= $post['adsense_code'];
			$adsense->display 	= $post['adsense_display'];
			$adsense->published = $post['adsense_published'];
			$adsense->store();
		}
		
		// Store feedburner data
		$feedburner	=& JTable::getInstance( 'Feedburner' , 'Table' );
		$feedburner->load( $user->id );
		
		$feedburner->url	= $post['feedburner_url'];
		$feedburner->store();

		$this->_saveSuccess( $user->id );
	}

	function _saveSuccess( $id = '' )
	{
		$mainframe	=& JFactory::getApplication();
		
		if( !empty($id) )
			$mainframe->redirect( 'index.php?option=com_easyblog&c=user&id='.$id.'&task=edit' , JText::_('COM_EASYBLOG_BLOGGER_SAVED') );
	}
	
	function _saveError( $id = '' )
	{
		$mainframe	=& JFactory::getApplication();
		
		if( !empty($id) )
			$mainframe->redirect( 'index.php?option=com_easyblog&c=user&task=edit&id=' . $id );
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
}