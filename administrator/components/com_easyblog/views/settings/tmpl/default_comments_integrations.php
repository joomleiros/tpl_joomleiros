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
<table class="noshow">
	<tr>
		<td width="50%" valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_OTHER_COMMENT_TITLE' ); ?></legend>
			<p><?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_OTHER_COMMENT_DESC');?></p>
			<table class="admintable" cellspacing="1">
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_FACEBOOK_COMMENTS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_FACEBOOK_COMMENTS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_FACEBOOK_COMMENTS' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'comment_facebook' , $this->config->get( 'comment_facebook' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_INTENSE_DEBATE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_INTENSE_DEBATE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_INTENSE_DEBATE' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'comment_intensedebate' , $this->config->get( 'comment_intensedebate' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_INTENSE_DEBATE_CODE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_INTENSE_DEBATE_CODE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_INTENSE_DEBATE_CODE' ); ?>
						</span>
					</td>
					<td valign="top">
						<textarea name="comment_intensedebate_code" class="inputbox full-width" rows="5" cols="50"><?php echo $this->config->get('comment_intensedebate_code');?></textarea>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_DISQUS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_DISQUS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_DISQUS' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'comment_disqus' , $this->config->get( 'comment_disqus' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_DISQUS_CODE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_DISQUS_CODE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_DISQUS_CODE' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" name="comment_disqus_code" class="inputbox" value="<?php echo $this->config->get('comment_disqus_code');?>" style="width: 100px;" />
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_JOMCOMMENT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_JOMCOMMENT_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_JOMCOMMENT' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php
							if($this->jcInstalled)
							{
								echo $this->renderCheckbox( 'comment_jomcomment' , $this->config->get( 'comment_jomcomment' ) );
							}
							else
							{
							?>
							<span><?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_JOMCOMMENT_NOT_FOUND'); ?></span>
							<?php
							}
						?>
					</td>
				</tr>
				
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_JCOMMENT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_JCOMMENT_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_JCOMMENT' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php
							if($this->jcommentInstalled)
							{
								echo $this->renderCheckbox( 'comment_jcomments' , $this->config->get( 'comment_jcomments' ) );
							}
							else
							{
							?>
							<span><?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_JCOMMENT_NOT_FOUND'); ?></span>
							<?php
							}
						?>
					</td>
				</tr>
				
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_RSCOMMENTS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_RSCOMMENTS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_COMMENTS_RSCOMMENTS' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php
							if($this->rscommentInstalled)
							{
								echo $this->renderCheckbox( 'comment_rscomments' , $this->config->get( 'comment_rscomments' ) );
							}
							else
							{
							?>
							<span><?php echo JText::_('COM_EASYBLOG_SETTINGS_COMMENTS_RSCOMMENTS_NOT_FOUND'); ?></span>
							<?php
							}
						?>
					</td>
				</tr>
			</table>
			</fieldset>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>