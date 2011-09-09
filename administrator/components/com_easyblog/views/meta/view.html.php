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

class EasyBlogViewMeta extends JView 
{
	
	var $_id	= null;
	var $_type	= null;
	
	function display($tpl = null)
	{
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();

		//Load pane behavior
		jimport('joomla.html.pane');

		$metatId		= JRequest::getVar( 'id' , '' );
		
		$meta		=& JTable::getInstance( 'meta' , 'Table' );
		
		$meta->load( $metatId );
		
		// assign title
		$meta->title = $this->_getItemTitle($meta->id);
		
		$this->meta	=& $meta;

		$this->assignRef( 'meta'		, $meta );
		
		parent::display($tpl);
	}

	function registerToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_EASYBLOG_META_TAG_EDIT' ), 'meta' );
		
		JToolBarHelper::back();
		JToolBarHelper::divider();
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}
	
	function registerSubmenu()
	{
		return 'submenu.php';
	}	
	
	
	function _getItemTitle($id) 
	{
// 		$db =& JFactory::getDBO();
		
		$title = '';
		
		switch ( $id ) 
		{
			case 1:
				$title = JText::_('Latest Posts Page');
				break;
			
			case 2:
				$title = JText::_('Categories Page');
				break;
			
			case 3:
				$title = JText::_('Tags Page');
				break;
			
			case 4:
				$title = JText::_('Bloggers Page');
				break;
			
			case 5:
				$title = JText::_('Team Blogs Page');
				break;
			
			default:
				$title = $this->_getTitle( $id );
				
		}		
		
		return $title;
	}
	
	
	function _getTitle( $id )
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT `type`, `content_id` FROM ' . $db->nameQuote('#__easyblog_meta') . ' WHERE id = ' . $db->Quote($id);
		$db->setQuery($query);
		
		$result = $db->loadObject();
		
		$query = '';
		switch ( $result->type )
		{
			case 'post':
				$query = 'SELECT `title` FROM ' . $db->nameQuote('#__easyblog_post') . ' WHERE id = ' . $db->Quote( $result->content_id );
				break;
			
			case 'blogger':
				$query = 'SELECT `name` AS title  FROM ' . $db->nameQuote('#__users') . ' WHERE id = ' . $db->Quote( $result->content_id );
				break;
				
			case 'team':
				$query = 'SELECT `title`  FROM ' . $db->nameQuote('#__easyblog_team') . ' WHERE id = ' . $db->Quote( $result->content_id );
				break;
			default:
			    return 'unknown';
			    break;
		}
		
		$db->setQuery($query);
		$result = $db->loadResult();

		return $result;
	}
}