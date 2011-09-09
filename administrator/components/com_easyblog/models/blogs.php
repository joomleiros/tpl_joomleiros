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

jimport('joomla.application.component.model');

/**
 * Content Component Article Model
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class EasyBlogModelBlogs extends JModel
{
	/**
	 * Blogs data array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Configuration data
	 * 
	 * @var int	Total number of rows
	 **/
	var $_total;
	
	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		$mainframe 	=& JFactory::getApplication();
		
		//get the number of events from database
		$limit       	= $mainframe->getUserStateFromRequest('com_easyblog.blogs.limit', 'limit', $mainframe->getCfg('list_limit') , 'int');
		$limitstart		= JRequest::getVar('limitstart', 0, '', 'int');
			
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}
	
	function getBlogs( $userId = null )
	{
		if(empty($this->_data) )
		{
			$query = $this->_buildQuery( $userId );

			$this->_data	= $this->_getList( $this->_buildQuery() , $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_data;
	}
	
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildQueryWhere();
		$orderby	= $this->_buildQueryOrderBy();
		$db			=& $this->getDBO();
		
		$filter_tag			= JRequest::getInt( 'tagid' , '' );
		

		$query	= 'SELECT DISTINCT a.*, tp.`team_id`, t.`title` as `teamname` FROM ' . $db->nameQuote( '#__easyblog_post' ) . ' AS a ';

		if( !empty( $filter_tag ) )
		{
			$query	.= ' INNER JOIN #__easyblog_post_tag AS b ';
			$query	.= 'ON a.`id`=b.`post_id` AND b.`tag_id`=' . $db->Quote( $filter_tag );
		}
		
		$query	.= ' LEFT JOIN #__easyblog_team_post AS tp ';
		$query	.= ' ON a.`id`=tp.`post_id`';
		
		$query	.= ' LEFT JOIN #__easyblog_team AS t ';
		$query	.= ' ON tp.`team_id`=t.`id`';

		
		$query	.= $where . ' ' . $orderby;

		return $query;
	}

	function _buildQueryWhere()
	{
		$mainframe			=& JFactory::getApplication();
		$db					=& $this->getDBO();
		
		$filter_state 		= $mainframe->getUserStateFromRequest( 'com_easyblog.blogs.filter_state', 'filter_state', '', 'word' );
		$filter_category 	= $mainframe->getUserStateFromRequest( 'com_easyblog.blogs.filter_category', 'filter_category', '', 'int' );
		
		$search 			= $mainframe->getUserStateFromRequest( 'com_easyblog.blogs.search', 'search', '', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );
		
		$where = array();
		
		if ( $filter_state )
		{
			if ( $filter_state == 'P' )
			{
				$where[] = 'a.`published` = ' . $db->Quote( '1' );
			}
			else if ($filter_state == 'U' )
			{
				$where[] = 'a.`published` = ' . $db->Quote( '0' );
			}
			else if ($filter_state == 'S' )
			{
				$where[] = 'a.`published` = ' . $db->Quote( '2' );
			}
		}
		
		if( $filter_category )
		{
		    $where[] = ' a.`category_id` = ' . $db->Quote($filter_category);
		}

		if ($search)
		{
			$where[] = ' LOWER( a.title ) LIKE \'%' . $search . '%\' ';
		}
		
		$where[] 	= ' `ispending` = ' . $db->Quote('0');
		
		$where 		= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' ;

		return $where;
	}

	function _buildQueryOrderBy()
	{
		$mainframe			=& JFactory::getApplication();

		$filter_order		= $mainframe->getUserStateFromRequest( 'com_easyblog.blogs.filter_order', 'filter_order', 	'a.id', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( 'com_easyblog.blogs.filter_order_Dir', 'filter_order_Dir',	'DESC', 'word' );

		$orderby 			= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.', ordering';

		return $orderby;
	}

	/**
	 * Method to return the total number of rows
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		// Load total number of rows
		if( empty($this->_total) )
		{
			$this->_total	= $this->_getListCount( $this->_buildQuery() );
		}

		return $this->_total;
	}

	/**
	 * Method to get a pagination object for the events
	 *
	 * @access public
	 * @return integer
	 */
	function &getPagination()
	{
		// Lets load the content if it doesn't already exist
		if ( empty( $this->_pagination ) )
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}
	
	function publish( $blogs = array(), $publish = 1 )
	{
		if( count( $blogs ) > 0 )
		{
			$db		=& $this->getDBO();
			
			$blogs	= implode( ',' , $blogs );
			
			$query	= 'UPDATE ' . $db->nameQuote( '#__easyblog_post' ) . ' '
					. 'SET ' . $db->nameQuote( 'published' ) . '=' . $db->Quote( $publish ) . ' '
					. 'WHERE ' . $db->nameQuote( 'id' ) . ' IN (' . $blogs . ')';
			$db->setQuery( $query );
			
			if( !$db->query() )
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}

    function approveBlog( $id )
    {
		$db 	=& JFactory::getDBO();
		
		$query = 'UPDATE `#__easyblog_post` SET `ispending`= ' . $db->Quote('0') . ' WHERE `id` = ' . $db->Quote($id) . ';';
		$db->setQuery($query);
		$db->query();
		
		return true;
	}	
}
