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

jimport( 'joomla.application.component.view');

require_once( EBLOG_HELPERS . DS . 'helper.php' );

class EasyBlogViewEasyblog extends JView
{
	function display($tpl = null)
	{
		//Load pane behavior
		jimport('joomla.html.pane');

		//initialise variables
		$document	=& JFactory::getDocument();
		$slider		=& JPane::getInstance( 'sliders' );
		$user		=& JFactory::getUser();

		$this->assignRef( 'slider'		, $slider );
		$this->assignRef( 'user'		, $user );

		parent::display($tpl);

	}

	function addButton( $link, $image, $text, $description = '' )
	{
?>
		<table class="icon-item" cellpadding="0" cellspacing="0">
			<tr>
				<td width="42"><a href="<?php echo $link;?>"><?php echo JHTML::_('image', 'administrator/components/com_easyblog/assets/images/'.$image, $text );?></a></td>
				<td>
					<div class="item-title"><a href="<?php echo $link;?>"><?php echo $text;?></a></div>
					<div class="item-description"><?php echo $description;?></div>
				</td>
			</tr>
		</table>
<?php
	}
	
	function getPopularEntries()
	{
	
	}
	
	function getVersion()
	{
		$version	= EasyBlogHelper::getVersion();
		$local		= EasyBlogHelper::getLocalVersion();
		
		// Test build only since build will always be incremented regardless of version
		$localVersion	= explode( '.' , $local );
		$localBuild		= $localVersion[2];
		
		if( !$version )
			return JText::_('Unable to contact update servers');

		$remoteVersion	= explode( '.' , $version );
		$build			= $remoteVersion[ 2 ];
		
		if( $localBuild >= $build )
		{
			return '<span class="version_latest">' . JText::sprintf('COM_EASYBLOG_VERSION_LATEST' , $local ) . '</span>';
		}
		
		return '<span class="version_outdated">' . JText::sprintf( 'COM_EASYBLOG_VERSION_OUTDATED' , $local , $version ) . '</span>';
	}
	
	function getTotalEntries()
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'SELECT COUNT(1) FROM #__easyblog_post';
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function getTotalComments()
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'SELECT COUNT(1) FROM #__easyblog_comment';
		$db->setQuery( $query );
		return $db->loadResult();
	}
	
	function getTotalUnpublishedEntries()
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'SELECT COUNT(1) FROM #__easyblog_post where `published`=' . $db->Quote( 0 );
		$db->setQuery( $query );
		return $db->loadResult();
	}
	
	function getTotalTags()
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'SELECT COUNT(1) FROM #__easyblog_tag';
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function getTotalCategories()
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'SELECT COUNT(1) FROM #__easyblog_category';
		$db->setQuery( $query );
		return $db->loadResult();
	}
	
	function getRecentNews()
	{
		return EasyBlogHelper::getRecentNews();
	}
	
	function registerToolbar()
	{
		// Set the titlebar text
		JToolBarHelper::title( JText::_( 'COM_EASYBLOG' ), 'home');
		
		// Add the necessary buttons
  /*temporary remove this until further version*/
		//JToolBarHelper::custom( 'support' , 'support' , 'support' , JText::_('Support') , false );
		//JToolBarHelper::custom( 'updates' , 'updates.png' , 'updates' , JText::_('Check for updates') , false );
	}
}