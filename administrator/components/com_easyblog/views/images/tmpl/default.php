<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined('_JEXEC') or die('Restricted access');
?>
<style type="text/css">
div.image-summary{width: auto !important;display: block !important;}
</style>
<div id="eblog-wrapper">
<script type="text/javascript">
(function($){
$(function()
{
    var imageItemTemplate       = $('#imageItemTemplate'),
        imageItemUploadTemplate = $('#imageItemUploadTemplate'),
        imageEditorTemplate     = $('#imageEditorTemplate');

    var imageManager   = $('.imageManager'),
        imageSearch    = imageManager.find('.image-search'),
        imageItemGroup = imageManager.find('.image-item-group');

    var imageUploader;

    var parentFrame =  $(window.parent.document).find('.dialog-middle-content');
    if (parentFrame.length < 1)
        parentFrame = $(window);

    imageManager
        .css('width', parentFrame.width() - (imageManager.outerWidth() - imageManager.width()))
        .css('height', parentFrame.height() - (imageManager.outerHeight() - imageManager.height()));

    function initialize()
    {
        // Create a list of available images
        var imageDataset = <?php echo $this->json->encode($this->images); ?>;
        $.each(imageDataset, function(i, imageData)
        {
            var imageItem = createImageItem(imageData);
            imageItem
                .prependTo(imageItemGroup);
        })

        noImageItem();

        // Set up image search
        imageSearch
            .keyup(function()
            {
                var noImageFound = imageItemGroup.find('.no-image-found').hide();

                var keyword = $.trim($(this).val());
                var results =
                    $('.image-item:not(.image-upload-item)')
                        .hide()
                        .filter(function()
                        {
                            return $(this).tmplItem().data.name.toUpperCase().indexOf(keyword.toUpperCase()) >= 0
                        })
                        .show();

                if (results.length < 1)
                {
                    noImageFound.show();
                }
            });

        // Don't set up uploader if upload button not found
        if ($('#image-upload-button').length < 1)
            return;

        // Set up image uploader
        imageUploader = window.imageUploader = new plupload.Uploader({

            browse_button : 'image-upload-button',
            container: 'image-toolbar',

            url: '<?php echo JURI::base(); ?>index.php?option=com_easyblog&c=images&task=ajaxupload&tmpl=component&no_html=1&format=json&<?php echo $this->session->getName().'='.$this->session->getId(); ?>&<?php echo JUtility::getToken();?>=1&bloggger_id=<?php echo $this->blogger_id; ?>',
			<?php if( $this->config->get( 'main_upload_client') == 'flash' ){ ?>
            runtimes           : 'flash,html4',
            <?php } else { ?>
            runtimes           : 'html4,flash',
            <?php } ?>
            flash_swf_url      : '<?php echo rtrim( JURI::root() , '/' ); ?>/components/com_easyblog/assets/vendors/plupload/plupload.flash.swf',
            max_file_size		: '5mb',
            max_file_count: 20,
            unique_names  : true,
            filters       : [{title: "Image files", extensions: "jpg,gif,png"}]

            <?php if( $this->config->get( 'main_resize_image_on_upload') ){ ?>
            ,resize: {
                width  : <?php echo $this->config->get( 'main_maximum_image_width' ); ?>,
                height : <?php echo $this->config->get( 'main_maximum_image_height' ); ?>,
                quality: <?php echo $this->config->get( 'main_upload_quality' ); ?>
            }
            <?php } ?>

        });

        imageUploader.init();

        imageUploader.bind('FilesAdded', function(up, files) {

            files = files.reverse();

            imageItemUploadTemplate
                .tmpl(files)
                .prependTo(imageItemGroup);

            imageUploader.start();
        });

        imageUploader.bind('UploadProgress', function(up, file) {
            var imageItemUpload = $('#upload-'+file.id);
            imageItemUpload
                .find('.image-progress')
                .addClass('ico-progress')
                .html(file.percent + "%")
                .show();
        });

        function showError(up, err)
        {
            var imageItemUpload = $('#upload-'+err.file.id);
            imageItemUpload
                .find('.image-progress')
                .removeClass('ico-progress')
                .html(err.details + ' - ' + err.message)
                .show();
        }

        imageUploader.bind('Error', showError);

        imageUploader.bind('FileUploaded', function(up, file, data) {
            var imageItemUpload = $('#upload-'+file.id);
            var imageData;
            try {
                imageData = eval('('+data.response+')').image;
            } catch(e) {
                showError('', {
                    file: file,
                    details: 'Error 204',
                    message: 'Invalid response'
                });
                return;
            }

            var imageItem = createImageItem(imageData);
            imageItemUpload
                .replaceWith(imageItem);

            $(window).focus();

            noImageItem();
        });
    }

    function createImageItem(imageData)
    {
        var imageItem =
            imageItemTemplate
                .tmpl(imageData);

        var imageSummary = imageItem.find('.image-summary'),
            imageProgress = imageSummary.find('.image-progress'),
            imageDeleteButton = imageSummary.find('.image-delete-button'),
            imageQuickInsertButton = imageSummary.find('.image-quickinsert-button');

        imageSummary
            .click(function()
            {
                var imageEditor = imageItem.find('.image-editor');

                if (!imageItem.is('.active'))
                {
                    imageItem.addClass('active');

                    imageEditor =
                        createImageEditor(imageData)
                            .appendTo(imageItem);

                    imageEditor
                        .onShow()
                        .hide(0, function()
                        {
                            imageEditor.slideDown();
                        });

                } else {

                    imageEditor
                        .slideUp(function()
                        {
                            imageItem.removeClass('active');
                            imageEditor.remove();
                        });
                }
            })
            .mouseover(function(event)
            {
                imageItem.addClass('showButton');

                if (event.shiftKey==true)
                {
                    imageQuickInsertButton.hide();
                    imageDeleteButton.show();
                } else {
                    imageQuickInsertButton.show();
                    imageDeleteButton.hide();
                }

                $(window)
                    .bind('keydown.imageItem keyup.imageItem', function(event)
                    {
                        if (imageItem.hasClass('showButton'))
                        {
                            if (event.keyCode==16)
                            {
                                imageQuickInsertButton.hide();
                                imageDeleteButton.show();
                            } else {
                                imageQuickInsertButton.show();
                                imageDeleteButton.hide();
                            }
                        } else {
                            imageQuickInsertButton.hide();
                            imageDeleteButton.hide();
                        }
                    })
                    .bind('keyup.imageItem', function(event)
                    {
                        if (imageItem.hasClass('showButton'))
                        {
                            imageQuickInsertButton.show();
                            imageDeleteButton.hide();
                        } else {
                            imageQuickInsertButton.hide();
                            imageDeleteButton.hide();
                        }
                    });
            })
            .mouseout(function(event)
            {
                imageItem.removeClass('showButton');

                imageQuickInsertButton.hide();
                imageDeleteButton.hide();
            });


        imageQuickInsertButton
            .click(function(event)
            {
                if (event.shiftKey==true)
                {
                    imageSummary.trigger('mouseover');
                    imageDeleteButton.trigger('click');
                    return;
                }

                event.stopPropagation();

                insertImageIntoEditor({
                    src   : '<?php echo $this->baseURL . '/'; ?>' + imageData.path_relative,
                    title : imageData.name,
                    width : imageData.width,
                    height: imageData.height
                });
            })
            .mouseover(function(event)
            {
                imageSummary.trigger('mouseover');
            })
            .hide();


        imageDeleteButton
            .click(function(event)
            {
                event.stopPropagation();

                imageItem.addClass('inactive');
                imageSummary.unbind();
                imageQuickInsertButton.hide();
                imageDeleteButton.hide()

                imageProgress.addClass('ico-progress').html('Deleting image...');

                ejax.call('images', 'deleteImage', [imageData.name, '<?php echo $this->blogger_id; ?>'], {
                    success: function()
                    {
                        imageItem.remove();
                        noImageItem();
                    },
                    error: function()
                    {
                        imageProgress.removeClass('ico-progress').html('Error deleting image.');
                    }
                });
            })
            .hide();

        return imageItem;
    }

    function createImageEditor(imageData)
    {
        var imageEditor =
            imageEditorTemplate
                .tmpl(imageData);

        var imageUrl         = imageEditor.find('#image-url'),
            imageTitle       = imageEditor.find('#image-title'),
            imageDescription = imageEditor.find('#image-description'),
            imageWidth       = imageEditor.find('#image-width'),
            imageHeight      = imageEditor.find('#image-height'),
            imageAspectRatio = imageEditor.find('#image-aspect-ratio'),
            imageAlign       = imageEditor.find('#image-align'),
            imageFeatured    = imageEditor.find('#image-featured'),
            imageEditorViewButton   = imageEditor.find('.image-view-button'),
            imageEditorInsertButton = imageEditor.find('.image-insert-button'),
            imageEditorCancelButton = imageEditor.find('.image-cancel-button'),
            imageEditorDeleteButton = imageEditor.find('.image-delete-button');

        imageEditor.onShow = function()
        {
            imageUrl
                .stretchToFit()
                .click(function()
                {
                    imageUrl.select();
                });

            imageTitle
                .stretchToFit();

            imageDescription
                .stretchToFit();

            return imageEditor;

        };

        imageEditorViewButton
            .click(function()
            {
                var imageEditorViewImage = window.open(imageUrl.val(), 'imageEditorViewImage', "location=0,menubar=0,scrollbars=1,width=640,height=480");
            });

        imageEditorInsertButton
            .click(function()
            {
                insertImageIntoEditor({
                    'src'   : imageUrl.val(),
                    'title' : imageTitle.val(),
                    'alt'   : imageDescription.val(),
                    'width' : imageWidth.val(),
                    'height': imageHeight.val(),
                    'align' : imageAlign.val(),
                    'class' : (imageFeatured.checked()) ? 'featured' : ''
                });
            });

        imageEditorCancelButton
            .click(function()
            {
                imageEditorCancelButton.closest('.image-item').find('.image-summary').click();
            });

        imageEditorDeleteButton
            .click(function()
            {
                var imageItem = imageEditorDeleteButton.closest('.image-item');

                imageItem
                    .removeClass('active')
                    .find('.image-summary .image-delete-button')
                    .trigger('click');

                imageEditor.hide();
            });

        imageWidth
            .numeric()
            .change(function()
            {
                var value = $.trim(imageWidth.val());

                if (value=='')
                {
                    imageWidth.val(imageData.width);
                    imageHeight.val(imageData.height);
                    return;
                }
            })
            .keyup(function(event)
            {
                if (!imageAspectRatio.checked())
                    return;

                if (event.keyCode == 9) return;

                var width = $.trim(imageWidth.val());
                var ratio = imageData.width / width;
                var height = imageData.height / ratio;
                imageHeight.val(Math.floor(height));
            });

        imageHeight
            .numeric()
            .change(function()
            {
                var value = $.trim(imageHeight.val());

                if (value=='')
                {
                    imageWidth.val(imageData.width);
                    imageHeight.val(imageData.height);
                    return;
                }
            })
            .keyup(function(event)
            {
                if (!imageAspectRatio.checked())
                    return;

                if (event.keyCode == 9) return;

                var height = parseInt(imageHeight.val());
                var ratio = imageData.height / height;
                var width = imageData.width / ratio;
                imageWidth.val(Math.floor(width));
            });

        imageAspectRatio.checked(function()
        {
            imageWidth.keyup();
        });

        return imageEditor;
    }

    function insertImageIntoEditor(properties)
    {
        var image = $('<div>').append($(new Image()).attr(properties)).html();
        window.parent.jInsertEditorText(image, '<?php echo JRequest::getVar('e_name'); ?>');
        window.parent.ejax.closedlg();
    }

    function noImageItem()
    {
        var noImageItem = imageItemGroup.find('.no-image-item').hide();
        if (imageItemGroup.find('.image-item').length < 1)
        {
            noImageItem.show();
        }
    }

    initialize();
    imageSearch.focus();
});
})(sQuery);

</script>
<div id="ezblog-dashboard" class="imageManager pam">
    <div id="image-toolbar" class="pbs clearfix">
        <?php if ($this->acl->rules->upload_image): ?>
            <button id="image-upload-button" type="button" class="ui-button fright"><span class="ico btn-add"><?php echo JText::_( 'COM_EASYBLOG_UPLOAD_BUTTON' ); ?></span></button>
        <?php endif; ?>
        <input type="text" name="image-search" class="image-search fleft" style="width: 150px;" value="" />
    </div>

    <ul class="image-item-group">
        <li class="no-image-item"><?php echo JText::_('COM_EASYBLOG_IMAGE_UPLOADER_YOU_DO_NOT_HAVE_ANY_IMAGES_UPLOADED'); ?></li>
        <li class="no-image-found"><?php echo JText::_( 'COM_EASYBLOG_IMAGE_UPLOADER_SEARCH_NO_RESULTS');?></li>
    </ul>
</div>

<script type="text/x-jquery-tmpl" id="imageItemTemplate">
<li class="image-item">
    <div class="image-summary pas clearfix" style="display: inline-block;width:100%;">
        <div class="image-thumbnail">
            <img src="<?php echo $this->baseURL . '/'; ?>${path_relative}" alt="${name} - ${size}" />
        </div>

        <div class="image-name mls">${name} <span>(${plupload.formatSize(size)})</span></div>

        <span class="image-progress fright"></span>

        <button type="button" class="ui-button fright image-quickinsert-button"><?php echo JText::_('COM_EASYBLOG_INSERT_BUTTON') ?></button>
        <button type="button" class="ui-button fright image-delete-button"><?php echo JText::_('COM_EASYBLOG_DELETE_BUTTON') ?></button>
    </div>
</li>
</script>

<script type="text/x-jquery-tmpl" id="imageItemUploadTemplate">
<li id="upload-${id}"class="image-item image-upload-item">
    <div class="image-summary pas">
        <div class="image-name">${name} <span>(${plupload.formatSize(size)})</span></div>
        <div class="image-progress fright ico-progress">${percent}%</div>
    </div>
</li>
</script>

<script type="text/x-jquery-tmpl" id="imageEditorTemplate">
<div class="image-editor">
    <div class="image-preview">
        <img src="<?php echo $this->baseURL . '/'; ?>${path_relative}" alt="${name} - ${size}" />
        <button type="button" class="ui-button image-view-button"><?php echo JText::_( 'COM_EASYBLOG_IMAGE_MANAGER_VIEW_IMAGE');?></button>
    </div>

    <div class="image-properties">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tablerest">
            <tr>
                <td><label for="image-url"><?php echo JText::_( 'COM_EASYBLOG_IMAGE_MANAGER_URL' );?></label></td>
                <td width="100%;"><input type="text" id="image-url" value="<?php echo $this->baseURL . '/'; ?>${path_relative}" readonly="readonly"/></td>
            </tr>
            <tr>
                <td><label for="image-title"><?php echo JText::_('COM_EASYBLOG_IMAGE_MANAGER_TITLE') ?></label></td>
                <td><input type="text" id="image-title" value="${name}" /></td>
            </tr>
            <tr>
                <td><label for="image-description"><?php echo JText::_('COM_EASYBLOG_IMAGE_MANAGER_DESCRIPTION') ?></label></td>
                <td><input type="text" id="image-description" value="" /></td>
            </tr>
            <tr>
                <td><label for="image-size"><?php echo JText::_('COM_EASYBLOG_IMAGE_MANAGER_DIMENSIONS') ?></label></td>
                <td>
                    <span class="image-size fleft">
                        <label for="image-width"><?php echo JText::_('COM_EASYBLOG_IMAGE_MANAGER_WIDTH') ?></label>
                        <input type="text" id="image-width" value="${width}" />
                    </span>
                    <span class="image-size fleft">
                        <label for="image-height"><?php echo JText::_('COM_EASYBLOG_IMAGE_MANAGER_HEIGHT') ?></label>
                        <input type="text" id="image-height" value="${height}" />
                        <input type="checkbox" id="image-aspect-ratio" checked="checked" /> <?php echo JText::_( 'COM_EASYBLOG_IMAGE_MANAGER_LOCK_RATIO' );?>
                    </span>
                </td>
            </tr>
            <tr>
                <td><label for="image-align"><?php echo JText::_('COM_EASYBLOG_IMAGE_MANAGER_ALIGNMENT') ?></label></td>
                <td>
                    <select size="1" id="image-align">
                        <option value="" selected="selected"><?php echo JText::_('COM_EASYBLOG_IMAGE_MANAGER_NOT_SET_OPTION') ?></option>
                        <option value="left"><?php echo JText::_('COM_EASYBLOG_IMAGE_MANAGER_LEFT_OPTION') ?></option>
                        <option value="right"><?php echo JText::_('COM_EASYBLOG_IMAGE_MANAGER_RIGHT_OPTION') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                <input type="checkbox" id="image-featured" />
                <label for="image-featured"><?php echo JText::_('COM_EASYBLOG_IMAGE_MANAGER_USE_AS_FEATURED') ?></label>
                </td>
            </tr>
        </table>
    </div>

    <div class="ui-inputbutton clearfix">
        <?php if ($this->acl->rules->upload_image): ?>
        <button type="button" class="ui-button fleft image-delete-button"><?php echo JText::_('COM_EASYBLOG_DELETE_BUTTON') ?></button>
        <?php endif; ?>
        <button type="button" class="ui-button fright image-insert-button"><?php echo JText::_('COM_EASYBLOG_INSERT_BUTTON') ?></button>
        <button type="button" class="ui-button fright image-cancel-button"><?php echo JText::_('COM_EASYBLOG_CANCEL_BUTTON') ?></button>
    </div>
</div>
</script>
</div>