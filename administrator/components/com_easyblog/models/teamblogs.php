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

class EasyBlogModelTeamBlogs extends JModel
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

		$limit		= $mainframe->getUserStateFromRequest( 'com_easyblog.teamblogs.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		//$limitstart = $mainframe->getUserStateFromRequest( 'com_easyblog.teamblogs.limitstart', 'limitstart', 0, 'int' );
		$limitstart		= JRequest::getVar('limitstart', 0, '', 'int');

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Method to get the total nr of the categories
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
	 * Method to get a pagination object for the categories
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

	/**
	 * Method to build the query for the tags
	 *
	 * @access private
	 * @return string
	 */
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildQueryWhere();
		$orderby	= $this->_buildQueryOrderBy();
		
		$db			=& $this->getDBO();
		
		$query	= 'SELECT * FROM ' . $db->nameQuote( '#__easyblog_team' ) . ' AS a'
				. $where . ' '
				. $orderby;

		return $query;
	}

	function _buildQueryWhere()
	{
		$mainframe			=& JFactory::getApplication();
		$db					=& $this->getDBO();
		
		$filter_state 		= $mainframe->getUserStateFromRequest( 'com_easyblog.teamblogs.filter_state', 'filter_state', '', 'word' );
		$search 			= $mainframe->getUserStateFromRequest( 'com_easyblog.teamblogs.search', 'search', '', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );

		$where = array();

		if ( $filter_state )
		{
			if ( $filter_state == 'P' )
			{
				$where[] = 'a.' . $db->nameQuote( 'published' ) . '=' . $db->Quote( '1' );
			}
			else if ($filter_state == 'U' )
			{
				$where[] = 'a.' . $db->nameQuote( 'published' ) . '=' . $db->Quote( '0' );
			}
		}

		if ($search)
		{
			$where[] = ' LOWER( a.`title` ) LIKE \'%' . $search . '%\' ';
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}

	function _buildQueryOrderBy()
	{
		$mainframe			=& JFactory::getApplication();

		$filter_order		= $mainframe->getUserStateFromRequest( 'com_easyblog.teamblogs.filter_order', 		'filter_order', 	'a.created DESC', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( 'com_easyblog.teamblogs.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

		return $orderby;
	}

	/**
	 * Method to get categories item data
	 *
	 * @access public
	 * @return array
	 */
	function getTeams()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query	= $this->_buildQuery();
			$pg		= $this->getPagination();
			
			//pr($query);
			
			$this->_data	= $this->_getList($query, $pg->limitstart, $pg->limit);
		}

		return $this->_data;
	}
	

	function getTeamJoined( $userId )
	{
	    $db =& $this->getDBO();

	    $query  = 'SELECT a.`team_id`, b.`title`, \'0\' AS `selected`';
		$query	.= ' FROM `#__easyblog_team_users`  AS `a`';
		$query  .= ' LEFT JOIN `#__easyblog_team` AS `b` ON a.`team_id` = b.`id`';
		$query	.= ' WHERE a.`user_id` = ' . $db->Quote($userId);

		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		//$result	= $db->loadAssocList();

		return $result;
	}

	function getBlogContributed( $postId )
	{
	    $db =& $this->getDBO();

	    $query  = 'SELECT a.`team_id`, b.`title`, \'1\' AS `selected`';
		$query	.= ' FROM `#__easyblog_team_post`  AS `a`';
		$query  .= ' LEFT JOIN `#__easyblog_team` AS `b` ON a.`team_id` = b.`id`';
		$query	.= ' WHERE a.`post_id` = ' . $db->Quote($postId);

		$db->setQuery( $query );
		$result	= $db->loadObject();
		//$result	= $db->loadAssocList();

		return $result;
	}
	
	function getTeamSubscribers($teamId)
    {
        $db =& JFactory::getDBO();

        $query  = "SELECT *, 'teamsubscription' as `type` FROM `#__easyblog_team_subscription`";
        $query  .= " WHERE `team_id` = " . $db->Quote($teamId);

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
    }
}