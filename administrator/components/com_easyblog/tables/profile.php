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

require_once( EBLOG_HELPERS . DS . 'image.php' );
require_once( EBLOG_HELPERS . DS . 'string.php' );

class TableProfile extends JTable
{
	var $id         	= null;
	var $title			= null;
	var $nickname   	= null;
	var $avatar      	= null;
	var $description 	= null;
	var $biography		= null;
	var $url         	= null;
	var $params      	= null;
	var $user			= null;
	var $permalink		= null;

	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_users' , 'id' , $db );
	}
	
	function bind( $data )
	{
		parent::bind( $data );
		
		$this->url	= $this->_appendHTTP( $this->url );
		
		//default to username for blogger permalink if empty
		if(empty($this->permalink))
		{
		    $user	=& JFactory::getUser($this->id);
		    $this->permalink	= $user->username;
		}
		else
		{
		    $this->permalink	= JFilterOutput::stringURLSafe($this->permalink);
		}
		
		return true;
	}
	
	function _createDefault( $id )
	{
		$db	=& $this->getDBO();
		
		$user	=& JFactory::getUser($id);
		
		$obj				= new stdClass();
		$obj->id 			= $user->id;
		$obj->nickname		= $user->name;
		$obj->avatar		= 'default_blogger.png';
		$obj->description 	= '';
		$obj->url			= '';
		$obj->params		= '';
		
		//default to username for blogger permalink
		$obj->permalink		= $user->username;
		
		$db->insertObject('#__easyblog_users', $obj);
	}
	
	/**
	 * override load method.
	 * if user record not found in eblog_profile, create one record.
	 *    	 
	 */
   	function load($id)
	{
	    static $users = null;
	    
		if( !isset( $users[ $id ] ) )
		{
			if((! parent::load($id)) && ($id != 0))
			{
				$this->_createDefault($id);
			}
			parent::load( $id );
			
			$users[ $id ] = $this;
		}
		$this->user	= JFactory::getUser( $id );
		$this->bind( $users[ $id ] );

	    return $users[ $id ];
   	}
	
	function setUser( $my )
	{
		$this->load( $my->id );
		$this->user = $my;
	}

	function getLink()
	{
		return 'index.php?option=com_easyblog&view=blogger&layout=listings&id=' . $this->id;
	}
		
	function getName(){

	    if($this->id == 0)
		{
			return JText::_('COM_EASYBLOG_GUEST');
		}
	        
		$config 		=& EasyBlogHelper::getConfig();
		$displayname    = $config->get('layout_nameformat');
		
		if( !$this->user )
		{
			$this->user	= JFactory::getUser( $this->id );
		}
		
		switch($displayname)
		{
			case "name" :
				$name = $this->user->name;
				break;
			case "username" :
				$name = $this->user->username;
				break;
			case "nickname" :
			default :
				$name = (empty($this->nickname)) ? $this->user->name : $this->nickname;
				break;
		}
		
		return EasyBlogStringHelper::escape( $name );
	}
	
	function getId(){
		return $this->id;
	}
	
	/**
	 * Retrieves the user's avatar
	 * 	 
	 **/	 	
	function getAvatar()
	{
		return EasyBlogHelper::getHelper( 'avatar' )->getAvatarURL( $this );
	}
	
	function getDescription(){
		return $this->description;
	}
	
	/**
	 * Retrieves the user's twitter link
	 **/
	function getTwitterLink()
	{
		return EasyBlogHelper::getHelper( 'SocialShare' )->getLink( 'twitter' , $this->id );
	}
		 	
	/**
	 * Determines whether the blogger is a featured blogger
	 **/
	function isFeatured()
	{
		return EasyBlogHelper::isFeatured( 'blogger', $this->id );
	}
	
	/**
	 * Retrieves the biography from the specific blogger
	 **/	 	
	function getBiography()
	{
		static $loaded	= array();
		
		if( !isset( $loaded[ $this->id ] ) )
		{
			$status		= '';
			$config		= EasyBlogHelper::getConfig();
			
			if( $config->get( 'integrations_jomsocial_blogger_status' ) )
			{
				$path		= JPATH_ROOT . DS  . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php';
				
				if( JFile::exists( $path ) )
				{
					require_once( $path );
					require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'helpers' . DS . 'string.php' );
					$user	= CFactory::getUser( $this->id );
					$status	= $user->getStatus();
				}
			}
						
			if( !empty( $this->biography ) && empty( $status ) )
			{
				$status	= nl2br( $this->biography );
			}
			
			if( empty( $status ) )
			{
				$lang	= JFactory::getLanguage();
				$lang->load( 'com_easyblog' , JPATH_ROOT );
				
				$status	= JText::sprintf( 'COM_EASYBLOG_BIOGRAPHY_NOT_SET' , $this->getName() );
			}
 
			$loaded[ $this->id ]	= $status;
		}

		return $loaded[ $this->id ];
	}
	
	function getWebsite()
	{
		if( $this->url == 'http://' || empty( $this->url ) )
		{
			return '';
		}
		
		return $this->url;
	}
	
	/*
	 * Generates profile links for the author.
	 *
	 * @param	null
	 * @return	string	The link to their profile
	 */
	public function getProfileLink()
	{
		$config	= EasyBlogHelper::getConfig();
		
		jimport( 'joomla.filesystem.file' );
		$file	= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php';
		
		if( JFile::exists( $file ) && $config->get( 'integrations_jomsocial_profile_link' ) )
		{
			require_once( $file );
			
			return CRoute::_( 'index.php?option=com_community&view=profile&userid=' . $this->id );
		}
		return false;
	}
	
	public function getPermalink()
	{
		$url	= EasyBlogRouter::_( 'index.php?option=com_easyblog&view=blogger&layout=listings&id=' . $this->id );
		
		return $url;
	}
	
	function getParams(){
		return $this->params;
	}
	
	function getUserType(){
		return $this->user->usertype;
	}

	function _appendHTTP($url)
	{
		$returnStr	= '';
		$regex = '/^(http|https|ftp):\/\/*?/i';
		if (preg_match($regex, trim($url), $matches)) { 
			$returnStr	= $url; 
		} else { 
			$returnStr	= 'http://' . $url; 
		}
		
		return $returnStr;
	}

	function getRSS()
	{
		$config			=& EasyBlogHelper::getConfig();
		
		if( $config->get( 'main_feedburnerblogger' ) )
		{
	        $feedburner	=& JTable::getInstance( 'Feedburner', 'Table' );
	        $feedburner->load($this->id);
	        
			if(! empty($feedburner->url))
			{
				$rssLink    = $feedburner->url;
				return $rssLink;
			}
		}

		return EasyBlogHelper::getHelper( 'Feeds' )->getFeedURL( 'index.php?option=com_easyblog&view=blogger&id=' . $this->id );
	}

	function getAtom()
	{
	    return EasyBlogHelper::getHelper( 'Feeds' )->getFeedURL( 'index.php?option=com_easyblog&view=blogger&id=' . $this->id, true );
	}
	
	function isOnline()
	{
		static	$loaded	= array();
		
		if( !isset( $loaded[ $this->id ] ) )
		{
			$db		= & JFactory::getDBO();
			$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__session' ) . ' '
					. 'WHERE ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $this->id ) . ' '
					. 'AND ' . $db->nameQuote( 'client_id') . '<>' . $db->Quote( 1 );
			$db->setQuery( $query );

			$loaded[ $this->id ]	= $db->loadResult() > 0 ? true : false;
		}
		return $loaded[ $this->id ];
	}
	
	/**
	 * Retrieve a list of tags created by this user	 
	 **/
	public function getTags()
	{
		$db		= JFactory::getDBO();
		$query	= 'SELECT * FROM ' . $db->nameQuote( '#__easyblog_tag' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'created_by' ) .'=' . $db->Quote( $this->id ) . ' '
				. 'AND ' . $db->nameQuote( 'published' ) . '=' . $db->Quote( 1 );
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
	 * Retrieve a list of tags created by this user	 
	 **/
	public function getCommentsCount()
	{
		$db		= JFactory::getDBO();
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__easyblog_comment' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'created_by' ) .'=' . $db->Quote( $this->id ) . ' '
				. 'AND ' . $db->nameQuote( 'published' ) . '=' . $db->Quote( 1 );
		$db->setQuery( $query );
		return $db->loadResult();
	}
}