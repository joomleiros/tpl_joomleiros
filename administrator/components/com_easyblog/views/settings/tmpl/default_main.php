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

$pane	=& JPane::getInstance('Tabs');

echo $pane->startPane("submain");
echo $pane->startPanel( JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_SUBTAB_GENERAL' ) , 'general');
echo $this->loadTemplate( 'main_general' );
echo $pane->endPanel();
echo $pane->startPanel( JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_SUBTAB_MEDIA' ) , 'media');
echo $this->loadTemplate( 'main_media' );
echo $pane->endPanel();
echo $pane->startPanel( JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_SUBTAB_SUBSCRIPTIONS' ) , 'subscriptions');
echo $this->loadTemplate( 'main_subscriptions' );
echo $pane->endPanel();
echo $pane->endPane();
