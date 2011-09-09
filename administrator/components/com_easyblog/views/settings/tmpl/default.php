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
<script type="text/javascript">
<?php
if($this->joomlaversion >= '1.6')
{
?>

Joomla.submitbutton = function(task){
	sQuery('#submenu li').children().each( function(){
		if( sQuery(this).hasClass( 'active' ) )
		{
			sQuery( '#active' ).val( sQuery(this).attr('id') );
		}
	});
	
	sQuery('dl#subtabs').children().each( function(){
		if( sQuery(this).hasClass( 'open' ) )
		{
			sQuery( '#activechild' ).val( sQuery(this).attr('class').split(" ")[0] );
		}
		
	});
	
	Joomla.submitform(task);
}

<?php
}
else
{
?>

function submitbutton( action )
{
	sQuery('#submenu li').children().each( function(){
		if( sQuery(this).hasClass( 'active' ) )
		{
			sQuery( '#active' ).val( sQuery(this).attr('id') );
		}
	});
	
	sQuery('dl#subtabs').children().each( function(){
		if( sQuery(this).hasClass( 'open' ) )
		{
			sQuery( '#activechild' ).val( sQuery(this).attr('id') );
		}
	});
	
	submitform( action );
}

<?php
}
?>
</script>
<form action="index.php" method="post" name="adminForm" id="settingsForm">
<div id="config-document">
	<div id="page-main" class="tab">
	    <div>
			<table class="noshow">
				<tr>
					<td><?php echo $this->loadTemplate('main');?></td>
				</tr>
			</table>
		</div>
	</div>
	<div id="page-ebloglayout" class="tab">
	    <div>
			<table class="noshow">
				<tr>
					<td><?php echo $this->loadTemplate('layout');?></td>
				</tr>
			</table>
		</div>
	</div>
	<div id="page-comments" class="tab">
	    <div>
			<table class="noshow">
				<tr>
					<td><?php echo $this->loadTemplate('comments');?></td>
				</tr>
			</table>
		</div>
	</div>
	<div id="page-integrations" class="tab">
	    <div>
			<table class="noshow">
				<tr>
					<td><?php echo $this->loadTemplate('integrations');?></td>
				</tr>
			</table>
		</div>
	</div>
	<div id="page-notifications" class="tab">
	    <div>
			<table class="noshow">
				<tr>
					<td><?php echo $this->loadTemplate('notifications');?></td>
				</tr>
			</table>
		</div>
	</div>
	<div id="page-social" class="tab">
	    <div>
			<table class="noshow">
				<tr>
					<td><?php echo $this->loadTemplate('social');?></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<div class="clr"></div>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="active" id="active" value="" />
<input type="hidden" name="activechild" id="activechild" value="" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="c" value="settings" />
</form>