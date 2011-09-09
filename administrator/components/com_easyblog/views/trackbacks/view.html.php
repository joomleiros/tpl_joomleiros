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

class EasyBlogViewTrackbacks extends JView 
{
	function display($tpl = null)
	{
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();
		
		$filter_state 	= $mainframe->getUserStateFromRequest( 'com_easyblog.trackbacks.filter_state', 	'filter_state', 	'*', 'word' );
		$search 		= $mainframe->getUserStateFromRequest( 'com_easyblog.trackbacks.search', 			'search', 			'', 'string' );
		
		$search 		= trim(JString::strtolower( $search ) );
		$order			= $mainframe->getUserStateFromRequest( 'com_easyblog.trackbacks.filter_order', 		'filter_order', 	'created', 'cmd' );
		$orderDirection	= $mainframe->getUserStateFromRequest( 'com_easyblog.trackbacks.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		//Get data from the model
		$trackbacks		=& $this->get( 'Data' );
		$pagination 	=& $this->get( 'Pagination' );
		
		$this->assignRef( 'trackbacks'	, $trackbacks );
		$this->assignRef( 'pagination'	, $pagination );
		$this->assign( 'state'			, $this->getFilterState($filter_state));
		$this->assign( 'search'			, $search );
		$this->assign( 'order'			, $order );
		$this->assign( 'orderDirection'	, $orderDirection );

		parent::display($tpl);
	}

	function registerToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_EASYBLOG_TRACKBACKS_TITLE' ), 'trackbacks' );
		
		JToolBarHelper::back( 'Home' , 'index.php?option=com_easyblog');
		JToolBarHelper::divider();
		JToolbarHelper::publishList();
		JToolbarHelper::unpublishList();
		JToolBarHelper::divider();
		JToolbarHelper::deleteList();
		
	}
	
	function getFilterState ($filter_state='*')
	{
        $state[] = JHTML::_('select.option',  '', '- '. JText::_( 'COM_EASYBLOG_SELECT_STATE' ) .' -' );
        $state[] = JHTML::_('select.option',  'P', JText::_( 'COM_EASYBLOG_PUBLISHED' ) );
        $state[] = JHTML::_('select.option',  'U', JText::_( 'COM_EASYBLOG_UNPUBLISHED' ) );
        $state[] = JHTML::_('select.option',  'M', JText::_( 'COM_EASYBLOG_MODERATE' ) );
        
        return JHTML::_('select.genericlist',   $state, 'filter_state', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_state );
	}
}