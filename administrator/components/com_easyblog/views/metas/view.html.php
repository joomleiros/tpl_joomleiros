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

// include_once( JPATH_ROOT . DS . 'components' . DS . 'com_easyblog' . DS . 'constants.php' );

class EasyBlogViewMetas extends JView 
{
	function display($tpl = null)
	{
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();
		
		$filter_state 	= $mainframe->getUserStateFromRequest( 'com_easyblog.metas.filter_state'	, 'filter_state', 	'*', 'word' );
		$search 		= $mainframe->getUserStateFromRequest( 'com_easyblog.metas.search'			, 'search', 		'', 'string' );
		
		$type 			= $mainframe->getUserStateFromRequest( 'com_easyblog.metas.filter_type'		, 'filter_type', 	'', 'word' );
		
		$search 		= trim(JString::strtolower( $search ) );
		$order			= $mainframe->getUserStateFromRequest( 'com_easyblog.metas.filter_order'	, 'filter_order', 	'id', 'cmd' );
		$orderDirection	= $mainframe->getUserStateFromRequest( 'com_easyblog.metas.filter_order_Dir', 'filter_order_Dir',	'', 'word' );

		//Get data from the model
		$model 			=& $this->getModel( 'Metas' );
		
		$metas			= $model->getData( $type );

		//filtering
		$filter = new stdClass();
		$filter->type 	= $this->getFilterType( $type );
 		$filter->search = $mainframe->getUserStateFromRequest( 'com_easyblog.meta.search', 'search', '', 'string' );
		 		
		for( $i = 0 ; $i < count( $metas ); $i++ )
		{
			$meta			=& $metas[ $i ];
			
			switch ( $meta->id ) 
			{
				case 1:
					$meta->title = JText::_('COM_EASYBLOG_LATEST_POSTS_PAGE');
					break;
				
				case 2:
					$meta->title = JText::_('COM_EASYBLOG_CATEGORIES_PAGE');
					break;
				
				case 3:
					$meta->title = JText::_('COM_EASYBLOG_TAGS_PAGE');
					break;
				
				case 4:
					$meta->title = JText::_('COM_EASYBLOG_BLOGGERS_PAGE');
					break;
				
				case 5:
					$meta->title = JText::_('COM_EASYBLOG_TEAM_BLOGS_PAGE');
					break;
				
				case 6:
					$meta->title = JText::_('COM_EASYBLOG_FEATURED_POSTS_PAGE');
					break;
				
				case 7:
					$meta->title = JText::_('COM_EASYBLOG_ARCHIVE_PAGE');
					break;
				
				case 30:
					$meta->title = '';
					break;
					
			}
		}
		$pagination 	= $model->getPagination( $type );;
		
		$this->assignRef( 'meta' 		, $metas );
		$this->assignRef( 'pagination'	, $pagination );
		$this->assignRef( 'type', $type );
		$this->assignRef( 'filter', $filter );
		$this->assign( 'state'			, JHTML::_('grid.state', $filter_state ) );
		
		$this->assign( 'search'			, $search );
		$this->assign( 'order'			, $order );
		$this->assign( 'orderDirection'	, $orderDirection );
		
		parent::display($tpl);
	}

	function getFilterType( $filter_type='*' )
	{
		$filter[] = JHTML::_('select.option', '', '- '. JText::_( 'COM_EASYBLOG_SELECT_TYPE' ) .' -' );
		$filter[] = JHTML::_('select.option', 'blogger', JText::_( 'COM_EASYBLOG_BLOGGERS' ) );
		$filter[] = JHTML::_('select.option', 'view', JText::_( 'COM_EASYBLOG_VIEWS' ) );
		$filter[] = JHTML::_('select.option', 'post', JText::_( 'COM_EASYBLOG_POSTS' ) );
		$filter[] = JHTML::_('select.option', 'team', JText::_( 'COM_EASYBLOG_TEAMS' ) );

		return JHTML::_('select.genericlist', $filter, 'filter_type', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_type );
	}

	function registerToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_EASYBLOG_META_TAG' ), 'meta' );
		
		JToolBarHelper::back();
	}
}