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
require_once( EBLOG_ADMIN_ROOT . DS . 'views.php');

class EasyBlogViewPending extends EasyBlogAdminView
{
	function display($tpl = null)
	{
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();

 		$search 			= $mainframe->getUserStateFromRequest( 'com_easyblog.blogs.search', 			'search', 			'', 'string' );
 		$filter_category 	= $mainframe->getUserStateFromRequest( 'com_easyblog.blogs.filter_category', 	'filter_category', 	'*', 'int' );
 		
		$search 		= trim(JString::strtolower( $search ) );
 		$order			= $mainframe->getUserStateFromRequest( 'com_easyblog.blogs.filter_order', 		'filter_order', 	'ordering', 'cmd' );
 		$orderDirection	= $mainframe->getUserStateFromRequest( 'com_easyblog.blogs.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
 
 		//Get data from the model
 		$blogs			=& $this->get( 'Blogs' );
 		$pagination 	=& $this->get( 'Pagination' );
 		
		$catFilter      = $this->getFilterCategory( $filter_category );
 		
 		$document->setTitle( JText::_('COM_EASYBLOG_PENDING_PAGE_TITLE') );	
 		
 		$this->assignRef( 'blogs' 		, $blogs );
 		$this->assignRef( 'pagination'	, $pagination );
 		$this->assign( 'category'		, $catFilter );
 		$this->assign( 'search'			, $search );
 		$this->assign( 'order'			, $order );
 		$this->assign( 'orderDirection'	, $orderDirection );
		
		parent::display($tpl);
	}
	
	function getFilterCategory($filter_type = '*')
	{
		$filter[] = JHTML::_('select.option', '', '- '. JText::_( 'Select Category' ) .' -' );
		
 		$model 		=& $this->getModel( 'Categories' );
 		$categories = $model->getAllCategories();
 		
 		foreach($categories as $cat)
 		{
 		    $filter[] = JHTML::_('select.option', $cat->id, $cat->title );
 		}

		return JHTML::_('select.genericlist', $filter, 'filter_category', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_type );
	}
	
	function getFilterState ($filter_state='*')
	{
        $state[] = JHTML::_('select.option',  '', '- '. JText::_( 'Select State' ) .' -' );
        $state[] = JHTML::_('select.option',  'P', JText::_( 'Published' ) );
        $state[] = JHTML::_('select.option',  'U', JText::_( 'Unpublished' ) );
        $state[] = JHTML::_('select.option',  'S', JText::_( 'Scheduled' ) );
        return JHTML::_('select.genericlist',   $state, 'filter_state', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_state );
	}
	
	function getCategoryName( $id )
	{
		$category	=& JTable::getInstance( 'ECategory' , 'Table');
		$category->load( $id );
		return $category->title;
	}
	
	function registerToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_EASYBLOG_PENDING_PAGE_TITLE' ), 'pending' );
		
		JToolBarHelper::back();		
	}
}