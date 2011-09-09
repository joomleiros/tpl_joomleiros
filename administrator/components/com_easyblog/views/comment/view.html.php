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

class EasyBlogViewComment extends JView
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

		$commentId		= JRequest::getVar( 'commentid' , '' );
		
		$comment		=& JTable::getInstance( 'Comment' , 'Table' );
		
		$comment->load( $commentId );
		
		$this->comment	=& $comment;

		// Set default values for new entries.
		if( empty( $comment->created ) )
		{
			$date   = EasyBlogDateHelper::getDate();
			$now 	= EasyBlogDateHelper::toFormat($date);
			
			$comment->created	= $now;
			$comment->published	= true;
		}

		$this->assignRef( 'comment'		, $comment );
		
		parent::display($tpl);
	}

	function registerToolbar()
	{
		JToolBarHelper::back();
		JToolBarHelper::divider();
		
		if( $this->comment->id != 0 )
		{
	        JToolBarHelper::title( JText::_('COM_EASYBLOG_COMMENTS_COMMENT_EDITING_COMMENT'), 'comments' );
		}
		
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}

	function registerSubmenu()
	{
		return 'submenu.php';
	}
}