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

class EasyBlogViewCategory extends JView 
{
	var $cat	= null;
	
	function display($tpl = null)
	{
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();
		$config     =& EasyBlogHelper::getConfig();
		$acl		= EasyBlogACLHelper::getRuleSet();

		//Load pane behavior
		jimport('joomla.html.pane');

		$catId		= JRequest::getVar( 'catid' , '' );
		
		$cat		=& JTable::getInstance( 'ECategory' , 'Table' );
		
		$cat->load( $catId );
		
		$this->cat	=& $cat;

		// Set default values for new entries.
		if( empty( $cat->created ) )
		{
			$date   = EasyBlogDateHelper::getDate();
			$now 	= EasyBlogDateHelper::toFormat($date);
			
			$cat->created	= $now;
			$cat->published	= true;
		}
		
		$parentList = EasyBlogHelper::populateCategories('', '', 'select', 'parent_id', $cat->parent_id);

		$this->assignRef( 'cat'			, $cat );
		$this->assignRef( 'config'		, $config );
		$this->assignRef( 'acl'			, $acl );
		$this->assignRef( 'parentList'	, $parentList );
		
		parent::display($tpl);
	}

	function registerToolbar()
	{
		if( $this->cat->id != 0 )
		{
			JToolBarHelper::title( JText::sprintf( 'COM_EASYBLOG_CATEGORIES_EDIT_CATEGORY_TITLE' , $this->cat->title ), 'categories' );
		}
		else
		{
			JToolBarHelper::title( JText::_( 'COM_EASYBLOG_CATEGORIES_EDIT_ADD_CATEGORY_TITLE' ), 'categories' );
		}
		
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}

	function registerSubmenu()
	{
		return 'submenu.php';
	}		
}