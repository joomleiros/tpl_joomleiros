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

class TableHashKeys extends JTable
{
	var $id			= null;
	var $uid		= null;
	var $type		= null;
	var $key		= null;
	
	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_hashkeys' , 'id' , $db );
	}

	public function load( $uid , $type )
	{
		$db		= JFactory::getDBO();
		$query	= 'SELECT * FROM ' . $db->nameQuote( $this->_tbl ) .  ' WHERE '
				. $db->nameQuote( 'uid' ) . '=' . $db->Quote( $uid ) . ' AND '
				. $db->nameQuote( 'type' ) . '=' . $db->Quote( $type );
		$db->setQuery( $query );
		
		$data	= $db->loadObject();
		
		return parent::bind( $data );
	}

	public function loadByKey( $key )
	{
		$db		= JFactory::getDBO();
		$query	= 'SELECT * FROM ' . $db->nameQuote( $this->_tbl ) .  ' WHERE '
				. $db->nameQuote( 'key' ) . '=' . $db->Quote( $key );
		$db->setQuery( $query );
		
		$data	= $db->loadObject();
		
		return parent::bind( $data );
	}
	
	public function store()
	{
		if( empty($this->key ) )
		{
			$this->key	= $this->generate();
		}
		return parent::store();
	}
	
	/**
	 * Verify response
	 * @param	$response	The response code given.
	 * @return	boolean		True on success, false otherwise.	 	 
	 **/
	function verify( $response )
	{
	}
	
	/*
	 * Generates a hashkey
	 *
	 * @param	null
	 * @return	string	Returns an md5 generated key.
	 */
	public function generate()
	{
		return JString::substr( md5( $this->uid . $this->type . JFactory::getDate()->toMySQL() ) , 0 , 12 );
	}
}