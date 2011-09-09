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

jimport('joomla.application.component.controller');

class EasyBlogControllerMigrators extends JController
{
	function add()
	{
		$mainframe	=& JFactory::getApplication();
		
		$post	= JRequest::get('POST');
		
		echo '<pre>';
		var_dump($post);
		echo '</pre>';
		
		exit;
		
		//$mainframe->redirect( 'index.php?option=com_easyblog&view=settings' , $message , $type );
	}
}