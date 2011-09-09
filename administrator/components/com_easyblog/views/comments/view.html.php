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

class EasyBlogViewComments extends JView 
{
	function display($tpl = null)
	{
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();
		
		$filter_state 	= $mainframe->getUserStateFromRequest( 'com_easyblog.comments.filter_state', 	'filter_state', 	'*', 'word' );
		$search 		= $mainframe->getUserStateFromRequest( 'com_easyblog.comments.search', 			'search', 			'', 'string' );
		
		$search 		= trim(JString::strtolower( $search ) );
		$order			= $mainframe->getUserStateFromRequest( 'com_easyblog.comments.filter_order', 		'filter_order', 	'ordering', 'cmd' );
		$orderDirection	= $mainframe->getUserStateFromRequest( 'com_easyblog.comments.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		//Get data from the model
		$comments		=& $this->get( 'Data' );
		$pagination 	=& $this->get( 'Pagination' );
		
		//convert the status.
		if(count($comments) > 0)
		{
		    for($i = 0; $i < count($comments); $i++)
		    {
		        $item   =& $comments[$i];
		        
		        if($item->published == '2')
		        {
		            $item->published    = '0';
		        }
		    }
		}
		
		$this->assignRef( 'comments'		, $comments );
		$this->assignRef( 'pagination'	, $pagination );
		$this->assign( 'state'			, $this->getFilterState($filter_state));
		$this->assign( 'search'			, $search );
		$this->assign( 'order'			, $order );
		$this->assign( 'orderDirection'	, $orderDirection );

		parent::display($tpl);
	}

	function registerToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_EASYBLOG_COMMENTS_TITLE' ), 'comments' );
		
		JToolBarHelper::back( 'Home' , 'index.php?option=com_easyblog');
		JToolBarHelper::divider();
		JToolbarHelper::publishList();
		JToolbarHelper::unpublishList();
		JToolBarHelper::divider();
		JToolbarHelper::deleteList();
		
	}
	
	function getFilterState ($filter_state='*')
	{
        $state[] = JHTML::_('select.option',  '', '- '. JText::_( 'Select State' ) .' -' );
        $state[] = JHTML::_('select.option',  'P', JText::_( 'COM_EASYBLOG_PUBLISHED' ) );
        $state[] = JHTML::_('select.option',  'U', JText::_( 'COM_EASYBLOG_UNPUBLISHED' ) );
        $state[] = JHTML::_('select.option',  'M', JText::_( 'COM_EASYBLOG_MODERATE' ) );
        
        return JHTML::_('select.genericlist',   $state, 'filter_state', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_state );
	}
}