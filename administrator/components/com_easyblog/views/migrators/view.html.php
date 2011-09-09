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

class EasyBlogViewMigrators extends JView 
{
	function display($tpl = null)
	{
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();
		
		include_once( JPATH_ROOT . DS . 'components' . DS . 'com_easyblog' . DS . 'helpers' . DS . 'loader.php' );
        EjaxLoader::_('ej,ejax','js');
        
        //check if myblog installed or not.
        $myblogInstalled	= $this->myBlogExists();
        $myBlogSection		= '';
        if($myblogInstalled)
        {
        	require_once(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_myblog' . DS . 'config.myblog.php');
        	$myblogConfig	= new MYBLOG_Config();
        	$myBlogSection	= $myblogConfig->get('postSection');
        }
        

		$categories[]	= JHTML::_('select.option', '0', '- '.JText::_('COM_EASYBLOG_MIGRATORS_SELECT_CATEGORY').' -');
		$authors[]		= JHTML::_('select.option', '0', '- '.JText::_('COM_EASYBLOG_MIGRATORS_SELECT_AUTHOR').' -', 'created_by', 'name');
		
		if(EasyBlogHelper::getJoomlaVersion() >= '1.6')
		{
		    $lists['sectionid'] = array();
		
			$articleCat			= JHtml::_('category.options', 'com_content');
			
			$articleAuthors		=& $this->get( 'ArticleAuthors16' );
		}
		else
		{
			// get list of sections for dropdown filter
			$lists['sectionid'] = $this->section($myBlogSection, 'sectionId', -1, '');

			// get article categories from model
			$model	=& $this->getModel( 'Migrators' );
			$articleCat		= $model->getArticleCategories( $myBlogSection );
			
			// get article authors from model
			$articleAuthors		=& $this->get( 'ArticleAuthors' );
		}
		
		$categories		= array_merge($categories, $articleCat);
		$lists['catid'] = JHTML::_('select.genericlist',  $categories, 'catId', 'class="inputbox"', 'value', 'text', '');
		
		$authors 	= array_merge($authors, $articleAuthors);
		$lists['authorid'] = JHTML::_('select.genericlist',  $authors, 'authorId', 'class="inputbox"', 'created_by', 'name', 0);
		
		
		// state filter
		$state          = $this->getDefaultState();
		
		//$state			= array('P' => 'Published', 'U' => 'Unpublished', 'A' => 'Archived');
		
		$articleState	= array();
		foreach($state as $key => $val)
		{
			$obj	= new stdClass();
			$obj->state	= $val;
			$obj->value	= $key;
			
			$articleState[]	= $obj;
		}
		
		$stateList		= array();
		$stateList[]	= JHTML::_('select.option', '*', '- '.JText::_('COM_EASYBLOG_MIGRATORS_SELECT_STATE').' -', 'value', 'state');
		
		$stateList 	= array_merge($stateList, $articleState);
		$lists['state'] = JHTML::_('select.genericlist',  $stateList, 'stateId', 'class="inputbox"', 'value', 'state', '*');

		
		$this->assignRef( 'smartblogInstalled' , $this->smartBlogExists() );
		$this->assignRef( 'lyftenbloggieInstalled' , $this->lyftenBloggieExists() );
		
		$this->assignRef( 'myblogInstalled' , $myblogInstalled );
		$this->assignRef( 'myBlogSection' 	, $myBlogSection );
		
		$this->assignRef( 'lists' , $lists );
		parent::display($tpl);
	}
	
	function section($excludeSection='', $name, $active = NULL, $javascript = NULL, $order = 'ordering', $uncategorized = true, $scope = 'content' )
	{
		$db =& JFactory::getDBO();

		$categories[] = JHTML::_('select.option',  '-1', '- '. JText::_( 'COM_EASYBLOG_MIGRATORS_SELECT_SECTION' ) .' -' );

		if ($uncategorized) {
			$categories[] = JHTML::_('select.option',  '0', JText::_( 'Uncategorized' ) );
		}
		
		$excludeSQL = '';
		if( !empty($excludeSection) )
		{
		    $excludeSQL = ' AND id != ' . $db->Quote($excludeSection);
		}

		$query = 'SELECT id AS value, title AS text'
		. ' FROM #__sections'
		. ' WHERE published = 1'
		. ' AND scope = ' . $db->Quote($scope)
		. $excludeSQL
		. ' ORDER BY ' . $order
		;
		$db->setQuery( $query );
		$sections = array_merge( $categories, $db->loadObjectList() );

		$category = JHTML::_('select.genericlist',   $sections, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );

		return $category;
	}
	
	function getDefaultState()
	{
	    $state			= null;
        if(EasyBlogHelper::getJoomlaVersion() >= '1.6')
        {
			$state = array('1' => 'Published', '0' => 'Unpublished', '2' => 'Archived', '-2' => 'Trash');
        }
        else
        {
            $state = array('P' => 'Published', 'U' => 'Unpublished', 'A' => 'Archived');
        }
        return $state;
	}
	
	function smartBlogExists()
	{
		if(! JFile::exists(JPATH_ROOT . DS . 'components' . DS . 'com_blog' . DS . 'blog.php'))
		{
			return false;
		}
		return true;
	}
	
	function myBlogExists()
	{
		if(! JFile::exists(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_myblog' . DS . 'config.myblog.php'))
		{
			return false;
		}
		return true;
	}
	
	function lyftenBloggieExists()
	{
		if(! JFile::exists(JPATH_ROOT . DS . 'components' . DS . 'com_lyftenbloggie' . DS . 'lyftenbloggie.php'))
		{
			return false;
		}
		return true;
	}
	
	function getWPBlogs()
	{
// 	    $db =& JFactory::getDBO();
//
// 		$query  = 'select * from `#__wp_blogs`';
// 		$db->setQuery( $query );
//
// 		$result = $db->loadObjectList();

		$htmlList   = array();
// 		if( count($result) > 0)
// 		{
// 		    foreach( $result as $item)
// 		    {
// 		        $htmlList[]	= JHTML::_('select.option', $item->blog_id, $item->domain . $item->path, 'value', 'state');
// 		    }
// 		}

		return $htmlList;
	}
	
	function wpExists()
	{
// 		if(! JFile::exists(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_myblog' . DS . 'config.myblog.php'))
// 		{
// 			return false;
// 		}
		return false;
	}
	
	function registerToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_EASYBLOG_MIGRATORS' ), 'migrators' );

		JToolBarHelper::back( 'Home' , 'index.php?option=com_easyblog');
	}

	function registerSubmenu()
	{
		return 'submenu.php';
	}
}