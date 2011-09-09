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

class EasyBlogViewBlog extends EasyBlogAdminView
{
	function display($tpl = null)
	{
	    $lang		= JFactory::getLanguage();
	    $lang->load( 'com_easyblog' , JPATH_ROOT );
	    
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();
		$acl		= EasyBlogACLHelper::getRuleSet();
		$config 	=& EasyBlogHelper::getConfig();

		$document	=& JFactory::getDocument();
		//$document->addStyleSheet( JURI::root() . 'components/com_easyblog/assets/css/common.css' );
		$document->addScript( JURI::root() . 'components/com_easyblog/assets/js/ej.js' );
		$document->addScript( JURI::root() . 'components/com_easyblog/assets/js/ejax.js' );
		$document->addScript( JURI::root() . 'components/com_easyblog/assets/js/eblog.js' );

	    // Add the Calendar includes to the document <head> section
		JHTML::_('behavior.calendar');
		
		// Add tooltip behavior.
		JHTML::_('behavior.tooltip');
		
		// Load the JEditor object
		$editor =& JFactory::getEditor( $config->get('layout_editor', 'tinymce') );
		
		//Load pane behavior
		jimport('joomla.html.pane');
		
		// enable datetime picker
		EasyBlogDateHelper::enableDateTimePicker();
		
		// required variable initiation.
        $meta   			= null;
        $blogContributed    = array();
        $tags               = null;
		
		//Load blog table
		$blogId		= JRequest::getVar( 'blogid' , '' );
		$blog		=& JTable::getInstance( 'Blog' , 'Table' );
		$blog->load( $blogId );
		
		
		$draft		=& JTable::getInstance( 'Draft' , 'Table' );
		$draft_id	= JRequest::getVar( 'draft_id' , '' );
		$isDraft    = false;
		
		$pending_approval   = JRequest::getVar( 'approval' , '' );
		
		if( !empty( $draft_id ) )
		{
		    //first check if the logged in user have the required acl or not.
			if( empty($acl->rules->add_entry) || empty($acl->rules->publish_entry) || empty($acl->rules->manage_pending))
			{
			    $message = JText::_('COM_EASYBLOG_BLOGS_BLOG_NO_PERMISSION_TO_CREATE_BLOG');
				$mainframe->enqueueMessage( $message, 'error' );
				$mainframe->redirect( JRoute::_('index.php?option=com_easyblog&view=blogs', false) );
			}
		
		    $draft->load( $draft_id );
		    $blog->load( $draft->entry_id );
		    
		    $blog->bind( $draft );
		    
			$blog->tags		= $this->bindTags( explode( ',' , $draft->tags ) );
			$tags           = $this->bindTags( explode( ',' , $draft->tags ) );

		    // metas
			$meta				= new stdClass();
			$meta->id			= '';
			$meta->keywords		= $draft->metakey;
			$meta->description	= $draft->metadesc;
			
			$blog->unsaveTrackbacks = '';
			if( !empty( $draft->trackbacks ) )
			{
				$blog->unsaveTrackbacks	= $draft->trackbacks;
			}

			if( $draft->blog_contribute )
			{
				$blogContributed	= $this->bindContribute( $draft->blog_contribute );
			}

			$blog->set( 'id' , $draft->entry_id );
			$blogId		= $blog->id;
			$isDraft	= true;
		}

		// set page title
		if ( !empty( $blogId ) )
		{
			$document->setTitle( JText::_('COM_EASYBLOG_BLOGS_EDIT_POST') . ' - ' . $config->get('main_title') );
			$editorTitle 	= JText::_('COM_EASYBLOG_BLOGS_EDIT_POST');
			
			// check if previous status is not Draft
			if ( $blog->published != POST_ID_DRAFT )
			{
				$isEdit			= true;
			}
		}
		else
		{
			$document->setTitle( JText::_('COM_EASYBLOG_BLOGS_NEW_POST') );
			$editorTitle 		= JText::_('COM_EASYBLOG_BLOGS_NEW_POST');
			
			// set to 'publish' for new blog in backend.
			$blog->published 	= $config->get('main_blogpublishing', '1');
		}
		
		$author = null;
		if(!empty($blog->created_by))
		{
			$creator	= JFactory::getUser($blog->created_by);
			$author		=& JTable::getInstance( 'Profile', 'Table' );
			$author->setUser( $creator );
			unset($creator);
		}
		else
		{
			$creator	= JFactory::getUser($user->id);
			$author		=& JTable::getInstance( 'Profile', 'Table' );
			$author->setUser( $creator );
			unset($creator);
		}
		
		//Get tag
		if( !$isDraft )
		{
			$tagModel	=& $this->getModel( 'PostTag' );
			$tags		=& $tagModel->getBlogTags($blogId);
		}

		$tagsArray = array();
		foreach($tags as $data)
		{
			$tagsArray[] = $data->title;	
		}
		$tagsString = implode(",", $tagsArray);
		unset($tags);
		
		//initialise variables
		$slider		=& JPane::getInstance( 'sliders' );
		
		//prepare initial blog settings.
		$isPrivate    	= $config->get('main_blogprivacy', '0');
		$allowComment   = $config->get('main_comment', 1);
		$allowSubscribe	= $config->get('main_subscription', 1);
		$showFrontpage  = $config->get('main_newblogonfrontpage', 0);
		
		$isSiteWide		= (isset($blog->issitewide)) ? $blog->issitewide : '1';
		
		$tbModel			=& $this->getModel( 'TeamBlogs' );
		$teamBlogJoined = $tbModel->getTeamJoined($author->id);

		if(! empty($blog->id))
		{
		    $isPrivate    	= $blog->private;
		    $allowComment   = $blog->allowcomment;
		    $allowSubscribe	= $blog->subscription;
		    $showFrontpage	= $blog->frontpage;
		    
		    //get user teamblog
		    $teamBlogJoined 	= $tbModel->getTeamJoined($blog->created_by);

			if( !$isDraft )
				$blogContributed    = $tbModel->getBlogContributed($blog->id);
		}
		
		if(count($blogContributed) > 0)
		{
			//$teamBlogJoined	= array_merge_recursive($teamBlogJoined, $blogContributed);

			for($i = 0; $i < count($teamBlogJoined); $i++)
			{
				$joined =& $teamBlogJoined[$i];

			    if($joined->team_id == $blogContributed->team_id)
			    {
			        $joined->selected   = 1;
					continue;
			    }
			}
		}
		
		//Get categories
		$onlyPublished  = ( empty( $blog->id ) ) ? true : false;
		$nestedCategories = EasyBlogHelper::populateCategories('', '', 'select', 'category_id', $blog->category_id, true, $onlyPublished);
		
		//get all tags ever created.
		$newTagsModel	=& $this->getModel( 'Tags' );
		$blog->newtags	= $newTagsModel->getData(false);
		
		//get tags used in this blog post
		if( !$isDraft )
		{
			$tagsModel	=& $this->getModel( 'PostTag' );
			$blog->tags	= $tagsModel->getBlogTags( $blogId );
		}
		
		//@task: List all trackbacks
		$trackbacksModel	=& $this->getModel( 'TrackbackSent' );
		$trackbacks			= $trackbacksModel->getSentTrackbacks( $blogId );		
		
		// get meta tags
		if( !$isDraft )
		{
			$metaModel		=& $this->getModel('Metas');
			$meta 			= $metaModel->getPostMeta($blogId);
		}
		
		//perform some title string formatting
		$blog->title    = $this->escape($blog->title);
		
		$blogger_id = ( !isset($blog->created_by) ) ? $user->id   : $blog->created_by;

		$this->assignRef( 'blogger_id' 		, $blogger_id );
		$this->assignRef( 'joomlaversion' 	, EasyBlogHelper::getJoomlaVersion() );
		$this->assignRef( 'isEdit' 			, $isEdit );
		$this->assignRef( 'editorTitle' 	, $editorTitle );
		$this->assignRef( 'blog' 			, $blog );
		$this->assignRef( 'meta' 			, $meta );
		$this->assignRef( 'editor' 			, $editor );
		$this->assignRef( 'tagsString' 		, $tagsString );
		$this->assignRef( 'acl' 			, $acl );
		$this->assignRef( 'isPrivate' 		, $isPrivate );
		$this->assignRef( 'allowComment'	, $allowComment );
		$this->assignRef( 'subscription' 	, $allowSubscribe );
		$this->assignRef( 'frontpage' 		, $showFrontpage );
		$this->assignRef( 'trackbacks'		, $trackbacks );
		$this->assignRef( 'author'			, $author );
		$this->assignRef( 'nestedCategories'	, $nestedCategories );
		$this->assignRef( 'teamBlogJoined'		, $teamBlogJoined );
		$this->assignRef( 'isSiteWide'			, $isSiteWide );
		$this->assignRef( 'draft'				, $draft );
		$this->assignRef( 'config'			, $config );
		$this->assignRef( 'pending_approval'	, $pending_approval );

		
		parent::display($tpl);
	}
	
	function bindTags( $arrayData )
	{
		$result	= array();

		if( count( $arrayData ) > 0 )
		{
			foreach( $arrayData as $tag )
			{
				$obj		= new stdClass();
				$obj->title	= $tag;
				$result[]	= $obj;
			}
		}
		return $result;
	}

	function bindContribute( $contribution = '' )
	{
		if( $contribution )
		{
			$contributed			= new stdClass();
			$contributed->team_id	= $contribution;
			$contributed->selected	= 1;

			return $contributed;
		}
		return false;
	}

	function registerToolbar()
	{
		if( !empty( $this->pending_approval ) )
		{
			JToolBarHelper::title( JText::sprintf( 'COM_EASYBLOG_PENDING_EDIT_PAGE_HEADING' ), 'blogs' );
			JToolBarHelper::custom('rejectBlog','save.png','save_f2.png', 'COM_EASYBLOG_REJECT_BUTTON', false);
			JToolBarHelper::custom('savePublish','save.png','save_f2.png', 'COM_EASYBLOG_APPROVE_BUTTON', false);
		}
		else
		{
			if( $this->blog->id != 0 )
			{
				JToolBarHelper::title( JText::sprintf( 'COM_EASYBLOG_BLOGS_EDITING_BLOG_TITLE' , $this->blog->title ), 'blogs' );
				JToolBarHelper::custom('savePublish','save.png','save_f2.png', 'COM_EASYBLOG_UPDATE_BUTTON', false);
			}
			else
			{
				JToolBarHelper::title( JText::_( 'COM_EASYBLOG_BLOGS_NEW_POST_TITLE' ), 'blogs' );
				JToolBarHelper::custom('savePublish','save.png','save_f2.png', 'COM_EASYBLOG_SAVE_BUTTON', false);
			}
		}
		JToolBarHelper::cancel();
	}

	function registerSubmenu()
	{
		return 'submenu.php';
	}
}