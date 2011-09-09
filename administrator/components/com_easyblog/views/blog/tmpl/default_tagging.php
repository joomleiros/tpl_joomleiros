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

require_once( EBLOG_CLASSES . DS  . 'json.php' );
$json	= new Services_JSON();
?>
<script type="text/javascript">

(function($)
{
	$(document).ready(function()
	{
		var tagInput = $('.tag-input');
		var tagList  = $('.tag-list');
		var tagTemplate = $('.tag-list-item');

		function noTagAvailable()
		{
			var tagItems = tagList.find('.tag-item');
			var noTag = tagList.find('.no-tag');
			noTag.toggle(tagItems.length < 1);
		}

		// Generate existing tags
		tagTemplate
			.tmpl(<?php echo $json->encode($this->blog->tags); ?>)
			.appendTo(tagList);
		noTagAvailable();

		// Enable suggestions on tagInput
		tagInput
			.stretchToFit()
			.stackSuggest({
				dataset: <?php echo $json->encode($this->blog->newtags); ?>,
				filterkey: ['title'],
				position: {
					my: 'left bottom',
					at: 'left top'
				},
				custom: function(keyword)
				{
					return {
						id: $.uid(),
						title: keyword
					};
				},
				add: function(data)
				{
					var existingTag = 
						tagList
							.find('input[name="tags[]"]')
							.filter(function()
							{
								return $(this).val()==data.title;
							});

					if (existingTag.length > 0)
					{
						existingTag.parent().hide().prependTo(tagList).fadeIn();
					} else {
						tagTemplate
							.tmpl(data)
							.hide()
							.prependTo('.tag-list')
							.fadeIn();

						tagInput.stackSuggest('exclude', data['$dataId']);
					}
					
					noTagAvailable();
				}
			});

		$('.tag-list .remove-tag').live('click', function()
		{
			var tag = $(this).parent();
			tagInput.stackSuggest('include', tag.attr('dataid'));
			tag.remove();
			noTagAvailable();
		});
	});
})(sQuery);
</script>
<div class="write-posttags" style="padding: 5px;">
    <?php if($this->acl->rules->create_tag): ?>
    <div class="write-taginput clearfix mbm">
        <div class="mbs"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_TAG_INSTRUCTIONS'); ?></div>

        <input type="text" class="tag-input" value="" name="rawtags" />
        <script type="text/x-jquery-tmpl" class="stackSuggest">
	    	<div class="stackSuggest tag-suggestion">
	    		<ul class="stackSuggestList">
	    			{{if dataset.length>0}}<div class="common-tags-label"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_BLOG_FREQUENTLY_USED_TAGS' );?></div>{{/if}}
	        		{{each(i,data) dataset}}<li class="stackSuggestItem" dataid=${data.$dataId}>${data.title}</li>{{/each}}
	    			<li class="stackSuggestItem custom"><ul>{{each(i,title) custom}}<li>${title}</li>{{/each}}</ul></li>
	    		</ul>
	    	</div>
        </script>
    </div>
    <?php endif; ?>


	<div id="tag-list-container" class="write-taglist">
	    <ul class="tag-list ul-reset flist">
	    	<script type="text/x-jquery-tmpl" class="tag-list-item">
	    		<li class="tag-item" dataid="${$dataId}">
	    			<a class="remove-tag">X</a>
	    			<span class="tag-title">${title}</span>
	    			<input type="hidden" name="tags[]" value="${title}" />
	    		</li>
	    	</script>
	    	<li class="no-tag"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_NO_TAGS_AVAILABLE'); ?></li>
	    </ul> 
	</div>
</div>