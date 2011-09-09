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

class TableUserApps extends JTable
{
	/*
	 * The id of the user apps
	 * @var int
	 */
	var $id          = null;

	/*
	 * The apps id foreign key for apps table
	 * @var string
	 */
	var $app_id    = null;

	/*
	 * usr id, foreign key for user table 
	 * @var string
	 */
	var $user_id = null;

	/*
	 * Date when this apps record is created.
	 * @var string
	 */
	var $created         = null;
	
	/*
	 * Date when this apps record is modified.
	 * @var string
	 */
	var $modified      = null;	
	
	/*
	 * publish 
	 * @var string
	 */
	var $published      = null;
	
	/*
	 * order.
	 * @var string
	 */
	var $ordering      = null;
		
	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db ){
		parent::__construct( '#__easyblog_userapps' , 'id' , $db );
	}
	
	/**
	 * override load method.
	 * if user record not found in eblog_profile, create one record.
	 *    	 
	 */
   	function load($id){   		
		if((! parent::load($id)) && ($id != 0)){
			$db	=& $this->getDBO();
			
			$obj				= new stdClass();
			$obj->app_id		= '';
			$obj->user_id		= '';
			$obj->created 		= '';
			$obj->modified		= '';
			$obj->published		= '';
			$obj->ordering		= '';
			
			$db->insertObject('#__easyblog_userapps', $obj);
		}
		
		return parent::load($id);
   	}
}