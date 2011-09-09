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

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );
require( EBLOG_ADMIN_ROOT . DS . 'views.php');

class EasyBlogViewSettings extends EasyBlogAdminView
{
	
	var $rules = array();
	
	function display($tpl = null)
	{
		//initialise variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();

		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.modal' , 'a.modal' );
		
		$config =& EasyBlogHelper::getConfig();
		
		
		$dstOptions	= array();
		$iteration 	= -12;
		for( $i = 0; $i <= 24; $i++ )
		{
			$dstOptions[]	= JHTML::_('select.option', $iteration, $iteration);
			$iteration++;
		}
		
		$dstList = JHTML::_('select.genericlist',  $dstOptions, 'main_dstoffset', 'class="inputbox" size="1"', 'value', 'text', $config->get('main_dstoffset', 0));
		
		//check if jomcomment installed.
		$jcInstalled = false;
		if(file_exists(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_jomcomment' . DS . 'config.jomcomment.php' ) )
		{
		    $jcInstalled = true;
		}
		
		//check if jcomments installed.
		$jcommentInstalled = false;
		if(file_exists( JPATH_ROOT . DS . 'components' . DS . 'com_jcomments' . DS . 'jcomments.php' ))
		{
		    $jcommentInstalled = true;
		}
		
		//check if rscomments installed.
		$rscommentInstalled = false;
		if(file_exists( JPATH_ROOT . DS . 'components' . DS . 'com_rscomments' . DS . 'rscomments.php' ))
		{
		    $rscommentInstalled = true;
		}
		
		$defaultSAId    = EasyBlogHelper::getDefaultSAIds();
		
		//check for centralized social sharing account.
		$centralizedSocialAcount = new stdClass();
		
		//twitter
		$twitterCentralizeId = $config->get( 'integrations_twitter_centralized_userid');
		$twitter =& JTable::getInstance( 'Oauth' , 'Table' );
		$twitter->loadByUser( $twitterCentralizeId , EBLOG_OAUTH_TWITTER );
		$centralizedSocialAcount->twitter->user =& JFactory::getUser( $twitterCentralizeId );
		$centralizedSocialAcount->twitter->settings = $twitter;
		
		//facebook
		$facebookCentralizeId = $config->get( 'integrations_facebook_centralized_userid');
		$facebook =& JTable::getInstance( 'Oauth' , 'Table' );
		$facebook->loadByUser( $facebookCentralizeId , EBLOG_OAUTH_FACEBOOK );
		$centralizedSocialAcount->facebook->user =& JFactory::getUser( $facebookCentralizeId );
		$centralizedSocialAcount->facebook->settings = $facebook;
		
		//linkedin
		$linkedinCentralizeId = $config->get( 'integrations_linkedin_centralized_userid');
		$linkedin =& JTable::getInstance( 'Oauth' , 'Table' );
		$linkedin->loadByUser( $linkedinCentralizeId , EBLOG_OAUTH_LINKEDIN );
		$centralizedSocialAcount->linkedin->user =& JFactory::getUser( $linkedinCentralizeId );
		$centralizedSocialAcount->linkedin->settings = $linkedin;
		
		$this->assignRef( 'config' , $config );
		$this->assignRef( 'dstList' , $dstList );
		$this->assignRef( 'jcInstalled' , $jcInstalled );
		$this->assignRef( 'jcommentInstalled' , $jcommentInstalled );
		$this->assignRef( 'rscommentInstalled' , $rscommentInstalled );
		$this->assignRef( 'defaultSAId' , $defaultSAId );
		$this->assignRef( 'centralizedSocialAcount' , $centralizedSocialAcount );
		$this->assignRef( 'joomlaversion' , EasyBlogHelper::getJoomlaVersion() );
		
		parent::display($tpl);
	}
	
	function getEditorList( $selected )
	{
		$db		=& JFactory::getDBO();
		
		// compile list of the editors
		if(EasyBlogHelper::getJoomlaVersion() >= '1.6')
		{
			$query = 'SELECT `element` AS value, `name` AS text'
					.' FROM `#__extensions`'
					.' WHERE `folder` = "editors"'
					.' AND `type` = "plugin"'
					.' AND `enabled` = 1'
					.' ORDER BY ordering, name'
					;
		}
		else
		{
			$query = 'SELECT element AS value, name AS text'
					.' FROM #__plugins'
					.' WHERE folder = "editors"'
					.' AND published = 1'
					.' ORDER BY ordering, name'
					;
		}
				
		//echo $query;
				
		$db->setQuery($query);
		$editors = $db->loadObjectList();
		
	    if(count($editors) > 0)
	    {
			if(EasyBlogHelper::getJoomlaVersion() >= '1.6')
			{
			    $lang = JFactory::getLanguage();
				for($i = 0; $i < count($editors); $i++)
				{
				    $editor =& $editors[$i];
					$lang->load($editor->text . '.sys', JPATH_ADMINISTRATOR, null, false, false);
				    $editor->text   = JText::_($editor->text);
				}
			}
	    }
		
		return JHTML::_('select.genericlist',  $editors , 'layout_editor', 'class="inputbox" size="1"', 'value', 'text', $selected );
	}
	
	function getThemes( $selectedTheme = 'default' )
	{
		$html	= '<select name="layout_theme" class="inputbox">';
		
		$themes	=& $this->get( 'Themes' );
		
		for( $i = 0; $i < count( $themes ); $i++ )
		{
			$theme		= JString::strtolower( $themes[ $i ] );
			
			if ( $theme != 'dashboard' ) {
				$selected	= ( $selectedTheme == $theme ) ? ' selected="selected"' : '';
				$html		.= '<option' . $selected . '>' . $theme . '</option>';
			}
		}
		
		$html	.= '</select>';
		
		return $html;
	}

	function getDashboardThemes( $selectedTheme = 'system' )
	{
		$html	= '<select name="layout_dashboard_theme" class="inputbox">';
		
		$model	= $this->getModel( 'Settings' );
		$themes	= $model->getThemes( true );

		for( $i = 0; $i < count( $themes ); $i++ )
		{
			$theme		= JString::strtolower( $themes[ $i ] );

			$selected	= ( $selectedTheme == $theme ) ? ' selected="selected"' : '';
			$html		.= '<option' . $selected . '>' . $theme . '</option>';
		}
		
		$html	.= '</select>';
		
		return $html;
	}
	
	function getBloggerThemes()
	{
		$config =& EasyBlogHelper::getConfig();
	
		$themes	=& $this->get( 'Themes' );
		
		$options = array ();

		foreach ($themes as $theme)
		{
			$options[] = JHTML::_('select.option', $theme, $theme);
		}
		
		$previouslyAvailable = $config->get('layout_availablebloggertheme');
		
		return JHTML::_('select.genericlist', $options, 'layout_availablebloggertheme[]', 'multiple="multiple" style="width: 200px;height: 200px;"', 'value', 'text', explode('|', $previouslyAvailable) );
	}
	
	function getEmailsTemplate()
	{
		JHTML::_('behavior.modal' , 'a.modal' );
		$html	= '';
		$emails = array('email.blog.new.php',
				'email.comment.moderate.php',
				'email.comment.new.php',
				'email.subscription.comment.new.php',
				'email.teamblog.request.php',
				'email.teamblog.request.approved.php',
				'email.teamblog.request.rejected.php',
				'email.blog.pending.review.php');

		ob_start();

		foreach($emails as $email)
		{
		?>
			<div>
				<div style="float:left; margin-right:5px;">
				<?php echo JText::_($email); ?>
				</div>
				<div style="margin-top: 5px;">
				[
				<?php
				if(is_writable(JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'themes'.DS.'default'.DS.$email))
				{
				?>
					<a class="modal" rel="{handler: 'iframe', size: {x: 700, y: 500}}" href="index.php?option=com_easyblog&view=settings&layout=editEmailTemplate&file=<?php echo $email; ?>&tmpl=component&browse=1"><?php echo JText::_('COM_EASYBLOG_EDIT');?></a>
				<?php
				}
				else
				{
				?>
					<span style="color:red; font-weight:bold;"><?php echo JText::_('COM_EASYBLOG_UNWRITABLE');?></span>
				<?php
				}
				?>
				]
				</div>
			</div>
		<?php
		}
		$html   = ob_get_contents();
		@ob_end_clean();

		return $html;
	}
	
	function editEmailTemplate()
	{
		$file		= JRequest::getVar('file', '', 'GET');
		$filepath	= JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'themes'.DS.'default'.DS.$file;
		$content	= '';
		$html		= '';
		$msg		= JRequest::getVar('msg', '', 'GET');
		$msgType	= JRequest::getVar('msgtype', '', 'GET');

		ob_start();

		if(!empty($msg))
		{
			$document =& JFactory::getDocument();
			$document->addStyleSheet( JURI::root() . '/components/com_easyblog/assets/css/common.css' );
		?>
			<div id="eblog-message" class="<?php echo $msgType; ?>"><?php echo $msg; ?></div>
		<?php
		}

		if(is_writable($filepath))
		{
			$content = JFile::read($filepath);
		?>
			<form name="emailTemplate" id="emailTemplate" method="POST">
				<textarea rows="28" cols="93" name="content"><?php echo $content; ?></textarea>
				<input type="hidden" name="option" value="com_easyblog">
				<input type="hidden" name="c" value="settings">
				<input type="hidden" name="task" value="saveEmailTemplate">
				<input type="hidden" name="file" value="<?php echo $file; ?>">
				<input type="hidden" name="tmpl" value="component">
				<input type="hidden" name="browse" value="1">
				<input type="submit" name="save" value="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_EMAIL_TEMPLATES_SAVE' );?>">
				<?php if(EasyBlogHelper::getJoomlaVersion() <= '1.5') : ?>
				<input type="button" value="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_NOTIFICATIONS_EMAIL_TEMPLATES_CLOSE' );?>" onclick="window.parent.document.getElementById('sbox-window').close();">
				<?php endif; ?>
			</form>
		<?php
		}
		else
		{
		?>
			<div><?php echo JText::_('COM_EASYBLOG_SETTINGS_NOTIFICATIONS_EMAIL_TEMPLATES_UNWRITABLE'); ?></div>
		<?php
		}

		$html = ob_get_contents();
		@ob_end_clean();

		echo $html;
	}
	
	function registerToolbar()
	{
		JToolBarHelper::title( JText::_( 'Settings' ), 'settings' );
		
		JToolBarHelper::back( 'Home' , 'index.php?option=com_easyblog');
		JToolBarHelper::divider();
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
	}
	
	function registerSubmenu()
	{
		return 'submenu.php';
	}
}