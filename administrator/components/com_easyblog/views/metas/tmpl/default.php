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
		  	<?php echo JText::_( 'Search' ); ?>
		</td>
		<td width="200">
			<input type="text" name="search" id="search" value="<?php echo $this->search; ?>" class="inputbox full-width" onchange="document.adminForm.submit();" />
		</td>
		<td>
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
		<td width="200" style="text-align:right">
		  <?php echo $this->filter->type; ?>
		</td>
	</tr>
</table>

<table class="adminlist" cellspacing="1">
<thead>
	<tr>
		<th width="1%">
			<?php echo JText::_( 'Num' ); ?>
		</th>
		<th width="1%" align="center" style="text-align: center;">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->meta ); ?>);" />
		</th>
		<th class="title" style="text-align: left;" width="30%"><?php echo JText::_('Title'); ?></th>
		<th class="title" style="text-align: left;" width="30%"><?php echo JText::_('Keywords'); ?></th>
		<th class="title" style="text-align: left;" width="30%"><?php echo JText::_('Description'); ?></th>
		<th class="title" style="text-align: left;" width="40"><?php echo JHTML::_('grid.sort', 'Type' , 'type', $this->orderDirection, $this->order ); ?></th>
		<th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID' , 'id', $this->orderDirection, $this->order ); ?></th>
	</tr>
</thead>
<tbody>
<?php
if( $this->meta )
{
	$k = 0;
	$x = 0;
	for ($i=0, $n=count($this->meta); $i < $n; $i++)
	{
		
		$row = $this->meta[$i];
		
		if ( $row->id != 30 ) {
		
		$link 			= 'index.php?option=com_easyblog&amp;view=meta&amp;id='. $row->id;
	?>
	<tr class="<?php echo "row$k"; ?>">
		<td align="center">
			<?php echo $this->pagination->getRowOffset( $i ); ?>
		</td>
		<td width="7" align="center" style="text-align: center;">
			<?php echo JHTML::_('grid.id', $x++, $row->id); ?>
		</td>
		<td align="left">
			<span class="editlinktip hasTip">
			<a href="<?php echo $link; ?>">
			<?php echo $row->title; ?>
			</a></span>
		</td>
		<td align="left">
			<?php echo $row->keywords; ?>
		</td>
		<td align="left">
			<?php echo $row->description; ?>
		</td>
		<td align="left">
			<?php echo $row->type; ?>
		</td>
		<td align="center">
			<?php echo $row->id;?>
		</td>

	</tr>
	<?php $k = 1 - $k; 
		}
	} 
	?>
<?php
}
else
{
?>
	<tr>
		<td colspan="7" align="center">
			<?php echo JText::_('No Meta Tags configured yet.');?>
		</td>
	</tr>
<?php
}
?>
</tbody>

<tfoot>
	<tr>
		<td colspan="7">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
</tfoot>
</table>

<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="view" value="metas" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="redirect" value="-1" />
<input type="hidden" name="filter_order" value="<?php echo $this->order; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>