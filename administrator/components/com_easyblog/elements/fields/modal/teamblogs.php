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

class JFormFieldModal_TeamBlogs extends JFormField
{

	protected $type = 'Modal_TeamBlogs';
	
	protected function getInput()
	{
		$mainframe	=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		$doc 		=& JFactory::getDocument();
		
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_easyblog' . DS . 'constants.php' );
		
		$options 	= array();
  		$attr 	 	= '';
  		$teamblogs	=array();

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

		// get teamblogs

		$query	= 'SELECT a.`id`, a.`title` FROM ' . $db->nameQuote( '#__easyblog_team' ) . ' AS a';
		$query	.= ' LEFT JOIN `#__easyblog_team_users` AS b ON a.`id` = b.`team_id` ';
		$query	.= ' WHERE a.`published` = ' . $db->Quote( '1' );
		$query	.= ' GROUP BY a.`id` HAVING (count(b.`team_id`) > 0)';
		$query	.= ' ORDER BY a.`id` DESC';
		
		$db->setQuery($query);
		$data		= $db->loadObjectList();

		if(count($data) > 0)
		{
			$optgroup = JHTML::_('select.optgroup','Select a team','id','title');
	 		array_push($teamblogs,$optgroup);
	 		
			foreach ($data as $row) {
			    $opt    = new stdClass();
			    $opt->id    = $row->id;
			    $opt->title = '(' . $row->id . ') ' . $row->title;

				array_push($teamblogs,$opt);
			}
		}


		$html = JHTML::_('select.genericlist',  $teamblogs, $this->name, trim($attr), 'id', 'title', $this->value );
		return $html;
	}
}
