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
require_once( EBLOG_HELPERS . DS . 'helper.php' );
require_once( EBLOG_HELPERS . DS . 'string.php' );
//require_once( EBLOG_HELPERS . DS . 'date.php' );

class EasyBlogViewMigrators extends JView
{	
	var $err				= null;

	function migrateArticle($params)
	{
	
		$post	= EasyBlogStringHelper::ejaxPostToArray($params);
		
		if(isset($post['com_type']))
		{
		
			$migrateStat    = new stdClass();
			$migrateStat->blog  	= 0;
			$migrateStat->category	= 0;
			$migrateStat->comments	= 0;
			$migrateStat->images	= 0;
			$migrateStat->user      = array();

			$jSession =& JFactory::getSession();
			$jSession->set('EBLOG_MIGRATOR_JOOMLA_STAT', $migrateStat, 'EASYBLOG');

			$com_type   = $post['com_type'];
			
			switch($com_type)
			{
			    case 'com_blog':
			    
					$migrateComment	= isset($post['smartblog_comment']) ? $post['smartblog_comment'] : '0';
					$migrateImage	= isset($post['smartblog_image']) ? $post['smartblog_image'] : '0';
					$imagePath		= isset($post['smartblog_imagepath']) ? $post['smartblog_imagepath'] : '';
					
					$this->_processSmartBlog($migrateComment, $migrateImage, $imagePath);
			    
			        break;
			    case 'com_content':
			    
					$authorId	= isset($post['authorId']) ? $post['authorId'] : '0';
					$stateId	= isset($post['stateId']) ? $post['stateId'] : '*';
					$catId		= isset($post['catId']) ? $post['catId'] : '0';
					$sectionId	= isset($post['sectionId']) ? $post['sectionId'] : '-1';
					$start		= 1;
					$myblogSection   = isset($post['$myblogSection']) ? $post['$myblogSection'] : '';
					
					$this->_process($authorId, $stateId, $catId, $sectionId, $myblogSection);
			    
			        break;
			    case 'com_lyftenbloggie':
			    	//migrate lyftenbloggie tags
			    	$migrateComment	= isset($post['lyften_comment']) ? $post['lyften_comment'] : '0';
			    	
					$this->_migrateLyftenTags();
			        $this->_processLyftenBloggie( $migrateComment );
			        break;
			    case 'com_myblog':
			    
		        	require_once(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_myblog' . DS . 'config.myblog.php');
		        	$myblogConfig	= new MYBLOG_Config();
		        	$myBlogSection	= $myblogConfig->get('postSection');
			    
			        $this->_processMyBlog( $myBlogSection );
			        break;
// 			    case 'com_wordpress':
//
// 					$wpBlogId	= isset($post['wpBlogId']) ? $post['wpBlogId'] : '-1';
//
// 			        $this->_processWordPress( $wpBlogId );
// 			        break;
			    default:
			        break;
			
			}

		}
										
	}
	
	function _processWordPress( $wpBlogId )
	{
	    $db			=& JFactory::getDBO();
	    $jSession 	=& JFactory::getSession();
		$ejax		= new EJax();

		$migrateStat	= $jSession->get('EBLOG_MIGRATOR_JOOMLA_STAT', '', 'EASYBLOG');
		if(empty($migrateStat))
		{
			$migrateStat    		= new stdClass();
			$migrateStat->blog  	= 0;
			$migrateStat->category	= 0;
			$migrateStat->user      = array();
		}

		$wpTableNamePrex    = ($wpBlogId == '1') ? '' : $wpBlogId . '_';
		$wpComponentName    = 'com_wordpress' . $wpBlogId;


		$query	= 'SELECT a.`ID` as `id`, a.* FROM `#__wp_' . $wpTableNamePrex . 'posts` AS a';
		$query	.= ' WHERE NOT EXISTS (';
		$query	.= ' SELECT content_id FROM `#__easyblog_migrate_content` AS b WHERE b.`content_id` = a.`id` and `component` = ' . $db->Quote( $wpComponentName );
		$query	.= ' )';
		$query  .= ' AND `post_type` = ' . $db->Quote( 'post' );
		$query	.= ' ORDER BY a.`id` LIMIT 1';


		$db->setQuery($query);
		$row	= $db->loadObject();

		if(is_null($row))
		{

			//at here, we check whether there are any records processed. if yes,
			//show the statistic.
			$ejax->append('progress-status5', '... finished.');
			$ejax->script("divSrolltoBottomWordPress();");

			//update statistic
			$stat   = '========================================== <br />';
			$stat  .= 'Total blog posts migrated : ' . $migrateStat->blog . '<br />';

			$statUser   = $migrateStat->user;
			if(! empty($statUser))
			{
			    $stat  .= '<br />';
			    $stat  .= 'Total user\'s contribution: ' . count($statUser) . '<br />';

			    foreach($statUser as $eachUser)
			    {
			        $stat   .= 'Total blog post from user \'' . $eachUser->name . '\': ' . $eachUser->blogcount . '<br />';
			    }
			}
			$stat   .= '<br />==========================================';
			$ejax->assign('stat-status5', $stat);

			$ejax->script("ej( '#migrator-submit5' ).html('Finished. Click here to re-run the process again.');");
			$ejax->script("ej( '#migrator-submit5' ).attr('disabled' , '');");
			$ejax->script("ej( '#icon-wait5' ).css( 'display' , 'none' );");

		}
		else
		{
			// step 1 : create categery if not exist in eblog_categories
			// step 2 : create user if not exists in eblog_users - create user through profile jtable load method.

			$date           =& JFactory::getDate();
			$blogObj    	= new stdClass();

			//default
			$blogObj->category_id   = 1;  //assume 1 is the uncategorized id.

			$wpCat  = $this->_getWPTerms( $wpTableNamePrex, $row->id, 'category');
			if(isset($wpCat->title))
			{
			    $eCat   	= $this->_isEblogCategoryExists($wpCat);
				if($eCat === false)
				{
				    $eCat   = $this->_createEblogCategory($wpCat);
				}

				$blogObj->category_id   = $eCat;
			}

			$profile	=& JTable::getInstance( 'Profile', 'Table' );
			$blog		=& JTable::getInstance( 'Blog', 'Table' );

			//load user profile
			$profile->load( $row->post_author );

			//assigning blog data
			$blogObj->created_by	= $profile->id;
			$blogObj->created 		= !empty( $row->post_date ) ? $row->post_date : $date->toMySQL();
			$blogObj->modified		= $date->toMySQL();

			$blogObj->title			= $row->post_title;
			$blogObj->permalink		= ( empty($row->post_name) ) ? EasyBlogHelper::getPermalink($row->post_title) : $row->post_name;

			/* replacing [caption] and [gallery] */

			// Migrate caption
			$pattern2   = '/\[caption.*caption="(.*)"\]/iU';
            $row->post_content  = preg_replace( $pattern2 , '<div class="caption">$1</div>' , $row->post_content );
            $row->post_content	= JString::str_ireplace( '[/caption]' , '<br />' , $row->post_content );

			// Migrate galleries
			$pattern	= '/\[gallery(.*)/i';
			preg_match( $pattern , $row->post_content , $matches );
			if( !empty( $matches ) )
			{
			    $folder   = JPATH_ROOT . DS . 'images' . DS . 'blogs' . DS . $row->id;
			    if( !JFolder::exists( $folder ) )
			    {
			    	JFolder::create( $folder );
				}

			    // Now fetch items
				$query	= 'SELECT a.guid FROM `#__wp_' . $wpTableNamePrex . 'posts` AS a';
				$query  .= ' WHERE `post_type` = ' . $db->Quote( 'attachment' );
				$query  .= ' AND `post_mime_type` LIKE "%image%"';
				$query	.= ' AND `post_parent`=' . $db->Quote( $row->id );

				//http://maephim.se/piccolina/wp-content/uploads/2011/04/Thailand-Apr-2010-080-Large.jpg
				$db->setQuery( $query );
				$cibais	= $db->loadObjectList();

				$images = array();
				foreach( $cibais as $cibai )
				{
				    $image  = $cibai->guid;

					$image  = str_ireplace( 'http://maephim.se/' , '' , $image );
					$image  = str_ireplace( '/' , DS , $image );

					$imageFull  = JPATH_ROOT . DS . $image;
					$parts= explode( DS , $imageFull );
					JFile::copy( $imageFull , JPATH_ROOT . DS . 'images' . DS . 'blogs' . DS  . $row->id . DS .  $parts[ count( $parts ) - 1 ] );
				}


				// Replace content with the proper gallery tag
				//{gallery}4745732{/gallery}
				$row->post_content	= JString::str_ireplace( $matches[0] , '{gallery}' . $row->id . '{/gallery}' , $row->post_content );
			}

			/* end replacing [caption] and [gallery] */

			$blogObj->intro			= $row->post_excerpt;
			$blogObj->content		= $row->post_content;

			//translating the article state into easyblog publish status.
			$blogState  	= '0';
			$isPrivate		= '0';
			if( $row->post_status == 'private' )
			{
                $isPrivate  = '1';
                $blogState  = '1';
			}
			else if( $row->post_status == 'publish' )
			{
                $isPrivate  = '0';
                $blogState  = '1';
			}

			$blogObj->blogpassword  = $row->post_password;
			$blogObj->private       = $isPrivate;
			$blogObj->published		= $blogState;
			$blogObj->publish_up 	= !empty( $row->post_date )? $row->post_date : $date->toMySQL();
			$blogObj->publish_down	= '0000-00-00 00:00:00';

			$blogObj->ordering		= 0;
			$blogObj->hits			= 0;
			$blogObj->frontpage     = 1;
			$blogObj->allowcomment  = ($row->comment_status == 'open') ? 1 : 0;

			$blog->bind($blogObj);
			$blog->store();

			// add tags.
			$wpPostTag  = $this->_getWPTerms( $wpTableNamePrex, $row->id, 'post_tag');
			if( count($wpPostTag) > 0)
			{

			    foreach($wpPostTag as $item)
			    {
				    $now    =& JFactory::getDate();
					$tag	=& JTable::getInstance( 'Tag', 'Table' );

					if( $tag->exists( $item->title ) )
					{
					    $tag->load( $item->title, true);
					}
					else
					{
					    $tagArr = array();
					    $tagArr['created_by']  	= $this->_getSAUserId();
					    $tagArr['title']  		= $item->title;
					    $tagArr['alias']  		= $item->alias;
					    $tagArr['published']  	= '1';
					    $tagArr['created']     	= $now->toMySQL();

                        $tag->bind($tagArr);
					    $tag->store();
					}

					$postTag	=& JTable::getInstance( 'PostTag', 'Table' );
					$postTag->tag_id	= $tag->id;
					$postTag->post_id	= $blog->id;
					$postTag->created	= $now->toMySQL();
					$postTag->store();

			    }
			}


			// add comments
			$query	= 'SELECT * FROM `#__wp_' . $wpTableNamePrex . 'comments` AS a';
			$query	.= ' where `comment_post_ID` = ' . $db->Quote( $row->id );
			$query  .= ' and `comment_approved` = ' . $db->Quote('1');
			$query  .= ' and `comment_parent` = ' . $db->Quote('0');
			$query  .= ' order by `comment_date` ASC';

			$db->setQuery($query);
			$result = $db->loadObjectList();

			if( count($result) > 0)
			{
			    $next   = array();
			    $next['lft'] = 1;
			    $next['rgt'] = 2;

			    foreach( $result as $item)
			    {
			        $next	= $this->_migrateWPComments($wpTableNamePrex, $row->id, $blog->id, '0', $item, $next);
			    }
			}
		    //end adding comments



			//update session value
			$migrateStat->blog++;
			$statUser   	= $migrateStat->user;
			$statUserObj    = null;
			if(! isset($statUser[$profile->id]))
			{
			    $statUserObj    = new stdClass();
			    $statUserObj->name  		= $profile->nickname;
			    $statUserObj->blogcount		= 0;
			}
			else
			{
			    $statUserObj    = $statUser[$profile->id];
			}
			$statUserObj->blogcount++;
			$statUser[$profile->id] = $statUserObj;
			$migrateStat->user  	= $statUser;


			$jSession->set('EBLOG_MIGRATOR_JOOMLA_STAT', $migrateStat, 'EASYBLOG');


			//log the entry into migrate table.
			$migrator =& JTable::getInstance( 'Migrate', 'Table' );

			$migrator->content_id	= $row->id;
			$migrator->post_id		= $blog->id;
			$migrator->session_id	= $jSession->getToken();
			$migrator->component    = $wpComponentName;
			$migrator->store();

			$ejax->append('progress-status5', 'Migrated WordPress blog post :' . $row->id . ' into EasyBlog with blog id:' . $blog->id . '<br />');
			$ejax->script("ejax.load('migrators','_processWordPress','$wpBlogId');");

		}

		$ejax->send();
	}
	
	function _migrateWPComments($wpTableNamePrex, $postId, $blogId, $parentId, $item, $next)
	{
		$now	=& JFactory::getDate();
		$db		=& JFactory::getDBO();
		$commt	=& JTable::getInstance( 'Comment', 'Table' );

		//we need to rename the esname and esemail back to name and email.
		$post               = array();
		$post['name']		= $item->comment_author;
		$post['email']		= $item->comment_author_email;
		$post['id']     	= $blogId;
		$post['comment']    = $item->comment_content;
		$post['title']      = '';
        $post['url']        = $item->comment_author_url;
        $post['ip']        	= $item->comment_author_IP;
		$commt->bindPost($post);

		$commt->created_by  = $item->user_id;
		$commt->created		= $item->comment_date;
		$commt->modified	= $item->comment_date;
		$commt->published   = 1;
		$commt->parent_id   = $parentId;
		$commt->sent        = 1;

		$latestCmmt	= $this->_getEasyBlogLatestComment($blogId, $parentId);
		if( $parentId != 0)
		{
			$parentCommt	=& JTable::getInstance( 'Comment', 'Table' );
			$parentCommt->load($parentId);

			//adding new child comment
			$next['lft']		= $parentCommt->lft + 1;
			$next['rgt']		= $parentCommt->lft + 2;
			$nodeVal			= $parentCommt->lft;

			if(! empty($latestCmmt))
			{
			 	$next['lft']		= $latestCmmt->rgt + 1;
			 	$next['rgt']		= $latestCmmt->rgt + 2;
			 	$nodeVal			= $latestCmmt->rgt;
			}

			$this->_updateEasyBlogCommentSibling($blogId, $nodeVal);

			$commt->lft	= $next['lft'];
			$commt->rgt	= $next['rgt'];
		}
		else
		{
			//adding new comment
			if(! empty($latestCmmt))
			{
			 	$next['lft']	= $latestCmmt->rgt + 1;
			 	$next['rgt']	= $latestCmmt->rgt + 2;

			 	$this->_updateEasyBlogCommentSibling($blogId, $latestCmmt->rgt);
			}

			$commt->lft	= $next['lft'];
			$commt->rgt	= $next['rgt'];
		}

		$commt->store();

		//check to see if there is any child comments or not.
		$query	= 'SELECT a.* FROM `#__wp_' . $wpTableNamePrex . 'comments` AS a';
		$query	.= ' where `comment_post_ID` = ' . $db->Quote( $postId );
		$query  .= ' and `comment_approved` = ' . $db->Quote('1');
		$query  .= ' and `comment_parent` = ' . $db->Quote ( $item->comment_ID );
		$query  .= ' order by `comment_date` ASC';

		$db->setQuery($query);
		$result = $db->loadObjectList();

		if( count($result) > 0)
		{
		    foreach( $result as $citem)
		    {
		        $next	= $this->_migrateWPComments($wpTableNamePrex, $postId, $blogId, $commt->id, $citem, $next);
		    }
		}

        return $next;
	}

	function _updateEasyBlogCommentSibling($blogId, $nodeValue)
	{
		$db	=& JFactory::getDBO();

		$query	= 'UPDATE `#__easyblog_comment` SET `rgt` = `rgt` + 2';
		$query	.= ' WHERE `rgt` > ' . $db->Quote($nodeValue);
		$query	.= ' AND `post_id` = ' . $db->Quote($blogId);
		$db->setQuery($query);
		$db->query();

		$query	= 'UPDATE `#__easyblog_comment` SET `lft` = `lft` + 2';
		$query	.= ' WHERE `lft` > ' . $db->Quote($nodeValue);
		$query	.= ' AND `post_id` = ' . $db->Quote($blogId);
		$db->setQuery($query);
		$db->query();
	}


	function _getEasyBlogLatestComment($blogId, $parentId = 0)
	{
		$db	=& JFactory::getDBO();

		$query	= 'SELECT `id`, `lft`, `rgt` FROM `#__easyblog_comment`';
		$query	.= ' WHERE `post_id` = ' . $db->Quote($blogId);
		if($parentId != 0)
			$query	.= ' AND `parent_id` = ' . $db->Quote($parentId);
		else
		    $query	.= ' AND `parent_id` = ' . $db->Quote('0');
		$query	.= ' ORDER BY `lft` DESC LIMIT 1';

		$db->setQuery($query);
		$result	= $db->loadObject();

		return $result;
	}

	function _getWPTerms( $wpTBPrex = '', $postId, $type)
	{
	    $db		=& JFactory::getDBO();

		$query   = 'select distinct a.`name` as `title`, a.`slug` as `alias`, 1 as `published` from `#__wp_'.$wpTBPrex.'terms` as a';
		$query  .= '  inner join `#__wp_term_'.$wpTBPrex.'taxonomy` as b on a.`term_id` = b.`term_id`';
		$query  .= '  inner join `#__wp_term_'.$wpTBPrex.'relationships` as c on b.`term_taxonomy_id` = c.`term_taxonomy_id`';
		$query  .= ' where c.`object_id` = ' . $db->Quote($postId);
		$query  .= ' and b.`taxonomy` = ' . $db->Quote($type);

		$db->setQuery($query);

		$result = '';
		if( $type == 'category')
		{
			// always load one category bcos easyblog only support one category.
			$result = $db->loadObject();
		}
		else
		{
		    //tags
		    $result = $db->loadObjectList();
		}
	    return $result;
	}
	
	function _processMyBlog( $myBlogSection )
	{
	    $db			=& JFactory::getDBO();
	    $jSession 	=& JFactory::getSession();
		$ejax		= new EJax();

		$migrateStat	= $jSession->get('EBLOG_MIGRATOR_JOOMLA_STAT', '', 'EASYBLOG');
		if(empty($migrateStat))
		{
			$migrateStat    		= new stdClass();
			$migrateStat->blog  	= 0;
			$migrateStat->category	= 0;
			$migrateStat->user      = array();
		}
		
		$query	= 'SELECT * FROM `#__content` AS a';
		$query	.= ' WHERE NOT EXISTS (';
		$query	.= ' SELECT content_id FROM `#__easyblog_migrate_content` AS b WHERE b.`content_id` = a.`id` and `component` = ' . $db->Quote('com_myblog');
		$query	.= ' )';
		$query	.= ' AND a.`sectionid` = ' . $db->Quote($myBlogSection);
		$query	.= ' ORDER BY a.`id` LIMIT 1';

		$db->setQuery($query);
		$row	= $db->loadObject();
		
		if(is_null($row))
		{

			//at here, we check whether there are any records processed. if yes,
			//show the statistic.
			$ejax->append('progress-status4', '... finished.');
			$ejax->script("divSrolltoBottomMyblog();");

			//update statistic
			$stat   = '========================================== <br />';
			$stat  .= 'Total blog posts migrated : ' . $migrateStat->blog . '<br />';

			$statUser   = $migrateStat->user;
			if(! empty($statUser))
			{
			    $stat  .= '<br />';
			    $stat  .= 'Total user\'s contribution: ' . count($statUser) . '<br />';

			    foreach($statUser as $eachUser)
			    {
			        $stat   .= 'Total blog post from user \'' . $eachUser->name . '\': ' . $eachUser->blogcount . '<br />';
			    }
			}
			$stat   .= '<br />==========================================';
			$ejax->assign('stat-status4', $stat);

			$ejax->script("ej( '#migrator-submit4' ).html('Finished. Click here to re-run the process again.');");
			$ejax->script("ej( '#migrator-submit4' ).attr('disabled' , '');");
			$ejax->script("ej( '#icon-wait4' ).css( 'display' , 'none' );");

		}
		else
		{
			// here we should process the migration

			// step 1 : create categery if not exist in eblog_categories
			// step 2 : create user if not exists in eblog_users - create user through profile jtable load method.

			$date           =& JFactory::getDate();
			$blogObj    	= new stdClass();

			//default
			$blogObj->category_id   = 1;  //assume 1 is the uncategorized id.

			if(! empty($row->catid))
			{

			    $joomlaCat  = $this->_getJoomlaCategory($row->catid);

			    $eCat   	= $this->_isEblogCategoryExists($joomlaCat);
				if($eCat === false)
				{
				    $eCat   = $this->_createEblogCategory($joomlaCat);
				}

				$blogObj->category_id   = $eCat;
			}

			$profile	=& JTable::getInstance( 'Profile', 'Table' );
			$blog		=& JTable::getInstance( 'Blog', 'Table' );

			//load user profile
			$profile->load( $row->created_by );

			//assigning blog data
			$blogObj->created_by	= $profile->id;
			$blogObj->created 		= !empty( $row->created ) ? $row->created : $date->toMySQL();
			$blogObj->modified		= $date->toMySQL();

			$blogObj->title			= $row->title;
			$blogObj->permalink		= ( empty($row->alias) ) ? EasyBlogHelper::getPermalink($row->title) : $row->alias;

			if(empty($row->fulltext))
			{
				$blogObj->intro			= '';
				$blogObj->content		= $row->introtext;
			}
			else
			{
				$blogObj->intro			= $row->introtext;
				$blogObj->content		= $row->fulltext;
			}

			//translating the article state into easyblog publish status.
			$blogState  = '';
			if(EasyBlogHelper::getJoomlaVersion() >= '1.6')
			{
			    $blogState  = ($row->state == 2 || $row->state == -2) ? 0 : $row->state;
			}
			else
			{
			    $blogState  = ($row->state == -1) ? 0 : $row->state;
			}

			$blogObj->published		= $blogState;
			$blogObj->publish_up 	= !empty( $row->publish_up )? $row->publish_up : $date->toMySQL();
			$blogObj->publish_down	= !empty( $row->publish_down )? $row->publish_down : $date->toMySQL();

			$blogObj->ordering		= $row->ordering;
			$blogObj->hits			= $row->hits;
			$blogObj->frontpage     = 1;

			$blog->bind($blogObj);
			$blog->store();
			
			//migrate meta description
			$this->_migrateContentMeta($row->metakey, $row->metadesc, $blog->id);
			
			//map myblog tags into EasyBlog tags.
			$query  = 'SELECT a.*, b.`name`, b.`slug` FROM `#__myblog_content_categories` as a INNER JOIN `#__myblog_categories` as b';
			$query  .= ' ON a.`category` = b.`id`';
			$query  .= ' WHERE a.`contentid` = ' . $db->Quote($row->id);
			$db->setQuery($query);

			$myblogTags = $db->loadObjectList();

			if(count($myblogTags) > 0)
			{
			    foreach($myblogTags as $item)
			    {
				    $now    =& JFactory::getDate();
					$tag	=& JTable::getInstance( 'Tag', 'Table' );

					if( $tag->exists( $item->name ) )
					{
					    $tag->load( $item->name, true);
					}
					else
					{
					    $tagArr = array();
					    $tagArr['created_by']  	= $this->_getSAUserId();
					    $tagArr['title']  		= $item->name;
					    $tagArr['alias']  		= $item->slug;
					    $tagArr['published']  	= '1';
					    $tagArr['created']     	= $now->toMySQL();

                        $tag->bind($tagArr);
					    $tag->store();
					}

					$postTag	=& JTable::getInstance( 'PostTag', 'Table' );
					$postTag->tag_id	= $tag->id;
					$postTag->post_id	= $blog->id;
					$postTag->created	= $now->toMySQL();
					$postTag->store();

			    }
			}
			

			//update session value
			$migrateStat->blog++;
			$statUser   	= $migrateStat->user;
			$statUserObj    = null;
			if(! isset($statUser[$profile->id]))
			{
			    $statUserObj    = new stdClass();
			    $statUserObj->name  		= $profile->nickname;
			    $statUserObj->blogcount		= 0;
			}
			else
			{
			    $statUserObj    = $statUser[$profile->id];
			}
			$statUserObj->blogcount++;
			$statUser[$profile->id] = $statUserObj;
			$migrateStat->user  	= $statUser;


			$jSession->set('EBLOG_MIGRATOR_JOOMLA_STAT', $migrateStat, 'EASYBLOG');


			//log the entry into migrate table.
			$migrator =& JTable::getInstance( 'Migrate', 'Table' );

			$migrator->content_id	= $row->id;
			$migrator->post_id		= $blog->id;
			$migrator->session_id	= $jSession->getToken();
			$migrator->component    = 'com_myblog';
			$migrator->store();
			
			$ejax->append('progress-status4', 'Migrated MyBlog blog post :' . $row->id . ' into EasyBlog with blog id:' . $blog->id . '<br />');
			$ejax->script("ejax.load('migrators','_processMyBlog','$myBlogSection');");
		
		}
		
		$ejax->send();
		
	}
	
	function _processLyftenBloggie( $migrateComment )
	{
	    $db			=& JFactory::getDBO();
	    $jSession 	=& JFactory::getSession();
		$ejax		= new EJax();

		$migrateStat	= $jSession->get('EBLOG_MIGRATOR_JOOMLA_STAT', '', 'EASYBLOG');
		if(empty($migrateStat))
		{
			$migrateStat    		= new stdClass();
			$migrateStat->blog  	= 0;
			$migrateStat->category	= 0;
			$migrateStat->comments	= 0;
			$migrateStat->images	= 0;
			$migrateStat->user      = array();
		}

		$query	= 'SELECT * FROM `#__bloggies_entries` AS a';
		$query	.= ' WHERE NOT EXISTS (';
		$query	.= ' SELECT content_id FROM `#__easyblog_migrate_content` AS b WHERE b.`content_id` = a.`id` and `component` = ' . $db->Quote('com_lyftenbloggie');
		$query	.= ' )';
		$query	.= ' ORDER BY a.`id` LIMIT 1';

		$db->setQuery($query);
		$row	= $db->loadObject();

		if(is_null($row))
		{
		    // now we migrate the remaining categories
     		$this->_migrateLyftenCategories();

			//at here, we check whether there are any records processed. if yes,
			//show the statistic.
			$ejax->append('progress-status3', '... finished.');
			$ejax->script("divSrolltoBottomLyften();");

			//update statistic
			$stat   = '========================================== <br />';
			$stat  .= 'Total blog posts migrated : ' . $migrateStat->blog . '<br />';
			$stat  .= 'Total comments migrated : ' . $migrateStat->comments . '<br />';
			//$stat  .= 'Total images migrated : ' . $migrateStat->images . '<br />';

			$statUser   = $migrateStat->user;
			if(! empty($statUser))
			{
			    $stat  .= '<br />';
			    $stat  .= 'Total user\'s contribution: ' . count($statUser) . '<br />';

			    foreach($statUser as $eachUser)
			    {
			        $stat   .= 'Total blog post from user \'' . $eachUser->name . '\': ' . $eachUser->blogcount . '<br />';
			    }
			}
			$stat   .= '<br />==========================================';
			$ejax->assign('stat-status3', $stat);

			$ejax->script("ej( '#migrator-submit3' ).html('Finished. Click here to re-run the process again.');");
			$ejax->script("ej( '#migrator-submit3' ).attr('disabled' , '');");
			$ejax->script("ej( '#icon-wait3' ).css( 'display' , 'none' );");

		}
		else
		{
			// here we should process the migration
			// step 1 : create user if not exists in eblog_users - create user through profile jtable load method.
			// step 2: create categories / tags if needed.
			// step 3: migrate comments if needed.

			$date           =& JFactory::getDate();
			$blogObj    	= new stdClass();

			//default
			$blogObj->category_id   = 1;  //assume 1 is the uncategorized id.

			if(! empty($row->catid))
			{

			    $joomlaCat  = $this->_getLyftenCategory($row->catid);

			    $eCat   	= $this->_isEblogCategoryExists($joomlaCat);
				if($eCat === false)
				{
				    $eCat   = $this->_createEblogCategory($joomlaCat);
				}

				$blogObj->category_id   = $eCat;
			}

			$profile	=& JTable::getInstance( 'Profile', 'Table' );
			$blog		=& JTable::getInstance( 'Blog', 'Table' );

			//load user profile
			$profile->load( $row->created_by );

			//assigning blog data
			$blogObj->created_by	= $profile->id;
			$blogObj->created 		= !empty( $row->created ) ? $row->created : $date->toMySQL();
			$blogObj->modified		= !empty( $row->modified ) ? $row->modified : $date->toMySQL();

			$blogObj->title			= $row->title;
			$blogObj->permalink		= EasyBlogHelper::getPermalink( $row->title );

			if(empty($row->fulltext))
			{
				$blogObj->intro			= '';
				$blogObj->content		= $row->introtext;
			}
			else
			{
				$blogObj->intro			= $row->introtext;
				$blogObj->content		= $row->fulltext;
			}


			$blogObj->published		= ($row->state == '1') ? '1' : '0'; // set to unpublish for now.
			$blogObj->publish_up 	= !empty( $row->created ) ? $row->created : $date->toMySQL();
			$blogObj->publish_down	= '0000-00-00 00:00:00';

			$blogObj->hits			= $row->hits;
			$blogObj->frontpage     = 1;
			$blogObj->allowcomment  = 1;
			$blogObj->subscription  = 1;

			$blog->bind($blogObj);
			$blog->store();

			//add meta description
			$this->_migrateContentMeta($row->metakey, $row->metadesc, $blog->id);
			
			
			//step 2: tags
			$query  = 'insert into `#__easyblog_post_tag` (`tag_id`, `post_id`, `created`)';
			$query  .= ' select a.`id`, ' . $db->Quote($blog->id) . ', ' . $db->Quote($date->toMySQL());
			$query  .= ' from `#__easyblog_tag` as a inner join `#__bloggies_tags` as b';
			$query  .= ' on a.`title` = b.`name`';
			$query  .= ' inner join `#__bloggies_relations` as c on b.`id` = c.`tag`';
			$query  .= ' where c.`entry` = ' . $db->Quote($row->id);
			
			$db->setQuery($query);
			$db->query();
			

			// migrate Jcomments from lyftenbloggie into EasyBlog
			// $this->_migrateJCommentIntoEasyBlog($row->id, $blog->id, 'com_lyftenbloggie');
			// step 3
			if($migrateComment)
			{

			    //required frontend model file.
			    require_once (JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'models'.DS.'comment.php');
				$model	= new EasyBlogModelComment();

				$queryComment  = 'SELECT * FROM `#__bloggies_comments` WHERE `entry_id` = ' . $db->Quote($row->id);
				$queryComment  .= ' ORDER BY `id`';
				$db->setQuery($queryComment);
				$resultComment  = $db->loadObjectList();


				if(count($resultComment) > 0)
				{
				
					$lft    = 1;
					$rgt    = 2;
				
				    foreach($resultComment as $itemComment)
				    {
	    				$now	=& JFactory::getDate();
						$commt	=& JTable::getInstance( 'Comment', 'Table' );


						$commt->post_id      = $blog->id;
						$commt->comment      = $itemComment->content;
						$commt->title        = '';

						$commt->name         = $itemComment->author;
						$commt->email        = $itemComment->author_email;
						$commt->url          = $itemComment->author_url;
						$commt->created_by   = $itemComment->user_id;
						$commt->created      = $itemComment->date;
						$commt->published    = ($itemComment->state == '1') ? '1' : '0';

						$commt->lft          = $lft;
						$commt->rgt          = $rgt;

						$commt->store();

						//update state
						$migrateStat->comments++;

					    // next set of siblings
					    $lft    = $rgt + 1;
					    $rgt    = $lft + 1;

				    }//end foreach

				}//end if count(comment)

			}


			//update session value
			$migrateStat->blog++;
			$statUser   	= $migrateStat->user;
			$statUserObj    = null;
			if(! isset($statUser[$profile->id]))
			{
			    $statUserObj    = new stdClass();
			    $statUserObj->name  		= $profile->nickname;
			    $statUserObj->blogcount		= 0;
			}
			else
			{
			    $statUserObj    = $statUser[$profile->id];
			}
			$statUserObj->blogcount++;
			$statUser[$profile->id] = $statUserObj;
			$migrateStat->user  	= $statUser;


			$jSession->set('EBLOG_MIGRATOR_JOOMLA_STAT', $migrateStat, 'EASYBLOG');


			//log the entry into migrate table.
			$migrator =& JTable::getInstance( 'Migrate', 'Table' );

			$migrator->content_id	= $row->id;
			$migrator->post_id		= $blog->id;
			$migrator->session_id	= $jSession->getToken();
			$migrator->component    = 'com_lyftenbloggie';
			$migrator->store();

			$ejax->append('progress-status3', 'Migrated LyftenBloggie blog post :' . $row->id . ' into EasyBlog with blog id:' . $blog->id . '<br />');
			$ejax->script("ejax.load('migrators','_processLyftenBloggie', '$migrateComment');");

		}//end if else isnull

		$ejax->send();
	}
	
	
	function _processSmartBlog($migrateComment, $migrateImage, $imagePath)
	{
	
		$db			=& JFactory::getDBO();
		$jSession 	=& JFactory::getSession();
		$ejax		= new EJax();
		
		//check if com_blog installed.
		if(! JFile::exists(JPATH_ROOT . DS . 'components' . DS . 'com_blog' . DS . 'blog.php'))
		{
		    $ejax->append('progress-status2', 'Component SmartBlog not found. Action aborted!');
			$ejax->script("ej( '#migrator-submit2' ).html('Aborted.');");
			$ejax->script("ej( '#migrator-submit2' ).attr('disabled' , '');");
			$ejax->script("ej( '#icon-wait2' ).css( 'display' , 'none' );");
			$ejax->send();
			exit;
		}

		$migrateStat	= $jSession->get('EBLOG_MIGRATOR_JOOMLA_STAT', '', 'EASYBLOG');
		if(empty($migrateStat))
		{
			$migrateStat    		= new stdClass();
			$migrateStat->blog  	= 0;
			$migrateStat->category	= 0;
			$migrateStat->comments	= 0;
			$migrateStat->images	= 0;
			$migrateStat->user      = array();
		}
		
		$query	= 'SELECT * FROM `#__blog_postings` AS a';
		$query	.= ' WHERE NOT EXISTS (';
		$query	.= ' SELECT content_id FROM `#__easyblog_migrate_content` AS b WHERE b.`content_id` = a.`id` and `component` = ' . $db->Quote('com_blog');
		$query	.= ' )';
		$query	.= ' ORDER BY a.`id` LIMIT 1';

		$db->setQuery($query);
		$row	= $db->loadObject();

		if(is_null($row))
		{
			//at here, we check whether there are any records processed. if yes,
			//show the statistic.
			$ejax->append('progress-status2', '... finished.');
			$ejax->script("divSrolltoBottomSmartBlog();");

			//update statistic
			$stat   = '========================================== <br />';
			$stat  .= 'Total blog posts migrated : ' . $migrateStat->blog . '<br />';
			$stat  .= 'Total comments migrated : ' . $migrateStat->comments . '<br />';
			$stat  .= 'Total images migrated : ' . $migrateStat->images . '<br />';

			$statUser   = $migrateStat->user;
			if(! empty($statUser))
			{
			    $stat  .= '<br />';
			    $stat  .= 'Total user\'s contribution: ' . count($statUser) . '<br />';

			    foreach($statUser as $eachUser)
			    {
			        $stat   .= 'Total blog post from user \'' . $eachUser->name . '\': ' . $eachUser->blogcount . '<br />';
			    }
			}
			$stat   .= '<br />==========================================';
			$ejax->assign('stat-status2', $stat);

			$ejax->script("ej( '#migrator-submit2' ).html('Finished. Click here to re-run the process again.');");
			$ejax->script("ej( '#migrator-submit2' ).attr('disabled' , '');");
			$ejax->script("ej( '#icon-wait2' ).css( 'display' , 'none' );");
		
		}
		else
		{
			// here we should process the migration
			// step 1 : create user if not exists in eblog_users - create user through profile jtable load method.
			// step 2 : migrate image files.
			//      step 2.1: create folder if not exist.
			// step 3: migrate comments if needed.

			$date           =& JFactory::getDate();
			$blogObj    	= new stdClass();

			//default
			$blogObj->category_id   = 1;  //assume 1 is the uncategorized id.

			$profile	=& JTable::getInstance( 'Profile', 'Table' );
			$blog		=& JTable::getInstance( 'Blog', 'Table' );

			//load user profile
			$profile->load( $row->user_id );

			//assigning blog data
			$blogObj->created_by	= $profile->id;
			$blogObj->created 		= !empty( $row->post_date ) ? $row->post_date : $date->toMySQL();
			$blogObj->modified		= !empty( $row->post_update ) ? $row->post_update : $date->toMySQL();

			$blogObj->title			= $row->post_title;
			$blogObj->permalink		= EasyBlogHelper::getPermalink( $row->post_title );


			$blogObj->intro			= '';
			$blogObj->content		= $row->post_desc;


			$blogObj->published		= $row->published;
			$blogObj->publish_up 	= !empty( $row->post_date ) ? $row->post_date : $date->toMySQL();
			$blogObj->publish_down	= '0000-00-00 00:00:00';

			$blogObj->hits			= $row->post_hits;
			$blogObj->frontpage     = 1;

			$blog->bind($blogObj);
			
			//step 2
			$imageMigrated  = false;
			if($migrateImage)
			{
			    $newImagePath   = JPATH_ROOT . DS . 'images';
			    if(! empty($imagePath))
			    {
			        $tmpimagePath	= JString::str_ireplace('/', DS,  $imagePath);
			        $newImagePath   .= DS . $tmpimagePath;
			        $newImagePath   = JFolder::makeSafe($newImagePath);
			    }
			    
			    if(! JFolder::exists($newImagePath))
			    {
			        JFolder::create($newImagePath);
			    }
			    
			    $src	= JPATH_ROOT . DS . 'components' . DS . 'com_blog' . DS . 'Images' . DS . 'blogimages' . DS . 'th'.$row->post_image;
			    $dest	= $newImagePath . DS . $row->post_image;
			    
			    
			    if(JFile::exists($src))
			    {
			        $imageMigrated	= JFile::copy($src, $dest);
			    }
			}
			
			if($imageMigrated)
			{
			    $destSafeURL	= JString::str_ireplace(DS, '/',  $imagePath);
			    $destSafeURL    = 'images/' . $destSafeURL . '/' . $row->post_image;
			
			    $imageContent	= '<p><img style="padding:0px 10px 10px 0px;" align="left" src="' . $destSafeURL. '" border="0" /> </p>';
			    $blog->content  = $imageContent . $blog->content;
			    $migrateStat->images++;
			}
			
			$blog->store();
			
			// step 3
			if($migrateComment)
			{

			    //required frontend model file.
			    require_once (JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'models'.DS.'comment.php');
				$model	= new EasyBlogModelComment();
				
				$queryComment  = 'SELECT * FROM `#__blog_comment` WHERE `post_id` = ' . $db->Quote($row->id);
				$queryComment  .= ' ORDER BY `id`';
				$db->setQuery($queryComment);
				$resultComment  = $db->loadObjectList();
				
				
				if(count($resultComment) > 0)
				{
				    foreach($resultComment as $itemComment)
				    {
						$commentor	=& JTable::getInstance( 'Profile', 'Table' );

						//load user profile
						$commentor->load( $itemComment->user_id );
						
						$user   =& JFactory::getUser($itemComment->user_id );
				    
	    				$now	=& JFactory::getDate();
						$commt	=& JTable::getInstance( 'Comment', 'Table' );
						
						
						$commt->post_id      = $blog->id;
						$commt->comment      = $itemComment->comment_desc;
						$commt->title        = $itemComment->comment_title;
						
						$commt->name         = $user->name;
						$commt->email        = $user->email;
						$commt->url          = $commentor->url;
						$commt->created_by   = $itemComment->user_id;
						$commt->created      = $itemComment->comment_date;
						$commt->published    = $itemComment->published;
						

						//adding new comment
						$latestCmmt	= $model->getLatestComment($blog->id, '0');
						$lft	= 1;
						$rgt	= 2;

						if(! empty($latestCmmt))
						{
						 	$lft	= $latestCmmt->rgt + 1;
						 	$rgt	= $latestCmmt->rgt + 2;

						 	$model->updateCommentSibling($blog->id, $latestCmmt->rgt);
						}
						
						$commt->lft          = $lft;
						$commt->rgt          = $rgt;

						$commt->store();
						
						//update state
						$migrateStat->comments++;
				    
				    }//end foreach
				
				}//end if count(comment)

			}
			
			
			
			//update session value
			$migrateStat->blog++;
			$statUser   	= $migrateStat->user;
			$statUserObj    = null;
			if(! isset($statUser[$profile->id]))
			{
			    $statUserObj    = new stdClass();
			    $statUserObj->name  		= $profile->nickname;
			    $statUserObj->blogcount		= 0;
			}
			else
			{
			    $statUserObj    = $statUser[$profile->id];
			}
			$statUserObj->blogcount++;
			$statUser[$profile->id] = $statUserObj;
			$migrateStat->user  	= $statUser;


			$jSession->set('EBLOG_MIGRATOR_JOOMLA_STAT', $migrateStat, 'EASYBLOG');


			//log the entry into migrate table.
			$migrator =& JTable::getInstance( 'Migrate', 'Table' );

			$migrator->content_id	= $row->id;
			$migrator->post_id		= $blog->id;
			$migrator->session_id	= $jSession->getToken();
			$migrator->component    = 'com_blog';
			$migrator->store();

			$ejax->append('progress-status2', 'Migrated SmartBlog blog post :' . $row->id . ' into EasyBlog with blog id:' . $blog->id . '<br />');
			$ejax->script("ejax.load('migrators','_processSmartBlog','$migrateComment', '$migrateImage', '$imagePath');");
		
		}
		
		$ejax->send();
	
	}
	
	function _process($authorId, $stateId, $catId, $sectionId, $myblogSection)
	{
		$db			=& JFactory::getDBO();
		$jSession 	=& JFactory::getSession();
		$ejax		= new EJax();

		$migrateStat	= $jSession->get('EBLOG_MIGRATOR_JOOMLA_STAT', '', 'EASYBLOG');
		if(empty($migrateStat))
		{
			$migrateStat    		= new stdClass();
			$migrateStat->blog  	= 0;
			$migrateStat->category	= 0;
			$migrateStat->user      = array();
		}
		
		$query	= 'SELECT * FROM `#__content` AS a';
		$query	.= ' WHERE NOT EXISTS (';
		$query	.= ' SELECT content_id FROM `#__easyblog_migrate_content` AS b WHERE b.`content_id` = a.`id` and `component` = ' . $db->Quote('com_content');
		$query	.= ' )';
		if($authorId != '0')
			$query	.= ' AND a.`created_by` = ' . $db->Quote($authorId);
			
		if($stateId != '*')
		{
			switch($stateId)
			{
				case 'P':
					$query	.= ' AND a.`state` = ' . $db->Quote('1');
					break;
				case 'U':
					$query	.= ' AND a.`state` = ' . $db->Quote('0');
					break;
				case 'A':
					$query	.= ' AND a.`state` = ' . $db->Quote('-1');
					break;

				// joomla 1.6 compatibility
				case '1': // publish
					$query	.= ' AND a.`state` = ' . $db->Quote('1');
					break;
				case '0': //unpublish
					$query	.= ' AND a.`state` = ' . $db->Quote('0');
					break;
				case '2': // archive
					$query	.= ' AND a.`state` = ' . $db->Quote('2');
					break;
				case '-2': // trash
					$query	.= ' AND a.`state` = ' . $db->Quote('-2');
					break;
					
				default:
					break;
			}
		}
		if($sectionId != '-1')
			$query	.= ' AND a.`sectionid` = ' . $db->Quote($sectionId);
			
		// we do not want the myblog post process here.
		if($myblogSection != '')
			$query	.= ' AND a.`sectionid` != ' . $db->Quote($myblogSection);
			
		if($catId != '0')
			$query	.= ' AND a.`catid` = ' . $db->Quote($catId);			
		
		$query	.= ' ORDER BY a.`id` LIMIT 1';
		
		$db->setQuery($query);
		$row	= $db->loadObject();
		
		if(is_null($row))
		{
			//at here, we check whether there are any records processed. if yes,
			//show the statistic.
			$ejax->append('progress-status', '... finished.');
			$ejax->script("divSrolltoBottom();");
			
			//update statistic
			$stat   = '========================================== <br />';
			$stat  .= 'Total joomla article migrated : ' . $migrateStat->blog . '<br />';
			$stat  .= 'Total joomla category migrated : ' . $migrateStat->category . '<br />';
			
			$statUser   = $migrateStat->user;
			if(! empty($statUser))
			{
			    $stat  .= '<br />';
			    $stat  .= 'Total user\'s contribution: ' . count($statUser) . '<br />';
			    
			    foreach($statUser as $eachUser)
			    {
			        $stat   .= 'Total articles from user \'' . $eachUser->name . '\': ' . $eachUser->blogcount . '<br />';
			    }
			}
			$stat   .= '<br />==========================================';
			$ejax->assign('stat-status', $stat);
	
			$ejax->script("ej( '#migrator-submit' ).html('Finished. Click here to re-run the process again.');");
			$ejax->script("ej( '#migrator-submit' ).attr('disabled' , '');");
			$ejax->script("ej( '#icon-wait' ).css( 'display' , 'none' );");
		}
		else
		{
			// here we should process the migration
			
			// step 1 : create categery if not exist in eblog_categories
			// step 2 : create user if not exists in eblog_users - create user through profile jtable load method.

			$date           =& JFactory::getDate();
			$blogObj    	= new stdClass();
			
			//default
			$blogObj->category_id   = 1;  //assume 1 is the uncategorized id.

			if(! empty($row->catid))
			{
			
			    $joomlaCat  = $this->_getJoomlaCategory($row->catid);
			    
			    $eCat   	= $this->_isEblogCategoryExists($joomlaCat);
				if($eCat === false)
				{
				    $eCat   = $this->_createEblogCategory($joomlaCat);
				}
				
				$blogObj->category_id   = $eCat;
			}
			
			$profile	=& JTable::getInstance( 'Profile', 'Table' );
			$blog		=& JTable::getInstance( 'Blog', 'Table' );
			
			//load user profile
			$profile->load( $row->created_by );
			
			//assigning blog data
			$blogObj->created_by	= $profile->id;
			$blogObj->created 		= !empty( $row->created ) ? $row->created : $date->toMySQL();
			$blogObj->modified		= $date->toMySQL();
			
			$blogObj->title			= $row->title;
			$blogObj->permalink		= $row->alias;
			
			if(empty($row->fulltext))
			{
				$blogObj->intro			= '';
				$blogObj->content		= $row->introtext;
			}
			else
			{
				$blogObj->intro			= $row->introtext;
				$blogObj->content		= $row->fulltext;
			}
			
			//translating the article state into easyblog publish status.
			$blogState  = '';
			if(EasyBlogHelper::getJoomlaVersion() >= '1.6')
			{
			    $blogState  = ($row->state == 2 || $row->state == -2) ? 0 : $row->state;
			}
			else
			{
			    $blogState  = ($row->state == -1) ? 0 : $row->state;
			}
			
			$blogObj->published		= $blogState;
			$blogObj->publish_up 	= !empty( $row->publish_up )? $row->publish_up : $date->toMySQL();
			$blogObj->publish_down	= !empty( $row->publish_down )? $row->publish_down : $date->toMySQL();
			
			$blogObj->ordering		= $row->ordering;
			$blogObj->hits			= $row->hits;
			$blogObj->frontpage     = 1;
			
			$blog->bind($blogObj);
			$blog->store();
			
			//migrate meta description
			$this->_migrateContentMeta($row->metakey, $row->metadesc, $blog->id);
			
			//isfeatured! only applicable in joomla1.6
			if(EasyBlogHelper::getJoomlaVersion() >= '1.6')
			{
			    if($row->featured)
			    {
			        EasyBlogHelper::makeFeatured('post', $blog->id);
			    }
			}
			
			//update session value
			$migrateStat->blog++;
			$statUser   	= $migrateStat->user;
			$statUserObj    = null;
			if(! isset($statUser[$profile->id]))
			{
			    $statUserObj    = new stdClass();
			    $statUserObj->name  		= $profile->nickname;
			    $statUserObj->blogcount		= 0;
			}
			else
			{
			    $statUserObj    = $statUser[$profile->id];
			}
			$statUserObj->blogcount++;
			$statUser[$profile->id] = $statUserObj;
			$migrateStat->user  	= $statUser;
			
			
			$jSession->set('EBLOG_MIGRATOR_JOOMLA_STAT', $migrateStat, 'EASYBLOG');
			
			
			//log the entry into migrate table.
			$migrator =& JTable::getInstance( 'Migrate', 'Table' );

			$migrator->content_id	= $row->id;
			$migrator->post_id		= $blog->id;
			$migrator->session_id	= $jSession->getToken();
			$migrator->component    = 'com_content';
			$migrator->store();
			
			
			$ejax->append('progress-status', 'Migrated joomla article :' . $row->id . ' into EasyBlog with blog id:' . $blog->id . '<br />');
			$ejax->script("ejax.load('migrators','_process','$authorId', '$stateId', '$catId', '$sectionId', '$myblogSection');");
		}
		$ejax->send();	
	}
	
	function _getJoomlaCategory( $catId )
	{
	    $db =& JFactory::getDBO();

	    $query  = 'select * from `#__categories` where `id` = ' . $db->Quote($catId);
	    $db->setQuery($query);
	    $result = $db->loadObject();

	    return $result;
	}


	function _isEblogCategoryExists( $joomlaCatObj )
	{
	    $db =& JFactory::getDBO();

	    $query  = 'select id from `#__easyblog_category`';
		$query	.= ' where lower(`title`) = ' . $db->Quote(JString::strtolower($joomlaCatObj->title));
		$query  .= ' OR lower(`alias`) = ' . $db->Quote(JString::strtolower($joomlaCatObj->alias));
		$query  .= ' LIMIT 1';

	    $db->setQuery($query);
	    $result = $db->loadResult();

	    if(empty($result))
	        return false;
	    else
	        return $result;
	}

	function _createEblogCategory($joomlaCatObj)
	{
		$jSession 		=& JFactory::getSession();
		$migrateStat	= $jSession->get('EBLOG_MIGRATOR_JOOMLA_STAT', '', 'EASYBLOG');
		if(empty($migrateStat))
		{
			$migrateStat    		= new stdClass();
			$migrateStat->blog  	= 0;
			$migrateStat->category	= 0;
			$migrateStat->user      = array();
		}

	    $category	=& JTable::getInstance( 'ECategory', 'Table' );

	    $arr    = array();
	    $arr['created_by']  = $this->_getSAUserId();
	    $arr['title']  		= $joomlaCatObj->title;
	    $arr['alias']  		= $joomlaCatObj->alias;
	    $arr['published']  	= $joomlaCatObj->published;

	    $category->bind($arr);
	    $category->store();

	    //update session value
	    $migrateStat->category++;
	    $jSession->set('EBLOG_MIGRATOR_JOOMLA_STAT', $migrateStat, 'EASYBLOG');

	    return $category->id;
	}
	
	function _getSAUserId()
	{
		$saUserId   = '62';
		if(EasyBlogHelper::getJoomlaVersion() >= '1.6')
		{
			$saUsers	= EasyBlogHelper::getSAUsersIds();

			$saUserId = '42';
			if(count($saUsers) > 0)
			{
			    $saUserId = $saUsers['0'];
			}
		}
		return $saUserId;
	}
	
	function _getLyftenCategory($catId)
	{
	    $db =& JFactory::getDBO();

	    $query  = 'select *, slug as `alias` from `#__bloggies_categories` where `id` = ' . $db->Quote($catId);
	    $db->setQuery($query);

	    $result = $db->loadObject();
	    $result->alias  = JFilterOutput::stringURLSafe( trim( $result->slug ) );

	    return $result;
	}

	function _migrateContentMeta($metaKey, $metaDesc, $blogId)
	{
	    $db 	=& JFactory::getDBO();

	    if(empty($metaKey) && empty($metaDesc))
	    {
			return true;
	    }

	    $meta				=& JTable::getInstance( 'Meta', 'Table' );
	    $meta->keywords		= $metaKey;
	    $meta->description	= $metaDesc;
	    $meta->content_id	= $blogId;
	    $meta->type			= 'post';
		$meta->store();

		return true;
	}

	function _migrateLyftenTags()
	{
	    //this will plot all lyften bloggie tags into easyblog's tags
	    // no relations created for each blog vs tag

	    $db 	=& JFactory::getDBO();
	    $suId   = $this->_getSAUserId();
	    $now	=& JFactory::getDate();

	    $query  = 'insert into `#__easyblog_tag` (`created_by`, `title`, `alias`, `created`, `published`)';
		$query  .= ' select ' . $db->Quote($suId) . ', `name`, `slug`, '. $db->Quote($now->toMySQL()).', ' . $db->Quote('1');
		$query  .= ' from `#__bloggies_tags`';
		$query  .= ' where `name` not in (select `title` from `#__easyblog_tag`)';

		$db->setQuery($query);
		$db->query();

		return true;
	}

	function _migrateLyftenCategories()
	{
		$jSession 		=& JFactory::getSession();
		$migrateStat	= $jSession->get('EBLOG_MIGRATOR_JOOMLA_STAT', '', 'EASYBLOG');
		if(empty($migrateStat))
		{
			$migrateStat    		= new stdClass();
			$migrateStat->blog  	= 0;
			$migrateStat->category	= 0;
			$migrateStat->user      = array();
		}

	    $db 	=& JFactory::getDBO();
	    $suId   = $this->_getSAUserId();
	    $now	=& JFactory::getDate();

		$query  = ' select `title`, `slug`, `published`';
		$query  .= ' from `#__bloggies_categories`';
		$query  .= ' where `title` != \'\' and `title` not in (select `title` from `#__easyblog_category`)';

		$db->setQuery($query);
		$results    = $db->loadObjectList();

		$suId       = $this->_getSAUserId();

		for($i = 0; $i < count($results); $i++)
		{
		    $catObj     = $results[$i];

		    $category	=& JTable::getInstance( 'ECategory', 'Table' );

		    $arr    = array();
		    $arr['created_by']  = $suId;
		    $arr['title']  		= $catObj->title;
		    $arr['alias']  		= JFilterOutput::stringURLSafe(trim($catObj->slug));
		    $arr['published']  	= $catObj->published;

		    $category->bind($arr);
		    $category->store();

		    //update session value
		    $migrateStat->category++;

		}

		if(count($results) > 0)
		{
			$jSession->set('EBLOG_MIGRATOR_JOOMLA_STAT', $migrateStat, 'EASYBLOG');
		}

		return true;
	}
	
	function _migrateJCommentIntoEasyBlog($contentId, $blogId, $contentGroup)
	{
        $db 	=& JFactory::getDBO();

        $query  = 'select * from `#__jcomments`';
		$query	.= ' where `object_id` = ' . $db->Quote($contentId);
		$query  .= ' and `object_group` = ' . $db->Quote($contentGroup);
		$query  .= ' order by `id` asc';

		$db->setQuery($query);

		$results    = $db->loadObjectList();

		$lft    = 1;
		$rgt    = 2;

		for($i = 0; $i < count($results); $i++)
		{
		    $itemComment   = $results[$i];

			$commt		=& JTable::getInstance( 'Comment', 'Table' );
			$now		=& JFactory::getDate();

			$commt->post_id      = $blogId;
			$commt->comment      = $itemComment->comment;
			$commt->title        = $itemComment->title;

			$commt->name         = $itemComment->name;
			$commt->email        = $itemComment->email;
			$commt->url          = $itemComment->homepage;
			$commt->created_by   = $itemComment->userid;
			$commt->created      = $itemComment->date;
			$commt->published    = $itemComment->published;

			$commt->lft          = $lft;
			$commt->rgt          = $rgt;

			$commt->store();

			//update state
			$migrateStat->comments++;

		    // next set of siblings
		    $lft    = $rgt + 1;
		    $rgt    = $lft + 1;
		}

		return true;
	}


}