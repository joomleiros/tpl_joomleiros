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

require_once( JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'constants.php' );
require_once( EBLOG_HELPERS . DS . 'router.php' );

class TableECategory extends JTable
{
	/*
	 * The id of the category
	 * @var int
	 */
	var $id 						= null;

	/*
	 * The author of the category
	 * @var int
	 */
	var $created_by		= null;

	/*
	 * Category title
	 * @var string
	 */
	var $title					= null;
	
	/*
	 * Category title alias
	 * @var string
	 */
	var $alias					= null;

	/*
	 * Category avatar image filename
	 * @var string
	 */
	var $avatar					= null;
	
	/*
	 * Category parent_id
	 * @var int
	 */
	var $parent_id				= null;
	
	/*
	 * Category private
	 * @var int
	 */
	var $private				= null;

	/*
	 * Created datetime of the category
	 * @var datetime
	 */
	var $created				= null;

	/*
	 * Category status
	 * @var int
	 */
	var $status			= null;

	/*
	 * Category publishing status
	 * @var int
	 */
	var $published		= null;
	
	/*
	 * Category ordering
	 * @var int
	 */
	var $ordering		= null;


	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_category' , 'id' , $db );
	}

	function load( $key , $permalink = false )
	{
		if( !$permalink )
		{
			return parent::load( $key );
		}
		
		$db		=& $this->getDBO();
		
		$query	= 'SELECT id FROM ' . $this->_tbl . ' '
				. 'WHERE alias=' . $db->Quote( $key );
		$db->setQuery( $query );
		
		$id		= $db->loadResult();
		
		// Try replacing ':' to '-' since Joomla replaces it
		if( !$id )
		{
			$query	= 'SELECT id FROM ' . $this->_tbl . ' '
					. 'WHERE alias=' . $db->Quote( JString::str_ireplace( ':' , '-' , $key ) );
			$db->setQuery( $query );
			
			$id		= $db->loadResult();			
		}
		return parent::load( $id );
	}
	
	/**
	 * Overrides parent's delete method to add our own logic.
	 * 
	 * @return boolean
	 * @param object $db
	 */
	function delete()
	{
		$db		=& $this->getDBO();
		$config =& EasyBlogHelper::getConfig();
		
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__easyblog_post' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'category_id' ) . '=' . $db->Quote( $this->id );
		$db->setQuery( $query );
		
		$count	= $db->loadResult();
	
		if( $count > 0 )
		{
			return false;
		}
		
		/* TODO */
		//remove avatar if previously already uploaded.
		$avatar = $this->avatar;
		
		if( $avatar != 'cdefault.png' && !empty($avatar))
		{
		
			$avatar_config_path = $config->get('main_categoryavatarpath');
	        $avatar_config_path = rtrim($avatar_config_path, '/');
			$avatar_config_path = JString::str_ireplace('/', DS, $avatar_config_path);

			$upload_path		= JPATH_ROOT.DS.$avatar_config_path;
		
			$target_file_path		= $upload_path;
			$target_file 			= JPath::clean($target_file_path . DS. $avatar);
		
			if(JFile::exists( $target_file ))
			{
				JFile::delete( $target_file );
			}
		}
		
		return parent::delete();
	}
	
	function aliasExists()
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__easyblog_category' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'alias' ) . '=' . $db->Quote( $this->alias );
		
		if( $this->id != 0 )
		{
			$query	.= ' AND ' . $db->nameQuote( 'id' ) . '!=' . $db->Quote( $this->id ); 
		}
		$db->setQuery( $query );
		
		return $db->loadResult() > 0 ? true : false; 
	}
	
	/**
	 * Overrides parent's bind method to add our own logic.
	 * 
	 * @param Array $data	 
	 **/	 	 	
	function bind( $data )
	{
		parent::bind( $data );
		
		if( empty( $this->created ) )
		{
			$date			=& JFactory::getDate();
			$this->created	= $date->toMySQL();
		}
		
		jimport( 'joomla.filesystem.filter.filteroutput');

		$i	= 1;
		while( $this->aliasExists() || empty($this->alias) )
		{
			$this->alias	= empty($this->alias) ? $this->title : $this->alias . '-' . $i;
			$i++;
		}
		
		//$this->alias 	= JFilterOutput::stringURLSafe( $this->alias );
		$this->alias 	= EasyBlogRouter::generatePermalink( $this->alias );
	}
	
	function getRSS()
	{
		return EasyBlogHelper::getHelper( 'Feeds' )->getFeedURL( 'index.php?option=com_easyblog&view=categories&id=' . $this->id, false, 'category' );
	}

	function getAtom()
	{
		return EasyBlogHelper::getHelper( 'Feeds' )->getFeedURL( 'index.php?option=com_easyblog&view=categories&id=' . $this->id , true, 'category' );
	}
	
	function getAvatar()
	{
	    $avatar_link    = '';

        if($this->avatar == 'cdefault.png' || $this->avatar == 'default_category.png' || $this->avatar == 'components/com_easyblog/assets/images/default_category.png' || $this->avatar == 'components/com_easyblog/assets/images/cdefault.png' || empty($this->avatar))
        {
            $avatar_link   = 'components/com_easyblog/assets/images/default_category.png';
        }
        else
        {
    		$avatar_link   = EasyImageHelper::getAvatarRelativePath('category') . '/' . $this->avatar;
    	}

		return rtrim(JURI::root(), '/') . '/' . $avatar_link;
	}
	
	function getPostCount()
	{
	    $db =& JFactory::getDBO();
	    
	    $query  = 'SELECT count(1) FROM `#__easyblog_post` WHERE `category_id` = ' . $db->Quote($this->id);
	    $db->setQuery($query);
	    
	    return $db->loadResult();
	}
	
	function getChildCount()
	{
	    $db =& JFactory::getDBO();

	    $query  = 'SELECT count(1) FROM `#__easyblog_category` WHERE `parent_id` = ' . $db->Quote($this->id);
	    $db->setQuery($query);

	    return $db->loadResult();
	}
	
	/*
	 * Retrieves a list of active bloggers that contributed in this category.
	 *
	 * @param	null
	 * @return	Array	An array of TableProfile objects.
	 */
	public function getActiveBloggers()
	{
		$db		= JFactory::getDBO();
		$query	= 'SELECT DISTINCT(`created_by`) FROM ' . $db->nameQuote( '#__easyblog_post' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'category_id' ) . '=' . $db->Quote( $this->id );
		$db->setQuery( $query );
		
		$rows		= $db->loadObjectList();
		
		if( !$rows )
		{
			return false;
		}
		
		$bloggers	= array();
		foreach( $rows as $row )
		{
			$profile	= JTable::getInstance( 'Profile' , 'Table' );
			$profile->load( $row->created_by );
			
			$bloggers[]	= $profile;
		}
		
		return $bloggers;
	}
	
	public function store()
	{
	    if( !empty( $this->created ))
	    {
	        $offset     	= EasyBlogDateHelper::getOffSet();
	        $newDate		=& JFactory::getDate( $this->created, $offset );
	        $this->created  = $newDate->toMySQL();
	    }
	    else
	    {
	        $newDate		=& JFactory::getDate();
	        $this->created  = $newDate->toMySQL();
	    }
	    
	    return parent::store();
	}
}