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

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldModal_Bloggers extends JFormField
{

	protected $type = 'Modal_Bloggers';
	
	protected function getInput()
	{
		$mainframe	=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		$doc 		=& JFactory::getDocument();
		
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_easyblog' . DS . 'constants.php' );
		
		$options 	= array();
  		$attr 	 	= '';
  		$bloggers	=array();

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ( (string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
			$attr .= ' disabled="disabled"';
		}

		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		// get only bloggers

		$query	= ' (SELECT a.`id`, a.`name`, a.`username`';
		$query	.= ' FROM `#__users` AS `a`';
		$query	.= '  INNER JOIN `#__user_usergroup_map` AS `d` ON a.`id` = d.`user_id`';
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
		$query 	.= ' )';
		
		$db->setQuery($query);
		$data		= $db->loadObjectList();

		if(count($data) > 0)
		{
			$optgroup = JHTML::_('select.optgroup','Select blogger','id','title');
	 		array_push($bloggers,$optgroup);
	 		
			foreach ($data as $row) {
			    $opt    = new stdClass();
			    $opt->id    = $row->id;
			    $opt->title = $row->name . ' (' . $row->username . ')';

				array_push($bloggers,$opt);
			}
		}


		$html = JHTML::_('select.genericlist',  $bloggers, $this->name, trim($attr), 'id', 'title', $this->value );
		return $html;
	}
}
