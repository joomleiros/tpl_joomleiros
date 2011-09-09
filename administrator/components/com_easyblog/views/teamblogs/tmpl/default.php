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
<form action="index.php" method="post" name="adminForm">
<table class="adminform">
	<tr>
		<td width="40">
		  	<?php echo JText::_( 'COM_EASYBLOG_SEARCH' ); ?>
		</td>
		<td width="200">
			<input type="text" name="search" id="search" value="<?php echo $this->search; ?>" style="width:200px;" class="inputbox" onchange="document.adminForm.submit();" />
		</td>
		<td>
			<button onclick="this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_SUBMIT_BUTTON' ); ?></button>
			<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_RESET_BUTTON' ); ?></button>
		</td>
		<td width="200" style="text-align: right;"><?php echo $this->state; ?></td>
	</tr>
</table>

<div style="margin: 0 10px 10px 0;"><a href="<?php echo JRoute::_('index.php?option=com_easyblog&view=teamrequest'); ?>" class="button"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_VIEW_REQUEST'); ?></a></div>

<table class="adminlist" cellspacing="1">
<thead>
	<tr>
		<th width="5">
			<?php echo JText::_( 'Num' ); ?>
		</th>
		<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->teams ); ?>);" /></th>	
		<th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_TEAMBLOGS_TEAM_NAME', 'a.title', $this->orderDirection, $this->order ); ?></th>
		<th width="50px" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_PUBLISHED' ); ?></th>
		<th width="150px" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_TEAMBLOGS_ACCESS' ); ?></th>
		<th width="50px" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_TEAMBLOGS_MEMBERS' ); ?></th>
		<th width="50px" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'a.id', $this->orderDirection, $this->order ); ?></th>
		<th width="50px" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_PREVIEW' ); ?></th>
	</tr>
</thead>
<tbody>
<?php
if( $this->teams )
{
	$k = 0;
	$x = 0;
	for ($i=0, $n=count($this->teams); $i < $n; $i++)
	{
		$row = $this->teams[$i];

		$editLink 		= 'index.php?option=com_easyblog&amp;c=teamblogs&amp;task=edit&amp;id=' . $row->id;
		$previewLink	= rtrim( JURI::root() , "/" ) . "/" . JRoute::_("index.php?option=com_easyblog&view=teamblog&id=" . $row->id);	
	?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
		<td width="7"><?php echo JHTML::_('grid.id', $x++, $row->id); ?></td>
		<td><a href="<?php echo $editLink;?>"><?php echo $row->title;?></a></td>
		<td align="center"><?php echo JHTML::_('grid.published', $row, $i ); ?></td>
		<td align="center"><?php echo $this->getAccessHTML( $row->access ); ?></td>
		<td align="center"><?php echo $this->getMembersCount( $row->id );?></td>
		<td width="7" align="center"><?php echo $row->id;?></td>
		<td align="center"><a href="<?php echo $previewLink;?>" target="_blank" class="preview"><?php echo JText::_('COM_EASYBLOG_PREVIEW');?></a></td>
	</tr>
	<?php $k = 1 - $k; } ?>
<?php
}
else
{
?>
	<tr>
		<td colspan="8" align="center">
			<?php echo JText::_('COM_EASYBLOG_NO_TEAM_BLOGS_CREATED_YET');?>
		</td>
	</tr>
<?php
}
?>
</tbody>

<tfoot>
	<tr>
		<td colspan="11">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
</tfoot>
</table>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="view" value="teamblogs" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="c" value="teamblogs" />
<input type="hidden" name="filter_order" value="<?php echo $this->order; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>