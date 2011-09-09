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

class EasyBlogViewTeamRequest extends JView
{
	function display($tpl = null)
	{	
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();
		$config 	=& EasyBlogHelper::getConfig();

		// get all the team request that this user assigned as admin.
 		$requests		=& $this->get( 'Data' );
 		$pagination 	=& $this->get( 'Pagination' );
		
 		$this->assignRef( 'requests' 	, $requests );
 		$this->assignRef( 'pagination'	, $pagination );
 		$this->assignRef( 'config'	, $config );

		parent::display($tpl);
	}

	function registerToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_EASYBLOG_TEAMBLOGS_JOIN_REQUEST' ), 'teamrequest' );
		
		JToolBarHelper::back();
	}
}