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

$eConfig =& EasyBlogHelper::getConfig();
?>
<form action="index.php" method="post" name="adminForm">
<table class="adminform">
	<tr>
		<td width="40">
		  	<?php echo JText::_( 'COM_EASYBLOG_BLOGS_SEARCH' ); ?>
		</td>
		<td width="200">
			<input type="text" name="search" id="search" value="<?php echo $this->search; ?>" class="inputbox full-width" onchange="document.adminForm.submit();" />
		</td>
		<td>
			<button onclick="this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_SUBMIT_BUTTON' ); ?></button>
			<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_RESET_BUTTON' ); ?></button>
		</td>
		<td width="300" align="right" style="text-align:right">
			<?php echo JText::_( 'COM_EASYBLOG_BLOGS_FILTER_BY' ); ?>
			<?php echo $this->category; ?>
			<?php echo $this->state; ?>
		</td>
	</tr>
</table>
<table class="adminlist" cellspacing="1">
<thead>
	<tr>
		<th width="5"><?php echo JText::_( 'Num' ); ?></th>
		<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->blogs ); ?>);" /></th>
		<th class="title"><?php echo JHTML::_('grid.sort', 'Title', 'a.title', $this->orderDirection, $this->order ); ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_CONTRIBUTED_IN' ); ?></th>
		<th width="1%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_FEATURED' ); ?></th>
		<th width="1%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_PUBLISHED' ); ?></th>
		<th width="1%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_FRONTPAGE' ); ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_CATEGORY' ); ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_AUTHOR' ); ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_DATE', 'a.created', $this->orderDirection, $this->order ); ?></th>
		<th width="3%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_HITS' );?></th>
		<th width="20" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_ID', 'a.id', $this->orderDirection, $this->order ); ?></th>
		<th width="1%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_PREVIEW' ); ?></th>
	</tr>
</thead>
<tbody>
<?php
if( $this->blogs )
{
	$k = 0;
	$x = 0;
	$config	= JFactory::getConfig();
	for ($i=0, $n=count($this->blogs); $i < $n; $i++)
	{
		$row = $this->blogs[$i];
		$user		=& JFactory::getUser( $row->created_by ); 
		$previewLink	= EasyBlogRouter::getRoutedURL('index.php?option=com_easyblog&view=entry&id=' . $row->id, true, true);
		$preview 	= '<a href="' . $previewLink .'" target="_blank"><img src="'.JURI::base().'/images/preview_f2.png"/ style="width:20px; height:20px; "></a>';
		$editLink	= JRoute::_('index.php?option=com_easyblog&c=blogs&task=edit&blogid='.$row->id);
		$published 	= JHTML::_('grid.published', $row, $i );
		$date		= EasyBlogDateHelper::getDate($row->created);
	?>
	<tr class="<?php echo "row$k"; ?>">
		<td>
			<?php echo $this->pagination->getRowOffset( $i ); ?>
		</td>
		<td width="7">
			<?php echo JHTML::_('grid.id', $x++, $row->id); ?>
		</td>
		<td align="left">
			<span>
				<a href="<?php echo $editLink; ?>">
				<?php echo $row->title; ?>
				</a>
			</span>
		</td>
		<td align="center">
			<?php echo ($row->issitewide) ? JText::_('COM_EASYBLOG_BLOGS_WIDE') : $row->teamname; ?>
		</td>
		<td align="center">
			<?php if( EasyBlogHelper::getJoomlaVersion() <= '1.5' ){ ?>
				<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo ( EasyBlogHelper::isFeatured( EBLOG_FEATURED_BLOG , $row->id ) ) ? 'unfeature' : 'feature';?>')"><img src="images/<?php echo ( EasyBlogHelper::isFeatured( EBLOG_FEATURED_BLOG , $row->id ) ) ? 'tick.png' : 'publish_x.png';?>" width="16" height="16" border="0" /></a>
			<?php } else { ?>
				<?php echo JHTML::_( 'grid.boolean' , $i , EasyBlogHelper::isFeatured( EBLOG_FEATURED_BLOG , $row->id ) , 'feature' , 'unfeature' ); ?>
			<?php } ?>
		</td>
		<td align="center">
		    <?php if($row->published == 2) : ?>
		    <img src="<?php echo JURI::base() . 'components/com_easyblog/assets/images/schedule.png';?>" border="0" alt="<?php echo JText::_('COM_EASYBLOG_SCHEDULED');?>" />
		    <?php elseif($row->published == 3) : ?>
		    <img src="<?php echo JURI::base() . 'components/com_easyblog/assets/images/draft.png';?>" border="0" alt="<?php echo JText::_('COM_EASYBLOG_DRAFT');?>" />
		    <?php else: ?>
			<?php echo $published; ?>
			<?php endif; ?>
		</td>
		<td align="center">
			<?php if( EasyBlogHelper::getJoomlaVersion() <= '1.5' ){ ?>
				<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','toggleFrontpage')"><img src="images/<?php echo ( $row->frontpage ) ? 'tick.png' : 'publish_x.png';?>" width="16" height="16" border="0" /></a>
			<?php } else { ?>
				<?php echo JHTML::_( 'grid.boolean' , $i , $row->frontpage , 'toggleFrontpage' , 'toggleFrontpage' ); ?>
			<?php } ?>
		</td>
		<td align="center">
			<a href="<?php echo JRoute::_('index.php?option=com_easyblog&c=category&task=edit&catid=' . $row->category_id);?>"><?php echo $this->getCategoryName( $row->category_id);?></a>
		</td>
		<td align="center">
			<span class="editlinktip hasTip">
				<a href="<?php echo JRoute::_('index.php?option=com_easyblog&c=user&id=' . $row->created_by . '&task=edit'); ?>"><?php echo $user->name; ?></a>
			</span>
		</td>
		<td align="center">
			<?php echo EasyBlogDateHelper::toFormat($date); ?>
		</td>
		<td align="center"><?php echo $row->hits;?></td>
		<td align="center"><?php echo $row->id; ?></td>
		<td align="center">
<?php
		    if($row->published == 1)
		    {
?>
			<a href="<?php echo $previewLink;?>" target="_blank" class="preview"><?php echo JText::_('COM_EASYBLOG_PREVIEW');?></a>
<?php
			}
			else
			{
				echo 'N/A';
			}
?>
		</td>
	</tr>
	<?php $k = 1 - $k; } ?>
<?php
}
else
{
?>
	<tr>
		<td colspan="13" align="center">
			<?php echo JText::_('COM_EASYBLOG_BLOGS_NO_ENTRIES');?>
		</td>
	</tr>
<?php
}
?>
</tbody>

<tfoot>
	<tr>
		<td colspan="13">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
</tfoot>
</table>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="view" value="blogs" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="c" value="blogs" />
<input type="hidden" name="filter_order" value="<?php echo $this->order; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>