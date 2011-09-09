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

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

require_once( JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'constants.php' );
require_once( EBLOG_HELPERS . DS . 'router.php' );

class TableTeamBlog extends JTable
{
	var $id 			= null;
	var $title			= null;
	var $description	= null;
	var $published		= null;
	var $created		= null;
	var $alias			= null;
	var $access			= null;
	var $avatar			= null;
	
	/**
	 * Constructor for this class.
	 *
	 * @return
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_team' , 'id' , $db );
	}
	
	function load( $key , $permalink = false )
	{
		if( !$permalink )
		{
			return parent::load( $key );
		}

		$db		=& $this->getDBO();

		$query	= 'SELECT id FROM ' . $this->_tbl . ' '
				. 'WHERE `alias`=' . $db->Quote( $key );
		$db->setQuery( $query );

		$id		= $db->loadResult();

		// Try replacing ':' to '-' since Joomla replaces it
		if( !$id )
		{
			$query	= 'SELECT id FROM ' . $this->_tbl . ' '
					. 'WHERE `alias`=' . $db->Quote( JString::str_ireplace( ':' , '-' , $key ) );
			$db->setQuery( $query );

			$id		= $db->loadResult();
		}
		
		return parent::load( $id );
	}

	function delete()
	{
		if( parent::delete() )
		{
			$this->deleteMembers();
			return true;
		}
	}
	
	function deleteMembers($userId = '')
	{
		// Delete existing members first so we dont have to worry what's changed
		if( $this->id != 0 )
		{
			$db		=& JFactory::getDBO();
			$query	= 'DELETE FROM #__easyblog_team_users ';
			$query	.= ' WHERE `team_id`=' . $db->Quote( $this->id );
			if(! empty($userId))
				$query	.= ' AND `user_id`=' . $db->Quote( $userId );
			
			$db->setQuery( $query );
			$db->Query();
		}
	}
	
	function getMembers()
	{
		if( $this->id != 0 )
		{
			$db		=& JFactory::getDBO();
			$query	= 'SELECT user_id FROM #__easyblog_team_users '
					. 'WHERE `team_id`=' . $db->Quote( $this->id );
			$db->setQuery( $query );

			return $db->loadResultArray();
		}
		
		return false;
	}

	function isMember($userId)
	{
		if( $this->id != 0 )
		{
			$db		=& JFactory::getDBO();
			
			$query	= 'SELECT `user_id` FROM `#__easyblog_team_users`';
			$query  .= ' WHERE `team_id`=' . $db->Quote( $this->id );
			$query  .= ' AND `user_id` = ' . $db->Quote( $userId );
			
			$db->setQuery( $query );
			
			$result = $db->loadResult();
			return $result;
		}
		return false;
	}
	
	/**
	 * Overrides parent's bind method to add our own logic.
	 * 
	 * @param Array $data	 
	 **/	 	 	
	function bind( $data )
	{
		parent::bind( $data );
		
		if( empty( $this->created ) )
		{
			$date			=& JFactory::getDate();
			$this->created	= $date->toMySQL();
		}
		
		jimport( 'joomla.filesystem.filter.filteroutput');

		$i	= 1;
		while( $this->aliasExists() || empty($this->alias) )
		{
			$this->alias	= empty($this->alias) ? $this->title : $this->alias . '-' . $i;
			$i++;
		}
		
		$this->alias 	= EasyBlogRouter::generatePermalink( $this->alias );
	}
	
	function aliasExists()
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__easyblog_team' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'alias' ) . '=' . $db->Quote( $this->alias );
		
		if( $this->id != 0 )
		{
			$query	.= ' AND ' . $db->nameQuote( 'id' ) . '!=' . $db->Quote( $this->id ); 
		}
		$db->setQuery( $query );
		
		return $db->loadResult() > 0 ? true : false; 
	}
	
	function getAvatar()
	{
	    $avatar_link    = '';

        if($this->avatar == 'tdefault.png' || $this->avatar == 'default_teamblog.png' || $this->avatar == 'components/com_easyblog/assets/images/default_teamblog.png' || $this->avatar == 'components/com_easyblog/assets/images/tdefault.png' || empty($this->avatar))
        {
            $avatar_link   = 'components/com_easyblog/assets/images/default_teamblog.png';
        }
        else
        {
    		$avatar_link   = EasyImageHelper::getAvatarRelativePath('team') . '/' . $this->avatar;
    	}

		return rtrim(JURI::root(), '/') . '/' . $avatar_link;
	}
	
	function getTeamAdminEmails()
	{
	    $db		=& $this->getDBO();
	    
		$query  = 'select `email` from `#__users` as a inner join `#__easyblog_team_users` as b on a.`id` = b.`user_id`';
		$query  .= ' where b.`team_id` = ' . $db->Quote($this->id);
		$query  .= ' and b.isadmin = ' . $db->Quote('1');
	    
	    $db->setQuery($query);
		$result = $db->loadResultArray();
		
		if(count($result) == 0)
		{
		    $notify	=& EasyBlogHelper::getNotification();
		    $adminEmails = $notify->getAdminEmails();
		    
		    foreach($adminEmails as $row)
		    {
		        $result[]   = $row->email;
		    }
		}
		
		return $result;
	}
	
	function allowSubscription($access, $userid, $ismember, $aclallowsubscription=false)
	{
		$allowSubscription = false;
		
		$config =& EasyBlogHelper::getConfig();
		
		if($config->get('main_teamsubscription', false))
		{
			switch($access)
			{
				case EBLOG_TEAMBLOG_ACCESS_MEMBER:
					if($ismember && $aclallowsubscription)
						$allowSubscription = true;
					else
						$allowSubscription = false;
					break;
				case EBLOG_TEAMBLOG_ACCESS_REGISTERED:
					if($userid != 0 && $aclallowsubscription)
						$allowSubscription = true;
					else
						$allowSubscription = false;
					break;
				case EBLOG_TEAMBLOG_ACCESS_EVERYONE:
					if($aclallowsubscription || (empty($userid) && $config->get('main_allowguestsubscribe')))
						$allowSubscription = true;
					else
						$allowSubscription = false;
					break;
				default:
					$allowSubscription = false;
			}	
		}
		
		return $allowSubscription;
	}

	/**
	 * Retrieve a list of tags created by this team	 
	 **/
	public function getTags()
	{
		$db			= JFactory::getDBO();
		
		$query		= 'SELECT a.* FROM ' . $db->nameQuote( '#__easyblog_tag' ) .  ' AS a '
					. 'INNER JOIN ' . $db->nameQuote( '#__easyblog_post_tag' ) . ' AS b '
					. 'ON b.' . $db->nameQuote( 'tag_id' ) . '=a.' . $db->nameQuote( 'id' ) . ' '
					. 'INNER JOIN ' . $db->nameQuote( '#__easyblog_team_post' ) . ' AS c '
					. 'ON c.' . $db->nameQuote( 'post_id' ) . '=b.' . $db->nameQuote( 'post_id' ) . ' '
					. 'INNER JOIN ' . $db->nameQuote( '#__easyblog_post' ) . ' AS d '
					. 'ON d.' . $db->nameQuote( 'id' ) . '=c.' . $db->nameQuote( 'post_id' ) . ' '
					. 'WHERE c.' . $db->nameQuote( 'team_id' ) . '=' . $db->Quote( $this->id ) . ' '
					. 'AND d.' . $db->nameQuote( 'published' ) . '=' . $db->Quote( POST_ID_PUBLISHED ) . ' '
					. 'GROUP BY a.' . $db->nameQuote( 'id' );

		$db->setQuery( $query );

		$rows	= $db->loadObjectList();
		$tags	= array();
		
		foreach( $rows as $row )
		{
			$tag	= JTable::getInstance( 'Tag' , 'Table' ); 
			$tag->bind( $row );
			$tags[]	= $tag;
		}
		
		return $tags;
	}

	/**
	 * Retrieve a list of tags created by this team	 
	 **/
	public function getPostCount()
	{
		$db     =& JFactory::getDBO();
		
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__easyblog_post' ) . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote( '#__easyblog_team_post' ) . ' AS b '
				. 'ON b.' .  $db->nameQuote( 'post_id' ) . '=a.' . $db->nameQuote( 'id' ) . ' '
				. 'WHERE b.' . $db->nameQuote( 'team_id' ) . '=' . $db->Quote( $this->id ) . ' '
				. 'AND ' . $db->nameQuote( 'published' ) . '=' . $db->Quote( 1 );
		$db->setQuery( $query );
		return $db->loadResult();
	}
	
	/**
	 * Retrieve a list of categories used and created by this team members
	 **/	 	
	public function getCategories()
	{
		$db			= JFactory::getDBO();

		$query	= 'SELECT DISTINCT a.*, COUNT( b.' . $db->nameQuote( 'id' ) . ' ) AS ' . $db->nameQuote( 'post_count' ) . ' '
				. 'FROM ' . $db->nameQuote( '#__easyblog_category' ) . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote( '#__easyblog_post' ) . ' AS b '
				. 'ON a.' . $db->nameQuote( 'id' ) . '=b.' . $db->nameQuote( 'category_id' ) . ' '
				. 'INNER JOIN ' . $db->nameQuote( '#__easyblog_team_post' ) . ' AS c '
				. 'ON c.' . $db->nameQuote( 'post_id' ) . '=b.' . $db->nameQuote( 'id' ) . ' '
				. 'WHERE c.' . $db->nameQuote( 'team_id' ) . '=' . $db->Quote( $this->id ) . ' '
				. 'GROUP BY a.' . $db->nameQuote( 'id' );

		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	/*
	 * Determines whether the current blog entry belongs to the team.
	 *
	 * @param	int		$entryId	The subject's id.
	 * @return	boolean		True if entry was contributed to the team and false otherwise.
	 */	 
	public function isPostOwner( $postId )
	{
		if( empty( $postId ) )
		{
		    return false;
		}
		
	    $db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__easyblog_team_post' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'post_id' ) . '=' . $db->Quote( $postId ) . ' '
				. 'AND ' . $db->nameQuote( 'team_id' ) . '=' . $db->Quote( $this->id );

		$db->setQuery( $query );
		$result	= $db->loadResult();
		
		return $result > 0;
	}
	
	function getRSS()
	{
		return EasyBlogHelper::getHelper( 'Feeds' )->getFeedURL( 'index.php?option=com_easyblog&view=teamblog&id=' . $this->id );
	}

	function getAtom()
	{
		return EasyBlogHelper::getHelper( 'Feeds' )->getFeedURL( 'index.php?option=com_easyblog&view=teamblog&id=' . $this->id , true );
	}
}