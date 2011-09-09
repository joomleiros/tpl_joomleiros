<?php
/**
 * @version		$Id: index.php 1.0 2011-03-08 19:09:53 
 * @package		JoomLess Teplate Pack
 * @copyright	Copyright (C) 1998 - 2011 Ideas Net Studio. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die; 

$doc 	=& JFactory::getDocument();
$br 	= strtolower($_SERVER['HTTP_USER_AGENT']);

/* Script to prevent mootools to load */
if ($this->params->get('mootools') == 0): 
	$user =& JFactory::getUser();
	if($user->get('guest') == 1) :
		$search = array('mootools', 'caption.js');
		// remove the js files
		foreach($this->_scripts as $key => $script) :
			foreach($search as $findme) :
				if(stristr($key, $findme) !== false) :
					unset($this->_scripts[$key]);
				endif;
			endforeach;
		endforeach;
	endif; 
endif;

/* Script to load LESS CSS PHP with 4 CSS files for Media Queries */
if ($this->params->get('lessphp') == 1):
	require_once( JPATH_THEMES.DS.$this->template.DS.'includes'.DS.'lessc.inc.php' );
	
	try {
		lessc::ccompile(JPATH_THEMES.DS.$this->template.DS.'less'.DS.'template.less', JPATH_THEMES.DS.$this->template.DS.'css'.DS.'template.css');
	} catch (exception $ex) {
		exit('lessc fatal error:<br />'.$ex->getMessage());
	}
	
	try {
		lessc::ccompile(JPATH_THEMES.DS.$this->template.DS.'less'.DS.'768px.less', JPATH_THEMES.DS.$this->template.DS.'css'.DS.'768px.css');
	} catch (exception $ex) {
		exit('lessc fatal error:<br />'.$ex->getMessage());
	}
	
	try {
		lessc::ccompile(JPATH_THEMES.DS.$this->template.DS.'less'.DS.'480px.less', JPATH_THEMES.DS.$this->template.DS.'css'.DS.'480px.css');
	} catch (exception $ex) {
		exit('lessc fatal error:<br />'.$ex->getMessage());
	}
	
	try {
		lessc::ccompile(JPATH_THEMES.DS.$this->template.DS.'less'.DS.'320px.less', JPATH_THEMES.DS.$this->template.DS.'css'.DS.'320px.css');
	} catch (exception $ex) {
		exit('lessc fatal error:<br />'.$ex->getMessage());
	}
endif;

/* Set Generator */
if ($this->params->get('generator')):
	$this->setGenerator($this->params->get('generator'));
endif;

/* Add jQuery into the head of the template */
if ($this->params->get('jquery') == 1):
	$doc->addScript( $this->baseurl."/templates/".$this->template."/js/libs/jquery-1.6.2.min.js");
endif;

/* Add jQuery No conflict instance to work with Mootools */
if ($this->params->get('noconflict') == 1):
	$doc->addScriptDeclaration( 'jQuery.noConflict();' );
endif;

/* Add Modernizr into the head of the template */
if ($this->params->get('jquery') == 1):
	$doc->addScript( $this->baseurl."/templates/".$this->template."/js/libs/modernizr-2.0.min.js");
endif;

/* Add Modernizr into the head of the template */
if ($this->params->get('960') == 1):
	$doc->addScript( $this->baseurl."/templates/".$this->template."/js/960.js");
endif;

//If IE is detected, it will load ie.php into the head of the template
if(preg_match("/msie/", $br)) :
	require_once( JPATH_THEMES.DS.$this->template.DS.'includes'.DS.'ie.php' ); 
endif;

