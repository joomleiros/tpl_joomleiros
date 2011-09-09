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

class TableTrackback extends JTable
{
	var $id			= null;
	var $post_id	= null;
	var $ip			= null;
	var $title		= null;
	var $excerpt	= null;
	var $url		= null;
	var $blog_name	= null;
	var $charset	= null;
	var $created	= null;
	var $published	= null;

	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_trackback' , 'id' , $db );
	}
}