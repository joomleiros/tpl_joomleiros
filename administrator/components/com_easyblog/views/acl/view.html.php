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
require( EBLOG_ADMIN_ROOT . DS . 'views.php');

class EasyBlogViewAcl extends EasyBlogAdminView
{	
	function display($tpl = null)
	{
		$mainframe	=& JFactory::getApplication();
		$model 		=& $this->getModel( 'Acl' );		
		$document	=& JFactory::getDocument();
		
		$cid	= JRequest::getVar('cid', '', 'REQUEST');
		$type	= JRequest::getVar('type', '', 'REQUEST');
		$add	= JRequest::getVar('add', '', 'REQUEST');
		
		JHTML::_('behavior.modal' , 'a.modal' );
		JHTML::_('behavior.tooltip');
		
		if((empty($cid) || empty($type)) && empty($add))
		{
			$mainframe->redirect( 'index.php?option=com_easyblog&view=acls' , JText::_('Invalid Id or acl type. Please try again.') , 'error' );
		}
		
		$rulesets = $model->getRuleSet($type, $cid, $add);
		
		if ( $type == 'assigned' )
		{
			$document->setTitle( JText::_("COM_EASYBLOG_ACL_ASSIGN_USER") );
			JToolBarHelper::title( JText::_( 'COM_EASYBLOG_ACL_ASSIGN_USER' ), 'acl' );
		}
		else
		{
			$document->setTitle( JText::_("COM_EASYBLOG_ACL_JOOMLA_USER_GROUP") );
			JToolBarHelper::title( JText::_( 'COM_EASYBLOG_ACL_JOOMLA_USER_GROUP' ), 'acl' );
		}
		
		$this->assignRef( 'joomlaversion'	, EasyBlogHelper::getJoomlaVersion() );
		$this->assignRef( 'rulesets' 		, $rulesets );
		$this->assignRef( 'type' 			, $type );	
		$this->assignRef( 'add' 			, $add );	
		
		parent::display($tpl);
	}
	
	function registerToolbar()
	{
		JToolBarHelper::back( 'COM_EASYBLOG_HOME' , 'index.php?option=com_easyblog');
 		JToolBarHelper::divider();
 		JToolBarHelper::save();
 		JToolBarHelper::apply();
 		JToolBarHelper::cancel();
	}

	function registerSubmenu()
	{
		return 'submenu.php';
	}
}