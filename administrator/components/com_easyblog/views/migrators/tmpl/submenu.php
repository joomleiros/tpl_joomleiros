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

<div id="submenu-box">
	<div class="t">
		<div class="t">
			<div class="t"></div>
		</div>
	</div>
	<div class="m">
		<div class="submenu-box">
			<div class="submenu-pad">
				<ul id="submenu">
					<?php if(EasyBlogHelper::getJoomlaVersion() <= '1.5') : ?>
					<li><a id="home" href="<?php echo JRoute::_('index.php?option=com_easyblog&view=easyblog');?>">&laquo; <?php echo JText::_( 'Back' ); ?></a></li>
					<?php endif; ?>
					<li><a id="joomla" class="active"><?php echo JText::_( 'Joomla' ); ?></a></li>
					<li><a id="smartblog"><?php echo JText::_( 'SmartBlog' ); ?></a></li>
					<li><a id="lyften"><?php echo JText::_( 'LyftenBloggie' ); ?></a></li>
					<li><a id="myblog"><?php echo JText::_( 'My Blog' ); ?></a></li>
					<!-- li><a id="wordpress"><?php echo JText::_( 'WordPress' ); ?></a></li -->
					<!-- li><a id="jomcomment"><?php echo JText::_( 'Jomcomment' ); ?></a></li>
					<li><a id="jcomments"><?php echo JText::_( 'JComments' ); ?></a></li>
					<li><a id="mxcomments"><?php echo JText::_( 'MXComments' ); ?></a></li -->
				</ul>
				<div class="clr"></div>
			</div>
		</div>
		<div class="clr"></div>
	</div>
	<div class="b">
		<div class="b">
			<div class="b"></div>
		</div>
	</div>
</div>
