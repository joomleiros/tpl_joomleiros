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
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="top" width="50%">
			<table class="noshow">
				<tr>
					<td width="50%" valign="top">
						<a name="main_config" id="main_config"></a>
						<fieldset class="adminform">
						<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_GENERAL_TITLE' ); ?></legend>
						<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_BLOG_TITLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_BLOG_TITLE_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_BLOG_TITLE' ); ?>
								</span>
								</td>
								<td valign="top">
									<input type="text" name="main_title" class="inputbox full-width" value="<?php echo $this->config->get('main_title');?>" />
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_BLOG_DESCRIPTION' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_BLOG_DESCRIPTION_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_BLOG_DESCRIPTION' ); ?>
								</span>
								</td>
								<td valign="top">
									<textarea name="main_description" rows="5" class="inputbox  full-width textarea" cols="35"><?php echo $this->config->get('main_description');?></textarea> 
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MAXIMUM_RELATED_POSTS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_MAXIMUM_RELATED_POSTS_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MAXIMUM_RELATED_POSTS' ); ?>
								</span>
								</td>
								<td valign="top">
									<input type="text" name="main_max_relatedpost" class="inputbox" style="width: 50px;" maxlength="2" value="<?php echo $this->config->get('main_max_relatedpost', '5' );?>" />
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ORPHANED_ITEMS_OWNER' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ORPHANED_ITEMS_OWNER_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ORPHANED_ITEMS_OWNER' ); ?>
								</span>
								</td>
								<td valign="top">
									<input type="text" name="main_orphanitem_ownership" class="inputbox" style="width: 50px;" value="<?php echo $this->config->get('main_orphanitem_ownership', $this->defaultSAId );?>" />
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_JOOMLA_USER_PARAMETERS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_JOOMLA_USER_PARAMETERS_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_JOOMLA_USER_PARAMETERS' ); ?>
								</span>
								</td>
								<td valign="top" class="value">
									<?php echo $this->renderCheckbox( 'main_joomlauserparams' , $this->config->get( 'main_joomlauserparams' , true ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_EDIT_ACCOUNT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_EDIT_ACCOUNT_DESC'); ?>">
										<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_EDIT_ACCOUNT' ); ?>
									</span>
								</td>
								<td valign="top">
									<?php echo $this->renderCheckbox( 'main_dashboard_editaccount' , $this->config->get( 'main_dashboard_editaccount' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_DISPLAY_AUTHOR_INFO' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_DISPLAY_AUTHOR_INFO_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_DISPLAY_AUTHOR_INFO' ); ?>
								</span>
								</td>
								<td valign="top" class="value">
									<?php echo $this->renderCheckbox( 'main_showauthorinfo' , $this->config->get( 'main_showauthorinfo' , true ) );?> 
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_TRACKBACKS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_TRACKBACKS_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_TRACKBACKS' ); ?>
								</span>
								</td>
								<td valign="top" class="value">
									<?php echo $this->renderCheckbox( 'main_trackbacks' , $this->config->get( 'main_trackbacks' , true ) );?>
								</td>
							</tr>
							<tr>
								<td class="key" width="300">
									<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_RSS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_RSS_DESC'); ?>">
										<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_RSS' ); ?>
									</span>
								</td>
								<td valign="top" class="value">
									<?php echo $this->renderCheckbox( 'main_rss' , $this->config->get( 'main_rss' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_RELATED_POSTS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_RELATED_POSTS_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_RELATED_POSTS' ); ?>
								</span>
								</td>
								<td valign="top" class="value">
									<?php echo $this->renderCheckbox( 'main_relatedpost' , $this->config->get( 'main_relatedpost' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_PASSWORD_PROTECTION' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_PASSWORD_PROTECTION_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_PASSWORD_PROTECTION' ); ?>
								</span>
								</td>
								<td valign="top" class="value">
									<?php echo $this->renderCheckbox( 'main_password_protect' , $this->config->get( 'main_password_protect' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_AUTOMATIC_FEATURE_BLOG_POST' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_AUTOMATIC_FEATURE_BLOG_POST_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_AUTOMATIC_FEATURE_BLOG_POST' ); ?>
								</span>
								</td>
								<td valign="top" class="value">
									<?php echo $this->renderCheckbox( 'main_autofeatured' , $this->config->get( 'main_autofeatured' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_HIDE_INTROTEXT_IN_ENTRY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_HIDE_INTROTEXT_IN_ENTRY_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_HIDE_INTROTEXT_IN_ENTRY' ); ?>
								</span>
								</td>
								<td valign="top" class="value">
									<?php echo $this->renderCheckbox( 'main_hideintro_entryview' , $this->config->get( 'main_hideintro_entryview' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_HIDE_EMPTY_CATEGORIES' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_HIDE_EMPTY_CATEGORIES_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_HIDE_EMPTY_CATEGORIES' ); ?>
								</span>
								</td>
								<td valign="top" class="value">
									<?php echo $this->renderCheckbox( 'main_categories_hideempty' , $this->config->get( 'main_categories_hideempty' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_NEW_ENTRY_ON_FRONTPAGE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_NEW_ENTRY_ON_FRONTPAGE_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_NEW_ENTRY_ON_FRONTPAGE' ); ?>
								</span>
								</td>
								<td valign="top" class="value">
								<?php
			  						$frontpageFormat = array();
									$frontpageFormat[] = JHTML::_('select.option', '0', JText::_( 'COM_EASYBLOG_NO_OPTION' ) );
									$frontpageFormat[] = JHTML::_('select.option', '1', JText::_( 'COM_EASYBLOG_YES_OPTION' ) );
									echo JHTML::_('select.genericlist', $frontpageFormat, 'main_newblogonfrontpage', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('main_newblogonfrontpage' ) );
								?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_DEFAULT_BLOG_PRIVACY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_DEFAULT_BLOG_PRIVACY_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_DEFAULT_BLOG_PRIVACY' ); ?>
								</span>
								</td>
								<td valign="top">
								<?php
			  						$nameFormat = array();
									$nameFormat[] = JHTML::_('select.option', '0', JText::_( 'COM_EASYBLOG_PUBLIC_OPTION' ) );
									$nameFormat[] = JHTML::_('select.option', '1', JText::_( 'COM_EASYBLOG_PRIVATE_OPTION' ) );
									$showdet = JHTML::_('select.genericlist', $nameFormat, 'main_blogprivacy', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('main_blogprivacy' ) );
									echo $showdet;
								?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_DEFAULT_BLOG_PUBLISHING_STATUS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_DEFAULT_BLOG_PUBLISHING_STATUS_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_DEFAULT_BLOG_PUBLISHING_STATUS' ); ?>
								</span>
								</td>
								<td valign="top">
								<?php
			  						$publishFormat = array();
									$publishFormat[] = JHTML::_('select.option', '0', JText::_( 'COM_EASYBLOG_UNPUBLISHED_OPTION' ) );
									$publishFormat[] = JHTML::_('select.option', '1', JText::_( 'COM_EASYBLOG_PUBLISHED_OPTION' ) );
									$publishFormat[] = JHTML::_('select.option', '2', JText::_( 'COM_EASYBLOG_SCHEDULED_OPTION' ) );
									$publishFormat[] = JHTML::_('select.option', '3', JText::_( 'COM_EASYBLOG_DRAFT_OPTION' ) );
									
									$showdet = JHTML::_('select.genericlist', $publishFormat, 'main_blogpublishing', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('main_blogpublishing' , '1' ) );
									echo $showdet;
								?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_DAYLIGHT_SAVING_TIME' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_DAYLIGHT_SAVING_TIME_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_DAYLIGHT_SAVING_TIME' ); ?>
								</span>
								</td>
								<td valign="top">
									<?php echo $this->dstList; ?>
								</td>
							</tr>
							</tbody>
						</table>
						</fieldset>
					</td>
				</tr>			
			</table>
		</td>
		<td valign="top">
			<table class="noshow">
				<tr>
					<td>
						<fieldset class="adminform">
						<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_PERMALINKS_TITLE' ); ?></legend>
						<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td width="300" class="key" style="vertical-align: top !important;">
									<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_SEF_FORMAT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_SEF_FORMAT_DESC'); ?>">
										<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_SEF_FORMAT' ); ?>
									</span>
									<span><?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_SEF_FORMAT_NOTICE');?></span>
								</td>
								<td valign="top">
									<div>
										<input type="radio" class="inputbox" value="default" id="main_sef0" name="main_sef"<?php echo $this->config->get('main_sef') == 'default' ? ' checked="checked"' : '';?>>
										<label for="main_sef0">
											<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_SEF_FORMAT_TITLE_TYPE');?>
										</label>
									</div>
									<div style="clear: both;">
										<input type="radio" class="inputbox" value="date" id="main_sef1" name="main_sef"<?php echo $this->config->get('main_sef') == 'date' ? ' checked="checked"' : '';?>>
										<label for="main_sef1">
											<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_SEF_FORMAT_DATE_TYPE');?>
										</label>
									</div>
									<div style="clear: both;">
										<input type="radio" class="inputbox" value="category" id="main_sef2" name="main_sef"<?php echo $this->config->get('main_sef') == 'category' ? ' checked="checked"' : '';?>>
										<label for="main_sef2">
											<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_SEF_FORMAT_CATEGORY_TYPE');?>
										</label>
									</div>
									<div style="clear: both;">
										<input type="radio" class="inputbox" value="datecategory" id="main_sef3" name="main_sef"<?php echo $this->config->get('main_sef') == 'datecategory' ? ' checked="checked"' : '';?>>
										<label for="main_sef3">
											<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_SEF_FORMAT_CATEGORY_DATE_TYPE');?>
										</label>
									</div>
									<div style="clear: both;">
										<input type="radio" class="inputbox" value="simple" id="main_sef4" name="main_sef"<?php echo $this->config->get('main_sef') == 'simple' ? ' checked="checked"' : '';?>>
										<label for="main_sef4">
											<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_SEF_FORMAT_SIMPLE_TYPE');?>
										</label>
									</div>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_SEF_ENABLE_UNICODE_ALIAS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_SEF_ENABLE_UNICODE_ALIAS_DESC'); ?>">
										<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_SEF_ENABLE_UNICODE_ALIAS' ); ?>
									</span>
								</td>
								<td class="value">
									<?php echo $this->renderCheckbox( 'main_sef_unicode' , $this->config->get( 'main_sef_unicode' ) );?>
								</td>
							</tr>
							</tbody>
						</table>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td>
						<fieldset class="adminform">
						<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_RATINGS_TITLE' ); ?></legend>
						<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td width="300" class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_RATINGS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_RATINGS_DESC'); ?>">
										<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_RATINGS' ); ?>
									</span>
								</td>
								<td class="value">
									<?php echo $this->renderCheckbox( 'main_ratings' , $this->config->get( 'main_ratings' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_RATINGS_FRONTPAGE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_RATINGS_FRONTPAGE_DESC'); ?>">
										<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_RATINGS_FRONTPAGE' ); ?>
									</span>
								</td>
								<td class="value">
									<?php echo $this->renderCheckbox( 'main_ratings_frontpage' , $this->config->get( 'main_ratings_frontpage' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_LOCKED_ON_FRONTPAGE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_LOCKED_ON_FRONTPAGE_DESC'); ?>">
										<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_LOCKED_ON_FRONTPAGE' ); ?>
									</span>
								</td>
								<td class="value">
									<?php echo $this->renderCheckbox( 'main_ratings_frontpage_locked' , $this->config->get( 'main_ratings_frontpage_locked' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_GUEST_RATING' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_GUEST_RATING_DESC'); ?>">
										<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ALLOW_GUEST_RATING' ); ?>
									</span>
								</td>
								<td class="value">
									<?php echo $this->renderCheckbox( 'main_ratings_guests' , $this->config->get( 'main_ratings_guests' ) );?>
								</td>
							</tr>
							</tbody>
						</table>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td>
						<fieldset class="adminform">
						<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_AUTODRAFTING_TITLE' ); ?></legend>
						<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td width="300" class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_AUTODRAFTING' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_AUTODRAFTING_DESC'); ?>">
										<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_AUTODRAFTING' ); ?>
									</span>
								</td>
								<td class="value">
									<?php echo $this->renderCheckbox( 'main_autodraft' , $this->config->get( 'main_autodraft' ) );?>
								</td>
							</tr>
							<tr>
								<td width="300" class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_AUTODRAFTING_INTERVAL' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_AUTODRAFTING_INTERVAL_DESC'); ?>">
										<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_AUTODRAFTING_INTERVAL' ); ?>
									</span>
								</td>
								<td class="value">
									<input type="text" name="main_autodraft_interval" class="inputbox" style="width: 30px;text-align: center;" value="<?php echo $this->config->get('main_autodraft_interval', '0' );?>" />
									<?php echo JText::_( 'COM_EASYBLOG_SECONDS' );?>
								</td>
							</tr>
							</tbody>
						</table>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td>
						<fieldset class="adminform">
						<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MAILSPOOL_TITLE' ); ?></legend>
						<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MAILSPOOL_SENDMAIL_ON_PAGE_LOAD' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_MAILSPOOL_SENDMAIL_ON_PAGE_LOAD_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MAILSPOOL_SENDMAIL_ON_PAGE_LOAD' ); ?>
								</span>
								</td>
								<td valign="top" class="value">
									<?php echo $this->renderCheckbox( 'main_mailqueueonpageload' , $this->config->get( 'main_mailqueueonpageload' ) );?>
								</td>
							</tr>
							
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MAILSPOOL_SENDMAIL_HTML' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_MAILSPOOL_SENDMAIL_HTML_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_MAILSPOOL_SENDMAIL_HTML' ); ?>
								</span>
								</td>
								<td valign="top" class="value">
									<?php echo $this->renderCheckbox( 'main_mailqueuehtmlformat' , $this->config->get( 'main_mailqueuehtmlformat' ) );?>
								</td>
							</tr>
							</tbody>
						</table>
						</fieldset>
					</td>
				</tr>
				
				<tr>
					<td>
						<fieldset class="adminform">
						<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_TEAMBLOG_TITLE' ); ?></legend>
						<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_TEAMBLOG_INCLUDE_TEAMBLOG_POSTS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_TEAMBLOG_INCLUDE_TEAMBLOG_POSTS_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_TEAMBLOG_INCLUDE_TEAMBLOG_POSTS' ); ?>
								</span>
								</td>
								<td valign="top" class="value">
									<?php echo $this->renderCheckbox( 'main_includeteamblogpost' , $this->config->get( 'main_includeteamblogpost' ) );?>
								</td>
							</tr>
							</tbody>
						</table>
						</fieldset>
					</td>
				</tr>
				
				<tr>
					<td>
						<fieldset class="adminform">
						<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_METADATA_TITLE' ); ?></legend>
						<table class="admintable" cellspacing="1">
							<tbody>
							<tr>
								<td width="300" class="key">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_METADATA_AUTO_FILL_KEYWORDS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_WORKFLOW_METADATA_AUTO_FILL_KEYWORDS_DESC'); ?>">
									<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_WORKFLOW_METADATA_AUTO_FILL_KEYWORDS' ); ?>
								</span>
								</td>
								<td valign="top" class="value">
									<?php echo $this->renderCheckbox( 'main_meta_autofillkeywords' , $this->config->get( 'main_meta_autofillkeywords' ) );?>
								</td>
							</tr>
							</tbody>
						</table>
						</fieldset>
					</td>
				</tr>
				
			</table>
		</td>
	</tr>
</table>