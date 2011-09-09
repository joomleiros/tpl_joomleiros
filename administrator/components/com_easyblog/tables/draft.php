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

class TableDraft extends JTable
{
	var $id				= null;
	var $entry_id		= null;
	var $created_by		= null;
	var $modified		= null;
	var $created		= null;
	var $publish_up		= null;
	var $publish_down	= null;
	var $title			= null;
	var $permalink		= null;
	var $content		= null;
	var $intro			= null;
	var $category_id	= null;
	var $published		= null;
	var $ordering		= null;
	var $vote			= null;
	var $hits			= null;
	var $private		= null;
	var $allowcomment	= null;
	var $subscription	= null;
	var $frontpage		= null;
	var $isnew			= null;
	var $ispending		= null;
	var $issitewide		= null;
	var $tags			= null;
	var $metakey		= null;
	var $metadesc		= null;
	var $trackbacks		= null;
	var $blog_contribute	= null;
	var $autopost			= null;
	var $pending_approval	= null;
	
	/**
	 * Constructor for this class.
	 *
	 * @return
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_drafts' , 'id' , $db );
	}

	function loadByEntry( $id )
	{
		$db		= JFactory::getDBO();
		$query	= 'SELECT * FROM ' . $db->nameQuote( '#__easyblog_drafts' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'entry_id' ) . '=' . $db->Quote( $id );

		$db->setQuery( $query );
		return parent::bind( $db->loadObject() );
	}

    /**
     * Must only be bind when using POST data
     **/
    function bind( $data , $post = false )
    {
    	if( !$post )
    	{
			return parent::bind( $data );
		}
    	
    	parent::bind( $data );
  		$acl		= EasyBlogACLHelper::getRuleSet();
    	$my			=& JFactory::getUser();
    	
    	// Some properties needs to be overriden.
    	$content	= $this->content;
		$intro		= $this->intro;
		
		//remove unclean editor code.
		$pattern    = array('/<p><br _mce_bogus="1"><\/p>/i',
		                    '/<p><br mce_bogus="1"><\/p>/i',
		                    '/<br _mce_bogus="1">/i',
							'/<p><br><\/p>/i');
		$replace    = array('','','','');
		
		$intro      = preg_replace($pattern, $replace, $intro);
		$content    = preg_replace($pattern, $replace, $content);
		
		$publish_up		= '';
		$publish_down 	= '';
		$created_date   = '';
		$tzoffset       = EasyBlogDateHelper::getOffSet();
		
		if(!empty( $this->created ))
		{
		    $date 			=& JFactory::getDate( $this->created, $tzoffset );
		    $created_date   = $date->toMySQL();
		}
		
		if($this->publish_down == '0000-00-00 00:00:00')
		{
			$publish_down   = $this->publish_down;
		}
		else if(!empty( $this->publish_down ))
		{
		    $date =& JFactory::getDate( $this->publish_down, $tzoffset );
		    $publish_down   = $date->toMySQL();
		}
		
		
		if(!empty( $this->publish_up ))
		{
		    $date =& JFactory::getDate( $this->publish_up,  $tzoffset);
		    $publish_up   = $date->toMySQL();
		}
		
		//default joomla date obj
		$date		=& JFactory::getDate();
		
		$this->created 		= !empty( $created_date ) ? $created_date : $date->toMySQL();
		$this->intro		= $intro;
		$this->content		= $content;
		$this->modified		= $date->toMySQL();
		$this->publish_up 	= (!empty( $publish_up)) ? $publish_up : $date->toMySQL();
		$this->publish_down	= (empty( $publish_down ) ) ? '0000-00-00 00:00:00' : $publish_down;
		$this->ispending 	= (empty($acl->rules->publish_entry)) ? 1 : 0;
		$this->issitewide	= ( empty( $this->blog_contribute ) ) ? 1 : 0;
		
		// Bind necessary stuffs for the next load
		if( isset( $data[ 'tags' ] ) && !empty( $data[ 'tags' ] ) && is_array( $data[ 'tags' ] ) )
		{
			$this->set( 'tags'	, implode( ',' , $data[ 'tags' ] ) );
		}
 		
 		if( isset( $data[ 'keywords' ] ) && !empty( $data[ 'keywords' ] ) )
 		{
 			$this->set( 'metakey'	, $data[ 'keywords' ] );
		}

 		if( isset( $data[ 'description' ] ) && !empty( $data[ 'description' ] ) )
 		{
 			$this->set( 'metadesc'	, $data[ 'description' ] );
		}

 		if( isset( $data[ 'trackback' ] ) && !empty( $data[ 'trackback' ] ) )
 		{
 			$this->set( 'trackbacks'	, $data[ 'trackback' ] );
		}

 		if( isset( $data[ 'blogpassword' ] ) && !empty( $data[ 'blogpassword' ] ) )
 		{
 			$this->set( 'blogpassword'	, $data[ 'blogpassword' ] );
		}
		
 		if( isset( $data[ 'socialshare' ] ) && !empty( $data[ 'socialshare' ] ) )
 		{
 			$this->set( 'autopost'	, implode( ',' , $data[ 'socialshare' ] ) );
		}

 
		return true;
	}
	
	public function getRejected()
	{
		$db		= JFactory::getDBO();
		
		$query	= 'SELECT * FROM ' . $db->nameQuote( '#__easyblog_post_rejected' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'draft_id' ) . '=' . $db->Quote( $this->id );
		$db->setQuery( $query );
		$result	= $db->loadObject();
		
		if( !$result )
		{
			return false;
		}
		
		$result->author	= JTable::getInstance( 'Profile' , 'Table' )->load( $result->created_by );
		return $result;
	}
}