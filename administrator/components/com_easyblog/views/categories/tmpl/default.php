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

$ordering   = true;
?>
<form action="index.php" method="post" name="adminForm">
<table class="adminform">
	<tr>
		<td width="40">
		  	<?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_SEARCH' ); ?>
		</td>
		<td width="200">
			<input type="text" name="search" id="search" value="<?php echo $this->search; ?>" class="inputbox full-width" onchange="document.adminForm.submit();" />
		</td>
		<td>
			<button onclick="this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_SUBMIT_BUTTON' ); ?></button>
			<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_RESET_BUTTON' ); ?></button>
		</td>
		<td width="200" style="text-align: right;">
			<?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_FILTER_BY' ); ?>
		  <?php echo $this->state; ?>
		</td>
	</tr>
</table>
<table class="adminlist" cellspacing="1">
<thead>
	<tr>
		<th width="1%"><?php echo JText::_( 'Num' ); ?></th>
		<th width="1%"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->categories ); ?>);" /></th>
		<th class="title" style="text-align: left;"><?php echo JHTML::_('grid.sort', JText::_( 'COM_EASYBLOG_CATEGORIES_CATEGORY_TITLE' ) , 'title', $this->orderDirection, $this->order ); ?></th>
		<th width="5%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_PRIVACY' ); ?></th>
		<th width="5%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_PUBLISHED' ); ?></th>
		<th width="8%">
			<?php echo JHTML::_('grid.sort',   'Order', 'ordering', $this->orderDirection, $this->order ); ?>
			<?php echo JHTML::_('grid.order',  $this->categories ); ?>
		</th>
		<th width="5%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_ENTRIES' ); ?></th>
		<th width="5%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_CHILD_COUNT' ); ?></th>
		<th class="title" width="8%"><?php echo JHTML::_('grid.sort', JText::_( 'COM_EASYBLOG_CATEGORIES_AUTHOR' ) , 'created_by', $this->orderDirection, $this->order ); ?></th>
		<th class="title" width="1%"><?php echo JText::_('COM_EASYBLOG_PREVIEW');?></th>
		<th width="1%"><?php echo JText::_( 'COM_EASYBLOG_ID' ); ?></th>
	</tr>
</thead>
<tbody>
<?php
if( $this->categories )
{

	$k = 0;
	$x = 0;
	for ($i=0, $n=count($this->categories); $i < $n; $i++)
	{
		$row = $this->categories[$i];

		$link 			= 'index.php?option=com_easyblog&amp;c=category&amp;task=edit&amp;catid='. $row->id;
		$previewLink	= JURI::root() . 'index.php?option=com_easyblog&amp;view=categories&layout=listBlogs&id=' . $row->id;
		$published 	= JHTML::_('grid.published', $row, $i );
		$user		=& JFactory::getUser( $row->created_by ); 
	?>
	<tr class="<?php echo "row$k"; ?>">
		<td align="center"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
		<td><?php echo JHTML::_('grid.id', $x++, $row->id); ?></td>
		<td align="left">
			<span class="editlinktip hasTip"><a href="<?php echo $link; ?>"><?php echo $row->title; ?></a></span>
		</td>
		
		<td align="center">
			<?php echo ( $row->private ) ? JText::_('COM_EASYBLOG_CATEGORIES_PRIVATE') : JText::_('COM_EASYBLOG_CATEGORIES_PUBLIC') ?>
		</td>
		
		<td align="center">
			<?php echo $published; ?>
		</td>
		<td class="order">
			<span><?php echo $this->pagination->orderUpIcon( $i, true, 'orderup', 'Move Up', $ordering); ?></span>
			<span><?php echo $this->pagination->orderDownIcon( $i, $n, true, 'orderdown', 'Move Down', $ordering ); ?></span>
			<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
			<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="text_area" style="text-align: center" />
		</td>
		<td align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_easyblog&view=blogs&filter_category=' . $row->id);?>"><?php echo $row->count;?></a>
		</td>
		<td align="center">
			<?php echo $row->child_count; ?>
		</td>
		<td align="center">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit category' );?>::<?php echo $row->title; ?>">
				<a href="<?php echo JRoute::_('index.php?option=com_easyblog&c=user&id=' . $row->created_by . '&task=edit'); ?>"><?php echo $user->name; ?></a>
			</span>
		</td>
		<td align="center"><a href="<?php echo $previewLink; ?>" target="_blank" class="preview"><?php echo JText::_('COM_EASYBLOG_PREVIEW');?></a></td>
		<td align="center"><?php echo $row->id;?></td>
	</tr>
	<?php $k = 1 - $k; } ?>
<?php
}
else
{
?>
	<tr>
		<td colspan="12" align="center">
			<?php echo JText::_('No category created yet.');?>
		</td>
	</tr>
<?php
}
?>
</tbody>

<tfoot>
	<tr>
		<td colspan="12">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
</tfoot>
</table>
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="view" value="categories" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="c" value="category" />
<input type="hidden" name="filter_order" value="<?php echo $this->order; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>