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

class TableMeta extends JTable
{
	/*
	 * The id of the category
	 * @var int
	 */
	var $id 						= null;

	/*
	 * The keywords
	 * @var int
	 */
	var $keywords					= null;

	/*
	 * The description
	 * @var string
	 */
	var $description				= null;
	
	/*
	 * Content ID
	 * @var string
	 */
	var $content_id					= null;	

	/*
	 * Created datetime of the category
	 * @var datetime
	 */
	var $type				= null;


	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_meta' , 'id' , $db );
	}

	function loadByType( $type , $id )
	{
		$db		=& $this->getDBO();		
		$query	= 'SELECT * FROM ' . $this->_tbl . ' '
				. 'WHERE `type`=' . $db->Quote( $type ) . ' '
				. 'AND `content_id`=' . $db->Quote( $id );
		$db->setQuery( $query );

		$data	= $db->loadObject();
		return parent::bind( $data );
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
		
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__easyblog_post' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'category_id' ) . '=' . $db->Quote( $this->id );
		$db->setQuery( $query );
		
		$count	= $db->loadResult();
	
		if( $count > 0 )
		{
			return false;
		}
		
		return parent::delete();
	}

	
	/**
	 * Overrides parent's bind method to add our own logic.
	 * 
	 * @param Array $data	 
	 **/	 	 	
	function bind( $data )
	{
		parent::bind( $data );
	}
}