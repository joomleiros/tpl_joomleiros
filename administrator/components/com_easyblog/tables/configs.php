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

class TableConfigs extends JTable
{
	/*
	 * The key of the current config
	 * @var string
	 */
	var $name = null;

	/*
	 * Raw parameters values. 
	 * @var string
	 */
	var $params	= null;

	
	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_configs' , 'name' , $db );
	}

	/**
	 * Save the configuration
	 **/	 	
	function store( $key = 'config' )
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__easyblog_configs') . ' '
				. 'WHERE ' . $db->nameQuote( 'name' ) . '=' . $db->Quote( $key );
		$db->setQuery( $query );
		
		$exists	= ( $db->loadResult() > 0 ) ? true : false;

		$data			= new stdClass();
		$data->name		= $this->name;
		$data->params	= trim( $this->params );

		if( $exists )
		{
			return $db->updateObject( '#__easyblog_configs' , $data , 'name' );
		}

		return $db->insertObject( '#__easyblog_configs' , $data );
	}	
}