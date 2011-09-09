<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *  
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class EasyBlogModelSubscriptions extends JModel
{
	/**
	 * Category total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Category data array
	 *
	 * @var array
	 */
	var $_data = null;
	
	function __construct()
	{
		parent::__construct();

		
		$mainframe	=& JFactory::getApplication();
				
		//$limit		= ($mainframe->getCfg('list_limit') == 0) ? 5 : $mainframe->getCfg('list_limit');
		$limit		= $mainframe->getUserStateFromRequest( 'com_easyblog.subscriptions.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	    $limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');
	    
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);		

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}
	
	function getSubscriptions($sort = 'latest', $filter='blogger')
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			
			//echo $query;
			
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}
	
	/**
	 * Method to build the query for the tags
	 *
	 * @access private
	 * @return string
	 */
	function _buildQuery()
	{
		
		$db			=& $this->getDBO();
		$mainframe  =& JFactory::getApplication();
		
		$filter		= $mainframe->getUserStateFromRequest( 'com_easyblog.subscriptions.filter', 		'filter', 	'blogger', 'word' );

		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildQueryWhere();
		$orderby	= $this->_buildQueryOrderBy();

		$query  = '';
		
		if($filter	== 'blog')
		{
			$query	.= 'SELECT a.*, b.`title` as `bname`, c.`name`, c.`username`';
			$query	.= '  FROM `#__easyblog_post_subscription` a';
			$query	.= '    left join `#__easyblog_post` b on a.`post_id` = b.`id`';
			$query	.= '    left join `#__users` c on a.`user_id` = c.`id`';
		}
		else if($filter == 'category')
		{
			$query	.= 'SELECT a.*, b.`title` as `bname`, c.`name`, c.`username`';
			$query	.= '  FROM `#__easyblog_category_subscription` a';
			$query	.= '    left join `#__easyblog_category` b on a.`category_id` = b.`id`';
			$query	.= '    left join `#__users` c on a.`user_id` = c.`id`';
		}
		else if($filter == 'site')
		{
			$query	.= 'SELECT a.*, '.$db->Quote('site').' as `bname`, c.`name`, c.`username`';
			$query	.= '  FROM `#__easyblog_site_subscription` a';
			$query	.= '    left join `#__users` c on a.`user_id` = c.`id`';
		}
		else if($filter == 'team')
		{
			$query	.= 'SELECT a.*, b.`title` as `bname`, c.`name`, c.`username`';
			$query	.= '  FROM `#__easyblog_team_subscription` a';
			$query	.= '    left join `#__easyblog_team` b on a.`team_id` = b.`id`';
			$query	.= '    left join `#__users` c on a.`user_id` = c.`id`';
		}
		else
		{
			$query	.= 'SELECT a.*, b.`name` as `bname`, b.`username` as `busername`, c.`name`, c.`username`';
			$query	.= '  FROM `#__easyblog_blogger_subscription` a';
			$query	.= '    left join `#__users` b on a.`blogger_id` = b.`id`';
			$query	.= '    left join `#__users` c on a.`user_id` = c.`id`';
		}

		$query	.= $where;
		$query	.= $orderby;
		
		//echo $query . '<br>';

		return $query;
	}
	
	function _buildQueryWhere()
	{
		$mainframe	=& JFactory::getApplication();
		$db			=& $this->getDBO();
		
		//$filter     = JRequest::getVar('filter', 'blogger', 'REQUEST');
		$filter		= $mainframe->getUserStateFromRequest( 'com_easyblog.subscriptions.filter', 'filter', 'blogger', 'word' );

		$search 	= $mainframe->getUserStateFromRequest( 'com_easyblog.subscriptions.search', 'search', '', 'string' );
		$search 	= $db->getEscaped( trim(JString::strtolower( $search ) ) );

		$where = array();

		if ($search)
		{
			$where[] = ' LOWER( a.`email` ) LIKE \'%' . $search . '%\'';
			
			if($filter == 'blogger')
				$where[] = ' LOWER( b.`name` ) LIKE \'%' . $search . '%\'';
			else if( $filter != 'site')
				$where[] = ' LOWER( b.`title` ) LIKE \'%' . $search . '%\'';
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' OR ', $where ) : '' );

		return $where;
	}

	function _buildQueryOrderBy()
	{
		$mainframe			=& JFactory::getApplication();

		$filter_order		= $mainframe->getUserStateFromRequest( 'com_easyblog.subscriptions.filter_order', 		'filter_order', 	'bname', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( 'com_easyblog.subscriptions.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir . ', a.`email`';

		return $orderby;
	}
	
	/**
	 * Method to get the total number of records
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}
	

	/**
	 * Method to get a pagination object
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}
	
    function getSiteSubscribers()
    {
        $db =& JFactory::getDBO();

        $query  = "SELECT *, 'sitesubscription' as `type` FROM `#__easyblog_site_subscription`";

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
    }
	
		
}