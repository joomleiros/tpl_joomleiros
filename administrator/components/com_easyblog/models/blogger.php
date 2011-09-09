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

class EasyBlogModelBlogger extends JModel
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
				
		$limit		= ($mainframe->getCfg('list_limit') == 0) ? 5 : $mainframe->getCfg('list_limit');				
	    $limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');
	    
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);		

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	
	/**
	 * Method to get a pagination object for the categories
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination()
	{		
		return $this->_pagination;
	}
	
	function getBloggers($sort = 'latest', $limit = 0, $filter='showallblogger')
	{
		$db	=& $this->getDBO();
		
  		require_once( EBLOG_HELPERS . DS . 'helper.php' );
  		$config  			=& EasyBlogHelper::getConfig();  
		$nameDisplayFormat	= $config->get('layout_nameformat');		
		
		$limit		= ($limit == 0) ? $this->getState('limit') : $limit;
		$limitstart = $this->getState('limitstart');
		$limitSQL	= ' LIMIT ' . $limitstart . ',' . $limit;	
				
// 		$query	= 'SELECT COUNT(1)';
// 		$query	.= ' FROM ' . $db->nameQuote( '#__users' ) . ' AS `a`';
// 		$query	.= ' INNER JOIN '.$db->nameQuote( '#__easyblog_users' ) . ' AS `b`';
// 		$query	.= ' ON a.`id` = b.`id` ';
		
		$query	= 'SELECT COUNT(1) FROM (';
		$query	.= ' (SELECT a.`id`';
		$query	.= ' FROM `#__users` AS `a`';
		$query	.= '  INNER JOIN `#__easyblog_users` AS `b` ON a.`id` = b.`id`';
		$query	.= '  INNER JOIN `#__core_acl_aro` AS `c` ON a.`id` = c.`value`';
		$query	.= '    AND c.`section_value` = ' . $db->Quote('users');
		$query	.= '  INNER JOIN `#__core_acl_groups_aro_map` AS `d` ON c.`id` = d.`aro_id`';
		$query	.= '  INNER JOIN `#__easyblog_acl_group` AS `e` ON d.`group_id`  = e.`content_id`';
		$query	.= '    AND e.`type` = ' . $db->Quote('group') . ' AND e.`status` = 1';
		$query	.= '  INNER JOIN `#__easyblog_acl` as `f` ON e.`acl_id` = f.`id`';
		$query	.= '    AND f.`action` = ' . $db->Quote('add_entry');
		
		$query 	.= '  LEFT JOIN `#__easyblog_post` AS `p` ON a.`id` = p.`created_by`';
		$query 	.= '  GROUP BY a.`id`';
		if($filter == 'showbloggerwithpost')
			$query 	.= '  HAVING (COUNT(p.`id`) > 0)';
		$query 	.= ' )';
		
		$query	.= ' UNION ';
		$query	.= ' (SELECT a1.`id`';
		$query	.= ' FROM `#__users` AS `a1`';
		$query	.= '  INNER JOIN `#__easyblog_users` AS `b1` ON a1.`id` = b1.`id`';
		$query	.= '  INNER JOIN `#__easyblog_acl_group` AS `c1` ON a1.`id`  = c1.`content_id`';
		$query	.= '    AND c1.`type` = ' . $db->Quote('assigned') . ' AND c1.`status` = 1';
		$query	.= '  INNER JOIN `#__easyblog_acl` as `d1` ON c1.`acl_id` = d1.`id`';
		$query	.= '    AND d1.`action` = ' . $db->Quote('add_entry');
		
		$query 	.= '  LEFT JOIN `#__easyblog_post` AS `p1` ON a1.`id` = p1.`created_by`';
		$query 	.= '  GROUP BY a1.`id`';
		if($filter == 'showbloggerwithpost')
			$query 	.= '  HAVING (COUNT(p1.`id`) > 0)';
		$query 	.= ' )';
		
		$query  .= ' ) as x';

		
		//echo $query;
		//exit;
		
		$db->setQuery( $query );		
		$this->_total	= $db->loadResult();		

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
	    }
	    
		if( empty($this->_pagination) )
		{
			jimport('joomla.html.pagination');
			$this->_pagination	= new JPagination( $this->_total , $limitstart , $limit);
		}
				
// 		$query	= 'SELECT a.`id`, b.`nickname`, b.`avatar`, b.`description`,';
// 		$query	.= ' a.`name`, a.`username`, a.`registerDate`, a.`lastvisitDate`';
// 		$query	.= ' FROM ' . $db->nameQuote( '#__users' ) . ' AS `a`';
// 		$query	.= ' INNER JOIN '.$db->nameQuote( '#__easyblog_users' ) . ' AS `b`';
// 		$query	.= ' ON a.`id` = b.`id`';
		
		$query	= 'SELECT x.* FROM (';
		$query	.= ' (SELECT a.`id`, b.`nickname`, b.`avatar`, b.`description`,';
		$query	.= ' a.`name`, a.`username`, a.`registerDate`, a.`lastvisitDate`,';
		$query  .= ' COUNT(p.`id`) as `totalPost`, MAX(p.`created`) as `latestPostDate`';
		$query	.= ' FROM `#__users` AS `a`';
		$query	.= '  INNER JOIN `#__easyblog_users` AS `b` ON a.`id` = b.`id`';
		$query	.= '  INNER JOIN `#__core_acl_aro` AS `c` ON a.`id` = c.`value`';
		$query	.= '    AND c.`section_value` = ' . $db->Quote('users');
		$query	.= '  INNER JOIN `#__core_acl_groups_aro_map` AS `d` ON c.`id` = d.`aro_id`';
		$query	.= '  INNER JOIN `#__easyblog_acl_group` AS `e` ON d.`group_id`  = e.`content_id`';
		$query	.= '    AND e.`type` = ' . $db->Quote('group') . ' AND e.`status` = 1';
		$query	.= '  INNER JOIN `#__easyblog_acl` as `f` ON e.`acl_id` = f.`id`';
		$query	.= '    AND f.`action` = ' . $db->Quote('add_entry');
		
		$query 	.= '  LEFT JOIN `#__easyblog_post` AS `p` ON a.`id` = p.`created_by`';
		$query 	.= '  GROUP BY a.`id`';
		if($filter == 'showbloggerwithpost')
			$query 	.= '  HAVING (COUNT(p.`id`) > 0)';
		$query 	.= ' )';
		
		$query	.= ' UNION ';
		$query	.= ' (SELECT a1.`id`, b1.`nickname`, b1.`avatar`, b1.`description`,';
		$query	.= ' a1.`name`, a1.`username`, a1.`registerDate`, a1.`lastvisitDate`,';
		$query  .= ' COUNT(p1.`id`) as `totalPost`, MAX(p1.`created`) as `latestPostDate`';
		$query	.= ' FROM `#__users` AS `a1`';
		$query	.= '  INNER JOIN `#__easyblog_users` AS `b1` ON a1.`id` = b1.`id`';
		$query	.= '  INNER JOIN `#__easyblog_acl_group` AS `c1` ON a1.`id`  = c1.`content_id`';
		$query	.= '    AND c1.`type` = ' . $db->Quote('assigned') . ' AND c1.`status` = 1';
		$query	.= '  INNER JOIN `#__easyblog_acl` as `d1` ON c1.`acl_id` = d1.`id`';
		$query	.= '    AND d1.`action` = ' . $db->Quote('add_entry');
		
		$query 	.= '  LEFT JOIN `#__easyblog_post` AS `p1` ON a1.`id` = p1.`created_by`';
		$query 	.= '  GROUP BY a1.`id`';
		if($filter == 'showbloggerwithpost')
			$query 	.= '  HAVING (COUNT(p1.`id`) > 0)';
		$query 	.= ' )';
		
		$query  .= ' ) as x';
		
		switch($sort)
		{
			case 'latestpost' :
				$query .= '	ORDER BY x.`latestPostDate` DESC';
				break;
			case 'latest' :
				$query .= '	ORDER BY x.`registerDate` DESC';
				break;
			case 'active' :
				$query	.= ' ORDER BY x.`lastvisitDate` DESC';
				break;
			case 'alphabet' :
				if($nameDisplayFormat == 'name')
					$query .= '	ORDER BY x.`name` ASC';
				else if($nameDisplayFormat == 'username')
					$query .= '	ORDER BY x.`username` ASC';
				else
					$query .= '	ORDER BY x.`nickname` ASC';
				break;								
			default	:				
				break;
		}
		$query	.= 	$limitSQL;
		
		//echo $query;
		
		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
	    }
	    
		return $result;					
		
	}
	
    function isBloggerSubscribedUser($bloggerId, $userId)
    {
		$db	=& $this->getDBO();

        $query  = 'SELECT `id` FROM `#__easyblog_blogger_subscription`';
        $query  .= ' WHERE `blogger_id` = ' . $db->Quote($bloggerId);
        $query  .= ' AND `user_id` = ' . $db->Quote($userId);

        $db->setQuery($query);
        $result = $db->loadResult();

        return $result;
    }

    function isBloggerSubscribedEmail($bloggerId, $email)
    {
		$db	=& $this->getDBO();

        $query  = 'SELECT `id` FROM `#__easyblog_blogger_subscription`';
        $query  .= ' WHERE `blogger_id` = ' . $db->Quote($bloggerId);
        $query  .= ' AND `email` = ' . $db->Quote($email);

        $db->setQuery($query);
        $result = $db->loadResult();

        return $result;
    }
    
    function addBloggerSubscription($bloggerId, $email, $userId = '0')
    {
    	$config =& EasyBlogHelper::getConfig();
    	$acl = EasyBlogACLHelper::getRuleSet();
		$my = JFactory::getUser();
			
		if($acl->rules->allow_subscription || (empty($my->id) && $config->get('main_allowguestsubscribe')))
		{
			$date       =& JFactory::getDate();
			$subscriber =& JTable::getInstance( 'BloggerSubscription', 'Table' );
	
	        $subscriber->blogger_id = $bloggerId;
	        $subscriber->email    	= $email;
	        if($userId != '0')
	            $subscriber->user_id    = $userId;
	
	        $subscriber->created  = $date->toMySQL();
	        $subscriber->store();
		}
    }

    function updateBloggerSubscriptionEmail($sid, $email)
    {
    	$config =& EasyBlogHelper::getConfig();
    	$acl = EasyBlogACLHelper::getRuleSet();
    	$my = JFactory::getUser();
    	
		if($acl->rules->allow_subscription || (empty($my->id) && $config->get('main_allowguestsubscribe')))
		{
			$subscriber =& JTable::getInstance( 'BloggerSubscription', 'Table' );
			$subscriber->load($sid);
			$subscriber->email    = $email;
			$subscriber->store();
		}
    }
    
    function getBlogggerSubscribers($bloggerId)
    {
        $db =& JFactory::getDBO();
        
        $query  = "SELECT *. 'bloggersubscription' as `type` FROM `#__easyblog_blogger_subscription`";
        $query  .= " WHERE `blogger_id` = " . $db->Quote($bloggerId);
        
        //echo $query . '<br/><br/>';

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
    }
		
}