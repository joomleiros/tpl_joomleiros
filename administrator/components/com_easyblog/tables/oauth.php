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

class TableOauth extends JTable
{
	var $id			= null;
	var $user_id	= null;
	var $type		= null;
	var $auto		= null;
	var $request_token	= null;
	var $access_token	= null;
	var $message	= null;
	var $created	= null;
	var $private	= null;
	var $params		= null;
	
	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct( $db )
	{
		parent::__construct( '#__easyblog_oauth' , 'id' , $db );
	}
	
	function loadByUser( $id , $type )
	{
	    $db		=& $this->getDBO();

		$query	= 'SELECT ' . $db->nameQuote( 'id' ) . ' FROM ' . $db->nameQuote( $this->_tbl ) . ' '
				. 'WHERE ' . $db->nameQuote( 'user_id' ) . '=' . $db->Quote( $id ) . ' '
				. 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $type );

	    $db->setQuery( $query );

	    $result = $db->loadResult();

	    if(empty($result))
	    {
	        $this->user_id  = $id;
	        return $this;
	    }

		return parent::load($result);
	}
	
	function store()
	{
		$db		=& $this->getDBO();
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( $this->_tbl ) . ' '
				. 'WHERE ' . $db->nameQuote( 'user_id' ) . '=' . $db->Quote( $this->user_id ) . ' '
				. 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $this->type );
		$db->setQuery( $query );
		
		if( $db->loadResult() )
		{
			return $db->updateObject( $this->_tbl, $this, $this->_tbl_key );
		}
		
		return $db->insertObject( $this->_tbl, $this, $this->_tbl_key );
	}

	function getMessage()
	{
		$config		=& EasyBlogHelper::getConfig();
		$message	= !empty( $this->message ) ? $this->message : $config->get('integrations_' . $this->type . '_default_messsage' );
		return $message;
	}
	
	/*
	 * Determines whether we've shared the respective blog entry
	 * to the consumer site or not.
	 * 
	 * @param	int		$blogId	The respective blog id.
	 * @return	boolean	True if entry is shared previously.	 
	 */
	public function isShared( $blogId )
	{
		$db		= JFactory::getDBO();
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__easyblog_oauth_posts' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'oauth_id' ) . '=' . $db->Quote( $this->id ) . ' '
				. 'AND ' . $db->nameQuote( 'post_id' ) . '=' . $db->Quote( $blogId );

	    $db->setQuery( $query );
		$result = $db->loadResult();
		
		return $result > 0;
	}

	/*
	 * Get's the last shared date
	 * 
	 * @param	int		$blogId	The respective blog id.
	 * @return	boolean	True if entry is shared previously.	 
	 */
	public function getSharedDate( $blogId )
	{
		$db		= JFactory::getDBO();
		$query	= 'SELECT ' . $db->nameQuote( 'sent' ) . ' FROM ' . $db->nameQuote( '#__easyblog_oauth_posts' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'oauth_id' ) . '=' . $db->Quote( $this->id ) . ' '
				. 'AND ' . $db->nameQuote( 'post_id' ) . '=' . $db->Quote( $blogId );

	    $db->setQuery( $query );
		$result = $db->loadResult();
 		
		return EasyBlogDateHelper::dateWithOffSet( $result )->toMySQL();
	}	 	
}