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
<script>
sQuery(document).ready(function(){

	sQuery(".si_accordion > h3:first").addClass("active");
	//sQuery(".si_accordion h3 > div:not(:first)").hide();
    sQuery(".si_accordion > h3").siblings("div").hide();
    sQuery(".si_accordion > h3:first + div").show();


	sQuery(".si_accordion > h3").click(function(){

	  sQuery(this).next("div").toggle().siblings("div").hide();
	  sQuery(this).toggleClass("active");
	  sQuery(this).siblings("h3").removeClass("active");

	});

});
</script>

<div class="si_accordion">
    <h3><div><?php echo JText::_('COM_EASYBLOG_QUICKGUIDE_WHATS_NEXT_TITLE'); ?></div></h3>
    <div id="guide" class="user-guide">
        <ul class="ul-reset">
            <li>
            	<b><span><?php echo JText::_('COM_EASYBLOG_QUICKGUIDE_INSTRUCTIONS_SET_TITLE'); ?></span></b>
            	<div><?php echo JText::sprintf('COM_EASYBLOG_QUICKGUIDE_INSTRUCTIONS_SET_TITLE_DESC', rtrim(JURI::root(), '/') . '/administrator/index.php?option=com_easyblog&view=settings'); ?></div>
            	<a href="<?php echo rtrim(JURI::root(), '/') . '/administrator/index.php?option=com_easyblog&view=settings';?>" class="button"><?php echo JText::_( 'COM_EASYBLOG_QUICKGUIDE_CHANGE_TITLE_BUTTON' );?></a>
            </li>

        	<li>
                <b><span><?php echo JText::_('COM_EASYBLOG_QUICKGUIDE_INSTRUCTIONS_SETUP_PERMISSIONS'); ?></span></b>
            	<div><?php echo JText::sprintf('COM_EASYBLOG_QUICKGUIDE_INSTRUCTIONS_SETUP_PERMISSIONS_DESC', rtrim(JURI::root(), '/') . '/administrator/index.php?option=com_easyblog&view=acls'); ?></div>
                <a href="<?php echo rtrim(JURI::root(), '/') . '/administrator/index.php?option=com_easyblog&view=acls';?>" class="button"><?php echo JText::_( 'COM_EASYBLOG_QUICKGUIDE_SET_PERMISSION_BUTTON' );?></a>
            </li>

            <li>
                <b><span><?php echo JText::_('COM_EASYBLOG_QUICKGUIDE_INSTRUCTIONS_ADD_MORE_CATEGORIES'); ?></span></b>
            	<div><?php echo JText::sprintf('COM_EASYBLOG_QUICKGUIDE_INSTRUCTIONS_ADD_MORE_CATEGORIES_DESC', rtrim(JURI::root(), '/') . '/administrator/index.php?option=com_easyblog&view=categories'); ?></div>
            	<a href="<?php echo rtrim(JURI::root(), '/') . '/administrator/index.php?option=com_easyblog&view=category';?>" class="button"><?php echo JText::_( 'COM_EASYBLOG_QUICKGUIDE_ADD_CATEGORY_BUTTON' );?></a>
            </li>

        	<li>
                <b><span><?php echo JText::_('COM_EASYBLOG_QUICKGUIDE_INSTRUCTIONS_UPDATE_PROFILE'); ?></span></b>
            	<div><?php echo JText::sprintf('COM_EASYBLOG_QUICKGUIDE_INSTRUCTIONS_UPDATE_PROFILE_DESC', rtrim(JURI::root(), '/') . '/index.php?option=com_easyblog&view=dashboard'); ?></div>
            	<a href="<?php echo rtrim(JURI::root(), '/') . '/index.php?option=com_easyblog&view=dashboard';?>" class="button"><?php echo JText::_( 'COM_EASYBLOG_QUICKGUIDE_DASHBOARD_BUTTON' );?></a>
            </li>

        	<li class="start">
                <div><span><?php echo JText::_('COM_EASYBLOG_QUICKGUIDE_INSTRUCTIONS_NEED_HELP_STARTING_UP_DESC'); ?></span></div>
            </li>
        </ul>
    </div>

    <h3><div><?php echo JText::_('COM_EASYBLOG_QUICKGUIDE_STATS_TITLE'); ?></div></h3>
    <div class="user-guide">
        <?php echo $this->loadTemplate( 'stats' );?>
    </div>

    <h3><div><?php echo JText::_('COM_EASYBLOG_QUICKGUIDE_ABOUT_TITLE'); ?></div></h3>
    <div class="user-guide">
        <?php echo $this->loadTemplate( 'about' );?>
    </div>

    <h3><div><?php echo JText::_('COM_EASYBLOG_QUICKGUIDE_NEWS_TITLE'); ?></div></h3>
    <div class="user-guide">
        <?php echo $this->loadTemplate( 'news' );?>
    </div>
</div>