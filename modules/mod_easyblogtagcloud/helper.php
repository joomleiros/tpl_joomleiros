<?php
/*
 * @package		mod_easyblogtagcloud
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

class modEasyBlogTagCloudHelper
{
	function getTagCloud( $params )
	{
		$mainframe	= JFactory::getApplication();
		$order		= $params->get('order', 'postcount');
		$sort		= $params->get('sort', 'desc');
		$count		= (int) trim( $params->get('count', 0) );
		$shuffeTags	= $params->get('shuffleTags', TRUE);
		$pMinSize	= $params->get('minsize', '10');
		$pMaxSize	= $params->get('maxsize', '30');

		if( !class_exists( 'EasyBlogModelTags' ) )
		{
			jimport( 'joomla.application.component.model' );
			JLoader::import( 'tags' , EBLOG_ROOT . DS . 'models' );
		}
		$model		=& JModel::getInstance( 'Tags' , 'EasyBlogModel' );
		$tagCloud	= $model->getTagCloud($count, $order, $sort);	
		$extraInfo  = array();
		
		if($shuffeTags)
			shuffle($tagCloud);
		
		foreach($tagCloud as $item)
		{
		    $extraInfo[]    = $item->post_count;
		}
		
		$min_size = $pMinSize;
		$max_size = $pMaxSize;
		
		//$tags = tag_info();
		$minimum_count	= 0;
		$maximum_count	= 0;
		
		
		if(! empty($extraInfo))
		{
			$minimum_count	= min($extraInfo);
			$maximum_count	= max($extraInfo);
		}
		
		$spread = $maximum_count - $minimum_count;
		
		if($spread == 0) {
			$spread = 1;
		}
		
		$cloud_html = '';
		$cloud_tags = array();
		
		//foreach ($tags as $tag => $count)
		for($i = 0; $i < count($tagCloud); $i++)
		{
			$row    =& $tagCloud[$i];
		    
			$size = $min_size + ($row->post_count - $minimum_count) * ($max_size - $min_size) / $spread;
			$row->fontsize  = $size;
		}
		
		return $tagCloud;
	}
}
