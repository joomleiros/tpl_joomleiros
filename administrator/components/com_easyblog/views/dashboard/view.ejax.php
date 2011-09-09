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
require_once( EBLOG_HELPERS . DS . 'string.php' );
require_once( EBLOG_HELPERS . DS . 'date.php' );
require_once( EBLOG_CLASSES . DS . 'ejax.php' );

class EasyBlogViewDashboard extends JView
{	
	var $err	= null;
	
	/**
	 * Generate proper permalink for a blog entry
	 **/	 	
	function getPermalink( $value )
	{
 		$ejax	= new Ejax();
		$value	= urldecode($value);

 		$permalink		= EasyBlogHelper::getPermalink( $value );
		$ejax->value('permalink', $permalink);
		$ejax->send();

	}
	
	public function showVideoForm( $editorName )
	{
		$ajax	= new Ejax();
		$my		= JFactory::getUser();
		
		if( $my->id <= 0 )
		{
			$title		= JText::_('COM_EASYBLOG_INFO');
			$callback 	= JText::_('COM_EASYBLOG_NO_PERMISSION_TO_PUBLISH_OR_UNPUBLISH_COMMENT');
			$width		= '450';
			$height		= 'auto';
			$ajax->alert( $callback, $title, $width, $height );
			$ajax->send();
			return;
		}
		
		$theme		= new CodeThemes( true );
		$theme->set( 'editorName' , $editorName );
		$content	= $theme->fetch( 'ajax.dialog.videos.add.php' );
		
		$title	= JText::_( 'COM_EASYBLOG_DASHBOARD_WRITE_INSERT_VIDEO_DIALOG_TITLE' );
		
		$ajax->dialog( EasyBlogHelper::getHelper( 'DialogOptions' )->set( 'title' , $title )->set( 'content' , $content )->toObject() );
		
		return $ajax->send();
	}
	
	function updateDisplayDate( $eleId, $dateString)
	{
	    $ajax		= new Ejax();
	    $config =& EasyBlogHelper::getConfig();

		$date 			= JFactory::getDate( $dateString );
		$now 			= EasyBlogDateHelper::toFormat( $date, $config->get( 'layout_dateformat' ) );

	    $ajax->assign( 'datetime_' . $eleId . ' .datetime_caption',  $now);
	    return $ajax->send();
	}

}