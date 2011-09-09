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

class TableTwitter extends JTable
{
	/*
	 * The id of the twitter config
	 * @var int
	 */
	var $id			= null;

	/*
	 * usr id, foreign key for user table 
	 * @var int
	 */
	var $user_id	= null;
	
	/*
	 * twitter id number
	 * @var int
	 */
	var $twitter_id	= null;
	
	/*
	 * twitter account's username
	 * @var string
	 */
	var $username	= null;
	
	/*
	 * twitter account's password
	 * @var string
	 */
	var $password	= null;
	
	/*
	 * twitter custom message
	 * @var string
	 */
	var $message	= null;
	
	/*
	 * twitter enable auto update twitter
	 * @var string
	 */
	var $auto	= null;
	
	/*
	 * twitter OAuth request token
	 * @var string
	 */
	var $oauth_request_token	= null;
	
	/*
	 * twitter OAuth access token
	 * @var string
	 */
	var $oauth_access_token	= null;
	
	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db ){
		parent::__construct( '#__easyblog_twitter' , 'id' , $db );
	}
	
	function load($id)
	{
	    $db		=& $this->getDBO();

	    $query  = 'select `id` FROM ' . $db->nameQuote( $this->_tbl );
	    $query  .= ' where user_id = ' . $db->Quote( $id );

	    $db->setQuery( $query );

	    $result = $db->loadResult();

	    if(empty($result))
	    {
	        $this->user_id  = $id;
	        return $this;
	    }
	    else
	    {
			return parent::load($result);
	    }

	}
	
	function store()
	{
		$db		=& $this->getDBO();
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( $this->_tbl ) . ' '
				. 'WHERE user_id=' . $db->Quote( $this->user_id );
		$db->setQuery( $query );
		
		if( $db->loadResult() )
		{
			return $db->updateObject( $this->_tbl, $this, $this->_tbl_key );
		}
		return $db->insertObject( $this->_tbl, $this, $this->_tbl_key );
	}
}