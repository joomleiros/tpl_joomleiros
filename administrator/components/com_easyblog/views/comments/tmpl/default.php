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
		<td width="40"><?php echo JText::_( 'COM_EASYBLOG_COMMENTS_SEARCH' ); ?></td>
		<td width="200">
			<input type="text" name="search" id="search" value="<?php echo $this->search; ?>" class="inputbox full-width" onchange="document.adminForm.submit();" />
		</td>
		<td>
			<button onclick="this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_SUBMIT_BUTTON' ); ?></button>
			<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_RESET_BUTTON' ); ?></button>
		</td>
		<td width="200" style="text-align: right;">
			<?php echo JText::_( 'COM_EASYBLOG_COMMENTS_FILTER_BY' );?><?php echo $this->state; ?>
		</td>
	</tr>
</table>

<table class="adminlist" cellspacing="1">
<thead>
	<tr>
		<th width="1%"><?php echo JText::_( 'Num' ); ?></th>
		<th width="1%"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->comments ); ?>);" /></th>
		<th class="title" style="text-align: left;"><?php echo JText::_('COM_EASYBLOG_COMMENTS_COMMENT');?></th>
		<th class="title" width="20%"><?php echo JHTML::_('grid.sort', JText::_( 'COM_EASYBLOG_COMMENTS_BLOG_TITLE' ), 'blog_name', $this->orderDirection, $this->order ); ?></th>
		<th width="1%"><?php echo JText::_( 'COM_EASYBLOG_COMMENTS_PUBLISHED' ); ?></th>
		<th class="title" width="10%"><?php echo JHTML::_('grid.sort', JText::_( 'COM_EASYBLOG_COMMENTS_DATE' ), 'created', $this->orderDirection, $this->order ); ?></th>
		<th class="title" width="5%"><?php echo JHTML::_('grid.sort', JText::_( 'COM_EASYBLOG_COMMENTS_AUTHOR' ) , 'created_by', $this->orderDirection, $this->order ); ?></th>
		<th width="1%"><?php echo JText::_( 'COM_EASYBLOG_ID' ); ?></th>
	</tr>
</thead>
<tbody>
<?php
if( $this->comments )
{
	$k = 0;
	$x = 0;
	$config	= JFactory::getConfig();
	for ($i=0, $n=count($this->comments); $i < $n; $i++)
	{
		$row			= $this->comments[$i];
		$date			= JFactory::getDate( $row->created );
		$date->setOffset(  $config->getValue('offset')  );
		$link 			= 'index.php?option=com_easyblog&amp;c=comment&amp;task=edit&amp;commentid='. $row->id;
		$userlink		= JURI::root() . 'index.php?option=com_easyblog&amp;view=blogger&amp;layout=listBlogs&amp;id='. $row->created_by;
	?>
	<tr class="<?php echo "row$k"; ?>">
		<td>
			<?php echo $this->pagination->getRowOffset( $i ); ?>
		</td>
		<td>
			<?php echo JHTML::_('grid.id', $x++, $row->id); ?>
		</td>
		<td align="left">
			<span class="editlinktip hasTip">
				<?php if( !empty( $row->title) ){ ?>
					<a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
				<?php }else{ ?>
					<a href="<?php echo $link; ?>"><?php echo JText::_( 'COM_EASYBLOG_COMMENTS_NO_TITLE'); ?></a>
				<?php } ?>
			</span>
			<div>
				<?php echo $row->comment;?>
			</div>
		</td>
		<td align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_easyblog&c=blogs&task=edit&blogid=' . $row->post_id);?>"><?php echo $row->blog_name; ?></a>
		</td>
		<td align="center">
			<?php echo JHTML::_('grid.published', $row, $i ); ?>
		</td>
		
		<td align="center">
			<?php echo $date->toMySQL( true );?>
		</td>
		<td align="center">
			<?php if ( $row->created_by == 0 ) : ?>
				<?php echo $row->name; ?>
			<?php else : ?>
				<a href="<?php echo JRoute::_('index.php?option=com_easyblog&c=user&id=' . $row->created_by . '&task=edit'); ?>"><?php echo $row->name; ?></a>
			<?php endif; ?>
		</td>
		<td align="center"><?php echo $row->id; ?></td>
	</tr>
	<?php $k = 1 - $k; } ?>
<?php
}
else
{
?>
	<tr>
		<td colspan="8" align="center">
			<?php echo JText::_('COM_EASYBLOG_COMMENTS_NO_COMMENT_YET');?>
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
<input type="hidden" name="view" value="comments" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="c" value="comment" />
<input type="hidden" name="filter_order" value="<?php echo $this->order; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>