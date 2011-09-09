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

class TableOauthPost extends JTable
{
	var $id			= null;
	var $oauth_id	= null;
	var $post_id	= null;
	var $created	= null;
	var $modified	= null;
	var $sent		= null;
	
	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct( $db )
	{
		parent::__construct( '#__easyblog_oauth_posts' , 'id' , $db );
	}
	
	function loadByOauthId( $blogId , $id )
	{
	    $db		=& $this->getDBO();

		$query	= 'SELECT * FROM ' . $db->nameQuote( $this->_tbl ) . ' '
				. 'WHERE ' . $db->nameQuote( 'oauth_id' ) . '=' . $db->Quote( $id ) . ' '
				. 'AND ' . $db->nameQuote( 'post_id' ) . '=' . $db->Quote( $blogId );

	    $db->setQuery( $query );
		$result = $db->loadResult();
		return $this->bind( $result );
	}	 	 	 	
}