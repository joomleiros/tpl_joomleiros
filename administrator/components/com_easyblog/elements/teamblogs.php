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

class JElementTeamBlogs extends JElement
{
	var	$_name = 'Teamblogs';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$mainframe	=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		$doc 		=& JFactory::getDocument();
		
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_easyblog' . DS . 'constants.php' );

		// get only bloggers

		$query	= 'SELECT a.`id`, a.`title` FROM ' . $db->nameQuote( '#__easyblog_team' ) . ' AS a';
		$query	.= ' LEFT JOIN `#__easyblog_team_users` AS b ON a.`id` = b.`team_id` ';
		$query	.= ' WHERE a.`published` = ' . $db->Quote( '1' );
		$query	.= ' GROUP BY a.`id` HAVING (count(b.`team_id`) > 0)';
		$query	.= ' ORDER BY a.`id` DESC';
		
		$db->setQuery($query);
		$data		= $db->loadObjectList();
		
		ob_start();
		?>
		<select name="<?php echo $control_name;?>[<?php echo $name;?>]">
			<option value="0"<?php echo $value == 0 ? ' selected="selected"' :'';?>><?php echo JText::_('Select a team');?></option>
		<?php

		if(count($data) > 0)
		{
			foreach($data as $team)
			{
				$selected	= $team->id == $value ? ' selected="selected"' : '';
			?>
			<option value="<?php echo $team->id;?>"<?php echo $selected;?>><?php echo '(' . $team->id . ') ' . $team->title; ?></option>
		<?php
			}
		}
		?>
		</select>
		<?php
		$html	= ob_get_contents();
		ob_end_clean();
		return $html;
	}
}
