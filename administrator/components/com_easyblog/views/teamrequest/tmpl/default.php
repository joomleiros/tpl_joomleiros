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

<table class="adminlist" cellspacing="1">

<thead>
	<tr class="page_row_white">
		<th width="20" align="center">#</th>
		<th><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_NAME'); ?></th>
		<th><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_REQUESTOR'); ?></th>
		<th><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_REQUEST_DATE'); ?></th>
		<th style="width: 250px; text-align: center;"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_APPROVAL'); ?></th>
	</tr>
</thead>
<tbody>
<?php
if( count($this->requests) > 0 )
{
	$k = 0;
	$x = 0;
	for ($i=0, $n=count($this->requests); $i < $n; $i++)
	{
	    $entry  = $this->requests[$i];

		$requestor	=& JFactory::getUser( $entry->user_id );
		$user		=& JTable::getInstance( 'Profile' , 'Table' );
		$user->setUser($requestor);
		$created	= EasyBlogDateHelper::dateWithOffSet($entry->created);
?>
	<tr class="<?php echo "row$k"; ?>">
		<td valign="top" style="text-align: center;"><?php echo $i + 1; ?></td>
		<td valign="top" class="item-data">
			<?php echo $entry->title; ?>
		</td>
		<td valign="top">
			<?php echo $user->getName(); ?>
		</td>
		<td>
		    <?php echo EasyBlogDateHelper::toFormat($created, $this->config->get('layout_dateformat', '%A, %d %B %Y') ); ?>
		</td>
		<td style="text-align: center;">
			<div class="item_actions" id="eblog-comment-toolbar<?php echo $entry->id; ?>">
                <a href="<?php echo JRoute::_('index.php?option=com_easyblog&c=teamblogs&task=teamApproval&id='.$entry->id.'&team='.$entry->team_id.'&approve=1&' . JUtility::getToken() . '=1' ); ?>" class="text-green"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_APPROVE_REQUEST'); ?></a> |
				<a href="<?php echo JRoute::_('index.php?option=com_easyblog&c=teamblogs&task=teamApproval&id='.$entry->id.'&team='.$entry->team_id.'&approve=0&' . JUtility::getToken() . '=1' ); ?>" class="text-red"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_REJECT_REQUEST'); ?></a>
			</div>
		</td>
	</tr>
<?php
    $k = 1 - $k;
	}//end for
}
else
{
?>
<tr>
	<td colspan="5" height="30">
		<div style="text-align: center; height: 30px; line-height: 30px;"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_NO_REQUEST'); ?></div>
	</td>
</tr>
<?php
} //end if else
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
<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="view" value="teamrequest" />
<input type="hidden" name="task" value="teamApproval" />
<input type="hidden" name="c" value="teamblogs" />
</form>