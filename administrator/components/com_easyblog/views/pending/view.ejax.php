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
require_once( EBLOG_CLASSES . DS . 'ejax.php' );

class EasyBlogViewPending extends EasyBlogAdminView
{
	var $err	= null;
	
	function confirmRejectBlog( $draftId )
	{
		$my			= JFactory::getUser();
		$ajax		= new Ejax();
		$acl		= EasyBlogACLHelper::getRuleSet();
		$config		= EasyBlogHelper::getConfig();
		$ids		= explode( ',' , $draftId );

		JTable::addIncludePath( EBLOG_TABLES );

		foreach( $ids as $id )
		{
			$blog		= JTable::getInstance( 'Draft' , 'Table' );
			$blog->load( $id );

			// @rule: Check if the blog is really under pending
			if( $blog->pending_approval != 1 )
			{
				$options			= new stdClass();
				$options->content	= JText::_( 'COM_EASYBLOG_NOT_ALLOWED' );
				$ajax->dialog( $options );
				return $ajax->send();
			}
		}
		
		$content = '';
		$content .= '<p>' . JText::_( 'COM_EASYBLOG_PENDING_REJECT_PENDING_ENTRIES_MESSAGE' ) . '</p>';
		$content .= '<form name="reject-post" id="reject-post" action="' . JRoute::_( 'index.php?option=com_easyblog&c=blogs&task=rejectBlog' ) . '" method="post">';
		$content .= '<div class="mtm">';
		$content .= '	<label for="message">' . JText::_( 'COM_EASYBLOG_PENDING_SPECIFY_REASON' ) . '</label>';
		$content .= '	<textarea class="full" id="message" name="message"></textarea>';
		$content .= '</div>';
		$content .= '<div class="dialog-actions">';
		$content .= JHTML::_( 'form.token' );
		$content .= '	<input type="hidden" name="draft_id" value="' . $draftId . '" />';
		$content .= '	<input type="button" value="' . JText::_( 'COM_EASYBLOG_PENDING_CANCEL_BUTTON' ) . '" class="button" id="edialog-cancel" name="edialog-cancel" onclick="ejax.closedlg();" />';
		$content .= '	<input type="submit" value="' . JText::_( 'COM_EASYBLOG_PENDING_PROCEED_BUTTON' ) . '" class="button" />';
		$content .= '</div>';

		$options			= new stdClass();
		$options->title		= JText::_( 'COM_EASYBLOG_PENDING_DIALOG_CONFIRM_REJECT_TITLE' );
		$options->content	= $content;
		$ajax->dialog( $options );
		return $ajax->send();
	}
	
	
}