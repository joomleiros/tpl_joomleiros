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

class TableTwitterPosts extends JTable
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
	var $post_id	= null;

	/*
	 * date time the entry is created
	 * @var string
	 */
	var $created	= null;
	
	/*
	 * date time the entry is modified
	 * @var string
	 */
	var $modified	= null;
	
		
	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_twitter_posts' , 'post_id' , $db );
	}
	
	function store()
	{
		$db		=& $this->getDBO();
		
		if( $this->id )
		{
			return $db->updateObject( $this->_tbl, $this, $this->_tbl_key );
		}
		
		return $db->insertObject( $this->_tbl, $this, $this->_tbl_key );
	} 
}