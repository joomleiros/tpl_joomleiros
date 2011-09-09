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
class EasyBlogModelPending extends JModel
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

		// Get the filter request variables
		//$this->setState('filter_order', JRequest::getCmd('filter_order', 'a.dates'));
		//$this->setState('filter_order_dir', JRequest::getCmd('filter_order_Dir', 'ASC'));
	}

    function approveBlog( $id )
    {
		$db 	=& JFactory::getDBO();
		
		$query = 'UPDATE `#__easyblog_post` SET `ispending`= ' . $db->Quote('0') . ' WHERE `id` = ' . $db->Quote($id) . ';';
		$db->setQuery($query);
		
		if (!($db->query())) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		
		return true;
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
		

		$query	= 'SELECT a.* FROM ' . $db->nameQuote( '#__easyblog_drafts' ) . ' AS a ';
		$query	.= $where . ' ' . $orderby;

		return $query;
	}

	function _buildQueryWhere()
	{
		$mainframe			=& JFactory::getApplication();
		$db					=& $this->getDBO();

		$search 			= $mainframe->getUserStateFromRequest( 'com_easyblog.blogs.search', 'search', '', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );
		
		$where = array();

		if ($search)
		{
			$where[] = ' LOWER( a.title ) LIKE \'%' . $search . '%\' ';
		}
		
		$where[] = ' `pending_approval` = ' . $db->Quote('1');

		$where 		= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' ;

		return $where;
	}

	function _buildQueryOrderBy()
	{
		$mainframe			=& JFactory::getApplication();

		$filter_order		= $mainframe->getUserStateFromRequest( 'com_easyblog.blogs.filter_order', 		'filter_order', 	'a.entry_id', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( 'com_easyblog.blogs.filter_order_Dir',	'filter_order_Dir',	'DESC', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

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
}
