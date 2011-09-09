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

class TablePostReject extends JTable
{
	var $id 			= null;
	var $draft_id		= null;
	var $message		= null;
	
	/**
	 * Constructor for this class.
	 *
	 * @return
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_post_rejected' , 'id' , $db );
	}

	public function clear( $draft_id )
	{
		// Delete any existing rejected messages
		$db		= JFactory::getDBO();
		$query	= 'DELETE FROM ' . $db->nameQuote( '#__easyblog_post_rejected' ) . ' WHERE '
				. $db->nameQuote( 'draft_id' ) . '=' . $db->Quote( $draft_id );

		$db->setQuery( $query );
		$db->Query();
		
		return true;
	}
		
	public function store()
	{
		$this->clear( $this->draft_id );
		
		return parent::store();
	}
}