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

class EasyBlogControllerSubscriptions extends EasyBlogController
{	
	function __construct()
	{
		parent::__construct();
	}
	
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$subs		= JRequest::getVar( 'cid' , '' , 'POST' );
		$filter		= JRequest::getVar( 'filter' , '' , 'POST' );
		
		$message	= '';
		$type		= 'message';
		
		if(empty($filter))
		{
			$message	= JText::_( 'COM_EASYBLOG_ERROR_REMOVING_SUBSCRIPTION_MISSING_SUBSCRIPTION_TYPE' );
			$type		= 'error';
			$this->setRedirect( 'index.php?option=com_easyblog&view=subscriptions' , $message , $type );
			return;
		}

		if( empty( $subs ) )
		{
			$message	= JText::_('COM_EASYBLOG_INVALID_SUBSCRIPTION_ID');
			$type		= 'error';
		}
		else
		{
		    $tablename  = '';
		    
			switch($filter)
			{
				case 'blog':
				    $tablename = 'Subscription';
				    break;
				case 'category':
				    $tablename = 'CategorySubscription';
				    break;
				case 'site':
				    $tablename = 'SiteSubscription';
				    break;
				case 'team':
				    $tablename = 'TeamSubscription';
				    break;
				case 'blogger':
				default:
				    $tablename = 'BloggerSubscription';
				    break;
			}
		    
			$table		=& JTable::getInstance( $tablename , 'Table' );
			foreach( $subs as $sub )
			{
				$table->load( $sub );

				if( ! $table->delete() )
				{
					$message	= JText::_( 'COM_EASYBLOG_ERROR_REMOVING_SUBSCRIPTION_PLEASE_TRY_AGAIN_LATER' );
					$type		= 'error';
					$this->setRedirect( 'index.php?option=com_easyblog&view=subscriptions' , $message , $type );
					return;
				}
			}

			$message	= JText::_('COM_EASYBLOG_SUBSCRIPTION_DELETED');
		}

		$this->setRedirect( 'index.php?option=com_easyblog&view=subscriptions' , $message , $type );
	}
}