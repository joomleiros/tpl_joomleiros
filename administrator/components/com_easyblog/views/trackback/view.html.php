<?php
/**
* @package  EasyBlog
* @copyright Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license  GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class EasyBlogViewTrackback extends JView
{
	function display($tpl = null)
	{
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();

		//Load pane behavior
		jimport('joomla.html.pane');

		$id			= JRequest::getVar( 'id' , '' );
		
		$trackback	=& JTable::getInstance( 'Trackback' , 'Table' );
		$trackback->load( $id );
		
		$this->assignRef( 'trackback' , $trackback );
		
		parent::display($tpl);
	}

	function registerToolbar()
	{
		JToolBarHelper::back();
		JToolBarHelper::divider();
		JToolBarHelper::title( JText::_('COM_EASYBLOG_TRACKBACKS_EDIT_TRACKBACK_TITLE'), 'trackbacks' );
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}

	function registerSubmenu()
	{
		return 'submenu.php';
	}
}