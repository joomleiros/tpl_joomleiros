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

class EasyBlogViewCategories extends JView 
{
	function display($tpl = null)
	{
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();
		
		$filter_state 	= $mainframe->getUserStateFromRequest( 'com_easyblog.categories.filter_state', 		'filter_state', 	'*', 'word' );
		$search 		= $mainframe->getUserStateFromRequest( 'com_easyblog.categories.search', 			'search', 			'', 'string' );
		
		$search 		= trim(JString::strtolower( $search ) );
		$order			= $mainframe->getUserStateFromRequest( 'com_easyblog.categories.filter_order', 		'filter_order', 	'ordering', 'cmd' );
		$orderDirection	= $mainframe->getUserStateFromRequest( 'com_easyblog.categories.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		//Get data from the model
		$categories			=& $this->get( 'Data' );
		$model				=& $this->getModel();

		JTable::addIncludePath( EBLOG_TABLES );
		$category			= JTable::getInstance( 'ECategory' , 'Table' );
		$category->reorder();
		
		for( $i = 0 ; $i < count( $categories ); $i++ )
		{
			$category			=& $categories[ $i ];
			
			$category->count	= $model->getUsedCount( $category->id );
			$category->child_count	= $model->getChildCount( $category->id );
		}
		$pagination 	=& $this->get( 'Pagination' );
		
		$this->assignRef( 'categories' 	, $categories );
		$this->assignRef( 'pagination'	, $pagination );
		$this->assign( 'state'			, JHTML::_('grid.state', $filter_state ) );
		$this->assign( 'search'			, $search );
		$this->assign( 'order'			, $order );
		$this->assign( 'orderDirection'	, $orderDirection );
		
		parent::display($tpl);
	}

	function registerToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_EASYBLOG_CATEGORIES_TITLE' ), 'category' );
		
		JToolBarHelper::back();
		JToolBarHelper::divider();
		JToolbarHelper::publishList();
		JToolbarHelper::unpublishList();
		JToolBarHelper::divider();
		JToolbarHelper::addNew();
		JToolbarHelper::deleteList();
		
	}
}