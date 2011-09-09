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
<form action="index.php?option=com_easyblog" method="post" name="adminForm">
	<table class="adminform">
		<tr>
			<td width="40">
			  	<?php echo JText::_( 'COM_EASYBLOG_ACL_SEARCH' ); ?>
			</td>
			<td width="200">
				<input type="text" name="search" id="search" value="<?php echo $this->filter->search; ?>" class="inputbox full-width" onchange="document.adminForm.submit();" />
			</td>
			<td>
				<button onclick="this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_SUBMIT_BUTTON' ); ?></button>
				<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'COM_EASYBLOG_RESET_BUTTON' ); ?></button>
			</td>
			<td width="200" style="text-align: right;">
				<?php echo JText::_( 'COM_EASYBLOG_ACL_FILTER_BY' ); ?>
				<?php echo $this->filter->type; ?>
			</td>
		</tr>
	</table>
	<table class="adminlist" cellpadding="1">
	<thead>
		<tr>
			<th class="title" style="width:1%;"><?php echo JText::_('Num'); ?></th>
			<th class="title" style="width:1%;"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( (array)$this->rulesets ); ?>);" /></th>
			<th class="title" style="text-align:left;"><?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_GROUP_NAME', 'a.`name`', $this->sort->orderDirection, $this->sort->order ); ?></th>
			<th class="title" style="width:5%;"><?php echo JText::_('COM_EASYBLOG_ACTION'); ?></th>
			<th class="title" style="width:1%;"><?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_ID', 'a.`id`', $this->sort->orderDirection, $this->sort->order ); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="5">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
<?php

	$k = 0;
	$x = 0;
	$count = 0;
	foreach($this->rulesets as $ruleset)
	{
		$tips 		= '';
		$iconPath	= 'components/com_easyblog/assets/images';
		
		foreach($ruleset as $key=>$value)
		{
			if($key!='name' && $key!='id' && $key != 'level')
			{
				$tipImg = empty($value)? $iconPath . '/publish_x.png' : $iconPath .'/tick.png';
				$tips .= '<div style="float:left; width:145px;">'.JText::_('COM_EASYBLOG_ACL_OPTION_' . $key).'</div><div style="float:left; width:10px;">:</div><div style="float:left;"><img src="'.$tipImg.'" /></div><div style="clear:both"></div>';
			}
		}
		
		$editlink = 'index.php?option=com_easyblog&c=acl&task=edit&cid='.$ruleset->id.'&type='.$this->type;
?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pagination->getRowOffset( $count ); ?></td>
			<td><?php echo JHTML::_('grid.id', $x++, $ruleset->id); ?></td>		
			<td class="editlinktip hasTip" title="<?php echo EasyBlogStringHelper::escape( $ruleset->name ); ?>::<?php echo EasyBlogStringHelper::escape( $tips ); ?>">
				<?php echo str_repeat('<span class="gi">|&mdash;</span>', $ruleset->level) ?>
				<a href="<?php echo JRoute::_($editlink);?>"><?php echo $ruleset->name; ?></a>
			</td>
			<td style="text-align:center;"><a href="<?php echo JRoute::_($editlink);?>"><?php echo JText::_('COM_EASYBLOG_EDIT');?></a></td>
			<td>
				<?php echo $ruleset->id;?>
			</td>
		</tr>
<?php
		$k = 1 - $k;
		$count++;
	}
?>
	</tbody>
	</table>
	<input type="hidden" name="option" value="com_easyblog" />
	<input type="hidden" name="view" value="acls" />
	<input type="hidden" name="c" value="acl" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->sort->order; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<input type="hidden" name="boxchecked" value="0" />
</form>