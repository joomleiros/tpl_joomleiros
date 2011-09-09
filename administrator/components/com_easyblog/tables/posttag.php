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

class TablePostTag extends JTable
{

	/*
	 * PK for tag table. Auto increment
	 * @var int
	 */
	var $id		= null;
	
	/*
	 * Foreign key for tag table
	 * @var int
	 */
	var $tag_id		= null;

	/*
	 * Foreign key for post table
	 * @var int
	 */
	var $post_id	= null;
	

	/*
	 * Post tag creation date
	 * @var datetime
	 */
	var $created	= null;	
	
	

	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_post_tag' , 'id' , $db );
	}
}