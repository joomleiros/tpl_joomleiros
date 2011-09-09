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
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');

class EasyBlogAdminView extends JView
{
	function &getModel( $name )
	{
		static $model = array();
		
		if( !isset( $model[ $name ] ) )
		{
			$path	= EBLOG_ADMIN_ROOT . DS . 'models' . DS . JString::strtolower( $name ) . '.php';

			jimport('joomla.filesystem.path');
			if ( !JFile::exists( $path ))
			{
				JError::raiseWarning( 0, 'Model file not found.' );
			}
			
			$modelClass		= 'EasyBlogModel' . ucfirst( $name );
			
			if( !class_exists( $modelClass ) )
				require_once( $path );
			
			
			$model[ $name ] = new $modelClass();
		}

		return $model[ $name ];
	}

	function renderCheckbox( $configName , $state )
	{
		ob_start();
	?>
		<label class="option-enable<?php echo $state == 1 ? ' selected' : '';?>"><span><?php echo JText::_( 'COM_EASYBLOG_YES_OPTION' );?></span></label>
		<label class="option-disable<?php echo $state == 0 ? ' selected' : '';?>"><span><?php echo JText::_( 'COM_EASYBLOG_NO_OPTION' ); ?></span></label>
		<input name="<?php echo $configName; ?>" value="<?php echo $state;?>" type="radio" class="radiobox" checked="checked" />
	<?php
		$html	= ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
}
