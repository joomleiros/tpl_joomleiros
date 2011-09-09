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
require_once( EBLOG_HELPERS . DS . 'string.php' );
require_once( EBLOG_HELPERS . DS . 'date.php' );

class EasyBlogViewSearch extends JView
{
	public function search( $query = '' , $elementId ='' )
	{
		$ajax	= new Ejax();

	    $lang		= JFactory::getLanguage();
	    $lang->load( 'com_easyblog' , JPATH_ROOT );
	    
		jimport( 'joomla.application.component.model' );
		JLoader::import( 'search' , EBLOG_ROOT . DS . 'models' );
		$model	= JModel::getInstance( 'Search' , 'EasyBlogModel' );
		$posts	= $model->searchText( $query );
		
		if( empty($posts) )
		{
			$ajax->script( 'sQuery("#editor-'.$elementId.' .search-results-content").height(24);' );
			$ajax->assign( 'editor-' . $elementId . ' .search-results-content', JText::_( 'No results found' ) );
			return $ajax->send();
		}

		$count = count($posts);
		
		if($count > 10)
		{
			$height = "240";
		}
		else
		{
			$height = "24" * $count;
		}
		$config = EasyBlogHelper::getConfig();
		
		ob_start();
?>
<ul class="blog-search-items ulrest">
	<?php foreach( $posts as $entry )
	{
		$postLink		= EasyBlogRouter::_( 'index.php?option=com_easyblog&view=entry&id=' . $entry->id );
		$externalLink	= EasyBlogRouter::getRoutedURL( 'index.php?option=com_easyblog&view=entry&id=' . $entry->id , false , true );
	?>
	<li>
        <input type="button" onclick="eblog.editor.search.insert('<?php echo $externalLink; ?>', '<?php echo $entry->title; ?>', '<?php echo $elementId ?>');return false;" value="<?php echo JText::_('COM_EASYBLOG_DASHBOARD_EDITOR_INSERT_LINK'); ?>" class="ui-button fright mts" />
        <div class="tablecell">
            <a href="<?php echo $postLink; ?>" target="_BLANK"><?php echo $entry->title; ?></a>
            <?php echo JText::_( 'COM_EASYBLOG_ON' );?>
            <?php echo $this->formatDate( $config->get('layout_dateformat') , $entry->created ); ?>
        </div>
        <div class="clear"></div>
	</li>
	<?php
	}
	?>
</ul>
<?php
		$html	= ob_get_contents();
		ob_end_clean();
		$ajax->assign( 'editor-' . $elementId . ' .search-results-content' , $html );
		$ajax->script( 'sQuery("#editor-' . $elementId . ' .search-results-content").height('.$height.');' );
		$ajax->script( 'sQuery("#editor-'.$elementId.' .search-results-content").show();' );
		return $ajax->send();
	}

	function formatDate( $format , $dateString )
	{
		$date	= EasyBlogDateHelper::dateWithOffSet($dateString);
		return EasyBlogDateHelper::toFormat($date, $format);
	}
}