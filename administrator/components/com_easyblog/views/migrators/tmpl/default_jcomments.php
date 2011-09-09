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
?>
<form action="" method="post" name="adminForm" id="adminForm">
<table class="noshow">
	<tr>		
		<td width="50%" valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'JComments' ); ?></legend>
			
			</fieldset>
		</td>				
		<td>
			<fieldset class="adminform" style="height:200px;">
				<legend><?php echo JText::_( 'Progress' ); ?></legend>
				<div id="no-progress">No progress yet.</div>
				<div id="progress-status"  style="overflow:auto; height:98%;"></div>
			</fieldset>
			
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Statistic' ); ?></legend>
			</fieldset>				
		</td>
	</tr>		
</table>		

<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="c" value="migrators" />
</form>