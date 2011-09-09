<?php
/*
 * @package		mod_easyblogtagcloud
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *  
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');

$path	= JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'helpers' . DS . 'helper.php';

jimport( 'joomla.filesystem.file' );

if( !JFile::exists( $path ) )
{
	return;
}
require_once( $path );
require_once( dirname(__FILE__).DS.'helper.php' );
EasyBlogHelper::loadModuleCss();

$tagcloud			= modEasyBlogTagCloudHelper::getTagCloud($params);
require( JModuleHelper::getLayoutPath('mod_easyblogtagcloud') );