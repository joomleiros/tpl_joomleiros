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

class EasyBlogControllerPending extends EasyBlogController
{	
	function __construct()
	{
		parent::__construct();
	}

	function approve()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$mainframe =& JFactory::getApplication();
		$id	= JRequest::getVar( 'blogid' );
		
		$message	= '';
		$type		= 'message';
		

		$model		=& $this->getModel( 'Pending' );
		
		$model->approveBlog($id);
		$message = 'Blog Post Approved!';
		
		$config     =& EasyBlogHelper::getConfig();
		$blog		=& JTable::getInstance( 'blog', 'Table' );
		$blog->load($id);

		//add jomsocial activities
		if(($blog->published == POST_ID_PUBLISHED) && ($config->get('main_jomsocial_activity')) )
		{
			EasyBlogHelper::addJomSocialActivityBlog($blog, 1);
		}


		$this->setRedirect( 'index.php?option=com_easyblog&view=pending' , $message , $type );
	}

}