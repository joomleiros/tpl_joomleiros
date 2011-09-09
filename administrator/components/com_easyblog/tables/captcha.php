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

class TableCaptcha extends JTable
{
	var $id			= null;
	var $response	= null;
	var $created	= null;
	
	/**
	 * Constructor for this class.
	 * 
	 * @return 
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_captcha' , 'id' , $db );
	}

	/**
	 * Verify response
	 * @param	$response	The response code given.
	 * @return	boolean		True on success, false otherwise.	 	 
	 **/
	function verify( $response )
	{
		return $this->response == $response;
	}


	/**
	 * Delete the outdated entries.
	 */
	function clear()
	{
	    $db =& JFactory::getDBO();
	    
	    $query  = 'DELETE FROM `#__easyblog_captcha` WHERE `created` <= DATE_SUB( NOW(), INTERVAL 12 HOUR )';
	    $db->setQuery($query);
	    $db->query();
	    
	    return true;
	}
}