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
function submitbutton(action)
{
	if(action == 'remove')
	{
	    if(confirm("<?php echo JText::_('COM_EASYBLOG_BLOGGERS_DELETE_NOTICE_CONFIRMATION'); ?>"))
	    {
            submitform( action );
	    }
	    else
	    {
			return false;
	    }
	}
	else
	{
	    submitform( action );
	}
}
</script>
<form action="index.php" method="post" name="adminForm">
<table class="adminform">
	<tr>
		<td width="40"><?php echo JText::_( 'COM_EASYBLOG_BLOGGERS_SEARCH' ); ?></td>
		<td width="200"><input type="text" name="search" id="search" value="<?php echo $this->search; ?>" class="inputbox full-width" onchange="document.adminForm.submit();" /></td>
		<td>
			<button onclick="this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_SUBMIT_BUTTON' ); ?></button>
			<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_RESET_BUTTON' ); ?></button>
		</td>
		<td width="200" style="text-align: right;">
			<?php echo JText::_( 'COM_EASYBLOG_BLOGGERS_FILTER_BY' ); ?>
			<?php echo $this->state; ?>
		</td>
	</tr>
</table>

<?php if( $this->users ) : ?>
<div class="notice" style="text-align: left !important;"><?php echo JText::_('COM_EASYBLOG_BLOGGERS_DELETE_NOTICE');?></div>
<?php endif; ?>

<table class="adminlist" cellspacing="1">
<thead>
	<tr>
		<th width="5"><?php echo JText::_( 'Num' ); ?></th>
		<?php if(empty($this->browse)) : ?>
		<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->users ); ?>);" /></th>
		<?php endif; ?>
		<th style="text-align: left;"><?php echo JHTML::_('grid.sort', JText::_( 'COM_EASYBLOG_BLOGGERS_NAME' ), 'a.name', $this->orderDirection, $this->order ); ?></th>
		<th style="text-align: left;" width="10%"><?php echo JHTML::_('grid.sort', JText::_( 'COM_EASYBLOG_BLOGGERS_USERNAME' ) , 'a.username', $this->orderDirection, $this->order ); ?></th>
		<th style="text-align: left;" width="10%"><?php echo JHTML::_('grid.sort', JText::_( 'COM_EASYBLOG_BLOGGERS_USER_GROUP' ) , 'a.usertype', $this->orderDirection, $this->order ); ?></th>
		<th style="text-align: left;" width="10%"><?php echo JHTML::_('grid.sort', JText::_( 'COM_EASYBLOG_EMAIL' ) , 'a.email', $this->orderDirection, $this->order ); ?></th>
		<th width="1%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGGERS_BLOG_ENTRIES' ); ?></th>
		<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'a.id', $this->orderDirection, $this->order ); ?></th>
	</tr>
</thead>
<tbody>
<?php
if( $this->users )
{
	$k = 0;
	$x = 0;
	for ($i=0, $n=count($this->users); $i < $n; $i++)
	{
		$row = $this->users[$i];

		$editLink 		= 'index.php?option=com_easyblog&amp;c=blogs&amp;task=edit&amp;blogid=' . $row->id;
		$previewLink	= rtrim( JURI::root() , "/" ) . "/" . JRoute::_("index.php?option=com_easyblog&view=entry&id=" . $row->id);
		$preview 	= '<a href="' . $previewLink .'" target="_blank"><img src="'.JURI::base().'/images/preview_f2.png"/ style="width:20px; height:20px; "></a>';
	?>
	<tr class="<?php echo "row$k"; ?>">
		<td>
			<?php echo $this->pagination->getRowOffset( $i ); ?>
		</td>
		<?php if(empty($this->browse)) : ?>
		<td width="7">
			<?php echo JHTML::_('grid.id', $x++, $row->id); ?>
		</td>
		<?php endif; ?>
		<td>
		<?php
		if( $this->browse )
		{
		?>
			<a href="javascript:void(0);" onclick="parent.<?php echo $this->browsefunction; ?>('<?php echo $row->id;?>','<?php echo $this->escape($row->name);?>');"><?php echo $row->name;?></a>
		<?php
		}
		else
		{
		?>
			<a href="index.php?option=com_easyblog&c=user&id=<?php echo $row->id;?>&task=edit"><?php echo $row->name;?></a>
		<?php
		}
		?>
		</td>
		<td><?php echo $row->username;?></td>
		<td><?php echo (EasyBlogHelper::getJoomlaVersion() >= '1.6') ? $row->usergroups : $row->usertype;?></td>
		<td><?php echo $row->email;?></td>
		<td align="center"><?php echo $this->getPostCount( $row->id );?></td>
		<td width="7" align="center"><?php echo $row->id;?></td>
	</tr>
	<?php $k = 1 - $k; } ?>
<?php
}
else
{
?>
	<tr>
		<td colspan="8" align="center">
			<?php echo JText::_('No user created yet.');?>
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

<?php if($this->browse): ?>
<input type="hidden" name="tmpl" value="component" />
<?php endif; ?>
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="view" value="users" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="c" value="users" />
<input type="hidden" name="browse" value="<?php echo $this->browse;?>" />
<input type="hidden" name="filter_order" value="<?php echo $this->order; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>