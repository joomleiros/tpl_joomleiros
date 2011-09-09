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
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DISPLAY_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_BLOG_THEME' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_BLOG_THEME_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_BLOG_THEME' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->getThemes( $this->config->get('layout_theme' ) ); ?> 
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_TOTAL_LATEST_POSTS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_TOTAL_LATEST_POSTS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_TOTAL_LATEST_POSTS' ); ?>
					</span>
					</td>
					<td valign="top">
					<?php
  						$listLength = array();
  						$listLength[] = JHTML::_('select.option', '0', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_USE_JOOMLA_LIST_LENGTH' ) );
						$listLength[] = JHTML::_('select.option', '5', JText::_( '5' ) );
						$listLength[] = JHTML::_('select.option', '10', JText::_( '10' ) );
						$listLength[] = JHTML::_('select.option', '15', JText::_( '15' ) );
						$listLength[] = JHTML::_('select.option', '20', JText::_( '20' ) );
						$listLength[] = JHTML::_('select.option', '25', JText::_( '25' ) );
						$listLength[] = JHTML::_('select.option', '30', JText::_( '30' ) );
						$listLength[] = JHTML::_('select.option', '50', JText::_( '50' ) );
						$listLength[] = JHTML::_('select.option', '100', JText::_( '100' ) );
						echo JHTML::_('select.genericlist', $listLength, 'layout_listlength', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('layout_listlength' , '0' ) );
					?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_POSTS_ORDERING' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_POSTS_ORDERING_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_POSTS_ORDERING' ); ?>
					</span>
					</td>
					<td valign="top">
					<?php
  						$listLength = array();
						$listLength[] = JHTML::_('select.option', 'latest', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_POSTS_ORDERING_OPTIONS_LATEST' ) );
						$listLength[] = JHTML::_('select.option', 'alphabet', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_POSTS_ORDERING_OPTIONS_ALPHABET' ) );
						$listLength[] = JHTML::_('select.option', 'popular', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_POSTS_ORDERING_OPTIONS_HITS' ) );
						echo JHTML::_('select.genericlist', $listLength, 'layout_postorder', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('layout_postorder' , 'latest' ) );
					?>
					</td>
				</tr>
				
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_POSTS_SORTING' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_POSTS_SORTING_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_POSTS_SORTING' ); ?>
					</span>
					</td>
					<td valign="top">
					<?php
  						$listLength = array();
						$listLength[] = JHTML::_('select.option', 'desc', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_POSTS_SORTING_OPTIONS_DESCENDING' ) );
						$listLength[] = JHTML::_('select.option', 'asc', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_POSTS_SORTING_OPTIONS_ASCENDING' ) );
						echo JHTML::_('select.genericlist', $listLength, 'layout_postsort', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('layout_postsort' , 'desc' ) );
					?>
					</td>
				</tr>
				
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DISPLAY_NAME_FORMAT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DISPLAY_NAME_FORMAT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DISPLAY_NAME_FORMAT' ); ?>
					</span>
					</td>
					<td valign="top">
					<?php
  						$nameFormat = array();
						$nameFormat[] = JHTML::_('select.option', 'name', JText::_( 'COM_EASYBLOG_REAL_NAME_OPTION' ) );
						$nameFormat[] = JHTML::_('select.option', 'username', JText::_( 'COM_EASYBLOG_USERNAME_OPTION' ) );
						$nameFormat[] = JHTML::_('select.option', 'nickname', JText::_( 'COM_EASYBLOG_NICKNAME_OPTION' ) );
						$showdet = JHTML::_('select.genericlist', $nameFormat, 'layout_nameformat', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('layout_nameformat' , 'name' ) );
						echo $showdet;
					?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_GOOGLE_FONT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_GOOGLE_FONT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_GOOGLE_FONT' ); ?>
					</span>
					</td>
					<td valign="top">
					<?php
  						$fonts = array();
  						$fonts[] = JHTML::_('select.option', 'site', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_IGNORE_USE_SITE_FONT' ) );
  						$fonts[] = JHTML::_('select.option', 'Droid Sans', JText::_( 'Droid Sans' ) );
  						$fonts[] = JHTML::_('select.option', 'Inconsolata', JText::_( 'Inconsolata' ) );
						$fonts[] = JHTML::_('select.option', 'Pacifico', JText::_( 'Pacifico' ) );
						$fonts[] = JHTML::_('select.option', 'Cabin Sketch bold', JText::_( 'Cabin Sketch Bold' ) );
						$fonts[] = JHTML::_('select.option', 'Kristi', JText::_( 'Kristi' ) );
						$fonts[] = JHTML::_('select.option', 'Molengo', JText::_( 'Molengo' ) );
						$fonts[] = JHTML::_('select.option', 'Orbitron', JText::_( 'Orbitron' ) );
						$showdet = JHTML::_('select.genericlist', $fonts , 'layout_googlefont', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('layout_googlefont' ) );
						echo $showdet;
					?>
					</td>
				</tr>
				<tr>
					<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_SHORT_DATE_FORMAT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_SHORT_DATE_FORMAT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_SHORT_DATE_FORMAT' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="layout_shortdateformat" class="inputbox" style="width: 150px;" value="<?php echo $this->config->get('layout_shortdateformat' , '%b %d' );?>" />
						<a href="http://my.php.net/manual/en/function.strftime.php" target="_blank" class="extra_text"><?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DATE_FORMAT'); ?></a>
					</td>
				</tr>
				<tr>
					<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_GENERAL_DATE_FORMAT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_GENERAL_DATE_FORMAT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_GENERAL_DATE_FORMAT' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="layout_dateformat" class="inputbox" style="width: 150px;" value="<?php echo $this->config->get('layout_dateformat' , '%b %d, %Y' );?>" />
						<a href="http://my.php.net/manual/en/function.strftime.php" target="_blank" class="extra_text"><?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DATE_FORMAT'); ?></a>
					</td>
				</tr>
				<tr>
					<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_TIME_FORMAT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_TIME_FORMAT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_TIME_FORMAT' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="layout_timeformat" class="inputbox" style="width: 150px;" value="<?php echo $this->config->get('layout_timeformat' , '%I:%M:%S %p' );?>" />
						<a href="http://my.php.net/manual/en/function.strftime.php" target="_blank" class="extra_text"><?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_TIME_FORMAT'); ?></a>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_EXCLUDE_USERS_FROM_BLOGGER_LISTINGS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_EXCLUDE_USERS_FROM_BLOGGER_LISTINGS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_EXCLUDE_USERS_FROM_BLOGGER_LISTINGS' ); ?>
					</span>
					</td>
					<td valign="top" class="value">
						<input type="text" name="layout_exclude_bloggers" class="inputbox" style="width: 300px;" value="<?php echo $this->config->get( 'layout_exclude_bloggers' );?>" />
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DISPLAY_COMMENT_IN_BLOG_LISTING' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DISPLAY_COMMENT_IN_BLOG_LISTING_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DISPLAY_COMMENT_IN_BLOG_LISTING' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_showcomment' , $this->config->get( 'layout_showcomment' ) );?>
					</td>
				</tr>
				<tr>
					<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_NUMBER_OF_COMMENT_DISPLAY' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_NUMBER_OF_COMMENT_DISPLAY_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_NUMBER_OF_COMMENT_DISPLAY' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="layout_showcommentcount" class="inputbox" style="width: 50px;" value="<?php echo $this->config->get('layout_showcommentcount' , '3' );?>" />
					</td>
				</tr>
				
				<tr>
					<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DISPLAY_BLOG_CONTENT_AS_INTROTEXT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DISPLAY_BLOG_CONTENT_AS_INTROTEXT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DISPLAY_BLOG_CONTENT_AS_INTROTEXT' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_blogasintrotext' , $this->config->get( 'layout_blogasintrotext' ) );?>
					</td>
				</tr>
				
				<tr>
					<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_MAX_LENGTH_OF_BLOG_CONTENT_AS_INTROTEXT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_MAX_LENGTH_OF_BLOG_CONTENT_AS_INTROTEXT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_MAX_LENGTH_OF_BLOG_CONTENT_AS_INTROTEXT' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="layout_maxlengthasintrotext" class="inputbox" style="width: 50px;" value="<?php echo $this->config->get('layout_maxlengthasintrotext' , '150' );?>" />
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_HITS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_HITS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_HITS' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_hits' , $this->config->get( 'layout_hits' ) );?>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_FEATURED_TITLE' );?></legend>
			<table class="admintable" cellspacing="1">
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_FEATURED_LISTINGS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_FEATURED_LISTINGS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_FEATURED_LISTINGS' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_featured' , $this->config->get( 'layout_featured' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_FEATURED_LISTINGS_AUTO_ROTATE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_FEATURED_LISTINGS_AUTO_ROTATE_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_FEATURED_LISTINGS_AUTO_ROTATE' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_featured_autorotate' , $this->config->get( 'layout_featured_autorotate' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_FEATURED_LISTINGS_AUTO_ROTATE_INTERVAL' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_FEATURED_LISTINGS_AUTO_ROTATE_INTERVAL_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_FEATURED_LISTINGS_AUTO_ROTATE_INTERVAL' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="layout_featured_autorotate_interval" class="inputbox" style="width: 50px;text-align:center;" value="<?php echo $this->config->get('layout_featured_autorotate_interval' );?>" />
						<?php echo JText::_( 'COM_EASYBLOG_SECONDS' );?>
					</td>
				</tr>
			</table>
			</fieldset>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_TOOLBAR_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOG_HEADER' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOG_HEADER_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOG_HEADER' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_headers' , $this->config->get( 'layout_headers' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOG_TOOLBAR' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOG_TOOLBAR_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOG_TOOLBAR' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_toolbar' , $this->config->get( 'layout_toolbar' ) );?>
					</td>
				</tr>
                <tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_LINK_TO_SETTING_PAGE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_LINK_TO_SETTING_PAGE_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_LINK_TO_SETTING_PAGE' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_option_minimenu' , $this->config->get( 'layout_option_minimenu' ) );?>
					</td>
				</tr>				

                 <tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_BUTTON_IN_TOOLBAR' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_BUTTON_IN_TOOLBAR_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_BUTTON_IN_TOOLBAR' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_option_toolbar' , $this->config->get( 'layout_option_toolbar' ) );?>
					</td>
				</tr>

				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_LATEST_POST' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_LATEST_POST_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_LATEST_POST' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_latest' , $this->config->get( 'layout_latest' ) );?>
					</td>
				</tr>				
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_CATEGORIES' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_CATEGORIES_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_CATEGORIES' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_categories' , $this->config->get( 'layout_categories' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_TAGS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_TAGS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_TAGS' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_tags' , $this->config->get( 'layout_tags' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOGGERS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOGGERS_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOGGERS' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_bloggers' , $this->config->get( 'layout_bloggers' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_ARCHIVE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_ARCHIVE_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_ARCHIVE' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_archive' , $this->config->get( 'layout_archive' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_SEARCH' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_SEARCH_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_SEARCH' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_search' , $this->config->get( 'layout_search' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_TEAMBLOG' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_TEAMBLOG_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_TEAMBLOG' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_teamblog' , $this->config->get( 'layout_teamblog' ) );?>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
		</td>
		<td valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_THEME' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_THEME_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_THEME' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->getDashboardThemes( $this->config->get('layout_dashboard_theme' ) ); ?> 
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_SELECT_DEFAULT_EDITOR' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_SELECT_DEFAULT_EDITOR_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_SELECT_DEFAULT_EDITOR' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->getEditorList( $this->config->get('layout_editor') ); ?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_HEADERS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_HEADERS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_HEADERS' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboard_show_headers' , $this->config->get( 'layout_dashboard_show_headers' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_DISABLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_DISABLE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_DISABLE' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_enabledashboardtoolbar' , $this->config->get( 'layout_enabledashboardtoolbar' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_HOME' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_HOME_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_HOME' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboardhome' , $this->config->get( 'layout_dashboardhome' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_BLOGS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_BLOGS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_BLOGS' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboardblogs' , $this->config->get( 'layout_dashboardblogs' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_MAIN' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_MAIN_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_MAIN' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboardmain' , $this->config->get( 'layout_dashboardmain' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_COMMENT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_COMMENT_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_COMMENT' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboardcomments' , $this->config->get( 'layout_dashboardcomments' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_CATEGORIES' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_CATEGORIES_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_CATEGORIES' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboardcategories' , $this->config->get( 'layout_dashboardcategories' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_DRAFTS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_DRAFTS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_DRAFTS' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboarddrafts' , $this->config->get( 'layout_dashboarddrafts' ) );?>
					</td>
				</tr>				
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_TAGS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_TAGS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_TAGS' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboardtags' , $this->config->get( 'layout_dashboardtags' ) );?>
					</td>
				</tr>
				
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_NEW_POST' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_NEW_POST_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_NEW_POST' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboardnewpost' , $this->config->get( 'layout_dashboardnewpost' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_SETTINGS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_SETTINGS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_SETTINGS' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboardsettings' , $this->config->get( 'layout_dashboardsettings' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_INTROTEXT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_INTROTEXT_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_INTROTEXT' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboardintro' , $this->config->get( 'layout_dashboardintro' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_SEO' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_SEO_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_SEO' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboardseo' , $this->config->get( 'layout_dashboardseo' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_TRACKBACK' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_TRACKBACK_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_TRACKBACK' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboardtrackback' , $this->config->get( 'layout_dashboardtrackback' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_ANCHORS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_ANCHORS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_ANCHORS' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_dashboardanchor' , $this->config->get( 'layout_dashboardanchor' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_VIDEO_WIDTH' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_VIDEO_WIDTH_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_VIDEO_WIDTH' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="dashboard_video_width" class="inputbox" style="width: 50px;" value="<?php echo $this->config->get('dashboard_video_width' );?>" />
						<?php echo JText::_( 'COM_EASYBLOG_PIXELS' );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_VIDEO_HEIGHT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_VIDEO_HEIGHT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_VIDEO_HEIGHT' ); ?>
					</span>
					</td>
					<td valign="top">
						<input type="text" name="dashboard_video_height" class="inputbox" style="width: 50px;" value="<?php echo $this->config->get('dashboard_video_height' );?>" />
						<?php echo JText::_( 'COM_EASYBLOG_PIXELS' );?>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
			
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_BLOGGER_THEME_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOGGER_THEME' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOGGER_THEME_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOGGER_THEME' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'layout_enablebloggertheme' , $this->config->get( 'layout_enablebloggertheme' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_BLOGGER_THEME_SELECTION' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_BLOGGER_THEME_SELECTION_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_BLOGGER_THEME_SELECTION' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php echo $this->getBloggerThemes() ?>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
			
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_PRINT' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_PRINT_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_PRINT' ); ?>
					</span>
					</td>
					<td valign="top">
						
						<?php
	  						$printButton = array();
							$printButton[] = JHTML::_('select.option', '0', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_DISABLE' ) );
							$printButton[] = JHTML::_('select.option', '1', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_ENABLE' ) );
							$printButton[] = JHTML::_('select.option', '2', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_REGISTERED' ) );
							$showdet = JHTML::_('select.genericlist', $printButton, 'layout_enableprint', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('layout_enableprint' , '1' ) );
							echo $showdet;
						?>
						
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_PDF' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_PDF_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_PDF' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php
	  						$pdfButton = array();
							$pdfButton[] = JHTML::_('select.option', '0', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_DISABLE' ) );
							$pdfButton[] = JHTML::_('select.option', '1', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_ENABLE' ) );
							$pdfButton[] = JHTML::_('select.option', '2', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_REGISTERED' ) );
							$showdet = JHTML::_('select.genericlist', $pdfButton, 'layout_enablepdf', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('layout_enablepdf' , '1' ) );
							echo $showdet;
						?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_BOOKMARK' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_BOOKMARK_DESC'); ?>">
						<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_BOOKMARK' ); ?>
					</span>
					</td>
					<td valign="top">
						<?php
	  						$bookmarkButton = array();
							$bookmarkButton[] = JHTML::_('select.option', '0', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_DISABLE' ) );
							$bookmarkButton[] = JHTML::_('select.option', '1', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_ENABLE' ) );
							$bookmarkButton[] = JHTML::_('select.option', '2', JText::_( 'COM_EASYBLOG_SETTINGS_LAYOUT_PUBLISHING_TOOL_REGISTERED' ) );
							$showdet = JHTML::_('select.genericlist', $bookmarkButton, 'layout_enablebookmark', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('layout_enablebookmark' , '1' ) );
							echo $showdet;
						?>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
		</td>
	</tr>
</table>