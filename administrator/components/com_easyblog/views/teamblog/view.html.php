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
require( EBLOG_ADMIN_ROOT . DS . 'views.php');

class EasyBlogViewTeamblog extends EasyBlogAdminView 
{
	var $team	= null;
	
	function display($tpl = null)
	{
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();
		
		$id			= JRequest::getInt( 'id' );
		JHTML::_('behavior.modal' , 'a.modal' );
		
		$document	=& JFactory::getDocument();
		$document->addStyleSheet( JURI::root() . 'components/com_easyblog/assets/css/common.css' );
		$document->addScript( JURI::root() . 'components/com_easyblog/assets/js/ej.js' );
		$document->addScript( JURI::root() . 'components/com_easyblog/assets/js/ejax.js' );
		
		$team	=& JTable::getInstance( 'TeamBlog' , 'Table' );
		$team->load( $id );
		$this->team	= $team;
		
		$blogAccess 	= array();
		$blogAccess[]	= JHTML::_('select.option', '1', JText::_( 'COM_EASYBLOG_TEAM_MEMBER_ONLY' ) );
		$blogAccess[]	= JHTML::_('select.option', '2', JText::_( 'COM_EASYBLOG_ALL_REGISTERED_USERS' ) );
		$blogAccess[]	= JHTML::_('select.option', '3', JText::_( 'COM_EASYBLOG_EVERYONE' ) );

		$blogAccessList = JHTML::_('select.genericlist', $blogAccess, 'access', 'size="1" class="inputbox"', 'value', 'text', $team->access );
		
		$config		= EasyBlogHelper::getConfig();
		$editor		=& JFactory::getEditor( $config->get('layout_editor') );

		// get meta tags
		$metaModel		=& $this->getModel('Metas');
		$meta 			= $metaModel->getMetaInfo(META_TYPE_TEAM, $id);	
		
		$this->assignRef( 'joomlaversion' , EasyBlogHelper::getJoomlaVersion() );
		$this->assignRef( 'editor'	, $editor );
		$this->assignRef( 'team' 	, $team );
		$this->assignRef( 'meta' 	, $meta );
		$this->assignRef( 'config'	, $config );
		$this->assignRef( 'blogAccessList' , $blogAccessList );
		
		parent::display($tpl);
	}

	function getMembers( $teamId )
	{
		if( $teamId == 0 )
			return;
			
		$db		=& JFactory::getDBO();
		
		$query	= 'SELECT * FROM #__easyblog_team_users '
				. 'WHERE `team_id`=' . $db->Quote( $teamId );
		$db->setQuery( $query );
		$members	= $db->loadObjectList();

		return $members;
	}
	
	function getPostCount( $id )
	{
		$db	=& JFactory::getDBO();
		
		$query	= 'SELECT COUNT(1) FROM #__easyblog_post '
				. 'WHERE `created_by`=' . $db->Quote( $id );
		$db->setQuery( $query );
		
		return $db->loadResult();
	}
	
	function registerToolbar()
	{
		if( $this->team->id != 0 )
			JToolBarHelper::title( JText::sprintf( 'COM_EASYBLOG_EDITING_TEAM' , $this->team->title ), 'teamblogs' );
		else
			JToolBarHelper::title( JText::_( 'COM_EASYBLOG_CREATE_NEW_TEAM' ), 'teamblogs' );
			
		JToolBarHelper::back();
		JToolBarHelper::divider();
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();	
	}
	
	function registerSubmenu()
	{
		return 'submenu.php';
	}	
}