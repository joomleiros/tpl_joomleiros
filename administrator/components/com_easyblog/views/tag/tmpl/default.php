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
<div id="config-document">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_EASYBLOG_ADD_NEW_TAG'); ?></legend>
		
		<table cellspacing="0" cellpadding="0" border="0" width="50%">
			<tr>
				<td valign="top">
					<table class="admintable">
						<tr>
							<td class="key">
								<label for="catname">
									<?php echo JText::_( 'COM_EASYBLOG_TAG' ); ?>
								</label>
							</td>
							<td>
								<input class="inputbox" name="title" size="55" maxlength="255" value="<?php echo $this->tag->title;?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<label for="alias">
									<?php echo JText::_( 'COM_EASYBLOG_TAG_ALIAS' ); ?>
								</label>
							</td>
							<td>
								<input class="inputbox" name="alias" size="55" maxlength="255" value="<?php echo $this->tag->alias;?>" />
							</td>
						</tr>
						<tr>													
							<td class="key">
								<label for="published"><?php echo JText::_( 'COM_EASYBLOG_PUBLISHED' ); ?></label>
							</td>
							<td>
								<?php echo JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $this->tag->published ); ?>
							</td>
						</tr>
						<tr style="display: none;">
							<td>
								<label for="created">
									<?php echo JText::_( 'COM_EASYBLOG_CREATED' ); ?>
								</label>
							</td>
							<td>
								<?php echo JHTML::_('calendar', $this->tag->created , "created", "created"); ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<?php echo JHTML::_( 'form.token' ); ?>
		<input type="hidden" name="option" value="com_easyblog" />
		<input type="hidden" name="c" value="tag" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="tagid" value="<?php echo $this->tag->id;?>" />
	</fieldset>
</form>
</div>