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

class EasyBlogControllerTeamBlogs extends EasyBlogController
{	
	function __construct()
	{
		parent::__construct();
		
		$this->registerTask( 'add' , 'edit' );
		$this->registerTask( 'apply' , 'save' );
	}
	
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$post	= JRequest::get('post');
		
		$team_desc	= JRequest::getVar('write_description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post['description']    = $team_desc;
		
		if(!empty($post['title']))
		{
			$team	=& JTable::getInstance( 'TeamBlog' , 'Table' );
			
			$date	=& JFactory::getDate();
			$team->created	= $date->toMySQL();
			$team->bind( $post );
				
			$team->title = JString::trim($team->title);
			$team->alias = JString::trim($team->alias);
			
			$msgStatus 	= 'message';
			$message	= JText::_('COM_EASYBLOG_TEAM_BLOG_ADDED');
			
			if( $team->id != 0 )
				$message = JText::_('COM_EASYBLOG_TEAMBLOG_SAVED_SUCCESSFULLY');
				
			if($team->store())
			{
			
				//meta post info
				$metapost	= array();
				$metapost['keywords']		= JRequest::getVar('keywords', '');
				$metapost['description']	= JRequest::getVar('description', '');
				$metapost['content_id']		= $team->id;
				$metapost['type']			= META_TYPE_TEAM;

				$metaId		= JRequest::getVar( 'metaid' , '' );

				$meta		=& JTable::getInstance( 'Meta', 'Table' );
				$meta->load($metaId);
				$meta->bind($metapost);
				$meta->store();

				if( isset( $post['members']) )
				{
				
					$delMember	= explode(',', $post['deletemembers']);
					if(count($delMember) > 0)
					{
					    foreach($delMember as $id)
					    {
					        if( !empty($id) )
								$team->deleteMembers($id);
					    }
					}

					foreach( $post['members'] as $id )
					{
						$member				=& JTable::getInstance( 'TeamBlogUsers' , 'Table' );
						$member->team_id	= $team->id;
						$member->user_id	= $id;

						if( !$member->exists() )
						{
							$member->addMember();
						}
					}
				}
				
				$file = JRequest::getVar( 'Filedata', '', 'files', 'array' );
				if(! empty($file['name']))
				{
					$newAvatar  		= EasyBlogHelper::uploadTeamAvatar($team, true);
					$team->avatar   	= $newAvatar;
					$team->store(); //now update the avatar.
				}
				
			}
		}
		else
		{
			$msgStatus 	= 'error';
			$message	= JText::_('COM_EASYBLOG_INVALID_TEAM_BLOG_TITLE');
		}

		if( JRequest::getVar( 'task' ) == 'apply' )
		{
			$this->setRedirect( 'index.php?option=com_easyblog&c=teamblogs&task=edit&id=' . $team->id , $message , $msgStatus );
			return;
		}
		
		$this->setRedirect(  'index.php?option=com_easyblog&view=teamblogs' , $message , $msgStatus );
		return;		
	}

	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_easyblog&view=teamblogs' );
		
		return;
	}

	function edit()
	{
		JRequest::setVar( 'view', 'teamblog' );
		JRequest::setVar( 'id' , JRequest::getVar( 'id' , '' , 'REQUEST' ) );
		
		parent::display();
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$teams	= JRequest::getVar( 'cid' , '' , 'POST' );
		
		$message	= '';
		$type		= 'message';
		
		if( empty( $teams ) )
		{
			$message	= JText::_('Invalid Team id');
			$type		= 'error';
		}
		else
		{
			$table		=& JTable::getInstance( 'TeamBlog' , 'Table' );
			foreach( $teams as $id )
			{
				$table->load( $id );
				
				if( !$table->delete() )
				{
					$message	= JText::_( 'Error removing Team.' );
					$type		= 'error';
					$this->setRedirect( 'index.php?option=com_easyblog&view=teamblogs' , $message , $type );
					return;
				}
			}
			
			$message	= JText::_('Team(s) deleted');
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=teamblogs' , $message , $type );
	}

	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$teams	= JRequest::getVar( 'cid' , array(0) , 'POST' );
		$message	= '';
		$type		= 'message';
		
		if( count( $teams ) <= 0 )
		{
			$message	= JText::_('Invalid team id');
			$type		= 'error';
		}
		else
		{
			$team	=& JTable::getInstance( 'TeamBlog' , 'Table' );
			$team->publish( $teams );

			$message	= JText::_('Team(s) published');
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=teamblogs' , $message , $type );
	}

	function unpublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$teams	= JRequest::getVar( 'cid' , array(0) , 'POST' );
		$message	= '';
		$type		= 'message';
		
		if( count( $teams ) <= 0 )
		{
			$message	= JText::_('Invalid team id');
			$type		= 'error';
		}
		else
		{
			$team	=& JTable::getInstance( 'TeamBlog' , 'Table' );
			$team->publish( $teams , 0 );

			$message	= JText::_('Team(s) unpublished');
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=teamblogs' , $message , $type );
	}
	
	function markAdmin()
	{
		// Check for request forgeries
		JRequest::checkToken( 'GET' ) or jexit( 'Invalid Token' );
		
	    $teamId	= JRequest::getVar( 'teamid', '' );
	    $userId	= JRequest::getVar( 'userid', '' );
	    
	    if(empty($teamId) || empty($userId))
	    {
	        $this->setRedirect( 'index.php?option=com_easyblog&view=teamblogs');
	    }
	    
		$this->setAsAdmin($teamId, $userId, true);
	    
	    $this->setRedirect( 'index.php?option=com_easyblog&c=teamblogs&task=edit&id=' . $teamId);
	}
	
	function removeAdmin()
	{
		// Check for request forgeries
		JRequest::checkToken( 'GET' ) or jexit( 'Invalid Token' );
		
	    $teamId	= JRequest::getVar( 'teamid', '' );
	    $userId	= JRequest::getVar( 'userid', '' );

	    if(empty($teamId) || empty($userId))
	    {
	        $this->setRedirect( 'index.php?option=com_easyblog&view=teamblogs');
	    }

		$this->setAsAdmin($teamId, $userId, false);

	    $this->setRedirect( 'index.php?option=com_easyblog&c=teamblogs&task=edit&id=' . $teamId);
	}
	
	function setAsAdmin($teamId, $userId, $isAdmin)
	{
		// Check for request forgeries
		JRequest::checkToken( 'GET' ) or jexit( 'Invalid Token' );
		
	    $db =& JFactory::getDBO();

	    $query  = 'UPDATE `#__easyblog_team_users` SET ';
	    if($isAdmin)
			$query	.= ' `isadmin` = ' . $db->Quote('1');
		else
		    $query	.= ' `isadmin` = ' . $db->Quote('0');
	    $query  .= ' WHERE `team_id` = ' . $db->Quote($teamId);
	    $query  .= ' AND `user_id` = ' . $db->Quote($userId);

	    $db->setQuery($query);
	    $db->query();
	    
	    return true;
	}
	
	function teamApproval()
	{
		// Check for request forgeries
		JRequest::checkToken( 'GET' ) or jexit( 'Invalid Token' );
		
		$mainframe	=& JFactory::getApplication();
		$acl		= EasyBlogACLHelper::getRuleSet();
		$config 	=& EasyBlogHelper::getConfig();
		$document	=& JFactory::getDocument();
		$my			=& JFactory::getUser();

		$teamId 	= JRequest::getInt('team', 0);
		$approval	= JRequest::getInt('approve');
		$requestId	= JRequest::getInt('id', 0);

		$ok 		= true;
		$message    = '';
		$type       = 'info';
		
	    $teamRequest    =& JTable::getInstance( 'TeamBlogRequest','Table' );
	    $teamRequest->load($requestId);

		if($approval)
		{
		    $teamUsers    =& JTable::getInstance( 'TeamBlogUsers','Table' );

		    $teamUsers->user_id    = $teamRequest->user_id;
		    $teamUsers->team_id    = $teamRequest->team_id;

		    if($teamUsers->store())
			{
		        $message    = JText::_('COM_EASYBLOG_TEAMBLOGS_APPROVAL_APPROVED');
		    }
		    else
		    {
		        $ok 		= false;
		        $message    = JText::_('COM_EASYBLOG_TEAMBLOGS_APPROVAL_FAILED');
		        $type       = 'error';
			}
		}
		else
		{
		    $message    = JText::_('COM_EASYBLOG_TEAMBLOGS_APPROVAL_REJECTED');
		}

		if($ok)
		{
			$teamRequest->ispending = 0;
			$teamRequest->store();

			$teamBlog =& JTable::getInstance( 'TeamBlog','Table' );
			$teamBlog->load($teamRequest->team_id);

			//now we send notification to requestor
			$requestor  =& JFactory::getUser($teamRequest->user_id);
			$template   = ($approval) ? 'email.teamblog.request.approved.php' : 'email.teamblog.request.rejected.php';
			$toNotifyEmails = $requestor->email;

			$notify	=& EasyBlogHelper::getNotification();
			$emailData  = array();
			$emailData['team']  	= $teamBlog->title;
			$notify->sendEmails($toNotifyEmails, JText::_('COM_EASYBLOG_TEAMBLOGS_JOIN_REQUEST'), '', $template, $emailData);
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=teamrequest' , $message , $type );
	}
}