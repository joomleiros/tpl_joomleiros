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

class TableBlog extends JTable
{

	var $id 				= null;
	var $created_by			= null;
	var $modified			= null;
	var $created			= null;
	var $publish_up			= null;
	var $publish_down		= null;
	var $title				= null;
	var $permalink			= null;
	var $content			= null;
	var $intro				= null;
	var $category_id		= null;
	var $published			= null;
	var $ordering			= null;
	var $vote				= null;
	var $hits				= null;
	var $private			= null;
	var $allowcomment		= null;
	var $subscription		= null;
	var $frontpage			= null;
	var $isnew				= null;
	var $ispending			= null;
	var $issitewide			= null;
	var $blogpassword		= null;

	/**
	 * Constructor for this class.
	 *
	 * @return
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_post' , 'id' , $db );
	}
	
	function load( $key , $permalink = false )
	{
		if( !$permalink )
		{
			return parent::load( $key );
		}
		
		$db		=& $this->getDBO();
		
		$query	= 'SELECT id FROM ' . $this->_tbl . ' '
				. 'WHERE permalink=' . $db->Quote( $key );
		$db->setQuery( $query );
		
		$id		= $db->loadResult();
		
		// Try replacing ':' to '-' since Joomla replaces it
		if( !$id )
		{
			$query	= 'SELECT id FROM ' . $this->_tbl . ' '
					. 'WHERE permalink=' . $db->Quote( JString::str_ireplace( ':' , '-' , $key ) );
			$db->setQuery( $query );
			
			$id		= $db->loadResult();			
		}
		return parent::load( $id );
	}
	
    function getSubscribers()
    {
        $db =& $this->_db;

        $query  = 'SELECT * FROM `#__easyblog_post_subscription` WHERE `post_id` = ' . $db->Quote($this->id);
        $db->setQuery($query);

        $result = $db->loadObjectList();
        return $result;
    }
    
    function getCategoryName()
    {
        if($this->category_id == 0)
        {
            return JText::_('UNCATEGORIZED');
        }
        
        static $loaded	= array();
        
        if( !isset( $loaded[ $this->category_id ] ) )
        {
			$db		= JFactory::getDBO();
			
	    	$query  = 'SELECT `title` FROM `#__easyblog_category` WHERE `id` = ' . $db->Quote($this->category_id);
	        $db->setQuery($query);
	        
	        $loaded[ $this->category_id ]	= $db->loadResult();
		}
		return $loaded[ $this->category_id ];
    }
    
    /**
     * Must only be bind when using POST data
     **/
    function bind( $data , $post = false )
    {
    	parent::bind( $data );
    	
    	if( $post )
    	{
    		$acl		= EasyBlogACLHelper::getRuleSet();
	    	$my			=& JFactory::getUser();
	    	
	    	// Some properties needs to be overriden.
	    	$content	= JRequest::getVar('write_content', '', 'post', 'string', JREQUEST_ALLOWRAW );
			$intro		= JRequest::getVar('intro', '', 'post', 'string', JREQUEST_ALLOWRAW );
			if($this->id == 0)
			{
			    // this is to check if superadmin assign blog author during blog creation.
			    if(empty($this->created_by))
					$this->created_by	= $my->id;
			}
			
			//remove unclean editor code.
			$pattern    = array('/<p><br _mce_bogus="1"><\/p>/i',
			                    '/<p><br mce_bogus="1"><\/p>/i',
			                    '/<br _mce_bogus="1">/i',
			                    '/<br mce_bogus="1">/i',
								'/<p><br><\/p>/i');
			$replace    = array('','','','','');
			
			$intro      = preg_replace($pattern, $replace, $intro);
			$content    = preg_replace($pattern, $replace, $content);
			
			$publish_up		= '';
			$publish_down 	= '';
			$created_date   = '';

			$tzoffset       = EasyBlogDateHelper::getOffSet();
			if(!empty( $this->created ))
			{
			    $date =& JFactory::getDate( $this->created,  $tzoffset);
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
			    $date =& JFactory::getDate($this->publish_up, $tzoffset);
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
			$this->title		= trim($this->title);
			$this->permalink	= trim($this->permalink);
			
		}
		
		return true;
	}
	
	function deleteBlogTags()
	{
        $db =& $this->_db;

        if($this->id == 0)
            return false;

    	$query  = 'DELETE FROM `#__easyblog_post_tag` WHERE `post_id` = ' . $db->Quote($this->id);
        $db->setQuery($query);
        $db->query();

        return true;
	}
	
	function deleteMetas()
	{
        $db =& $this->_db;

        if($this->id == 0)
            return false;

    	$query  = 'DELETE FROM `#__easyblog_meta` WHERE `content_id` = ' . $db->Quote($this->id);
    	$query  .= ' AND `type` = ' . $db->Quote('post');
    	
        $db->setQuery($query);
        $db->query();

        return true;
	}
	
	function deleteComments()
	{
        $db =& $this->_db;

        if($this->id == 0)
            return false;

    	$query  = 'DELETE FROM `#__easyblog_comment` WHERE `post_id` = ' . $db->Quote($this->id);

        $db->setQuery($query);
        $db->query();

        return true;
	}
	
	function updateBlogContribution( $blogContribution )
	{
	    $db =& $this->_db;
	    
	    // we first remove all the associated contribution 1st.
	    // we use delete - insert method to update.
	    
	    $query  = 'DELETE FROM `#__easyblog_team_post` WHERE `post_id` = ' . $db->Quote($this->id);
        $db->setQuery($query);
        $db->query();
        

        if(! empty($blogContribution))
        {
        
        	if(is_array($blogContribution))
        	{
	            foreach($blogContribution as $bc)
	            {
	            	$post   = array();
	            	$post['team_id'] = $bc;
	            	$post['post_id'] = $this->id;

					$teamBlogPost		=& JTable::getInstance( 'TeamBlogPost', 'Table' );
	            	$teamBlogPost->bind($post);
	            	@$teamBlogPost->store();  // we supress the error here. its okay, it save to suppress it here.
	            }
        	}
        	else
        	{
            	$post   = array();
            	$post['team_id'] = $blogContribution;
            	$post['post_id'] = $this->id;
            	
				$teamBlogPost		=& JTable::getInstance( 'TeamBlogPost', 'Table' );
            	$teamBlogPost->bind($post);
            	@$teamBlogPost->store(); // we supress the error here. its okay, it save to suppress it here.
        	}
        }
	    
		return true;
	}
	
	function getTeamContributed()
	{
        $db =& $this->_db;
        
        $query  = 'SELECT a.`team_id` FROM `#__easyblog_team_post` AS a';
        $query  .= ' INNER JOIN `#__easyblog_team` AS b';
        $query  .= '   ON a.team_id = b.id';
        $query  .= ' WHERE a.`post_id` = ' . $db->Quote($this->id);
        
        $db->setQuery($query);
        $result = $db->loadResult();
        
        return $result;
	}

	/**
	 * Determines whether the current blog is accessible to
	 * the current browser.
	 * 
	 * @param	JUser	$my		Optional user object.
	 * @return	boolean		True if accessible and false otherwise.	 	 	 	 
	 **/
	public function isAccessible()
	{
		return EasyBlogHelper::getHelper( 'Privacy' )->checkPrivacy( $this );
	}

	/**
	 * Determines whether the current blog is featured or not.
	 * 
	 * @return	boolean		True if featured false otherwise	 	 	 
	 **/
	public function isFeatured()
	{
		if( $this->id == 0 )
		{
			return false;
		}

		static $loaded	= array();
		
		if( !isset( $loaded[ $this->id ] ) )
		{
			$loaded[ $this->id ]	= EasyBlogHelper::isFeatured( 'post' , $this->id );
		}
		return $loaded[ $this->id ];
	}

	public function getMetaId()
	{
        $db =& $this->_db;

        $query  = 'SELECT a.`id` FROM `#__easyblog_meta` AS a';
        $query  .= ' WHERE a.`content_id` = ' . $db->Quote($this->id);
        $query  .= ' AND a.`type` = ' . $db->Quote( 'post' );

        $db->setQuery($query);
        $result = $db->loadResult();
        
        return $result;
	}
	
	/*
	 * Process neccessary replacements here.
	 *
	 */	 
	public function store()
	{
		// @task: Process videos if neccessary
		//$this->content	= EasyBlogHelper::getHelper( 'Videos' )->processVideos( $this->content );
		//$this->intro	= EasyBlogHelper::getHelper( 'Videos' )->processVideos( $this->intro );
		
		// alway set this to false no matter what! TODO: remove this column.
		$this->ispending    = '0';
		
		return parent::store();
	}
}