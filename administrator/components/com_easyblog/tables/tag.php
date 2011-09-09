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

require_once( JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'constants.php' );
require_once( EBLOG_HELPERS . DS . 'router.php' );

class TableTag extends JTable
{
	/*
	 * The id of the tag
	 * @var int
	 */
	var $id 			= null;

	/*
	 * The author of the tag
	 * @var int
	 */
	var $created_by		= null;

	/*
	 * Tag title
	 * @var string
	 */
	var $title			= null;
	
	/*
	 * Tag alias
	 * @var string
	 */
	var $alias			= null;

	/*
	 * Created datetime of the tag
	 * @var datetime
	 */
	var $created		= null;

	/*
	 * Tag status
	 * @var int
	 */
	var $status			= null;

	/*
	 * Tag publishing status
	 * @var int
	 */
	var $published		= null;
	
	/*
	 * Default tag true or false
	 * @var boolean
	 */
	var $default		= null;
	
	/*
	 * tag ordering
	 * @var int
	 */
	var $ordering		= null;


	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_tag' , 'id' , $db );
	}

	function load( $id , $loadByTitle = false)
	{
		if( !$loadByTitle)
		{
			static $titles	= null;
			
			if( !isset( $titles[ $id ] ) )
			{
				$titles[ $id ]	= parent::load( $id );
			}
			return $titles[ $id ];
		}
		
		static $tags	= null;
		
		if( !isset( $tags[ $id ] ) )
		{	
			$db		=& JFactory::getDBO();
			$query	= 'SELECT *';
			$query	.= ' FROM ' 	. $db->nameQuote('#__easyblog_tag');
			$query	.= ' WHERE (' 	. $db->nameQuote('title') . ' = ' .  $db->Quote( JString::str_ireplace( ':' , '-' , $id ) );
			$query	.= ' OR ' 	. $db->nameQuote('title') . ' = ' .  $db->Quote( JString::str_ireplace( '-' , ' ' , $id ) ) . ' ';
			$query	.= ' OR ' 	. $db->nameQuote('alias') . ' = ' .  $db->Quote( JString::str_ireplace( ':' , '-' , $id ) ) . ')';
			$query	.= ' LIMIT 1';
			
			$db->setQuery($query);
			$result	= $db->loadObject();
			
			if( $result )
			{
				$this->id		= $result->id;
				$this->title	= $result->title;
				$this->created_by	= $result->created_by;
				$this->alias		= $result->alias;
				$this->created		= $result->created;
				$this->status		= $result->status;
				$this->published	= $result->published;
				$this->ordering		= $result->ordering;
				$tags[ $id ]		= true;
			}
			else
			{
				$tags[ $id ]		= false;
			}
		}
		return $tags[ $id ];
	}
	
	function aliasExists()
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__easyblog_tag' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'alias' ) . '=' . $db->Quote( $this->alias );
		
		if( $this->id != 0 )
		{
			$query	.= ' AND ' . $db->nameQuote( 'id' ) . '!=' . $db->Quote( $this->id ); 
		}
		$db->setQuery( $query );
		
		return $db->loadResult() > 0 ? true : false; 
	}

	function exists( $title )
	{
		$db	=& JFactory::getDBO();
		
		$query	= 'SELECT COUNT(1) '
				. 'FROM ' 	. $db->nameQuote('#__easyblog_tag') . ' '
				. 'WHERE ' 	. $db->nameQuote('title') . ' = ' . $db->quote($title) . ' '
				. 'LIMIT 1';
		$db->setQuery($query);
		
		$result	= $db->loadResult() > 0 ? true : false;
		
		return $result;
	}
	
	/**
	 * Overrides parent's bind method to add our own logic.
	 * 
	 * @param Array $data	 
	 **/	 	 	
	function bind( $data )
	{
		parent::bind( $data );
		
		if( empty( $this->created ) )
		{
			$date			=& JFactory::getDate();
			$this->created	= $date->toMySQL();
		}
		
		jimport( 'joomla.filesystem.filter.filteroutput');

		$i	= 1;
		while( $this->aliasExists() || empty($this->alias) )
		{
			$this->alias	= empty($this->alias) ? $this->title : $this->alias . '-' . $i;
			$i++;
		}
		
		$this->alias 	= EasyBlogRouter::generatePermalink( $this->alias );

	}
	
	/**
	 * Overrides parent's delete method to add our own logic.
	 * 
	 * @return boolean
	 * @param object $db
	 */
	function delete()
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__easyblog_post_tag' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'tag_id' ) . '=' . $db->Quote( $this->id );
		$db->setQuery( $query );
		
		$count	= $db->loadResult();
	
		if( $count > 0 )
		{
			return false;
		}
		
		return parent::delete();
	}
	
	// method to delete all the blog post that associated with the current tag
	function deletePostTag()
	{
		$db		=& $this->getDBO();

		$query	= 'DELETE FROM ' . $db->nameQuote( '#__easyblog_post_tag' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'tag_id' ) . '=' . $db->Quote( $this->id );
		$db->setQuery( $query );
		
		if($db->query($db))
		{
		    return true;
		}
		else
		{
		    return false;
		}
	}
	
	function getPostCount()
	{
	    $db		=& $this->getDBO();
	    
	    $query  = 'select count(1) from `#__easyblog_post_tag`';
	    $query  .= ' where `tag_id` = ' . $db->Quote( $this->id );
	    
	    $db->setQuery( $query );
	    
	    $result = $db->loadResult();
	    return ( empty( $result ) ) ? 0 : $result;
	}
	
	public function store()
	{
	    if( !empty( $this->created ))
	    {
	        $offset     	= EasyBlogDateHelper::getOffSet();
	        $newDate    =& JFactory::getDate($this->created, $offset);
	        $this->created  = $newDate->toMySQL();
	    }
	    else
	    {
	        $newDate    =& JFactory::getDate();
	        $this->created  = $newDate->toMySQL();
	    }

	    return parent::store();
	}
}