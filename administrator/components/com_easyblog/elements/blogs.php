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

class JElementBlogs extends JElement
{
	var	$_name = 'Blogs';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$mainframe	=& JFactory::getApplication();
		$db			=& JFactory::getDBO();
		$doc 		=& JFactory::getDocument();

		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_easyblog' . DS . 'models' . DS . 'blog.php' );
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_easyblog' . DS . 'constants.php' );
		$model		= new EasyBlogModelBlog();
		//$categories	= $model->getAllCategories();
		
		$data		= $model->getBlogsBy('', '', 'latest' , 30 , 'published', null, true, '');
		
		ob_start();
		?>
		<select name="<?php echo $control_name;?>[<?php echo $name;?>]">
			<option value="0"<?php echo $value == 0 ? ' selected="selected"' :'';?>><?php echo JText::_('Select a blog');?></option>
		<?php		
		foreach($data as $blog)
		{
			$selected	= $blog->id == $value ? ' selected="selected"' : '';
		?>
			<option value="<?php echo $blog->id;?>"<?php echo $selected;?>><?php echo '(' . $blog->id . ') ' . $blog->title;?></option>
		<?php
		}
		?>
		</select>
		<?php
		$html	= ob_get_contents();
		ob_end_clean();
		return $html;
	}
}
