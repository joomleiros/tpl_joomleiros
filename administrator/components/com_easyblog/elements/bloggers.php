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

class JElementBloggers extends JElement
{
	var	$_name = 'Bloggers';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$mainframe	=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		$doc 		=& JFactory::getDocument();
		
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_easyblog' . DS . 'constants.php' );

		// get only bloggers

		$query	= ' (SELECT a.`id`, a.`name`, a.`username`';
		$query	.= ' FROM `#__users` AS `a`';
		$query	.= '  INNER JOIN `#__core_acl_aro` AS `c` ON a.`id` = c.`value`';
		$query	.= '    AND c.`section_value` = ' . $db->Quote('users');
		$query	.= '  INNER JOIN `#__core_acl_groups_aro_map` AS `d` ON c.`id` = d.`aro_id`';
		$query	.= '  INNER JOIN `#__easyblog_acl_group` AS `e` ON d.`group_id`  = e.`content_id`';
		$query	.= '    AND e.`type` = ' . $db->Quote('group') . ' AND e.`status` = 1';
		$query	.= '  INNER JOIN `#__easyblog_acl` as `f` ON e.`acl_id` = f.`id`';
		$query	.= '    AND f.`action` = ' . $db->Quote('add_entry');
		$query 	.= '  GROUP BY a.`id`';
		$query 	.= ' )';

		$query	.= ' UNION ';
		$query	.= ' (SELECT a1.`id`, a1.`name`, a1.`username`';
		$query	.= ' FROM `#__users` AS `a1`';
		$query	.= '  INNER JOIN `#__easyblog_acl_group` AS `c1` ON a1.`id`  = c1.`content_id`';
		$query	.= '    AND c1.`type` = ' . $db->Quote('assigned') . ' AND c1.`status` = 1';
		$query	.= '  INNER JOIN `#__easyblog_acl` as `d1` ON c1.`acl_id` = d1.`id`';
		$query	.= '    AND d1.`action` = ' . $db->Quote('add_entry');
		$query 	.= '  GROUP BY a1.`id`';
		$query 	.= ' ) ORDER BY `name` ASC';
		
		$db->setQuery($query);
		$data		= $db->loadObjectList();
		
		ob_start();
		?>
		<select name="<?php echo $control_name;?>[<?php echo $name;?>]">
			<option value="0"<?php echo $value == 0 ? ' selected="selected"' :'';?>><?php echo JText::_('Select a blogger');?></option>
		<?php

		if(count($data) > 0)
		{
			foreach($data as $blogger)
			{
				$selected	= $blogger->id == $value ? ' selected="selected"' : '';
			?>
			<option value="<?php echo $blogger->id;?>"<?php echo $selected;?>><?php echo $blogger->name . ' (' . $blogger->username . ')'; ?></option>
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
