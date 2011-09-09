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
<div id="config-document">
	<div id="page-joomla" class="tab">
	    <div>
			<table class="noshow">
				<tr>
					<td><?php echo $this->loadTemplate('joomla');?></td>
				</tr>
			</table>
		</div>
	</div>
	<div id="page-smartblog" class="tab">
	    <div>
			<table class="noshow">
				<tr>
					<td><?php echo $this->loadTemplate('smartblog');?></td>
				</tr>
			</table>
		</div>
	</div>
	<div id="page-lyften" class="tab">
	    <div>
			<table class="noshow">
				<tr>
					<td><?php echo $this->loadTemplate('lyften');?></td>
				</tr>
			</table>
		</div>
	</div>
	<div id="page-myblog" class="tab">
	    <div>
			<table class="noshow">
				<tr>
					<td><?php echo $this->loadTemplate('myblog');?></td>
				</tr>
			</table>
		</div>
	</div>
	
	<!-- div id="page-jomcomment">
		<table class="noshow">
			<tr>
				<td><?php echo $this->loadTemplate('jomcomment');?></td>
			</tr>
		</table>
	</div>
	<div id="page-myblog">
		<table class="noshow">
			<tr>
				<td><?php echo $this->loadTemplate('myblog');?></td>
			</tr>
		</table>
	</div>
	<div id="page-jcomments">
		<table class="noshow">
			<tr>
				<td><?php echo $this->loadTemplate('jcomments');?></td>
			</tr>
		</table>
	</div>
	<div id="page-mxcomments">
		<table class="noshow">
			<tr>
				<td><?php echo $this->loadTemplate('mxcomments');?></td>
			</tr>
		</table>
	</div -->
</div>
<div class="clr"></div>