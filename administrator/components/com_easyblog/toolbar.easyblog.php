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

$submenus	= array(
						'easyblog'		=> JText::_('COM_EASYBLOG_TAB_HOME'),
						'settings'		=> JText::_('COM_EASYBLOG_HOME_SETTINGS'),
						'blogs'			=> JText::_('COM_EASYBLOG_HOME_BLOG_ENTRIES'),
						'pending'		=> JText::_('COM_EASYBLOG_HOME_PENDING_POSTS'),
						'categories'	=> JText::_('COM_EASYBLOG_HOME_CATEGORIES'),
						'tags'			=> JText::_('COM_EASYBLOG_HOME_TAGS'),
						'comments'		=> JText::_('COM_EASYBLOG_HOME_COMMENTS'),
						'trackbacks'	=> JText::_('COM_EASYBLOG_HOME_TRACKBACKS'),
						'users'			=> JText::_('COM_EASYBLOG_HOME_BLOGGERS'),
						'teamblogs'		=> JText::_('COM_EASYBLOG_HOME_TEAM_BLOGS'),
						'acls'			=> JText::_('COM_EASYBLOG_HOME_ACL'),
						'metas'			=> JText::_('COM_EASYBLOG_HOME_META_TAGS'),
						'subscriptions'	=> JText::_('COM_EASYBLOG_HOME_SUBSCRIPTIONS'),
						'migrators'		=> JText::_('COM_EASYBLOG_HOME_MIGRATORS')
					);

$current	= JRequest::getVar( 'view' , 'easyblog' );

// @task: For the frontpage, we just show the the icons.
if( $current == 'easyblog' )
{
	$submenus	= array( 'easyblog' => JText::_('COM_EASYBLOG_TAB_HOME') );
}
foreach( $submenus as $view => $title )
{
	$isActive	= ( $current == $view );

 	JSubMenuHelper::addEntry( $title , 'index.php?option=com_easyblog&view=' . $view , $isActive );
}