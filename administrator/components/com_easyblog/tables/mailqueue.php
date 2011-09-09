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

class TableMailQueue extends JTable
{
	/*
	 * The id of the mail queue
	 * @var int
	 */
	var $id 					= null;

	/*
	 * sender email
	 * @var string
	 */
	var $mailfrom		        = null;

	/*
	 * sender name (optional)
	 * @var string
	 */
	var $fromname				= null;

	/*
	 * recipient email
	 * @var string
	 */
	var $recipient				= null;

	/*
	 * email subject
	 * @var string
	 */
	var $subject				= null;
	
	/*
	 * email body
	 * @var string
	 */
	var $body					= null;
	
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
		parent::__construct( '#__easyblog_mailq' , 'id' , $db );
	}

}