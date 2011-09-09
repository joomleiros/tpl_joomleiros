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
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * Content Component Article Model
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class EasyBlogModelTwitter extends JModel
{
	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();
	}
	
	function markAsSent($blogId)
	{
		$posts =& JTable::getInstance( 'TwitterPosts', 'Table' );
    	$posts->load($blogId);
    	
    	$date 	= new JDate();
    	$now	= $date->toMySQL();
    	
    	if(empty($posts->id))
    	{
    		$posts->created = $now;
    	}
    	
    	$posts->modified = $now;
    	
    	if($posts->store())
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
	}
	
	function checkIfSent($blogId)
	{
		$posts =& JTable::getInstance( 'TwitterPosts', 'Table' );
    	$posts->load($blogId);
    	
    	if(empty($posts->id))
    	{
    		return false;
    	}
    	
    	return true;
	}
}
