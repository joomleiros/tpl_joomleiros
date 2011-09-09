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

class EasyBlogModelMetas extends JModel
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

		$limit		= $mainframe->getUserStateFromRequest( 'com_easyblog.meta.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Method to get the total nr of the categories
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal( $type = '' )
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery( $type );
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
	function getPagination( $type = '' )
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal( $type ), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Method to build the query for the tags
	 *
	 * @access private
	 * @return string
	 */
	function _buildQuery( $type = '' )
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildQueryWhere( $type );
		$orderby	= $this->_buildQueryOrderBy();
		$db			=& $this->getDBO();

		$query	= 'SELECT * FROM (';
		
		if($type == 'post' || $type == '')
		{
			$query	.= ' (SELECT m.*, p.title AS title';
			$query	.= '  FROM ' . $db->nameQuote( '#__easyblog_meta' ) . ' AS m';
			$query	.= '  LEFT JOIN ' . $db->nameQuote( '#__easyblog_post' ) . ' AS p';
			$query	.= '  ON m.content_id = p.id';
			$query	.= '  WHERE m.`type` = ' . $db->Quote( 'post' ) . ')';
		}
		
		if(! empty($query) && $type == '')
			$query	.= ' UNION ';
		
		if($type == 'team' || $type == '')
		{
			$query	.= '  (SELECT m.*, p.title AS title';
			$query	.= '   FROM ' . $db->nameQuote( '#__easyblog_meta' ) . ' AS m';
			$query	.= '   LEFT JOIN ' . $db->nameQuote( '#__easyblog_team' ) . ' AS p';
			$query	.= '   ON m.content_id = p.id';
			$query	.= '   WHERE m.`type` = ' . $db->Quote( 'team' ) . ')';
		}
		
		if(! empty($query) && $type == '')
			$query	.= ' UNION ';
		
		if($type == 'blogger' || $type == '')
		{
			$query	.= '  (SELECT m.*, p.name AS title';
			$query	.= '   FROM ' . $db->nameQuote( '#__easyblog_meta' ) . ' AS m';
			$query	.= '   LEFT JOIN ' . $db->nameQuote( '#__users' ) . ' AS p';
			$query	.= '   ON m.content_id = p.id';
			$query	.= '   WHERE m.`type` = ' . $db->Quote( 'blogger' ) . ')';
		}
		
		if(! empty($query) && $type == '')
			$query	.= ' UNION ';
		
		if($type == 'view' || $type == '')
		{
			$query	.= '  (SELECT m.*, '. $db->Quote('') . ' AS title';
			$query	.= '   FROM ' . $db->nameQuote( '#__easyblog_meta' ) . ' AS m';
			$query	.= '   WHERE m.`type` = ' . $db->Quote( 'view' ) . ')';
		}
		
		
		$query	.= ') AS x ';

		$query	.= $where;
		$query	.= $orderby;
				  
		//echo $query;exit;		  

		return $query;
	}

	function _buildQueryWhere( $type = '' )
	{
		$mainframe			=& JFactory::getApplication();
		$db					=& $this->getDBO();
		
		$filter_state 		= $mainframe->getUserStateFromRequest( 'com_easyblog.meta.filter_state', 'filter_state', '', 'word' );
		$search 			= $mainframe->getUserStateFromRequest( 'com_easyblog.meta.search', 'search', '', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );

		$where = array();

		if ($search)
		{
			$where[] = ' LOWER( x.title ) LIKE \'%' . $search . '%\' ';
		}
		
// 		if ( !empty( $type ) )
// 		{
// 			if ( $type == 'view' )
// 			{
// 				//$where[] = 'm.`id` = ' . $db->quote($cid);
// 				$where[] = 'x.`type` = '.$db->quote($type);
// 			}
// 			else if( $type == 'post' )
// 			{
// 				//$where[] = 'm.`id` = '.$db->quote($cid);
// 				$where[] = 'x.`type` = '.$db->quote($type);
// 			}
// 			else if( $type == 'blogger' )
// 			{
// 				//$where[] = 'm.`id` = '.$db->quote($cid);
// 				$where[] = 'x.`type` = '.$db->quote($type);
// 			}
// 		}
		
		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}

	function _buildQueryOrderBy()
	{
		$mainframe			=& JFactory::getApplication();

		$filter_order		= $mainframe->getUserStateFromRequest( 'com_easyblog.meta.filter_order', 		'filter_order', 	'x.id', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( 'com_easyblog.meta.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.'';

		return $orderby;
	}

	/**
	 * Method to get categories item data
	 *
	 * @access public
	 * @return array
	 */
	function getData( $type = '', $usePagination = true )
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery( $type );
			if($usePagination)
			{
				$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			} else {
			    $this->_data = $this->_getList($query);
			}
		}
		
		return $this->_data;
	}
	
	function getPostMeta( $id )
	{
		return $this->getMetaInfo(META_TYPE_POST, $id);
	}
	
	function getMetaInfo( $type, $id )
	{
		$db	=& JFactory::getDBO();
		$query 	= 'SELECT id, keywords, description FROM #__easyblog_meta';
		$query	.= ' WHERE `content_id` = ' . $db->Quote($id);
		$query	.= ' AND `type` = ' . $db->Quote($type);
		$query	.= ' ORDER BY `id` DESC';
		$query	.= ' LIMIT 1';
		
		$db->setQuery($query);
		$result = $db->loadObject();

		if ( ! isset($result->id) )
		{
			$obj	= new stdClass();
			$obj->id			= '';
			$obj->keywords		= '';
			$obj->description 	= '';

			return $obj;
		}
		return $result;
	}
}