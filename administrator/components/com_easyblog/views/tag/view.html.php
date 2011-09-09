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

class EasyBlogViewTag extends JView 
{
	var $tag	= null;
	
	function display($tpl = null)
	{
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();

		//Load pane behavior
		jimport('joomla.html.pane');

		$tagId		= JRequest::getVar( 'tagid' , '' );
		
		$tag		=& JTable::getInstance( 'Tag' , 'Table' );
		
		$tag->load( $tagId );
		
		$tag->title = JString::trim($tag->title);
		$tag->alias = JString::trim($tag->alias);
		
		$this->tag	=& $tag;

		// Set default values for new entries.
		if( empty( $tag->created ) )
		{
			$date   = EasyBlogDateHelper::getDate();
			$now 	= EasyBlogDateHelper::toFormat($date);
			
			$tag->created	= $now;
			$tag->published	= true;
		}

		$this->assignRef( 'tag'		, $tag );
		
		parent::display($tpl);
	}

	function registerToolbar()
	{
		if( $this->tag->id != 0 )
		{
			JToolBarHelper::title( JText::_( 'COM_EASYBLOG_TAGS_EDIT_TAG_TITLE'  ), 'tags' );
		}
		else
		{
			JToolBarHelper::title( JText::_( 'COM_EASYBLOG_TAGS_NEW_TAG_TITLE' ), 'tags' );
		}
		
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}

	function registerSubmenu()
	{
		return 'submenu.php';
	}	
}