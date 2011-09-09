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
		<td width="100%">
		  	<?php echo JText::_( 'COM_EASYBLOG_SEARCH' ); ?>
			<input type="text" name="search" id="search" value="<?php echo $this->search; ?>" class="text_area inputbox" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_SUBMIT_BUTTON' ); ?></button>
			<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_RESET_BUTTON' ); ?></button>
		</td>
		<td nowrap="nowrap" style="text-align: right;"><?php echo $this->filterList; ?></td>
	</tr>
</table>
<table class="adminlist" cellspacing="1">
<thead>
	<tr>
		<th width="5">
			<?php echo JText::_( 'Num' ); ?>
		</th>
		<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->subscriptions ); ?>);" /></th>
		<?php if($this->filter == 'blogger') : ?>
			<th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_BLOGGER_NAME', 'bname', $this->orderDirection, $this->order ); ?></th>
		<?php elseif($this->filter == 'blog') : ?>
		    <th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_BLOG_TITLE', 'bname', $this->orderDirection, $this->order ); ?></th>
		<?php elseif($this->filter == 'category') : ?>
		    <th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_CATEGORY_TITLE', 'bname', $this->orderDirection, $this->order ); ?></th>
		<?php elseif($this->filter == 'team') : ?>
		    <th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_TEAM_TITLE', 'bname', $this->orderDirection, $this->order ); ?></th>
		<?php endif; ?>
		<th width="300px" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_SUBSCRIBER_EMAIL' ); ?></th>
		<th width="300px" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_SUBSCRIBER_NAME' ); ?></th>
		<th width="150px" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_SUBSCRIPTION_DATE' ); ?></th>
		<th width="50px" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_ID', 'a.id', $this->orderDirection, $this->order ); ?></th>
	</tr>
</thead>
<tbody>
<?php
if( $this->subscriptions )
{
	$k = 0;
	$x = 0;
	for ($i=0, $n=count($this->subscriptions); $i < $n; $i++)
	{
		$row = $this->subscriptions[$i];
	?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
		<td width="7" align="center"><?php echo JHTML::_('grid.id', $x++, $row->id); ?></td>
		<?php if($this->filter != 'site') : ?>
		<td><?php echo $row->bname;?><?php echo ($this->filter == 'blogger') ? ' (' . $row->busername. ')' : ''; ?></td>
		<?php endif;?>
		<td align="center"><?php echo $row->email;?></td>
		<td align="center"><?php echo (empty($row->name)) ? $row->fullname :  $row->name;?></td>
		<td align="center"><?php echo $row->created; ?></td>
		<td align="center"><?php echo $row->id;?></td>
	</tr>
	<?php $k = 1 - $k; } ?>
<?php
}
else
{
?>
	<tr>
		<td colspan="7" align="center">
			<?php echo JText::_('COM_EASYBLOG_NO_SUBSCRIPTION_FOUND');?>
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
<input type="hidden" name="view" value="subscriptions" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="c" value="subscriptions" />
<input type="hidden" name="filter_order" value="<?php echo $this->order; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>