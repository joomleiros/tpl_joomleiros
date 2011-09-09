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
<form name="adminForm" id="adminForm" action="index.php?option=com_easyblog&c=user" method="post" enctype="multipart/form-data">
<table width="100%" cellpadding="5">
	<tr>
		<td width="60%" valign="top">
		<?php
		$pane	=& JPane::getInstance('Tabs');
		
		echo $pane->startPane("subuser");
		echo $pane->startPanel( JText::_( 'COM_EASYBLOG_BLOGGERS_TAB_ACCOUNT_DETAILS' ) , 'account');
		echo $this->loadTemplate( 'account' );
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_( 'COM_EASYBLOG_BLOGGERS_TAB_ACCOUNT_INFO' ) , 'blogger');
		echo $this->loadTemplate( 'blogger' );
		echo $pane->endPanel();
		echo $pane->endPane();
		?>

		</td>
		<td width="38%" valign="top">
		<?php
			$pane	= JPane::getInstance('sliders', array('allowAllClose' => true));

			echo $pane->startPane("content-pane");
			echo $pane->startPanel( JText::_( 'COM_EASYBLOG_BLOGGERS_PARAMS_TITLE_FEEDBURNER' ) , "detail-page" );
			echo $this->loadTemplate( 'feedburner' );
			echo $pane->endPanel();
			echo $pane->startPanel( JText::_( 'COM_EASYBLOG_BLOGGERS_PARAMS_TITLE_FACEBOOK' ), "metadata-page" );
			echo $this->loadTemplate( 'facebook' );
			echo $pane->endPanel();
			echo $pane->startPanel( JText::_( 'COM_EASYBLOG_BLOGGERS_PARAMS_TITLE_TWITTER' ), "params-page" );
			echo $this->loadTemplate( 'twitter' );
			echo $pane->endPanel();
			echo $pane->startPanel( JText::_( 'COM_EASYBLOG_BLOGGERS_PARAMS_TITLE_LINKEDIN' ), "linkedin-page" );
			echo $this->loadTemplate( 'linkedin' );
			echo $pane->endPanel();
			echo $pane->startPanel( JText::_( 'COM_EASYBLOG_BLOGGERS_PARAMS_TITLE_ADSENSE' ), "adsense-page" );
			echo $this->loadTemplate( 'adsense' );
			echo $pane->endPanel();
			echo $pane->endPane();
		?>
		</td>
	</tr>
</table>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="c" value="user" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="id" value="<?php echo $this->user->id;?>" />
</form>