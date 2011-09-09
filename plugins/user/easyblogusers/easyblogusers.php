<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');

class plgUserEasyBlogUsers extends JPlugin
{
	function plgUserEasyBlogUsers(& $subject, $config)
	{
		if(JFile::exists(JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'helpers'.DS.'helper.php'))
		{
			require_once (JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'helpers'.DS.'helper.php');
		}
		parent::__construct($subject, $config);
	}
	
	function onUserAfterSave( $user )
	{
	    //j.16
	    $this->onAfterStoreUser( $user );
	}
	
	function onAfterStoreUser( $user )
	{
	    //j.15
	    $db =& JFactory::getDBO();
	    
	    if( !isset( $user['id'] ) && empty( $user['id'] ) )
			return;
	    
	    //update subscription tables.
	    $userId     		= $user['id'];
	    $userFullname     	= $user['name'];
	    $userEmail     		= $user['email'];
	    
	    
	    //blogger
	    $query  = 'UPDATE `#__easyblog_blogger_subscription` SET';
		$query	.= ' `user_id` = ' . $db->Quote( $userId );
		$query	.= ', `fullname` = ' . $db->Quote( $userFullname );
		$query  .= ' WHERE `email` = ' . $db->Quote( $userEmail );
		$query  .= ' AND `user_id` = ' . $db->Quote('0');
		$db->setQuery( $query );
		$db->query();
	    
	    //category
	    $query  = 'UPDATE `#__easyblog_category_subscription` SET';
		$query	.= ' `user_id` = ' . $db->Quote( $userId );
		$query	.= ', `fullname` = ' . $db->Quote( $userFullname );
		$query  .= ' WHERE `email` = ' . $db->Quote( $userEmail );
		$query  .= ' AND `user_id` = ' . $db->Quote('0');
		$db->setQuery( $query );
		$db->query();
	    
	    //post
	    $query  = 'UPDATE `#__easyblog_post_subscription` SET';
		$query	.= ' `user_id` = ' . $db->Quote( $userId );
		$query	.= ', `fullname` = ' . $db->Quote( $userFullname );
		$query  .= ' WHERE `email` = ' . $db->Quote( $userEmail );
		$query  .= ' AND `user_id` = ' . $db->Quote('0');
		$db->setQuery( $query );
		$db->query();
	    
	    //site
	    $query  = 'UPDATE `#__easyblog_site_subscription` SET';
		$query	.= ' `user_id` = ' . $db->Quote( $userId );
		$query	.= ', `fullname` = ' . $db->Quote( $userFullname );
		$query  .= ' WHERE `email` = ' . $db->Quote( $userEmail );
		$query  .= ' AND `user_id` = ' . $db->Quote('0');
		$db->setQuery( $query );
		$db->query();
	    
	    //teamblog
	    $query  = 'UPDATE `#__easyblog_team_subscription` SET';
		$query	.= ' `user_id` = ' . $db->Quote( $userId );
		$query	.= ', `fullname` = ' . $db->Quote( $userFullname );
		$query  .= ' WHERE `email` = ' . $db->Quote( $userEmail );
		$query  .= ' AND `user_id` = ' . $db->Quote('0');
		$db->setQuery( $query );
		$db->query();
		
	}
	
	
	function onUserBeforeDelete($user)
	{
	    $this->onBeforeDeleteUser($user);
	}

	function onBeforeDeleteUser($user)
	{
		$mainframe	=& JFactory::getApplication();
		
		$userId     	= $user['id'];
		$newOwnerShip   = $this->_getnewOwnerShip();
		
		$this->ownerTransferCategory( $userId, $newOwnerShip );
		$this->ownerTransferTag( $userId, $newOwnerShip );
		$this->onwerTransferComment( $userId, $newOwnerShip );
		$this->ownerTransferPost( $userId, $newOwnerShip );
		
		$this->removeAssignedACLGroup( $userId );
		$this->removeAdsenseSetting( $userId );
		$this->removeFeedburnerSetting( $userId );
		$this->removeOAuthSetting( $userId );
		$this->removeFeaturedBlogger( $userId );
		$this->removeTeamBlogUser( $userId );
		$this->removeBloggerSubscription( $userId );
		$this->removeEasyBlogUser( $userId );
		
		
	}
	
	function _getnewOwnerShip()
	{
	    $econfig     	=& EasyBlogHelper::getConfig();
	    
	    // this should get from backend. If backend not defined, get the default superadmin.
	    
	    $user_id		= (EasyBlogHelper::getJoomlaVersion() >= '1.6') ? '42' : '62';
	    
	    $newOwnerShip	= $econfig->get('main_orphanitem_ownership', $user_id);
	    $newOwnerShip   = $this->_verifyOnwerShip($newOwnerShip);
	    
	    return $newOwnerShip;
	}
	
	function _verifyOnwerShip( $newOwnerShip )
	{
	    $db =& JFactory::getDBO();
	    
	    $query  = 'SELECT `id` FROM `#__users` WHERE `id` = ' . $db->Quote($newOwnerShip);
	    $db->setQuery($query);
	    $result = $db->loadResult();
	    
	    if(empty($result))
	    {
	        if(EasyBlogHelper::getJoomlaVersion() >= '1.6')
	        {
	            $saUsersId  = EasyBlogHelper::getSAUsersIds();
	            $result     = $saUsersId[0];
	        }
	        else
	        {
	        	$result = $this->_getSuperAdminId();
	        }
	    }
	    
	    return $result;
	}
	
	function _getSuperAdminId()
	{
		$db =& JFactory::getDBO();

		$query  = 'SELECT `id` FROM `#__users`';
		$query  .= ' WHERE (LOWER( usertype ) = ' . $db->Quote('super administrator');
		$query  .= ' OR `gid` = ' . $db->Quote('25') . ')';
		$query  .= ' ORDER BY `id` ASC';
		$query  .= ' LIMIT 1';

		$db->setQuery($query);
		$result = $db->loadResult();

		$result = (empty($result)) ? '62' : $result;
		return $result;
	}
	
	function ownerTransferCategory( $userId, $newOwnerShip )
	{
	    $db =& JFactory::getDBO();

	    $query  = 'UPDATE `#__easyblog_category`';
	    $query  .= ' SET `created_by` = ' . $db->Quote($newOwnerShip);
	    $query  .= ' WHERE `created_by` = ' . $db->Quote($userId);

		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function ownerTransferTag( $userId, $newOwnerShip )
	{
	    $db =& JFactory::getDBO();

	    $query  = 'UPDATE `#__easyblog_tag`';
	    $query  .= ' SET `created_by` = ' . $db->Quote($newOwnerShip);
	    $query  .= ' WHERE `created_by` = ' . $db->Quote($userId);

		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function ownerTransferPost( $userId, $newOwnerShip )
	{
	    $db =& JFactory::getDBO();

	    $query  = 'UPDATE `#__easyblog_post`';
	    $query  .= ' SET `created_by` = ' . $db->Quote($newOwnerShip);
	    $query  .= ' WHERE `created_by` = ' . $db->Quote($userId);

		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function onwerTransferComment( $userId, $newOwnerShip )
	{
	    $db =& JFactory::getDBO();

	    $query  = 'UPDATE `#__easyblog_comment`';
	    $query  .= ' SET `created_by` = ' . $db->Quote($newOwnerShip);
	    $query  .= ' WHERE `created_by` = ' . $db->Quote($userId);

		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	
	/**
	 * Remove assigned user acl group
	 */
	function removeAssignedACLGroup( $userId )
	{
	    $db =& JFactory::getDBO();
	    
	    $query  = 'DELETE FROM `#__easyblog_acl_group`';
	    $query  .= ' WHERE `content_id` = ' . $db->Quote($userId);
	    $query  .= ' AND `type` = ' . $db->Quote('assigned');
	    
		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function removeAdsenseSetting( $userId )
	{
	    $db =& JFactory::getDBO();
	    
	    $query  = 'DELETE FROM `#__easyblog_adsense`';
	    $query  .= ' WHERE `user_id` = ' . $db->Quote($userId);
	    
		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function removeFeedburnerSetting( $userId )
	{
	    $db =& JFactory::getDBO();

	    $query  = 'DELETE FROM `#__easyblog_feedburner`';
	    $query  .= ' WHERE `userid` = ' . $db->Quote($userId);

		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	/**
	 * Since EasyBlog 2.0
	 */
	function removeOAuthSetting( $userId )
	{
	    $db =& JFactory::getDBO();

		// removing oauth posts
	    $query  = 'DELETE FROM `#__easyblog_oauth_posts`';
	    $query  .= ' WHERE `oauth_id` IN (';
		$query  .= ' select `id` from `#__easyblog_oauth` where `user_id` = ' . $db->Quote( $userId );
		$query	.= ')';
		$db->setQuery( $query );
		$db->query();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		// removing oauth
	    $query  = 'DELETE FROM `#__easyblog_oauth`';
	    $query  .= ' WHERE `user_id` = ' . $db->Quote($userId);
		$db->setQuery( $query );
		$db->query();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function removeFeaturedBlogger( $userId )
	{
	    $db =& JFactory::getDBO();

	    $query  = 'DELETE FROM `#__easyblog_featured`';
	    $query  .= ' WHERE `content_id` = ' . $db->Quote($userId);
	    $query  .= ' AND `type` = ' . $db->Quote('blogger');

		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function removeTeamBlogUser( $userId )
	{
	    $db =& JFactory::getDBO();
	
	    $query  = 'DELETE FROM `#__easyblog_team_users`';
	    $query  .= ' WHERE `user_id` = ' . $db->Quote($userId);

		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	
	function removeBloggerSubscription( $userId )
	{
	    $db =& JFactory::getDBO();

	    $query  = 'DELETE FROM `#__easyblog_blogger_subscription`';
	    $query  .= ' WHERE `blogger_id` = ' . $db->Quote($userId);

		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	
	function removeEasyBlogUser( $userId )
	{
	    $db =& JFactory::getDBO();

	    $query  = 'DELETE FROM `#__easyblog_users`';
	    $query  .= ' WHERE `id` = ' . $db->Quote($userId);

		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
}
