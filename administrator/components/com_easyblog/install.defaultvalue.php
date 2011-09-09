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

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

function getEblogId()
{
	$db		=& JFactory::getDBO();
	
	if( getJoomlaVersion() >= '1.6' ) {
		$query 	= 'SELECT ' . $db->nameQuote( 'extension_id' ) . ' '
			. 'FROM ' . $db->nameQuote( '#__extensions' ) . ' '
			. 'WHERE `element`=' . $db->Quote( 'com_easyblog' ) . ' '
			. 'AND `type`=' . $db->Quote( 'component' ) . ' ';
	} else {
		$query 	= 'SELECT ' . $db->nameQuote( 'id' ) . ' '
			. 'FROM ' . $db->nameQuote( '#__components' ) . ' '
			. 'WHERE `option`=' . $db->Quote( 'com_easyblog' ) . ' '
			. 'AND `parent`=' . $db->Quote( '0');
	}
	
	$db->setQuery( $query );	
	
	return $db->loadResult();
}

function menuExist()
{
	$db		=& JFactory::getDBO();
	
	if( getJoomlaVersion() >= '1.6' ) {
		$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__menu' ) . ' '
			. 'WHERE ' . $db->nameQuote( 'link' ) . ' LIKE ' .  $db->Quote( '%option=com_easyblog%') . ' '
			. 'AND `client_id`=' . $db->Quote( '0' ) . ' '
			. 'AND `type`=' . $db->Quote( 'component' ) . ' '
			. 'AND `menutype`=' . $db->Quote( 'mainmenu' );
	} else {
		$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__menu' ) . ' '
			. 'WHERE ' . $db->nameQuote( 'link' ) . ' LIKE ' .  $db->Quote( '%option=com_easyblog%');
	}

	$db->setQuery( $query );

	$requiresUpdate	= ( $db->loadResult() >= 1 ) ? true : false;
	
	return $requiresUpdate;
}

/**
 * Method to update menu's component id.
 *
 * @return boolean true on success false on failure.
 */
function updateMenuItems()
{
	// Get new component id.
	$db 	=& JFactory::getDBO();
	
	$cid = getEblogId();
	
	if( !$cid )
		return false;
	
	$joomlaVersion = getJoomlaVersion();
	
	if( $joomlaVersion >= '1.6' ) {
		$query 	= 'UPDATE ' . $db->nameQuote( '#__menu' ) . ' '
			. 'SET component_id=' . $db->Quote( $cid ) . ' '
			. 'WHERE link LIKE ' . $db->Quote('%option=com_easyblog%') . ' '
			. 'AND `type`=' . $db->Quote( 'component' ) . ' '
			. 'AND `menutype` = ' . $db->Quote( 'mainmenu' ) . ' '
			. 'AND `client_id`=' . $db->Quote( '0' );
	} else {
		// Update the existing menu items.
		$query 	= 'UPDATE ' . $db->nameQuote( '#__menu' ) . ' '
			. 'SET componentid=' . $db->Quote( $cid ) . ' '
			. 'WHERE link LIKE ' . $db->Quote('%option=com_easyblog%');
	}

	$db->setQuery( $query );
	$db->query();
	
	if( $joomlaVersion < '1.6' ) {
		//now this is to update the old viewname 'easyblog' to new viewname 'latest'
		$query 	= 'UPDATE ' . $db->nameQuote( '#__menu' )
				. ' SET `link` = ' . $db->Quote( 'index.php?option=com_easyblog&view=latest' )
				. ' WHERE `link` = ' . $db->Quote('index.php?option=com_easyblog&view=easyblog')
				. ' AND `componentid` = ' . $db->Quote( $cid );
				
		$db->setQuery( $query );
		$db->query();
	}
	
	return true;
}

/**
 * Method to add menu's item.
 *
 * @return boolean true on success false on failure.
 */
function createMenuItems()
{
	// Get new component id.
	$db 	=& JFactory::getDBO();
	
	$cid = getEblogId();
	
	if( !$cid )
	{
		return false;
	}
	
	// We only insert if 'mainmenu' exists.
	$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__menu_types' ) . ' '
			. 'WHERE ' . $db->nameQuote( 'menutype' ) . '=' . $db->Quote( 'mainmenu' );
	$db->setQuery( $query );
	
	if( $db->loadResult() <= 0 )
	{
		return false;
	}

	$query 	= 'SELECT ' . $db->nameQuote( 'ordering' ) . ' '
			. 'FROM ' . $db->nameQuote( '#__menu' ) . ' '
			. 'ORDER BY ' . $db->nameQuote( 'ordering' ) . ' DESC LIMIT 1';
	$db->setQuery( $query );	
	$order 	= $db->loadResult() + 1;
	
	$status = true;
	if( getJoomlaVersion() >= '1.6' ) {
		$table = JTable::getInstance('Menu', 'JTable', array());
		
		$table->menutype		= 'mainmenu';
		$table->title 			= 'EasyBlog';
		$table->alias 			= 'easyblog';
		$table->path 			= 'easyblog';
		$table->link 			= 'index.php?option=com_easyblog&view=latest';
		$table->type 			= 'component';
		$table->published 		= '1';
		$table->parent_id 		= '1';
		$table->component_id	= $cid;
		$table->ordering 		= $order;
		$table->client_id 		= '0';
		$table->language 		= '*';
		
		$table->setLocation('1', 'last-child');
		
		if(!$table->store()){
			$status = false;
		}
	} else {
		// Update the existing menu items.
		$query 	= 'INSERT INTO ' . $db->nameQuote( '#__menu' ) 
			. '(' 
				. $db->nameQuote( 'menutype' ) . ', '
				. $db->nameQuote( 'name' ) . ', '
				. $db->nameQuote( 'alias' ) . ', '
				. $db->nameQuote( 'link' ) . ', '
				. $db->nameQuote( 'type' ) . ', '
				. $db->nameQuote( 'published' ) . ', '
				. $db->nameQuote( 'parent' ) . ', '
				. $db->nameQuote( 'componentid' ) . ', '
				. $db->nameQuote( 'sublevel' ) . ', '
				. $db->nameQuote( 'ordering' ) . ' '
			. ') '
			. 'VALUES('
				. $db->quote( 'mainmenu' ) . ', '
				. $db->quote( 'EasyBlog' ) . ', '
				. $db->quote( 'easyblog' ) . ', '
				. $db->quote( 'index.php?option=com_easyblog&view=latest' ) . ', '
				. $db->quote( 'component' ) . ', '
				. $db->quote( '1' ) . ', '
				. $db->quote( '0' ) . ', '
				. $db->quote( $cid ) . ', '
				. $db->quote( '0' ) . ', '
				. $db->quote( $order ) . ' '
			. ') ';
			
		$db->setQuery( $query );
		$db->query();
		
		if($db->getErrorNum())
		{
			$status = false;
		}
	}
	
	return $status;
}

function blogCategoryExist()
{
	$db		=& JFactory::getDBO();
	
	$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__easyblog_category' );
	$db->setQuery( $query );

	$exist	= ( $db->loadResult() > 0 ) ? true : false;
	
	return $exist;
}

function _getSuperAdminId()
{
	$db =& JFactory::getDBO();
	
	if( getJoomlaVersion() >= '1.6' ) {
		$saUsers	= getSAUsersIds();
	
		$result = '42';
		if(count($saUsers) > 0)
		{
		    $result = $saUsers['0'];
		}
	} else {
		$query  = 'SELECT `id` FROM `#__users`';
		$query  .= ' WHERE (LOWER( usertype ) = ' . $db->Quote('super administrator');
		$query  .= ' OR `gid` = ' . $db->Quote('25') . ')';
		$query  .= ' ORDER BY `id` ASC';
		$query  .= ' LIMIT 1';
		
		$db->setQuery($query);
		$result = $db->loadResult();
		
		$result = (empty($result)) ? '62' : $result;
	}
	
	return $result;
	
}

function createBlogCategory()
{
	$db 	=& JFactory::getDBO();
	
	$suAdmin    = _getSuperAdminId();
	$query 	= "INSERT INTO `#__easyblog_category` (`id`, `created_by`, `title`, `alias`, `created`, `status`, `published`, `ordering`) VALUES ('1', " . $db->Quote($suAdmin) .", 'Uncategorized', 'uncategorized', now(), 0, 1, 0)";
	
	$db->setQuery( $query );
	
	$db->query();
	
	if($db->getErrorNum())
	{
		return false;
	}	
	return true;
}

function updateACLRules()
{
	$db 	=& JFactory::getDBO();
	
	$query 	= "INSERT INTO `#__easyblog_acl` (`id`, `action`, `description`, `published`, `ordering`) VALUES
				(1, 'add_entry', 'Can write entry?', 1, 0),
				(2, 'publish_entry', 'Can publish entry?', 1, 0),
				(3, 'allow_feedburner', 'Can burn feed?', 1, 0),
				(4, 'upload_avatar', 'Can upload avatar?', 1, 0),
				(5, 'manage_comment', 'Can manage comments posted to his blog?', 1, 0),
				(6, 'update_twitter', 'Can update twitter?', 1, 0),
				(7, 'update_tweetmeme', 'Can update tweetmeme?', 0, 0),
				(8, 'delete_entry', 'Can delete own blogs?', 1, 0),
				(9, 'add_trackback', 'Can perform trackback action?', 1, 0),
				(10, 'contribute_frontpage', 'Can contribute to the frontpage?', 1, 0),
				(11, 'create_category', 'Can create category?', 1, 0),
				(12, 'create_tag', 'Can create tag?', 1, 0),
				(13, 'add_adsense', 'Can use adsense?', 1, 0),
				(14, 'allow_shortcode', 'Can use shortcode url?', 0, 0),
				(15, 'allow_rss', 'Can article support RSS features?', 0, 0),
				(16, 'custom_template', 'Can use different template?', 0, 0),
				(17, 'enable_privacy', 'Can enable blog privacy?', 1, 0),
				(18, 'allow_comment', 'Can user post comment?', 1, 0),
				(19, 'allow_subscription', 'Can subscribe to a blog?', 1, 0),
				(20, 'manage_pending', 'Can moderate pending post?', 1, 0),
				(21, 'upload_image', 'Can upload image during blog creation or edit?', 1, 0),
				(23, 'upload_cavatar', 'Can upload category avatar?', 1, 0),
				(24, 'update_linkedin', 'Allow to update LinkedIn?', 1, 0),
				(25, 'change_setting_comment', 'Can change own blog\'s comment settings?', 1, 0),
				(26, 'change_setting_subscription', 'Can change own blog\'s subscription settings?', 1, 0),
				(27, 'update_facebook', 'Allows user to post as links into Facebook', 1, 0),
				(28, 'delete_category', 'Can delete category?', 1, 0),
				(29, 'moderate_entry', 'Can moderate all blog entries from dashboard?', 1, 0),
				(30, 'edit_comment', 'Can edit comments from dashboard?', 1, 0),
				(31, 'delete_comment', 'Can delete comments from dashboard?', 1, 0)";
				
	$db->setQuery( $query );	
	$db->query();
	
	if($db->getErrorNum())
	{
		return false;
	}	
	
	return true;
}


function updateGroupACLRules()
{
	$db 	=& JFactory::getDBO();
	
	$userGroup  = array();
	
	if( getJoomlaVersion() >= '1.6' ) {
		//get all user group for 1.6
		$query = 'SELECT a.id, a.title AS `name`, COUNT(DISTINCT b.id) AS level';
		$query .= ' , GROUP_CONCAT(b.id SEPARATOR \',\') AS parents';
		$query .= ' FROM #__usergroups AS a';
		$query .= ' LEFT JOIN `#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt';
		$query .= ' GROUP BY a.id';
		$query .= ' ORDER BY a.lft ASC';
		
		$db->setQuery($query);
		$userGroups = $db->loadAssocList();
		
		$defaultAcl = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16 ,17, 18, 19, 21, 23, 24, 25, 26, 27, 28 );
		
		if(!empty($userGroups))
		{
			foreach($userGroups as $value)
			{
				switch($value['id'])
				{
					case '1':
						//default guest group in joomla 1.6
						$userGroup[$value['id']] = array();
						break;
					case '7':
						//default administrator group in joomla 1.6
					case '8':
						//default super user group in joomla 1.6
						$userGroup[$value['id']]  = 'all';
						break;
					default:
						//every other group
						$userGroup[$value['id']]  = $defaultAcl;
				}
			}
		}
	} else {
		$defaultAcl = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16 ,17, 18, 19, 21, 23, 24, 25, 26, 27, 28 );
	
		//18 registered
	    $userGroup[18]  = $defaultAcl;
	    
	    //19 author
	    $userGroup[19]  = $defaultAcl;
		
	    //20 editor
	    $userGroup[20]  = $defaultAcl;
	    
	    //21 publisher
	    $userGroup[21]  = $defaultAcl;
	    
	    //23 manager
	    $userGroup[23]  = $defaultAcl;
	    
	    //24 administrator
	    $userGroup[24]  = 'all';
	    
	    //25 super administrator
	    $userGroup[25]  = 'all';
	}

	//getting all acl rules.
	$query  = 'SELECT `id` FROM `#__easyblog_acl` ORDER BY `id` ASC';
	$db->setQuery($query);
	$aclTemp  	= $db->loadResultArray();
	
	$aclRules   			= array();
	$aclRulesAllEnabled   	= array();
	//do not use array_fill_keys for lower php compatibility. use old-fashion way. sigh.
	foreach($aclTemp as $item)
	{
	    $aclRules[$item] 			= 0;
	    $aclRulesAllEnabled[$item]	= 1;
	}
	
	$mainQuery  = array();
	foreach($userGroup as $uKey => $uGroup)
	{
	    $query  = 'SELECT COUNT(1) FROM `#__easyblog_acl_group` WHERE `content_id` = ' . $db->Quote($uKey);
	    $query  .= ' AND `type` = ' . $db->Quote('group');
	    
	    $db->setQuery($query);
	    $result = $db->loadResult();
	    
	    if(empty($result))
	    {
	        $udAcls  = array();
	        
	        if( is_array($uGroup))
	        {
	            $udAcls	= $aclRules;
	            
	            foreach($uGroup as $uAcl)
	            {
	                $udAcls[$uAcl] = 1;
	            }
	        }
	        else if($uGroup == 'all')
	        {
	            $udAcls = $aclRulesAllEnabled;
	        }
	        
	        foreach($udAcls as $key	=> $value)
	        {
	            $str    		= '(' . $db->Quote($uKey) . ', ' . $db->Quote($key) . ', ' . $db->Quote($value) . ', ' . $db->Quote('group') .')';
	            $mainQuery[]    = $str;
	        }
	    }//end if empty
	}//end foreach usergroup
	
	if(! empty($mainQuery))
	{
		$query  = 'INSERT INTO `#__easyblog_acl_group` (`content_id`, `acl_id`, `status`, `type`) VALUES ';
		$query  .= implode(',', $mainQuery);

		$db->setQuery($query);
		$db->query();
		
		if($db->getErrorNum())
		{
			return false;
		}
	}
	
	return true;
}

function truncateACLTable()
{
	$db 	=& JFactory::getDBO();

	$query 	= "TRUNCATE TABLE " . $db->nameQuote('#__easyblog_acl');	
	$db->setQuery( $query );	
	$db->query();
	
	if($db->getErrorNum())
	{
		return false;
	}
	
	return true;
}

function configExist()
{
	$db		=& JFactory::getDBO();
		
	$query	= 'SELECT COUNT(*) FROM ' 
			. $db->nameQuote( '#__easyblog_configs' ) . ' '
			. 'WHERE ' . $db->nameQuote( 'name' ) . '=' . $db->Quote( 'config' );
	$db->setQuery( $query );
	
	$exist	= ( $db->loadResult() > 0 ) ? true : false;
	
	return $exist;
}

function createConfig()
{
	$db			=& JFactory::getDBO();
	
	$config		= JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_easyblog' . DS . 'configuration.ini';
	$registry	=& JRegistry::getInstance( 'eblog' );
	$registry->loadFile( $config , 'INI' , 'eblog' );
	
	$obj			= new stdClass();
	$obj->name		= 'config';
	$obj->params	= $registry->toString( 'INI' , 'eblog' );
	
	return $db->insertObject( '#__easyblog_configs' , $obj );
}

function installDefaultPlugin( $sourcePath )
{
	$db 			=& JFactory::getDBO();
	$pluginFolder	= $sourcePath . DS . 'default_plugin';
	$plugins		= new stdClass();
	
	$joomlaVersion 	= getJoomlaVersion();
	
	//set plugin details
	$plugins->deleteuser->zip  		= $pluginFolder . DS . 'plg_easyblogusers.zip';
	
	if($joomlaVersion >= '1.6'){
		$plugins->deleteuser->path 	= JPATH_ROOT . DS . 'plugins' . DS . 'user' . DS . 'easyblogusers';
	} else {
		$plugins->deleteuser->path 	= JPATH_ROOT . DS . 'plugins' . DS . 'user';
	}
	
	$plugins->deleteuser->name 		= 'User - EasyBlog Users';
	$plugins->deleteuser->element 	= 'easyblogusers';
	$plugins->deleteuser->folder 	= 'user';
	$plugins->deleteuser->params 	= '';
	$plugins->deleteuser->lang 		= '';
	
	foreach($plugins as $plugin)
	{
		if(!JFolder::exists($plugin->path))
		{
			JFolder::create($plugin->path);
		}
		
		if( extractArchive($plugin->zip, $plugin->path) )
		{
			if( $joomlaVersion >= '1.6' ) {
				//delete old plugin entry before install
				$sql = 'DELETE FROM ' 
					 			. $db->nameQuote('#__extensions') . ' '
					 . 'WHERE ' . $db->nameQuote('element') . '=' . $db->quote($plugin->element) . ' AND '
					 		    . $db->nameQuote('folder') . '=' . $db->quote($plugin->folder) . ' AND '
								. $db->nameQuote('type') . '=' . $db->quote('plugin') . ' ';
				$db->setQuery($sql);
				$db->Query();
				
				//insert plugin again
				$sql 	= 'INSERT INTO ' . $db->nameQuote( '#__extensions' ) 
						. '('
							. $db->nameQuote( 'name' ) . ', '
							. $db->nameQuote( 'type' ) . ', '
							. $db->nameQuote( 'element' ) . ', '
							. $db->nameQuote( 'folder' ) . ', '
							. $db->nameQuote( 'client_id' ) . ', '
							. $db->nameQuote( 'enabled' ) . ', '
							. $db->nameQuote( 'access' ) . ', '
							. $db->nameQuote( 'protected' ) . ', '
							. $db->nameQuote( 'params' ) . ', '
							. $db->nameQuote( 'ordering' ) . ' '
						. ') '
						. 'VALUES('
							. $db->quote( $plugin->name ) . ', '
							. $db->quote( 'plugin' ) . ', '
							. $db->quote( $plugin->element ) . ', '
							. $db->quote( $plugin->folder ) . ', '
							. $db->quote( '0' ) . ', '
							. $db->quote( '1' ) . ', '
							. $db->quote( '1' ) . ', '
							. $db->quote( '0' ) . ', '
							. $db->quote( $plugin->params ) . ', '
							. $db->quote( '0' ) . ' '
						. ') ';
			} else {
				//delete old plugin entry before install
				$sql = 'DELETE FROM ' 
					 			. $db->nameQuote('#__plugins') . ' '
					 . 'WHERE ' . $db->nameQuote('element') . '=' . $db->quote($plugin->element) . ' AND '
					 		    . $db->nameQuote('folder') . '=' . $db->quote($plugin->folder);
				$db->setQuery($sql);
				$db->Query();
				
				//insert plugin again
				$sql 	= 'INSERT INTO ' . $db->nameQuote( '#__plugins' ) 
						. '('
							. $db->nameQuote( 'name' ) . ', '
							. $db->nameQuote( 'element' ) . ', '
							. $db->nameQuote( 'folder' ) . ', '
							. $db->nameQuote( 'access' ) . ', '
							. $db->nameQuote( 'ordering' ) . ', '
							. $db->nameQuote( 'published' ) . ', '
							. $db->nameQuote( 'iscore' ) . ', '
							. $db->nameQuote( 'client_id' ) . ', '
							. $db->nameQuote( 'params' ) . ' '
						. ') '
						. 'VALUES('
							. $db->quote( $plugin->name ) . ', '
							. $db->quote( $plugin->element ) . ', '
							. $db->quote( $plugin->folder ) . ', '
							. $db->quote( '0' ) . ', '
							. $db->quote( '0' ) . ', '
							. $db->quote( '1' ) . ', '
							. $db->quote( '0' ) . ', '
							. $db->quote( '0' ) . ', '
							. $db->quote( $plugin->params ) . ' '
						. ') ';
			}
			
			$db->setQuery($sql);
			$db->Query();
			
			if($db->getErrorNum()){
				JError::raiseError( 500, $db->stderr());
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
}

function backupThemes()
{
	$backupDate	= JFactory::getDate()->toFormat('%Y%m%d%H%M%S');
	
	$src	= JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'themes';
	$dest	= JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'themes_bak'.DS.$backupDate;
	
	if(!JFolder::exists($src))
	{
		return true;
	}
	
	if(JFolder::copy($src, $dest))
	{
		return JFolder::delete($src);	
	}

	return false;
}

function installThemes($sourcePath)
{
	$src	= $sourcePath . DS . 'site' . DS . 'themesnew';
	$dest	= JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'themes';
	
	if(JFolder::exists($dest))
	{
		JFolder::delete($dest);
	}
	
	$themeInstalled = JFolder::copy($src, $dest);
	
	if(JFolder::exists(JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'themesnew'))
	{
		JFolder::delete(JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'themesnew');
	}
	
	return $themeInstalled;
}

/**
 * Method to extract archive
 * 
 * @returns	boolean	True on success false if fail.
 **/ 
function extractArchive( $source , $destination )
{
	// Cleanup path
	$destination	= JPath::clean( $destination );
	$source			= JPath::clean( $source );

	return JArchive::extract( $source , $destination );
}

function updateEasyBlogDBColumns()
{
	$db		=& JFactory::getDBO();
	
	if(! _isColumnExists( '#__easyblog_post' , 'isnew' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_post` ADD `isnew` tinyint unsigned NULL DEFAULT 0';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_trackback_sent' , 'sent' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_trackback_sent` ADD COLUMN `sent` TINYINT(1) DEFAULT 0';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_team' , 'alias' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_team` ADD `alias` VARCHAR( 255 ) NULL AFTER `title` ';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_post' , 'ispending' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_post` ADD COLUMN `ispending` TINYINT(1) DEFAULT 0 NULL AFTER `isnew` ';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_migrate_content' , 'component' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_migrate_content` ADD COLUMN `component` varchar(255) NOT NULL DEFAULT ' . $db->Quote('com_content');
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_users' , 'permalink' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_users` ADD COLUMN `permalink` varchar(255) NULL';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_category' , 'avatar' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_category` ADD COLUMN `avatar` varchar(255) NULL';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_post_subscription' , 'fullname' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_post_subscription` ADD COLUMN `fullname` varchar(255) NULL';
		$db->setQuery($query);
		$db->query();
	}

	if(! _isColumnExists( '#__easyblog_blogger_subscription' , 'fullname' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_blogger_subscription` ADD COLUMN `fullname` varchar(255) NULL';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_category_subscription' , 'fullname' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_category_subscription` ADD COLUMN `fullname` varchar(255) NULL';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_category' , 'parent_id' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_category` ADD COLUMN `parent_id` int(11) NULL default 0';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_category' , 'private' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_category` ADD COLUMN `private` int(11) NULL default 0';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_post' , 'issitewide' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_post` ADD COLUMN `issitewide` TINYINT(1) DEFAULT 1 NULL';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_team' , 'access' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_team` ADD COLUMN `access` TINYINT(1) DEFAULT 1 NULL';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_team' , 'avatar' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_team` ADD COLUMN `avatar` varchar(255) NULL';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_feedburner' , 'id' ) )
	{
		//now remove the PK and recreate PK.
		$query = 'ALTER TABLE `#__easyblog_feedburner` DROP PRIMARY KEY';
		$db->setQuery($query);
		$db->query();
		
		$query = 'ALTER TABLE `#__easyblog_feedburner` ADD COLUMN `id` bigint(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_comment' , 'sent' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_comment` ADD `sent` TINYINT( 1 ) DEFAULT 1 NULL';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_team_users' , 'isadmin' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_team_users` ADD `isadmin` tinyint(1) DEFAULT 0 NULL';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_post' , 'blogpassword' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_post` ADD `blogpassword` VARCHAR( 100 ) NOT NULL DEFAULT ""';
		$db->setQuery($query);
		$db->query();
	}

	if(! _isColumnExists( '#__easyblog_ratings' , 'sessionid' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_ratings` ADD `sessionid` VARCHAR( 200 ) NOT NULL DEFAULT ""';
		$db->setQuery($query);
		$db->query();
	}
	
	if(! _isColumnExists( '#__easyblog_tag' , 'default' ) )
	{
		$query = 'ALTER TABLE `#__easyblog_tag` ADD `default` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `published`';
		$db->setQuery($query);
		$db->query();
	}
	return true;
}


function _isColumnExists($tbName, $colName)
{
	$db		=& JFactory::getDBO();

	$query	= 'SHOW FIELDS FROM ' . $db->nameQuote( $tbName );
	$db->setQuery( $query );

	$fields	= $db->loadObjectList();
	
	
	foreach( $fields as $field )
	{
		$result[ $field->Field ]	= preg_replace( '/[(0-9)]/' , '' , $field->Type );
	}
	
	if(array_key_exists($colName, $result))
	{
		return true;
	}
	
	return false;
}

function getJoomlaVersion()
{
    $jVerArr   = explode('.', JVERSION);
    $jVersion  = $jVerArr[0] . '.' . $jVerArr[1];

	return $jVersion;
}

function getSAUsersIds()
{
	$db =& JFactory::getDBO();

	$query  = 'SELECT a.`id`, a.`title`';
	$query	.= ' FROM `#__usergroups` AS a';
	$query	.= ' LEFT JOIN `#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt';
	$query	.= ' GROUP BY a.id';
	$query	.= ' ORDER BY a.lft ASC';
	
	$db->setQuery($query);
	$result = $db->loadObjectList();
	
	$saGroup    = array();
	foreach($result as $group)
	{
	    if(JAccess::checkGroup($group->id, 'core.admin'))
	    {
	        $saGroup[]  = $group;
	    }
	}
	
	
	//now we got all the SA groups. Time to get the users
	$saUsers    = array();
	if(count($saGroup) > 0)
	{
	    foreach($saGroup as $sag)
	    {
              $userArr	= JAccess::getUsersByGroup($sag->id);
              if(count($userArr) > 0)
              {
                  foreach($userArr as $user)
                  {
                      $saUsers[]    = $user;
                  }
              }
	    }
	}
	
	return $saUsers;
}

function postExist()
{
	$db		=& JFactory::getDBO();
		
	$query	= 'SELECT COUNT(1) FROM '
			. $db->nameQuote( '#__easyblog_post' );
	$db->setQuery( $query );

	$result = $db->loadResult();
	$exist	= ( !empty($result) ) ? true : false;
	
	return $exist;
}

function createSamplePost()
{
	$db 	=& JFactory::getDBO();
	
	$suAdmin    = _getSuperAdminId();
	
	$post = new stdClass();
	$post->title		= 'Congratulations! You have successfully installed EasyBlog!';
	$post->permalink	= 'congratulations-you-have-successfully-installed-easyblog';
	
	$post->content		= '<h2>With EasyBlog, you can be assured of quality blogging with the following features:</h2>'
						. '<ol>'
						. '<li> <strong><span style="text-decoration: underline;">Blog now, post later</span></strong><br />You can compose a blog now, suffer temporal writer\'s block, save and write again, later.</li>'
						. '<li> <strong><span style="text-decoration: underline;">Social media sharing</span></strong><br />Automatically post into your <em><strong>Twitter</strong></em>, <em><strong>Facebook</strong></em> and <em><strong>LinkedIn</strong></em> whenever you create new blog entries.</li>'
						. '<li> <strong><span style="text-decoration: underline;">Browse media</span></strong><br />Embedding images and videos is fast and easy.</li>'
						. '<li> <strong><span style="text-decoration: underline;">More third party integrations</span></strong><br />Having other Joomla! plugins and extensions to work with EasyBlog is just a few clicks away.</li>'
						. '<li> <strong><span style="text-decoration: underline;">Blog rating</span></strong><br />Users can show intensity of their favorite blog post by rating them with stars.</li>'
						. '</ol>'
						. '<p>And many more powerful features that you can use to make your blog work beautifully and professionally!</p>'
						. '<p> </p>'
						. '<p>We welcome any inquiries and feedback from you. Feel free to send us an email to <a href="mailto:support@stackideas.com" target="_blank">support@stackideas.com</a> for immediate attention. Or, you can also refer to our Documentations and FAQs from our website at <a href="http://stackideas.com" target="_blank">http://stackideas.com</a></p>';
	
	$post->intro		= '<h2>Thank you for making the right decision to start blogging in your Joomla! website.</h2>'
						. '<p><img src="http://stackideas.com/images/eblog/install_success.png" border="0" style="align:center;" /></p>'
						. '<p> </p>';
	
	$query 		= 'INSERT IGNORE INTO `#__easyblog_post` ( `id`, `created_by`, `created`, `modified`, `title`, `permalink`, `content`, `intro`, `excerpt`, `category_id`, `published`, `publish_up`, `publish_down`, `ordering`, `vote`, `hits`, `private`, `allowcomment`, `subscription`, `frontpage`, `isnew`, `ispending`, `issitewide`, `blogpassword` ) '
				. 'VALUES ( "1", ' . $db->Quote($suAdmin) . ', now(), now(), ' . $db->Quote($post->title) . ', ' . $db->Quote($post->permalink) . ', ' . $db->Quote($post->content) . ', ' . $db->Quote($post->intro) . ', ' . $db->Quote($post->intro) . ', "1", "1", now(), "0000-00-00 00:00:00", "0", "0", "0", "0", "0", "1", "1", "1", "0", "1", "" )';
	$db->setQuery( $query );
	$db->query();
	
	//create tag for sample post
	$query 		= 'INSERT IGNORE INTO `#__easyblog_tag` ( `id`, `created_by`, `title`, `alias`, `created`, `status`, `published`, `ordering`) '
				. 'VALUES ( "1", ' . $db->Quote($suAdmin) .', "Thank You", "thank-you", now(), "0", "1", "0" ), '
				. '( "2", ' . $db->Quote($suAdmin) .', "Congratulations", "congratulations", now(), "0", "1", "0" ) ';
	$db->setQuery( $query );
	$db->query();
	
	//create posts tags records
	$query 		= 'INSERT INTO `#__easyblog_post_tag` ( `tag_id`, `post_id`, `created`) '
				. 'VALUES ( "1", "1", now() ), '
				. '( "2", "1", now() ) ';
	$db->setQuery( $query );
	$db->query();
	
	if($db->getErrorNum())
	{
		return false;
	}	
	return true;
}

function removeAdminMenu()
{
	$db =& JFactory::getDBO();

	$query  = '	DELETE FROM `#__menu` WHERE link LIKE \'%com_easyblog%\' AND client_id = \'1\'';
	
	$db->setQuery($query);
	$db->query();
}

function twitterTableExist()
{
	$db =& JFactory::getDBO();

	$query  = 'SHOW TABLES LIKE ' . $db->quote('#__easyblog_twitter');
	
	$db->setQuery($query);
	$result = $db->loadResult();
	
	return $result? true : false;
}


function twitterTableMigrate()
{
	$db =& JFactory::getDBO();
	
	$query  = 	'INSERT INTO ' . $db->nameQuote('#__easyblog_oauth') . '(`user_id`, `type`, `auto`, `request_token`, `access_token`, `message`, `created`, `params`) '
			.		'SELECT `user_id`, "twitter" as type, `auto`, '
			.			'replace(replace(`oauth_request_token`, "oauth_token_secret", "secret"), "oauth_token", "token") as request_token, '
			.			'replace(replace(`oauth_access_token` , "oauth_token_secret", "secret"), "oauth_token", "token") as access_token, '
			.			'`message`, NOW() as created, CONCAT("user_id=", `user_id`, "\r\n", "screen_name=", `username` ) as params '
			.		'FROM ' . $db->nameQuote('#__easyblog_twitter');
	
	$db->setQuery($query);
	$result = $db->query();
	
	return $result;
}

function twitterTableRemove()
{
	$db =& JFactory::getDBO();
	
	$query  = 'DROP TABLE IF EXISTS ' . $db->nameQuote('#__easyblog_twitter_posts');
	$db->setQuery($query);
	$db->query();
	
	$query  = 'DROP TABLE IF EXISTS ' . $db->nameQuote('#__easyblog_twitter');
	$db->setQuery($query);
	$result = $db->query();
	
	//incase we doesn't have enough permission to drop table, we empty it.
	if(!$result)
	{
		$query  = 'TRUNCATE TABLE ' . $db->nameQuote('#__easyblog_twitter');
		
		$db->setQuery($query);
		$db->query();
	}
	
	return $result;
}