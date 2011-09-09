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

class TableRatings extends JTable
{
	/*
	 * The primary key for this table.
	 * @var int
	 */
	var $id 					= null;

	/**
	 * Universal id
	 * @var int
	 */
	var $uid		        = null;

	/**
	 * Rating type
	 * @var string
	 */
	var $type					= null;

	/*
	 * site member id
	 * @var int
	 */
	var $created_by				= null;

	/*
	 * Session id (optional)
	 * @var string
	 */
	var $sessionid				= null;

	/*
	 * Contains the value of the rating
	 * @var string
	 */
	var $value					= null;

	/*
	 * IP address of voter
	 * @var string
	 */
	var $ip						= null;

	/*
	 * Created datetime of the tag
	 * @var datetime
	 */
	var $published				= null;

	/*
	 * Created datetime of the tag
	 * @var datetime
	 */
	var $created				= null;

	/**
	 * Constructor for this class.
	 *
	 * @return
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_ratings' , 'id' , $db );
	}

	public function load( $userId , $postId , $type , $hash = '' )
	{
		static $objects	= array();

		if( !isset( $objects[ $type ][ $postId ] ) )
		{
			$db		=& $this->getDBO();
			$query	= 'SELECT * FROM ' . $db->nameQuote( $this->_tbl ) . ' '
					. 'WHERE ' . $db->nameQuote( 'created_by' ) . '=' . $db->Quote( $userId ) . ' '
					. 'AND ' . $db->nameQuote( 'uid' ) . '=' . $db->Quote( $postId ) . ' '
					. 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $type );

			if( !empty($hash) )
			{
				$query	.= ' AND ' . $db->nameQuote( 'sessionid' ) . '=' . $db->Quote( $hash );
			}
			$db->setQuery( $query );

			$result	= $db->loadObject();

			if (is_null($result))
			{
				return false;
			}

			$objects[ $type ][ $postId ] = $result;
		}

		return parent::bind( $objects[ $type ][ $postId ] );
	}
}