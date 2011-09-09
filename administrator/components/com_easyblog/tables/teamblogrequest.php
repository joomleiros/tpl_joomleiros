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

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class TableTeamBlogRequest extends JTable
{
	var $id			= null;
	var $team_id	= null;
	var $user_id	= null;
	var $ispeding   = null;
	var $created    = null;

	/**
	 * Constructor for this class.
	 *
	 * @return
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_team_request' , 'id' , $db );
	}
	
	
	function exists()
	{
		$db		=& JFactory::getDBO();

		$query	= 'SELECT COUNT(1) FROM #__easyblog_team_request '
				. 'WHERE `team_id`=' . $db->Quote( $this->team_id ) . ' '
				. 'AND `user_id`=' . $db->Quote( $this->user_id ) . ' '
				. 'AND `ispending` = ' . $db->Quote('1');
		$db->setQuery( $query );

		return $db->loadResult() > 0 ? true : false;
	}
}