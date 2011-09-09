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

class TableFeedburner extends JTable
{
	var $id			= null;
	var $userid		= null;
	var $url		= null;

	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_feedburner' , 'id' , $db );
	}
	
	function load($id)
	{
	    $db		=& $this->getDBO();

	    $query  = 'select `id` FROM ' . $db->nameQuote( $this->_tbl );
	    $query  .= ' where userid = ' . $db->Quote( $id );

	    $db->setQuery( $query );

	    $result = $db->loadResult();

	    if(empty($result))
	    {
	        $this->userid  = $id;
	        return $this;
	    }
	    else
	    {
			return parent::load($result);
	    }

	}
	
	function store()
	{
		$db		=& $this->getDBO();
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( $this->_tbl ) . ' '
				. 'WHERE `userid`=' . $db->Quote( $this->userid );
		$db->setQuery( $query );

		if( $db->loadResult() )
		{
			return $db->updateObject( $this->_tbl, $this, $this->_tbl_key );
		}
		else
		{
			$obj			= new stdClass();
			$obj->userid	= $this->userid;
			$obj->url		= $this->url;

			return $db->insertObject( $this->_tbl, $obj, $this->_tbl_key );
		}
	}
}