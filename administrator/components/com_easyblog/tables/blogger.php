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

class TableBlogger extends JTable
{
	var $id 			= null;
	var $title			= null;
	var $biography		= null;
	var $nickname		= null;
	var $avatar			= null;
	var $description	= null;
	var $url			= null;
	var $params			= null;
	var $published		= null;

	/**
	 * Constructor for this class.
	 *
	 * @return
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_users' , 'id' , $db );
	}
	
	function bindPost()
	{
		$data	= array(
							'nickname'		=> JRequest::getWord( 'nickname' ),
							'description'	=> JRequest::getVar( 'description' ),
							'url'			=> JRequest::getVar( 'url' ),
							'biography'		=> JRequest::getVar( 'biography' ),
							'title'			=> JRequest::getVar( 'title' )
						);
						
		pr($data);exit;
						
		parent::bind( $data );
		
		$avatar	= JRequest::getVar( 'avatar' , '' , 'Files');
		
		if( !empty( $avatar['tmp_name'] ) )
		{
			require_once( EBLOG_HELPERS . DS . 'image.php' );
		}
	}
	
	function getAvatar(){
	    $avatar_link    = '';

        if($this->avatar == 'default.png' || $this->avatar == 'default_blogger.png' || $this->avatar == 'components/com_easyblog/assets/images/default_blogger.png' || $this->avatar == 'components/com_easyblog/assets/images/default.png' || empty($this->avatar))
        {
            $avatar_link   = 'components/com_easyblog/assets/images/default_blogger.png';
        }
        else
        {
    		$avatar_link   = EasyImageHelper::getAvatarRelativePath() . '/' . $this->avatar;
    	}

		return $avatar_link;
	}

}