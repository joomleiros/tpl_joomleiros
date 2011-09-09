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

class EasyBlogModelTrackbackSent extends JModel
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

		$limit			= $mainframe->getUserStateFromRequest( 'com_easyblog.trackbacks.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart		= JRequest::getVar('limitstart', 0, '', 'int');

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function getSentTrackbacks( $id, $sent = false )
	{
		$db			=& $this->getDBO();
		$query		= 'SELECT * FROM #__easyblog_trackback_sent '
					. 'WHERE `post_id`=' . $db->Quote( $id );

		if ( $sent ) {
			$query .= ' AND `sent` = 0';
		}			
				
		$db->setQuery( $query );
		return $db->loadObjectList();
	}

	function _buildQueryByBlogger($bloggerId)
	{
		$db			=& $this->getDBO();

	    $query	= 	'select a.`id`, a.`title`, a.`alias`, a.`created`, count(b.`id`) as `post_count`';
		$query	.=  ' from #__easyblog_category as a';
		$query	.=  '    left join #__easyblog_post as b';
		$query	.=  '    on a.`id` = b.`category_id`';
		$query	.=  ' where a.created_by = ' . $db->Quote($bloggerId);
		$query	.=  ' group by (a.`id`)';
		return $query;
	}


	function getCategoriesByBlogger($bloggerId)
	{
	    $db =& JFactory::getDBO();
	    
	    $query  = $this->_buildQueryByBlogger($bloggerId);
	    $db->setQuery($query);
	    
	    $result = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
	    
	    return $result;
	}
	
	function getPaginationByBlogger($bloggerId)
	{
		jimport('joomla.html.pagination');
		$this->_pagination = new JPagination( $this->getTotalByBlogger($bloggerId), $this->getState('limitstart'), $this->getState('limit') );
		
		return $this->_pagination;
	}
	
	function getTotalByBlogger($bloggerId)
	{
		// Lets load the content if it doesn't already exist
		$query = $this->_buildQueryByBlogger($bloggerId);
		$total = $this->_getListCount($query);

		return $total;
	}
	
}