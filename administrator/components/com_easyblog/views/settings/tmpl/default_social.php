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

$active			= strtolower( JRequest::getVar('active', '') );
$activechild	= strtolower( JRequest::getVar('activechild', '') );

if($active=="social")
{
	switch($activechild)
	{
		case 'twitter':
			$children_name = 'Twitter';
			break;
		case 'facebook':
			$children_name = 'Facebook';
			break;
		case 'linkedin':
			$children_name = 'Linkedin';
			break;
		case 'google':
			$children_name = 'Google';
			break;
		case 'tweetmeme':
			$children_name = 'TweetMeme';
			break;
		case 'socialtools':
		case 'social-tools':
			$children_name = 'Social-Tools';
			break;
		default:
			$children_name = '';
			break;
	}
}
?>
<script type="text/javascript">
	sQuery(window).load(function(){
<?php
		if(!empty($children_name))
		{
			if($this->joomlaversion >= '1.6')
			{
			?>$$( 'dl#subtabs dt.<?php echo $children_name; ?>' ).fireEvent('click');<?php
			}
			else
			{
			{
			?>$$( 'dl#subtabs dt#<?php echo $children_name; ?>' ).fireEvent('click');<?php
			}
			}
		}
		else
		{
			?>$$($$( 'dl#subtabs dt' )[0]).fireEvent('click');<?php
		}
?>
	});
</script>
<?php

$pane	=& JPane::getInstance('Tabs');

echo $pane->startPane("subtabs");
echo $pane->startPanel( JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_SUBTAB_TWITTER' ) , 'Twitter');
echo $this->loadTemplate( 'social_twitter' );
echo $pane->endPanel();
echo $pane->startPanel( JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_SUBTAB_FACEBOOK' ) , 'Facebook');
echo $this->loadTemplate( 'social_facebook' );
echo $pane->endPanel();
echo $pane->startPanel( JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_SUBTAB_LINKEDIN' ) , 'Linkedin');
echo $this->loadTemplate( 'social_linkedin' );
echo $pane->endPanel();
echo $pane->startPanel( JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_SUBTAB_OTHERS' ) , 'Social-Tools');
echo $this->loadTemplate( 'social_buttons' );
echo $pane->endPanel();
echo $pane->endPane();

