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
require_once( EBLOG_HELPERS . DS . 'oauth.php' );
require_once( EBLOG_HELPERS . DS . 'subscription.php' );

class EasyBlogControllerBlogs extends EasyBlogController
{	
	function __construct()
	{
		parent::__construct();
		
		$this->registerTask( 'add' , 'edit' );
		$this->registerTask( 'unfeature' , 'toggleFeatured' );
		$this->registerTask( 'feature' , 'toggleFeatured' );
	}

	function toggleFrontpage()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$mainframe	=& JFactory::getApplication();
		
		$records	= JRequest::getVar( 'cid' );
		$msg		= '';
		
		foreach( $records as $record )
		{
			$blog		= JTable::getInstance( 'Blog' , 'Table' );
			$blog->load( $record );
			
			$blog->frontpage	= !$blog->frontpage;
			$blog->store();
			$msg				= $blog->frontpage ? JText::sprintf( 'COM_EASYBLOG_BLOGS_SET_AS_FRONTPAGE_SUCCESS' , $blog->title ) : JText::sprintf( 'COM_EASYBLOG_BLOGS_REMOVED_FROM_FRONTPAGE_SUCCESS' , $blog->title );
		}
		
		
	    $mainframe->enqueueMessage( $msg , 'message' );
		$mainframe->redirect( 'index.php?option=com_easyblog&view=blogs' );
	}

	function toggleFeatured()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$mainframe	=& JFactory::getApplication();
		$records	= JRequest::getVar( 'cid' , '' );
		$message	= '';
		$task		= JRequest::getVar( 'task' );
		
		if( empty( $records ) )
		{
		    $mainframe->enqueueMessage( JText::_( 'COM_EASYBLOG_INVALID_BLOG_ID' ) , 'error' );
			$mainframe->redirect( 'index.php?option=com_easyblog&view=blogs' );
			$mainframe->close();
		}
		
		foreach( $records as $record )
		{
			if( $task == 'unfeature' )
			{
				EasyBlogHelper::removeFeatured( EBLOG_FEATURED_BLOG, $record );
				$message	= JText::_( 'COM_EASYBLOG_BLOGS_UNFEATURED_SUCCESSFULLY' );
			}
			else
			{
				EasyBlogHelper::makeFeatured( EBLOG_FEATURED_BLOG, $record );
				$message	= JText::_( 'COM_EASYBLOG_BLOGS_FEATURED_SUCCESSFULLY' );
			}
		}
	    $mainframe->enqueueMessage( $message , 'message' );
		$mainframe->redirect( 'index.php?option=com_easyblog&view=blogs' );
		$mainframe->close();
	}
	
	function approveBlog()
	{
		$mainframe	=& JFactory::getApplication();
		$config 	=& EasyBlogHelper::getConfig();
		$message	= '';
		$type		= 'message';

		$my				=& JFactory::getUser();
		$acl			= EasyBlogACLHelper::getRuleSet();
		$joomlaVersion	= EasyBlogHelper::getJoomlaVersion();
		$redirect       = 'index.php?option=com_easyblog&view=pending';
		
		// draft id.
		$id				= JRequest::getInt( 'draft_id' );
		
		if( empty( $id ) )
		{
			$mainframe->enqueueMessage( JText::_( 'COM_EASYBLOG_NOT_ALLOWED' ), 'error' );
			$mainframe->redirect( $redirect );
			$mainframe->close();
		}

		if( empty( $acl->rules->add_entry ) && empty( $acl->rules->manage_pending ) )
		{
			$mainframe->enqueueMessage( JText::_( 'COM_EASYBLOG_BLOGS_BLOG_NO_PERMISSION_TO_CREATE_BLOG' ), 'error' );
			$mainframe->redirect( $redirect );
			$mainframe->close();
			return;
		}

		if( $my->id == 0 )
		{
			$mainframe->enqueueMessage( JText::_( 'COM_EASYBLOG_YOU_ARE_NOT_LOGIN' ), 'error' );
			$mainframe->redirect( $redirect );
			$mainframe->close();
			return;
		}

		JPluginHelper::importPlugin( 'easyblog' );
		$dispatcher =& JDispatcher::getInstance();


		$draft		=& JTable::getInstance( 'Draft', 'Table' );
		$draft->load( $id );


		$blog		=& JTable::getInstance( 'blog', 'Table' );
		$isNew		= ( $blog->load( $draft->entry_id ) ) ? false : true;

		if(! $isNew)
		{
		    // if this is blog edit, then we should see the column isnew to determine
		    // whether we should send any notification
		    $isNew  = $blog->isnew;
		}


		// @task: Map the data from draft table.
		$blog->bind( $draft );
		$blog->set( 'id' , $draft->entry_id );

		//author acl
		$authorAcl		= EasyBlogACLHelper::getRuleSet( $blog->created_by );


		//check if user have permission to enable privacy.
		$blog->private	= empty( $authorAcl->rules->enable_privacy ) ? 0 : $blog->private;

		//check if user have permission to contribute the blog post to eblog frontpage
		$blog->frontpage 	= ( empty($authorAcl->rules->contribute_frontpage) ) ? '0' : $blog->frontpage;
		$blog->isnew 		= ($isNew && ($blog->published != 1)) ? '1' : '0';

		//now we need to check the blog contribution
		$blog->issitewide	= isset( $draft->blog_contribute) && $draft->blog_contribute != 0 ? false : true;

		$blogContribution   = array();
		$issitewide 		= '1';

		if( isset( $draft->blog_contribute) && $draft->blog_contribute == '0' )
		{
			$blog->issitewide	= true;
		}
		else
		{
			$blog->issitewide	= false;
			$blogContribution[]	= $draft->blog_contribute;
		}

		//onBeforeEasyBlogSave trigger start
		$blog->introtext	= '';
		$blog->text			= '';
		$dispatcher->trigger( 'onBeforeEasyBlogSave' , array( &$blog , $isNew ) );
		//onBeforeEasyBlogSave trigger end

		//onBeforeContentSave trigger start
		$blog->introtext	= $blog->intro;
		$blog->text			= $blog->content;

		if($joomlaVersion >= '1.6')
		{
			$dispatcher->trigger('onContentBeforeSave', array('easyblog.blog', &$blog, $isNew));
		}
		else
		{
			$dispatcher->trigger('onBeforeContentSave', array(&$blog, $isNew));
		}

		$blog->intro		= $blog->introtext;
		$blog->content		= $blog->text;
		unset($blog->introtext);
		unset($blog->text);
		//onBeforeContentSave trigger end

		if( !empty($draft->blogpassword) && !EasyBlogHelper::isFeatured( 'post' , $blog->id ) )
		{
			$blog->blogpassword = $draft->blogpassword;
		}

		if (!$blog->store())
		{
		    EasyBlogHelper::setMessageQueue( $blog->getError() , 'error');
			$mainframe->redirect( EasyBlogRouter::_( 'index.php?option=com_easyblog&view=pending' , false) );
		}

		//onAfterEasyBlogSave trigger start
		$blog->introtext	= "";
		$blog->text			= "";
		$dispatcher->trigger('onAfterEasyBlogSave', array(&$blog, $isNew));
		//onAfterEasyBlogSave trigger end

		//onAfterContentSave trigger start
		$blog->introtext	= $blog->intro;
		$blog->text			= $blog->content;

		if($joomlaVersion >= '1.6')
		{
			$dispatcher->trigger('onContentAfterSave', array('easyblog.blog', &$blog, $isNew));
		}
		else
		{
			$dispatcher->trigger('onAfterContentSave', array(&$blog, $isNew));
		}

		$blog->excerpt		= $blog->introtext;
		$blog->content		= $blog->text;
		unset($blog->introtext);
		unset($blog->text);
		//onAfterContentSave trigger end

		// this variable will be use when sending notification. This is bcos
		// the currently login use might be the admin and editing other blogger post.
	    $authorId   = $blog->created_by;

        //now we update the blog contribution
        $blog->updateBlogContribution($blogContribution);

		// @task: 3rd party points integrations.
		if($isNew && $blog->published == POST_ID_PUBLISHED)
		{
			if( ($my->id != 0) && $config->get('main_jomsocial_userpoint') )
			{
				$path	= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'userpoints.php';
				if( JFile::exists( $path ) )
				{
					require_once( $path );
					CUserPoints::assignPoint( 'com_easyblog.blog.add' , $blog->created_by );
				}
			}

			// AlphaUserPoint
			// since 1.2
			if( ($my->id != 0) && EasyBlogHelper::isAUPEnabled() )
			{
				AlphaUserPointsHelper::newpoints( 'plgaup_easyblog_add_blog', '', 'easyblog_add_blog_' . $blog->id, JText::sprintf('COM_EASYBLOG_AUP_NEW_BLOG_CREATED', EasyBlogRouter::_('index.php?option=com_easyblog&view=entry&id='.$blog->id) , $blog->title) );
			}
		}

		// @task: 3rd party activity integrations.
		if(($blog->published == POST_ID_PUBLISHED))
		{
			EasyBlogHelper::addJomSocialActivityBlog($blog, $isNew);
		}

		$blogId		= $blog->id;

		//meta post info
		$metaId		= $blog->getMetaId();

		$metapost	= array();
		$metapost['keywords']		= $draft->metakey;
		$metapost['description']	= $draft->metadesc;
		$metapost['content_id']		= $blog->id;
		$metapost['type']			= META_TYPE_POST;

		// @rule: Save meta tags for this entry.
		$meta		=& JTable::getInstance( 'Meta', 'Table' );
		$meta->load( $metaId );
		$meta->bind( $metapost );
		$meta->store();

		$author		=& JTable::getInstance( 'Profile', 'Table' );
		$author->load( $blog->created_by );

		// @task: Store trackbacks into the trackback table for tracking purposes.
		$trackbacks	= $draft->trackbacks;

		if( !empty( $acl->rules->add_trackback) && !empty( $trackbacks ) )
		{
			$rows	= explode( "\n" , $trackbacks);

			foreach( $rows as $row )
			{
				$trackback	= JTable::getInstance( 'TrackbackSent' , 'Table' );

				if( !$trackback->load( $row , true , $blog->id ) )
				{
					$trackback->post_id		= $blog->id;
					$trackback->url			= $row;
					$trackback->sent		= 0;
					$trackback->store();
				}
			}
		}

		// @task: Process additional stuffs if the blog is published
		if ( $blog->published == POST_ID_PUBLISHED )
		{
			$trackbackModel =& $this->getModel('TrackbackSent');

			// get lists of trackback URLs based on blog ID
			$trackbacks		= $trackbackModel->getSentTrackbacks( $blogId , true );

			require_once( EBLOG_CLASSES . DS . 'trackback.php' );
			require_once( EBLOG_HELPERS . DS . 'router.php' );

			if( $trackbacks )
			{
				foreach( $trackbacks as $trackback )
				{
					$tb		= new EasyBlogTrackBack( $author->getName() , $author->getName() , 'UTF-8' );
					$text	= empty( $blog->intro ) ? $blog->content : $blog->intro;
					$url	= EasyBlogRouter::getRoutedURL( 'index.php?option=com_easyblog&view=entry&id=' . $blog->id , false , true );

					if( $tb->ping( $trackback->url , $url , $blog->title , $text ) )
					{
						$table		= JTable::getInstance( 'TrackbackSent' , 'Table' );
						$table->load( $trackback->id );
						$table->markSent();
					}
				}
			}
		}

		// @task: Save any tags associated with the blog entry.
		$postTagModel	=& $this->getModel( 'PostTag' );
		$tags           = explode(',', $draft->tags);
		$date			=& JFactory::getDate();

		// @task: Delete existing associated tags.
		$postTagModel->deletePostTag( $blog->id );

		if( !empty( $tags ) )
		{
			$tagModel	=& $this->getModel( 'Tags' );

			foreach( $tags as $tag )
			{
				if(!empty($tag))
				{
					$table	=& JTable::getInstance( 'Tag' , 'Table' );

					//@task: Only add tags if it doesn't exist.
					if( !$table->exists( $tag ) )
					{
						if($acl->rules->create_tag)
						{
							$tagInfo['created_by']	= $blog->created_by;
							$tagInfo['title'] 		= JString::trim($tag);
							$tagInfo['created']		= $date->toMySQL();

							$table->bind($tagInfo);

							$table->published	= 1;
							$table->status		= '';

							$table->store();

							if( $blog->created_by != 0 && $config->get('main_jomsocial_userpoint') )
							{
								$path	= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'userpoints.php';
								if( JFile::exists( $path ) )
								{
									require_once( $path );
									CUserPoints::assignPoint( 'com_easyblog.tag.add' , $blog->created_by );
								}
							}
							
							// AlphaUserPoints
							if( ($blog->created_by != 0) && EasyBlogHelper::isAUPEnabled() )
							{
							    $aupid = AlphaUserPointsHelper::getAnyUserReferreID( $blog->created_by );
								AlphaUserPointsHelper::newpoints( 'plgaup_easyblog_add_tag', $aupid, 'easyblog_add_tag_' . $table->id, JText::sprintf('COM_EASYBLOG_AUP_TAG_ADDED', $table->title) );
							}

						}
					}
					else
					{
						$table->load( $tag , true );
					}

					//@task: Store in the post tag
					$postTagModel->add( $table->id , $blog->id , $date->toMySQL() );
				}
			}
		}

		// @task: check if the auto featured enabled.
		if( $config->get('main_autofeatured', 0) && EasyBlogHelper::isFeatured('blogger', $blog->created_by) )
		{
	        if(! EasyBlogHelper::isFeatured('post', $blog->id) )
	        {
	        	EasyBlogHelper::makeFeatured('post', $blog->id);
		    }
		}

		$autopost	= $draft->autopost;

		if( !empty( $autopost ) )
		{
			$allowed	= array( EBLOG_OAUTH_LINKEDIN , EBLOG_OAUTH_FACEBOOK , EBLOG_OAUTH_TWITTER );

			foreach( $autopost as $item )
			{
				if( in_array( $item , $allowed ) && $blog->published == POST_ID_PUBLISHED && $config->get( 'integrations_' . $item ) )
				{
					EasyBlogSocialShareHelper::share( $blog , constant( 'EBLOG_OAUTH_' . JString::strtoupper( $item ) ) );
				}
			}
		}

		if( $isNew && ($blog->published == POST_ID_PUBLISHED) )
		{
			// the sequece of the email data will be the same as the sequence in jtext::sprint params
			$emailData['blogTitle']		= $blog->title;
			$emailData['blogAuthor']	= $author->getName();
			$emailData['blogLink']		= EasyBlogRouter::getRoutedURL('index.php?option=com_easyblog&view=entry&id=' . $blog->id, false, true);
			$emailData['blogContent']	= strip_tags($blog->intro . $blog->content, '<p><img><a><div><span><table><tr><td>');
			$notify						=& EasyBlogHelper::getNotification();

			// send notification to admins
			if($config->get('notification_blogadmin'))
			{
				$notify->notifyAdmins( JText::_('COM_EASYBLOG_EMAIL_TITLE_NEW_BLOG_ADDED') , '' , 'email.blog.new.php' , $emailData );
			}

			if( $config->get( 'notification_allmembers' ) )
			{
                  EasyBlogSubscription::blogSendToAll( $blog, $emailData );
			}
			else
			{
			    // process all subscroptions on new blog post.
                  EasyBlogSubscription::processNewBlogNotification( $blog, $emailData, $blogContribution );
			}

			if( $config->get( 'main_pingomatic' ) )
			{
				if( !EasyBlogHelper::getHelper( 'Pingomatic' )->ping( $blog->title , EasyBlogRouter::getRoutedURL('index.php?option=com_easyblog&view=entry&id=' . $blog->id, true, true) ) )
				{
					EasyBlogHelper::setMessageQueue( JText::_('COM_EASYBLOG_BLOGS_BLOG_SAVE_PINGOMATIC_ERROR') , 'error');
				}
			}

			$blog->isnew    = '0';
			$blog->store();
		}

		// @task: Cleanup any messages
		$postreject	= JTable::getInstance( 'PostReject' , 'Table' );
		$postreject->clear( $draft->id );

	    // now blog updated, we need to remove the draft
	    $draft->delete();

		$message	= JText::_('COM_EASYBLOG_BLOGS_BLOG_SAVE_APPROVED');
		
		$mainframe->enqueueMessage( JText::_( 'COM_EASYBLOG_BLOGS_BLOG_SAVE_APPROVED' ), 'info' );
		$mainframe->redirect( $redirect );
		$mainframe->close();
	}
	
	function rejectBlog()
	{
		$mainframe  =& JFactory::getApplication();
		$redirect	= base64_decode( JRequest::getVar( 'redirect' , '' ) );
		$redirect	= empty( $redirect ) ? EasyBlogRouter::_( 'index.php?option=com_easyblog&view=pending' , false ) : EasyBlogRouter::_( $redirect , false );
		$my			= JFactory::getUser();
		$config     =& EasyBlogHelper::getConfig();
		$acl		= EasyBlogACLHelper::getRuleSet();
		$message	= JRequest::getVar( 'message' , '' );
		
		
		$id  		= JRequest::getVar('draft_id', '');

		if( empty( $id ) )
		{
			$mainframe->enqueueMessage( JText::_( 'COM_EASYBLOG_NOT_ALLOWED' ), 'error' );
			$mainframe->redirect( $redirect );
			$mainframe->close();
		}

		if( !EasyBlogHelper::isSiteAdmin() || empty( $acl->rules->manage_pending ) )
		{
			$mainframe->enqueueMessage( JText::_( 'COM_EASYBLOG_NOT_ALLOWED' ), 'error' );
			$mainframe->redirect( $redirect );
			$mainframe->close();
		}

		JTable::addIncludePath( EBLOG_TABLES );

		$draft		=& JTable::getInstance( 'Draft', 'Table' );
		$draft->load( $id );

		if( $draft->pending_approval != 1 )
		{
			$mainframe->enqueueMessage( JText::_( 'COM_EASYBLOG_NOT_ALLOWED' ), 'error' );
			$mainframe->redirect( $redirect );
			$mainframe->close();
		}

		// If the draft is rejected, revert draft status and create a note for the rejected draft.
		$draft->set( 'pending_approval' , 0 );
		$draft->store();

		// Create the message
		$postreject				= JTable::getInstance( 'PostReject' , 'Table' );
		$postreject->draft_id	= $draft->id;
		$postreject->message	= $message;
		$postreject->created_by	= $my->id;
		$postreject->created	= JFactory::getDate()->toMySQL();
		$postreject->store();
		
	    $message    = JText::_('COM_EASYBLOG_BLOGS_BLOG_SAVE_REJECTED');
		$mainframe->enqueueMessage( $message, 'infro' );
		$mainframe->redirect( JRoute::_( $redirect, false) );
		$mainframe->close();
	}
	
	function _saveDraft( )
	{
		$mainframe		=& JFactory::getApplication();
		$config 		=& EasyBlogHelper::getConfig();
		$message		= '';
		$type			= 'message';
		$my				=& JFactory::getUser();
		$acl			= EasyBlogACLHelper::getRuleSet();
		$joomlaVersion	= EasyBlogHelper::getJoomlaVersion();


        $redirect   = 'index.php?option=com_easyblog&view=blogs';

		$params		= JRequest::get( 'post' );

		// Try to load this draft to see if it exists
		$draft	= JTable::getInstance( 'Draft' , 'Table' );
		$draft->load( $params[ 'draft_id' ] );

		if( isset( $params[ 'blogid' ] ) && !empty( $params[ 'blogid' ] ) )
		{
			$draft->entry_id	= $params[ 'blogid' ];
			unset( $params[ 'blogid' ] );
		}

		$content		= JRequest::getVar('write_content', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$intro			= JRequest::getVar('intro', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$params[ 'content' ]	= $content;
		$params[ 'intro' ] 		= $intro;
		
		$authorId				= JRequest::getVar( 'authorId' );
		$params[ 'created_by' ] = $authorId;

		$trackbacks	= JRequest::getVar( 'trackback' , '' , 'POST' );
		$params[ 'trackback' ]  = $trackbacks;
		$draft->bind( $params );

		if( isset( $params[ 'draft_id'] ) && !empty( $params[ 'draft_id' ] ) )
		{
			$draft->id	= $params[ 'draft_id' ];
		}

		$draft->pending_approval    = '1';

		// @task: Cleanup any messages
		$postreject	= JTable::getInstance( 'PostReject' , 'Table' );
		$postreject->clear( $draft->id );

		if( $draft->store() )
		{
			$author =& JTable::getInstance( 'Profile', 'Table' );
			$author->load( $draft->created_by );

			$emailData['blogTitle']		= $draft->title;
			$emailData['blogAuthor']	= $author->getName();
			$notify						=& EasyBlogHelper::getNotification();

			// send notification to admins
			$notify->notifyAdmins( JText::_('COM_EASYBLOG_EMAIL_TITLE_NEW_BLOG_PENDING_REVIEW') , '' , 'email.blog.pending.review.php' , $emailData );
		
		    $message    = JText::_('COM_EASYBLOG_BLOGS_BLOG_SAVE_BUT_PENDING_FOR_APPROVAL');
			$mainframe->enqueueMessage( $message, 'infro' );
			$mainframe->redirect( JRoute::_( $redirect, false) );

		}
		else
		{
		    $mainframe->enqueueMessage( $draft->getError(), 'error' );
			$mainframe->redirect( JRoute::_( $redirect , false) );
		}
	}
	
	function savePublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$mainframe	=& JFactory::getApplication();		
		$config 	=& EasyBlogHelper::getConfig();
		$message	= '';
		$type		= 'message';
		
		$authorId	= JRequest::getVar( 'authorId' );
		$user		=& JFactory::getUser($authorId);
		
		$joomlaVersion = EasyBlogHelper::getJoomlaVersion();
		
		if( !empty($user->id) && JRequest::getMethod() == 'POST' )
		{
		    $acl		= EasyBlogACLHelper::getRuleSet();
			if(empty($acl->rules->add_entry))
			{
			    $message = JText::_('COM_EASYBLOG_BLOGS_BLOG_NO_PERMISSION_TO_CREATE_BLOG');
				$mainframe->enqueueMessage( $message, 'error' );
				$this->setRedirect( JRoute::_('index.php?option=com_easyblog&view=blogs', false) );
			}
		
			JPluginHelper::importPlugin( 'easyblog' );
			$dispatcher =& JDispatcher::getInstance();
			$blog		=& JTable::getInstance( 'blog', 'Table' );
			$post		= JRequest::get( 'post' );
			
			$id			= JRequest::getInt( 'blogid' );
			$blog->load( $id );
			$isNew		= ( $blog->id == 0 ) ? true : false;
			
			$draftId		= JRequest::getInt( 'draft_id' );
			$under_approval	= JRequest::getInt( 'under_approval' );
			
			
			//override the isnew variable
			if(! $isNew)
			{
			    // if this is blog edit, then we should see the column isnew to determine
			    // whether we should send any notification
			    $isNew  = $blog->isnew;
			}

			$txOffset		= EasyBlogDateHelper::getOffSet();
			
			// we do not proccess this on draft
			if ( $post['published'] != POST_ID_DRAFT )
			{
				// we check the publishing date here
				// if user set the future date then we will automatically change
				// the status to Schedule
				$today   		=& JFactory::getDate();
				
				if($post['published'] == POST_ID_PUBLISHED)
				{
				    $publishing     = JFactory::getDate( $post[ 'publish_up' ], $txOffset );
					if ( $publishing->toUnix() > $today->toUnix() )
					{
						$post['published'] = POST_ID_SCHEDULED;
					}

				}//end if
			}

			if ( empty($post['publish_down']) ) {
				$post['publish_down'] = '0000-00-00 00:00:00';
			}
			
			// set author
			$post['created_by']	= $authorId;
			
			// @task: Password encryption for blog entry
			if( isset($post['blogpassword']) && !empty($post['blogpassword']) && ($isNew || !EasyBlogHelper::isFeatured( 'post' , $blog->id )) )
			{
				$post['blogpassword'] = $post['blogpassword'];
			}
			else
			{
				$post['blogpassword']='';
			}

			$blog->bind( $post , true );
			
			if(empty($blog->title))
			{
			    $mainframe->enqueueMessage( JText::_( 'COM_EASYBLOG_BLOGS_BLOG_SAVE_EMPTY_TITLE'), 'error' );
				$mainframe->redirect( 'index.php?option=com_easyblog&c=blogs&task=edit&blogid=' . $id );
			}
			
			
			if(empty($blog->content) && empty($blog->intro))
			{
			    $mainframe->enqueueMessage( JText::_( 'COM_EASYBLOG_BLOGS_BLOG_SAVE_EMPTY_CONTENT_ERROR'), 'error' );
				$mainframe->redirect( 'index.php?option=com_easyblog&c=blogs&task=edit&blogid=' . $id );
			}
			
			/**
			 * check here if user do not have the 'Publish Entry' acl, send this post for pending approval.
			 */
			 
			if( empty( $draftId ) && empty($acl->rules->publish_entry) )
			{
				$this->_saveDraft();
				return;
			}
			
			//check if user have permission to enable privacy.
			$aclBlogPrivacy = $acl->rules->enable_privacy;
			$blog->private = empty($aclBlogPrivacy)? '0' : $blog->private;
			
			//check if user have permission to contribute the blog post to eblog frontpage
			$blog->frontpage 	= (empty($acl->rules->contribute_frontpage)) ? '0' : $blog->frontpage;
			$blog->isnew 		= ($isNew && ($blog->published != 1)) ? '1' : '0';
			
			//now we need to check the blog contribution
			$blogContribution   = array();
			$issitewide 		= '1';

			if(isset( $post['blog_contribute']))
			{
			    $myContribution = $post['blog_contribute'];
			    //reset the value
			    $issitewide = '0';

			    if($myContribution == '0')
			    {
			        $issitewide = '1';
			    }
			    else
			    {
 			        $blogContribution[] = $myContribution;
			    }
			}
			$blog->issitewide   = $issitewide;

			//onBeforeEblogSave trigger start
			$blog->introtext	= "";
			$blog->text			= "";
			$dispatcher->trigger('onBeforeEasyBlogSave', array(&$blog, $isNew));
			//onBeforeEblogSave trigger end
			
			//onBeforeContentSave trigger start
			$blog->introtext	= $blog->intro;
			$blog->text			= $blog->content;
			if($joomlaVersion >= '1.6'){
				$dispatcher->trigger('onContentBeforeSave', array('easyblog.blog', &$blog, $isNew));
			} else {
				$dispatcher->trigger('onBeforeContentSave', array(&$blog, $isNew));
			}
			$blog->intro		= $blog->introtext;
			$blog->content		= $blog->text;
			unset($blog->introtext);
			unset($blog->text);
			//onBeforeContentSave trigger end
			
			if (!$blog->store()) 
			{
	        	JError::raiseError(500, $blog->getError() );
			}
			
			//onAfterEblogSave trigger start
			$blog->introtext	= "";
			$blog->text			= "";
			$dispatcher->trigger('onAfterEasyBlogSave', array(&$blog, $isNew));
			//onAfterEblogSave trigger end
			
			//onAfterContentSave trigger start
			$blog->introtext	= $blog->intro;
			$blog->text			= $blog->content;
			if($joomlaVersion >= '1.6'){
				$dispatcher->trigger('onContentAfterSave', array('easyblog.blog', &$blog, $isNew));
			} else {
				$dispatcher->trigger('onAfterContentSave', array(&$blog, $isNew));
			}	
			$blog->excerpt		= $blog->introtext;
			$blog->content		= $blog->text;
			unset($blog->introtext);
			unset($blog->text);
			//onAfterContentSave trigger end
			
			
			// this variable will be use when sending notification. This is bcos
			// the currently login use might be the admin and editing other blogger post.
            //$blog->created_by  = $authorId;
            
            //now we update the blog contribution
            $blog->updateBlogContribution($blogContribution);
			
			/**
			 * JomSocial userpoint.
			 */
			if($isNew && $blog->published == POST_ID_PUBLISHED)
			{
				if( ($user->id != 0) && ($authorId == $user->id) && ($config->get('main_jomsocial_userpoint')) )
				{
					$jsUserPoint	= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'userpoints.php';
					if( JFile::exists( $jsUserPoint ) )
					{
						require_once( $jsUserPoint );
						CUserPoints::assignPoint( 'com_easyblog.blog.add' , $authorId );
					}
				}
				
				// AlphaUserPoints
				// since 1.2
				if ( ($user->id != 0) && ($authorId == $user->id) &&  (EasyBlogHelper::isAUPEnabled()) )
				{
					// get blog post URL
					$aupid = AlphaUserPointsHelper::getAnyUserReferreID( $authorId);
					$url = EasyBlogRouter::_('index.php?option=com_easyblog&view=entry&id='.$blog->id);
					AlphaUserPointsHelper::newpoints( 'plgaup_easyblog_add_blog', $aupid, 'easyblog_add_blog_' . $blog->id, JText::sprintf('AUP NEW BLOG CREATED', $url, $blog->title) );
				}
			}
			
			//add jomsocial activities
			if(($blog->published == POST_ID_PUBLISHED) && ($user->id == $authorId) && ($config->get('main_jomsocial_activity')) && (! $blog->ispending))
			{
				EasyBlogHelper::addJomSocialActivityBlog($blog, $isNew);
			}

			$blogId = $blog->id;

			//meta post info
			$metaId		= JRequest::getVar( 'metaid' , '' );
			
			$metapost	= array();
			$metapost['keywords']		= JRequest::getVar('keywords', '');
			$metapost['description']	= JRequest::getVar('description', '');
			$metapost['content_id']		= $blogId;
			$metapost['type']			= META_TYPE_POST;
			
			// save meta tag for post
			$meta		=& JTable::getInstance( 'Meta', 'Table' );
			$meta->load($metaId);
			$meta->bind($metapost);
			$meta->store();
			
			JTable::addIncludePath( EBLOG_TABLES );
			$author		=& JTable::getInstance( 'Profile', 'Table' );
			$author->setUser( $user );
			
			$trackback			= JRequest::getVar( 'trackback' , '' , 'POST' );
			
			// check if user have permission to add trackbacks
			if(! empty($acl->rules->add_trackback))
			{
				if ( !empty( $trackback ) ) {
					
					$trackbacks	= explode( "\n" , JRequest::getVar( 'trackback' , '' , 'POST' ));

					for ( $x = 0; $x < count($trackbacks); $x++ )
					{
						$tbl =& JTable::getInstance( 'TrackbackSent' , 'Table' );
						
						// check if the URL has been added to our record
						$exists	= $tbl->load( $trackbacks[$x] , true , $blogId );
						
						// if not exists, we need to store them
						if( !$exists )
						{
							$tbl =& JTable::getInstance( 'TrackbackSent' , 'Table' );
							
							$tbl->post_id	= $blogId;
							$tbl->url		= $trackbacks[$x];
							$tbl->sent		= 0;
							$tbl->store();
						}	
					}
				}

				
				// only process this part when publish blog
				if ( $blog->published == POST_ID_PUBLISHED ) {
				
					// now load trackback model 
					$trackbackModel =& $this->getModel('TrackbackSent');
					
					// get lists of trackback URLs based on blog ID
					$tbacks = $trackbackModel->getSentTrackbacks( $blogId, true );

					require_once( EBLOG_CLASSES . DS . 'trackback.php' );
					require_once( EBLOG_HELPERS . DS . 'router.php' );
					
					// loop each URL, ping if necessary
					foreach( $tbacks as $tback )
					{
						$tb		= new EasyBlogTrackBack( $author->getName() , $author->getName() , 'UTF-8');
						$text	= empty( $blog->intro ) ? $blog->content : $blog->intro;
						if( $tb->ping( $tback->url , EasyBlogRouter::getRoutedURL( 'index.php?option=com_easyblog&view=entry&id=' . $blog->id , false , true ) , $blog->title , $text ) )
						{
							$tbl =& JTable::getInstance( 'TrackbackSent' , 'Table' );
							
							//@task: Since the trackback was successful, store the trackback into the table.
							
							$tbl->load($tback->id);
							
							$new_trackbacks = array();
							$new_trackbacks['url']		= $tback->url;
							$new_trackbacks['post_id']	= $tback->post_id;
							$new_trackbacks['sent']		= 1;
							
							$tbl->bind($new_trackbacks);
							$tbl->store();
						}	
					}
				}				
			}		
			

			//@task: Save tags
			$postTagModel	=& $this->getModel( 'PostTag' );
			$tags			= JRequest::getVar( 'tags' , '' , 'POST' );
			$rawtags        = JRequest::getVar( 'rawtags' );

			if( !empty( $rawtags ) )
			{
			    if( is_array( $tags ) )
			    {
			        $tags	= array_merge( $tags , explode( ',' , $rawtags ) );
				}
				else
				{
				    $tags   = explode( ',' , $rawtags );
				}
			}

			$date			=& JFactory::getDate();
			//@task: Delete existing associated tags.
			$postTagModel->deletePostTag( $blog->id );
			
			if( !empty( $tags ) )
			{
				$tagModel	=& $this->getModel( 'Tags' );
				
				foreach( $tags as $tag )
				{
					if(!empty($tag))
					{
						$tagTbl	=& JTable::getInstance( 'Tag' , 'Table' );
						
						//@task: Only add tags if it doesn't exist.
						if( !$tagTbl->exists( $tag ) )
						{
							if($acl->rules->create_tag)
							{
								$tagInfo['created_by']	= $user->id;
								$tagInfo['title'] 		= JString::trim($tag);
								$tagInfo['created']		= $date->toMySQL();

								$tagTbl->bind($tagInfo);
								
								$tagTbl->published	= 1;
								$tagTbl->status		= '';
								
								$tagTbl->store();
								
								if( $user->id != 0 && $config->get('main_jomsocial_userpoint') )
								{
									$jsUserPoint	= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'userpoints.php';
									if( JFile::exists( $jsUserPoint ) )
									{
										require_once( $jsUserPoint );
										CUserPoints::assignPoint( 'com_easyblog.tag.add' , $user->id );
									}
								}
								
								if( ($user->id != 0) && EasyBlogHelper::isAUPEnabled() )
								{
								    $aupid = AlphaUserPointsHelper::getAnyUserReferreID( $user->id );
									AlphaUserPointsHelper::newpoints( 'plgaup_easyblog_add_tag', $aupid, 'easyblog_add_tag_' . $tagTbl->id, JText::sprintf('COM_EASYBLOG_AUP_TAG_ADDED', $tagTbl->title) );
								}
								
							}
						}
						else
						{
							$tagTbl->load( $tag , true );
						}
						
						//@task: Store in the post tag
						$postTagModel->add( $tagTbl->id , $blog->id , $date->toMySQL() );
					}
				}
			}
			
			//check if the auto featured enabled.
			if($config->get('main_autofeatured', 0))
			{
			    if(EasyBlogHelper::isFeatured('blogger', $blog->created_by))
			    {
			        if(! EasyBlogHelper::isFeatured('post', $blog->id))
			        	EasyBlogHelper::makeFeatured('post', $blog->id);
			    }
			}
			
			$autopost	= JRequest::getVar( 'socialshare' , '' );
			if( !empty( $autopost ) )
			{
				$allowed	= array( EBLOG_OAUTH_LINKEDIN , EBLOG_OAUTH_FACEBOOK , EBLOG_OAUTH_TWITTER );
	
				foreach( $autopost as $item )
				{
					if( in_array( $item , $allowed ) && $blog->published == POST_ID_PUBLISHED && $config->get( 'integrations_' . $item ) )
					{
						EasyBlogSocialShareHelper::share( $blog , constant( 'EBLOG_OAUTH_' . JString::strtoupper( $item ) ) );
					}
				}
			}
			
			if($isNew && ($blog->published == POST_ID_PUBLISHED))
			{
				require_once( EBLOG_HELPERS . DS . 'subscription.php' );
				
				//send notification to admin.
				$authorUser =& JFactory::getUser($authorId);
			    $blogger 	=& JTable::getInstance( 'Profile', 'Table' );
			    $blogger->setUser($authorUser);

				$emailData['blogTitle']		= $blog->title;
				$emailData['blogAuthor']	= $blogger->getName();
				$emailData['blogLink']		= EasyBlogRouter::getRoutedURL('index.php?option=com_easyblog&view=entry&id=' . $blog->id, false, true);
				$emailData['blogContent']	= strip_tags($blog->intro . $blog->content, '<p><img><a><div><span><table><tr><td>');

				$notify	=& EasyBlogHelper::getNotification();

				// send notification to admins
				if($config->get('notification_blogadmin'))
				{
					$notify->send('', 'admin', JText::_('COM_EASYBLOG_EMAIL_TITLE_NEW_BLOG_ADDED'), '', 'email.blog.new.php', $emailData);
				}

				if( $config->get( 'notification_allmembers' ) )
				{
                    EasyBlogSubscription::blogSendToAll( $blog, $emailData );
				}
				else
				{
 				    // process all subscroptions on new blog post.
                    EasyBlogSubscription::processNewBlogNotification( $blog, $emailData, $blogContribution );
 				}
				
				if($config->get('main_pingomatic', 0))
				{
					//if pingomatic enabled...send the pings.
					require_once( EBLOG_CLASSES . DS . 'pingomatic.php' );

					$siteURL    = EasyBlogRouter::getRoutedURL('index.php?option=com_easyblog&view=entry&id=' . $blog->id, true, true);
					$siteTitle  = htmlspecialchars($blog->title);

					$pingo  	= new EasyBlogPingomatic();
					$pingoResp  = $pingo->ping($siteTitle, $siteURL);

					if($pingoResp['status'] == 'ko')
					{
						$mainframe->enqueueMessage( JText::_( 'COM_EASYBLOG_BLOGGERS_EDIT_PING_TO_PINGOMATIC_FAILED' ) . ' : ' . $pingoResp['msg'] , 'error');
					}
				}
				
				//now we have to update the isnew to false
                $blog->isnew    = '0';
                $blog->store();
			}
			
			if( !empty($draftId) )
			{
				// @task: Cleanup any messages
				$postreject	= JTable::getInstance( 'PostReject' , 'Table' );
				$postreject->clear( $draftId );
			
			    $draft =& JTable::getInstance( 'Draft' , 'Table' );
			    $draft->load( $draftId );
			    $draft->delete();
			}

			//not ready.
            $mainframe->enqueueMessage( JText::_( 'COM_EASYBLOG_BLOGS_BLOG_SAVE_POST_SAVED') );
			$this->setRedirect( JRoute::_('index.php?option=com_easyblog&view=blogs', false) );
			//$mainframe->redirect( 'index.php?option=com_easyblog&view=blogs' , $message );
		}
		else
		{
			$this->setRedirect( JRoute::_('index.php', false), JText::_('COM_EASYBLOG_BLOGGERS_EDIT_YOU_ARE_NOT_LOGGED_IN_ERROR'), 'error' );
		}	
	}

	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_easyblog&view=blogs' );
		
		return;
	}
	
	function addNew()
	{
		$this->setRedirect( 'index.php?option=com_easyblog&view=blog' );
		
		return;
	}
	
	function edit()
	{
		JRequest::setVar( 'view', 'blog' );
		JRequest::setVar( 'blogid' , JRequest::getVar( 'blogid' , '' , 'REQUEST' ) );
		JRequest::setVar( 'draft_id' , JRequest::getVar( 'draft_id' , '' , 'REQUEST' ) );
		JRequest::setVar( 'approval' , JRequest::getVar( 'approval' , '' , 'REQUEST' ) );
		
		parent::display();
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
	    $config =& EasyBlogHelper::getConfig();
		$blogs	= JRequest::getVar( 'cid' , '' , 'POST' );
		
		$message	= '';
		$type		= 'message';
		
		JPluginHelper::importPlugin( 'easyblog' );
		$dispatcher =& JDispatcher::getInstance();
		
		if( empty( $blogs ) )
		{
			$message	= JText::_('Invalid blog id');
			$type		= 'error';
		}
		else
		{
			$blogTbl		=& JTable::getInstance( 'Blog' , 'Table' );
			foreach( $blogs as $blog )
			{
				$blogTbl->load( $blog );
				
				if( !$blogTbl->delete() )
				{
					$message	= JText::_( 'Error removing Blog.' );
					$type		= 'error';
					$this->setRedirect( 'index.php?option=com_easyblog&view=blogs' , $message , $type );
					return;
				}
				else
				{
					$dispatcher->trigger('onAfterEasyBlogDelete', array(&$blogTbl ));
					
				    $blogTbl->deleteBlogTags();
				    $blogTbl->deleteMetas();
				    $blogTbl->deleteComments();
				    
					if( $blogTbl->created_by != 0 && $config->get('main_jomsocial_userpoint') )
					{
						$jsUserPoint	= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'userpoints.php';
						if( JFile::exists( $jsUserPoint ) )
						{
							require_once( $jsUserPoint );
							CUserPoints::assignPoint( 'com_easyblog.blog.remove' , $blogTbl->created_by );
						}
					}
					
					// AlphaUserPoints
					// since 1.2
					if ( EasyBlogHelper::isAUPEnabled() )
					{
						$aupid = AlphaUserPointsHelper::getAnyUserReferreID( $blogTbl->created_by );
						AlphaUserPointsHelper::newpoints( 'plgaup_easyblog_delete_blog', $aupid, '', JText::sprintf('AUP BLOG DELETED', $blogTbl->title) );
					}
				}
			}
			
			$message	= JText::_('Blog deleted');
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=blogs' , $message , $type );
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
			$message	= JText::_('Invalid blog id');
			$type		= 'error';
		}
		else
		{
			$model		=& $this->getModel( 'Blogs' );
			
			if( $model->publish( $tags , 1 ) )
			{
				$message	= JText::_('Blog(s) published');
			}
			else
			{
				$message	= JText::_('Error publishing blog');
				$type		= 'error';
			}
			
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=blogs' , $message , $type );
	}

	function unpublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$blogs	= JRequest::getVar( 'cid' , array(0) , 'POST' );
		
		$message	= '';
		$type		= 'message';
		
		if( count( $blogs ) <= 0 )
		{
			$message	= JText::_('Invalid blog id');
			$type		= 'error';
		}
		else
		{
			$model		=& $this->getModel( 'Blogs' );
			
			if( $model->publish( $blogs , 0 ) )
			{
				$message	= JText::_('Blog(s) unpublished');
			}
			else
			{
				$message	= JText::_('Error unpublishing blog');
				$type		= 'error';
			}
			
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=blogs' , $message , $type );
	}
}