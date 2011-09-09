<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *  
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

require_once( EBLOG_HELPERS . DS . 'helper.php' );
require_once( EBLOG_HELPERS . DS . 'oauth.php' );

class EasyBlogControllerOauth extends JController
{
	/**
	 * Constructor
	 *
	 * @since 0.1
	 */
	function __construct()
	{
		// Include the tables in path
		JTable::addIncludePath( EBLOG_TABLES );

		parent::__construct();
	}
	
	function request()
	{
		$mainframe	= JFactory::getApplication();
		$type		= JRequest::getCmd( 'type' );
		$id			= JRequest::getCmd( 'id' );
		$return		= JRequest::getCmd( 'return', 'user' );
		$activechild= JRequest::getCmd( 'activechild', '' );
		$config		= EasyBlogHelper::getConfig();
		$key		= $config->get( 'integrations_' . $type . '_api_key' );
		$secret		= $config->get( 'integrations_' . $type . '_secret_key' );
		$callback	= trim(JURI::base(), "/").JRoute::_( '/index.php?option=com_easyblog&c=oauth&task=grant&type=' . $type, false , true );
  		$consumer	= EasyBlogOauthHelper::getConsumer( $type , $key , $secret , $callback );
		$request	= $consumer->getRequestToken();
		
		if( empty( $request->token ) || empty( $request->secret ) )
		{
			$mainframe->enqueueMessage( JText::_('COM_EASYBLOG_OAUTH_AUTHENTICATION_FAILED') , 'error');
			$this->setRedirect( JRoute::_('index.php?option=com_easyblog&c=user&id='.$id.'&task=edit' , false ) );
			return;
		}
		
		$obj    = new stdClass();
		$obj->return    	= $return;
		$obj->activechild   = $activechild;
		$obj->id   			= $id;

		EasyBlogHelper::storeSession($obj, 'EASYBLOG_OAUTH_DETAIL');

		$oauth				=& JTable::getInstance( 'Oauth' , 'Table' );
		$oauth->user_id		= JFactory::getUser($id)->id;
		$oauth->type		= $type;
		$oauth->created		= JFactory::getDate()->toMySQL();
		
		// Bind the request tokens
		$param				= new JParameter('');
		$param->set( 'token' , $request->token );
		$param->set( 'secret' , $request->secret );
		
		$oauth->request_token	= $param->toString();

		$oauth->store();

		$this->setRedirect( $consumer->getAuthorizationURL( $request->token , false ) );
	}
	
	/**
	 * This will be a callback from the oauth client.
	 * @param	null
	 * @return	null	 	 
	 **/	 	
	public function grant()
	{
		$mainframe	= JFactory::getApplication();
		$type		= JRequest::getCmd( 'type' );
		
		$obj		= EasyBlogHelper::getSession('EASYBLOG_OAUTH_DETAIL');
		$return		= $obj->return;
		$activechild= $obj->activechild;
		$id         = $obj->id;
		
		$mainframe	=& JFactory::getApplication();
		$config		= EasyBlogHelper::getConfig();
		$key		= $config->get( 'integrations_' . $type . '_api_key' );
		$secret		= $config->get( 'integrations_' . $type . '_secret_key' );
		$my			=& JFactory::getUser($id);
		
		if( $my->id == 0 || $my->id < 1 )
		{
			JError::raiseError( 500 , JText::_( 'You must be logged in to perform this request' ) );
		}
		
		$oauth		=& JTable::getInstance( 'Oauth' , 'Table' );
		$loaded		= $oauth->loadByUser( $my->id , $type );
		
		if( !$loaded )
		{
			JError::raiseError( 500 , JText::_( 'Unable to locate any request tokens in the database for your account.' ) );
		}

		$request	= new JParameter( $oauth->request_token );
		$callback	= trim(JURI::base(), "/").JRoute::_( '/index.php?option=com_easyblog&c=oauth&task=grant&type=' . $type);
		$consumer	= EasyBlogOauthHelper::getConsumer( $type , $key , $secret , $callback );
		$verifier	= $consumer->getVerifier();

		if( empty( $verifier ) )
		{
			// Since there is a problem with the oauth authentication, we need to delete the existing record.
			$oauth->delete();
			
			JError::raiseError( 500 , JText::_( 'Invalid verifier code' ) );
		}

		$access		= $consumer->getAccess( $request->get( 'token' ) , $request->get( 'secret' ) , $verifier );
		
		switch($return)
		{
			case 'settings':
				$redirect = JRoute::_('index.php?option=com_easyblog&view=settings&active=social&activechild='.$activechild , false );
				break;
			case 'user':
			default:
				$redirect = JRoute::_('index.php?option=com_easyblog&c=user&id='.$id.'&task=edit' , false );
				break;
		}
		
		if( !$access || empty( $access->token ) || empty( $access->secret ) )
		{
			// Since there is a problem with the oauth authentication, we need to delete the existing record.
			$oauth->delete();
			
			$mainframe->enqueueMessage( JText::_('There was an error when trying to retrieve your access tokens.') , 'error');
			$this->setRedirect( $redirect );
			return;
		}
		
		$param		= new JParameter('');
		$param->set( 'token' 	, $access->token );
		$param->set( 'secret'	, $access->secret );
		
		$oauth->access_token	= $param->toString();
		$oauth->store();
		
		$mainframe->enqueueMessage( JText::_('You have successfully associated your account') );
		$this->setRedirect( $redirect );
	}

	/**
	 * Responsible to revoke access for the specific oauth client
	 * 
	 * @param	null
	 * @return	null	 	 	 
	 **/	 	
	public function revoke()
	{
		$mainframe	= JFactory::getApplication();
		$id			= JRequest::getCmd( 'id' );
		$return		= JRequest::getCmd( 'return', 'user' );
		$activechild= JRequest::getCmd( 'activechild', '' );
		$my			= JFactory::getUser($id);
		$url		= JRoute::_('index.php?option=com_easyblog&view=dashboard&layout=profile' , false );
		$type		= JRequest::getWord( 'type' );
		$config		= EasyBlogHelper::getConfig();
		
		if( $my->id == 0 )
		{
			$mainframe->enqueueMessage( JText::_('COM_EASYBLOG_OAUTH_INVALID_USER') , 'error');
			$this->setRedirect( $return );
		}

		$oauth		= JTable::getInstance( 'OAuth' , 'Table' );
		$oauth->loadByUser( $my->id , $type );
		
		// Revoke the access through the respective client first.
		$callback	= trim(JURI::base(), "/").JRoute::_( '/index.php?option=com_easyblog&c=oauth&task=grant&type=' . $type . '&return=' . $return . '&activechild=' . $activechild . '&id=' . $id , false , true );
		$key		= $config->get( 'integrations_' . $type . '_api_key' );
		$secret		= $config->get( 'integrations_' . $type . '_secret_key' );
		$consumer	= EasyBlogOauthHelper::getConsumer( $type , $key , $secret , $callback );
		$consumer->setAccess( $oauth->access_token );
		
		switch($return)
		{
			case 'settings':
				$redirect = JRoute::_('index.php?option=com_easyblog&view=settings&active=social&activechild='.$activechild , false );
				break;
			case 'user':
			default:
				$redirect = JRoute::_('index.php?option=com_easyblog&c=user&id='.$id.'&task=edit' , false );
				break;
		}
		
		if( !$consumer->revokeApp() )
		{
			$mainframe->enqueueMessage( JText::_('There was an error when trying to revoke your app.') , 'error');
			$this->setRedirect( $redirect );
			return;
		}
		$oauth->delete();
		
		$mainframe->enqueueMessage( JText::_('Application revoked successfully.') );
		$this->setRedirect( $redirect );
	}
}