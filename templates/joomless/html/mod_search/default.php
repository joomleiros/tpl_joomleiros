<?php
/**
 * @version		$Id: default.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	mod_search
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<form action="<?php echo JRoute::_('index.php');?>" method="post">
  <div class="search<?php echo $moduleclass_sfx ?>">
    <input name="searchword" id="mod-search-searchword" maxlength="<?php echo $maxlength; ?>"  class="inputbox<?php echo $moduleclass_sfx; ?>" type="text" size="<?php echo $width; ?>" value="<?php echo $text; ?>"  onblur="if (this.value=='') this.value='<?php echo $text; ?>';" onfocus="if (this.value=='<?php echo $text; ?>') this.value='';" />
    <input type="submit" value="<?php echo $button_text; ?>" class="button<?php echo $moduleclass_sfx ?>" onclick="this.form.searchword.focus();"/>

    <input type="hidden" name="task" value="search" />
    <input type="hidden" name="option" value="com_search" />
    <input type="hidden" name="Itemid" value="<?php echo $mitemid; ?>" />
  </div>
</form>
