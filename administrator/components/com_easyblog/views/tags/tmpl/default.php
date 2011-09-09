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
			<input type="text" name="search" id="search" value="<?php echo $this->search; ?>" class="inputbox full-width" onchange="document.adminForm.submit();" />
		</td>
		<td>
			<button onclick="this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_SUBMIT_BUTTON' ); ?></button>
			<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_RESET_BUTTON' ); ?></button>
		</td>
		<td width="200" style="text-align: right;"><?php echo $this->state; ?></td>
	</tr>
</table>

<table class="adminlist" cellspacing="1">
<thead>
	<tr>
		<th width="1%">
			<?php echo JText::_( 'Num' ); ?>
		</th>
		<th width="1%"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->tags ); ?>);" /></th>
		<th class="title" style="text-align: left;" width="30%"><?php echo JHTML::_('grid.sort', 'Title' , 'title', $this->orderDirection, $this->order ); ?></th>
		<th class="title" style="text-align: left;" width="30%"><?php echo JHTML::_('grid.sort', 'Alias' , 'alias', $this->orderDirection, $this->order ); ?></th>
		<th width="1%" nowrap="nowrap"><?php echo JText::_( 'Published' ); ?></th>
		<th width="5%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_TAGS_ENTRIES' ); ?></th>
		<th class="title" width="10%"><?php echo JHTML::_('grid.sort', 'Creator', 'created_by', $this->orderDirection, $this->order ); ?></th>
		<th class="title" width="1%"><?php echo JText::_('COM_EASYBLOG_PREVIEW');?></th>
	</tr>
</thead>
<tbody>
<?php
if( $this->tags )
{
	$k = 0;
	$x = 0;
	for ($i=0, $n=count($this->tags); $i < $n; $i++)
	{
		$row 	= $this->tags[$i];
		$user	=& JFactory::getUser( $row->created_by ); 
	?>
	<tr class="<?php echo "row$k"; ?>">
		<td>
			<?php echo $this->pagination->getRowOffset( $i ); ?>
		</td>
		<td width="7">
			<?php echo JHTML::_('grid.id', $x++, $row->id); ?>
		</td>
		<td align="left">
			<span class="editlinktip hasTip">
				<a href="<?php echo JRoute::_('index.php?option=com_easyblog&amp;c=tag&amp;task=edit&amp;tagid='. $row->id); ?>"><?php echo $row->title; ?></a>
			</span>
		</td>
		<td align="left">
			<span class="editlinktip hasTip">
				<a href="<?php echo JRoute::_('index.php?option=com_easyblog&amp;c=tag&amp;task=edit&amp;tagid='. $row->id); ?>"><?php echo $row->alias; ?></a>
			</span>
		</td>
		<td align="center">
			<?php echo JHTML::_('grid.published', $row, $i ); ?>
		</td>
		<td align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_easyblog&view=blogs&tagid=' . $row->id);?>"><?php echo $row->count;?></a>
		</td>
		<td align="center">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit User' );?>::<?php echo $user->name; ?>">
				<a href="<?php echo JRoute::_('index.php?option=com_easyblog&c=user&id=' . $row->created_by . '&task=edit'); ?>"><?php echo $user->name; ?></a>
			</span>
		</td>
		<td align="center">
			<a href="<?php echo JURI::root() . 'index.php?option=com_easyblog&amp;view=tags&layout=tag&id=' . $row->id; ?>" target="_blank" class="preview"><?php echo JText::_('Preview');?></a>
		</td>
	</tr>
	<?php $k = 1 - $k; } ?>
<?php
}
else
{
?>
	<tr>
		<td colspan="8" align="center">
			<?php echo JText::_('No tags created yet.');?>
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
<input type="hidden" name="view" value="tags" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="c" value="tag" />
<input type="hidden" name="filter_order" value="<?php echo $this->order; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>