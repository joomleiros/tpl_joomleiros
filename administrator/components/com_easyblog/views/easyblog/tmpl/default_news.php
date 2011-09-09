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
<div id="guide">
<ul class="news-item ul-reset">
	<?php
	$news	= $this->getRecentNews();
	if( $news )
	{
		foreach( $this->getRecentNews() as $item )
		{
	?>
	<li>
		<b><span><?php echo $item->title . ' - ' . $item->date; ?></span></b>
		<div><?php echo $item->desc;?></div>
	</li>
	<?php
		}
	}
	else
	{
	?>
	<li><?php echo JText::_('Unable to contact news server');?></li>
	<?php
	}
	?>
</ul>
</div>
