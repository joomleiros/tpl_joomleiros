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

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_easyblog' . DS . 'constants.php' );
require_once( EBLOG_HELPERS . DS . 'string.php' );

class TableComment extends JTable
{
	/*
	 * The id of the comment
	 * @var int
	 */
	var $id 						= null;
	
	/*
	 * The id of the blog
	 * @var int
	 */
	var $post_id					= null;
	
	/*
	 * The comment
	 * @var string
	 */
	var $comment					= null;
	
	/*
	 * The name of the commenter
	 * @var string
	 */
	var $name					= null;
	
	/*
	 * The title of the comment
	 * optional	 
	 * @var string
	 */
	var $title					= null;
	
	/*
	 * The email of the commenter
	 * optional	 
	 * @var string
	 */
	var $email					= null;
	
	/*
	 * The website of the commenter
	 * optional	 
	 * @var string
	 */
	var $url					= null;
	
	/*
	 * The ip of the visitor
	 * optional	 
	 * @var string
	 */
	var $ip					= null;								

	/*
	 * The author of the comment
	 * optional	 
	 * @var int
	 */
	var $created_by		= null;


	/*
	 * Created datetime of the comment
	 * @var datetime
	 */
	var $created				= null;
	
	/*
	 * modified datetime of the comment
	 * optional	 
	 * @var datetime
	 */
	var $modified				= null;	

	/*
	 * Tag publishing status
	 * @var int
	 */
	var $published		= null;
	
	/*
	 * comment publish datetime
	 * optional	 
	 * @var datetime
	 */
	var $publish_up		= null;
	
	/*
	 * Comment un-publish datetime
	 * optional	 
	 * @var datetime
	 */
	var $publish_down		= null;	
		
	
	/*
	 * Comment ordering
	 * @var int
	 */
	var $ordering			= null;
	
	/*
	 * Comment vote
	 * @var int
	 */
	var $vote			= null;
	
	/*
	 * Comment hits
	 * @var int
	 */
	var $hits			= null;
	
	/*
	 * Comment notification sent
	 * @var int
	 */
	var $sent			= null;
	
	/*
	 * Comment lft - used in threaded comment
	 * @var int
	 */
	var $lft			= null;	
	
	/*
	 * Comment rgt - used in threaded comment
	 * @var int
	 */
	var $rgt			= null;						


	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_comment' , 'id' , $db );
	}
	
	/**
	 *
	 *
	 */	 	 	
	function bindPost($post)
	{
	    $config 		=& EasyBlogHelper::getConfig();
			
		if(! empty($post['commentId']))
			$this->id	= $post['commentId'];
				
		$this->post_id	= $post['id'];
		
		//replace a url to link
		$comment        = $post['comment'];
		
		$filter 		=& JFilterInput::getInstance();
		$comment		= $filter->clean($comment);
		
		$this->comment	= $comment;

		if( isset( $post['name'] ) )
		{
			$this->name		= $filter->clean($post['name']);
		}
		
		if( isset( $post['title'] ) )
		{
			$this->title	= $filter->clean($post['title']);
		}
		
		if( isset( $post['email'] ) )
		{
			$this->email	= $filter->clean($post['email']);
		}
		
		if( isset( $post['url'] ) )
		{
			$this->url		= $filter->clean($post['url']);
		}
	}
	
	function updateSent()
	{
	    $db =& JFactory::getDBO();
	
	    if(! empty($this->id))
	    {
	        $query  = 'UPDATE `#__easyblog_comment` SET `sent` = 1 WHERE `id` = ' . $db->Quote($this->id);
	        
	        $db->setQuery($query);
	        $db->query();
	    }
	    
	    return true;
	}
	
	public function isCreator( $id = '' )
	{
		if( empty( $id ) )
		{
			$id	= JFactory::getUser()->id;
		}
		
		return $this->created_by == $id;
	}

	public function validate( $type )
	{
		$config		= EasyBlogHelper::getConfig();
		
		
	    if( $config->get( 'comment_requiretitle' ) && $type == 'title' )
	    {
	    	return JString::strlen( $this->title ) != 0 ;
		}
		
		if( $type == 'name' )
		{
			return JString::strlen( $this->name ) != 0;
		}
		
		if( $type == 'email' )
		{
			return JString::strlen( $this->email ) != 0;
		}
		
		if( $type == 'comment' )
		{
			return JString::strlen( $this->comment ) != 0;
		}
		
		return true;
	}
}