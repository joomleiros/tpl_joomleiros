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

jimport( 'joomla.application.component.view');

class EasyBlogViewTeamblogs extends JView 
{
	function display($tpl = null)
	{
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();
		
 		$filter_state 	= $mainframe->getUserStateFromRequest( 'com_easyblog.teamblogs.filter_state', 		'filter_state', 	'*', 'word' );
 		$search 		= $mainframe->getUserStateFromRequest( 'com_easyblog.teamblogs.search', 			'search', 			'', 'string' );
 		
		$search 		= trim(JString::strtolower( $search ) );
 		$order			= $mainframe->getUserStateFromRequest( 'com_easyblog.teamblogs.filter_order', 		'filter_order', 	'a.id', 'cmd' );
 		$orderDirection	= $mainframe->getUserStateFromRequest( 'com_easyblog.teamblogs.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
 
 		//Get data from the model
 		$teams			=& $this->get( 'Teams' );
 		$pagination 	=& $this->get( 'Pagination' );

 		$this->assignRef( 'teams' 		, $teams );
 		$this->assignRef( 'pagination'	, $pagination );
 		$this->assign( 'state'			, JHTML::_('grid.state', $filter_state ) );
 		$this->assign( 'search'			, $search );
 		$this->assign( 'order'			, $order );
 		$this->assign( 'orderDirection'	, $orderDirection );
		
		parent::display($tpl);
	}
	
	function getMembersCount( $teamId )
	{
		$db	=& JFactory::getDBO();
		
		$query	= 'SELECT COUNT(1) FROM #__easyblog_team_users '
				. 'WHERE `team_id`=' . $db->Quote( $teamId );
		$db->setQuery( $query );
		
		return $db->loadResult();
	}
	
	function getPostCount( $id )
	{
		$db	=& JFactory::getDBO();
		
		$query	= 'SELECT COUNT(1) FROM #__easyblog_post '
				. 'WHERE `created_by`=' . $db->Quote( $id );
		$db->setQuery( $query );
		
		return $db->loadResult();
	}
	
	public function getAccessHTML( $access )
	{
		if( $access == '1' )
		{
			return JText::_('COM_EASYBLOG_TEAM_MEMBER_ONLY');
		}

		if( $access == '2')
		{
		    return JText::_('COM_EASYBLOG_ALL_REGISTERED_USERS');
		}
		
		return JText::_('COM_EASYBLOG_EVERYONE');
	}

	function registerToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_EASYBLOG_TEAMBLOGS_TITLE' ), 'teamblogs' );
		
		JToolBarHelper::back();
		JToolBarHelper::divider();
		JToolbarHelper::publishList();
		JToolbarHelper::unpublishList();
		JToolBarHelper::divider();
		JToolbarHelper::addNew();
		JToolbarHelper::deleteList();	
	}
}