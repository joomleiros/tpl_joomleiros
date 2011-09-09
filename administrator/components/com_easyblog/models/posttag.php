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
class EasyBlogModelPostTag extends JModel
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
	
	/*
	 * method to get blog tags.
	 * 
	 * param blogId - int
	 * return object list	 	 
	 */	 	 	
	function getBlogTags($blogId)
	{
		$db =& JFactory::getDBO();
		
		$query	= 'SELECT a.`id`, a.`title`, a.`alias`';
		$query	.= ' FROM `#__easyblog_tag` AS a';
		$query	.= ' LEFT JOIN `#__easyblog_post_tag` AS b';
		$query	.= ' ON a.`id` = b.`tag_id`';
		$query	.= ' WHERE b.`post_id` = '.$db->Quote($blogId);
		
		$db->setQuery($query);
		
		if($db->getErrorNum() > 0)
		{
			JError::raiseError( $db->getErrorNum() , $db->getErrorMsg() . $db->stderr());
		}		
		
		$result	= $db->loadObjectList();
		return $result;
	}
	
	function add( $tagId , $blogId , $creationDate )
	{
		$db				=& JFactory::getDBO();
		
		$obj			= new stdClass();
		$obj->tag_id	= $tagId;
		$obj->post_id	= $blogId;
		$obj->created	= $creationDate;
		
		return $db->insertObject( '#__easyblog_post_tag' , $obj );
	}
	
	function savePostTag($value)
	{
		$db	=& JFactory::getDBO();
		
		$query	= 'INSERT INTO ' . $db->nameQuote('#__easyblog_post_tag') . ' '
								 . '(' . ' ' 
								 . $db->nameQuote('tag_id') . ', ' 
								 . $db->nameQuote('post_id') . ', ' 
								 . $db->nameQuote('created') . ' '
								 . ') ' . ' '
				. 'VALUES ' . $value;
				
		$db->setQuery($query);		
		$result	= $db->Query();
		
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		return $result;
	}
	
	function deletePostTag($blogId)
	{
		$db	=& JFactory::getDBO();
		
		$query	= ' DELETE FROM ' . $db->nameQuote('#__easyblog_post_tag') 
				. ' WHERE ' . $db->nameQuote('post_id') . ' =  ' . $db->quote($blogId);
				
		$db->setQuery($query);		
		$result	= $db->Query();
		
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		return $result;
	}
}
