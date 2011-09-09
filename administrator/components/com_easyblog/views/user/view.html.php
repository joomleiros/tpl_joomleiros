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

jimport( 'joomla.application.component.view');
require( EBLOG_ADMIN_ROOT . DS . 'views.php');

class EasyBlogViewUser extends EasyBlogAdminView 
{
	function display($tpl = null)
	{
		require_once( EBLOG_HELPERS . DS . 'integrate.php' );
		
		//initialise variables
		$document	=& JFactory::getDocument();
		$mainframe	=& JFactory::getApplication();
		
		$config     =& EasyBlogHelper::getConfig();

 		$id		= JRequest::getInt('id');
		$blogger	=& JTable::getInstance( 'Profile' , 'Table' );
		$blogger->load( $id );

		//get integrated avatar
		//$integrate	= new EasyBlogIntegrate();
		//$blogger 	= $integrate->integrate($blogger);
		$post   = EasyBlogHelper::getSession( 'EASYBLOG_REGISTRATION_POST' );

		$avatarIntegration = $config->get( 'layout_avatarIntegration', 'default' );
		
		$user	=& JFactory::getUser( $id );
		$isNew  = ($user->id == 0) ? true : false;
		
		if($isNew && !empty( $post) )
		{
		    unset( $post['id'] );
		    
		    $pwd    = $post['password'];
		    
		    unset( $post['password'] );
		    unset( $post['password2'] );
		
		    $user->bind( $post );
		    
		    $post['password']   = $pwd;
			$blogger->bind( $post );
			
		}
		
		jimport('joomla.html.pane');

		$feedburner	=& JTable::getInstance( 'Feedburner' , 'Table' );
		$feedburner->load( $id );

		
		JTable::addIncludePath( EBLOG_TABLES );
		
		//twitter
		$twitter		=& JTable::getInstance( 'Oauth' , 'Table' );
		$twitter->loadByUser( $user->id , EBLOG_OAUTH_TWITTER );
		
		//linkedin
		$linkedin		=& JTable::getInstance( 'Oauth' , 'Table' );
		$linkedin->loadByUser( $user->id , EBLOG_OAUTH_LINKEDIN );
		
		//facebook
		$facebook		=& JTable::getInstance( 'Oauth' , 'Table' );
		$facebook->loadByUser( $user->id , EBLOG_OAUTH_FACEBOOK );
		
		$adsense =& JTable::getInstance( 'Adsense' , 'Table' );
		$adsense->load( $id );
		
		if($isNew && !empty( $post) )
		{
		    $feedburner->url   	= $post['feedburner_url'];
		
		    $twitter->message  	= $post['integrations_twitter_message'];
		    $twitter->auto  	= $post['integrations_twitter_auto'];
		    
		    $linkedin->auto      = $post['integrations_linkedin_auto'];
		    $linkedin->private   = isset( $post['integrations_linkedin_private'] ) ? $post['integrations_linkedin_private'] : false;
		    
		    $facebook->auto   = $post['integrations_facebook_auto'];
		    
            $adsense->published             = $post['adsense_published'];
            $adsense->code             		= $post['adsense_code'];
            $adsense->display             	= $post['adsense_display'];
            
		}
		
		if(EasyBlogHelper::getJoomlaVersion() >= '1.6')
		{
			require_once( JPATH_ROOT. DS . 'administrator' . DS . 'components' . DS . 'com_users' . DS . 'models' . DS . 'user.php');
			$jUserModel		= new UsersModelUser();
		
		    $form = $jUserModel->getForm();
			$form->setValue('password',		null);
			$form->setValue('password2',	null);
			
			$this->assignRef( 'form' , $form );
		}
		$joomla_date    = (EasyBlogHelper::getJoomlaVersion() <= '1.5') ? '%Y-%m-%d %H:%M:%S' : 'Y-m-d H:i:s';
		$editor 		= JFactory::getEditor( $config->get('layout_editor', 'tinymce') );
		
		$this->assignRef( 'editor'		, $editor );
		$this->assignRef( 'dateFormat'	, $joomla_date );
		$this->assignRef( 'config' 		, $config );
		$this->assignRef( 'pane' 		, $pane );
		$this->assignRef( 'feedburner' 	, $feedburner );
		$this->assignRef( 'adsense' 	, $adsense );
		$this->assignRef( 'twitter' 	, $twitter );
		$this->assignRef( 'facebook' 	, $facebook );
		$this->assignRef( 'linkedin' 	, $linkedin );
 		$this->assignRef( 'blogger' 	, $blogger );
		$this->assignRef( 'user' 		, $user );
		$this->assignRef( 'isNew' 		, $isNew );
		$this->assignRef( 'params' 		, $user->getParameters(true) );
		$this->assignRef( 'avatarIntegration' , $avatarIntegration );
		$this->assignRef( 'post' , $post );
		
		parent::display($tpl);
	}

	function getGroupsHTML( $userId )
	{
		$user	=& JFactory::getUser( $userId );
		$my		=& JFactory::getUser();
		
		if( EasyBlogHelper::getJoomlaVersion() >= '1.6' )
		{
			return JHTML::_('access.usergroups', 'jform[groups]', $user->groups, true);
			
		}
		else
		{
			$acl			=& JFactory::getACL();
			$userObjectID 	= $acl->get_object_id( 'users', $user->get('id'), 'ARO' );
			$userGroups 	= $acl->get_object_groups( $userObjectID, 'ARO' );
			$userGroupName 	= strtolower( $acl->get_group_name( $userGroups[0], 'ARO' ) );
	
			$myObjectID 	= $acl->get_object_id( 'users', $my->get('id'), 'ARO' );
			$myGroups 		= $acl->get_object_groups( $myObjectID, 'ARO' );
			$myGroupName 	= strtolower( $acl->get_group_name( $myGroups[0], 'ARO' ) );;
	
	
			if ( $userGroupName == $myGroupName && $myGroupName == 'administrator' )
			{
				// administrators can't change each other
				return '<input type="hidden" name="gid" value="'. $user->get('gid') .'" /><strong>'. JText::_( 'Administrator' ) .'</strong>';
			}
		
			$gtree = $acl->get_group_children_tree( null, 'USERS', false );
			return JHTML::_('select.genericlist',   $gtree, 'gid', 'size="10"', 'value', 'text', $user->get('gid') );
		}
	}
	
	function registerToolbar()
	{
		$id		= JRequest::getInt('id');
		$user	=& JTable::getInstance( 'User' , 'JTable' );
		$user->load( $id );
		
		$title  = ($user->id == 0) ? JText::_('COM_EASYBLOG_BLOGGERS_NEW_BLOGGER_TITLE') : JText::sprintf( 'COM_EASYBLOG_BLOGGERS_EDIT_BLOGGER_TITLE' , $user->name );
		
		JToolBarHelper::title( $title , 'users' );

		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}

	function registerSubmenu()
	{
		return 'submenu.php';
	}
}