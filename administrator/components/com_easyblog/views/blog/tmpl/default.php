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

$blog 		= $this->blog;
$draft		= $this->draft;
$editor		= $this->editor;
$acl		= $this->acl;
$author		= $this->author;

$blogId = JRequest::getInt('blogid', '');

$isPrivate = $this->isPrivate;
$allowComment = $this->allowComment;
$subscription = $this->subscription;
$frontpage = $this->frontpage;
$trackbacks = $this->trackbacks;

jimport( 'joomla.utilities.date' );
?>
<script type="text/javascript">
<?php if(EasyBlogHelper::getJoomlaVersion() >= 1.6) : ?>
	Joomla.submitbutton = function( action ) {
	
	    if( action == 'rejectBlog')
	    {
			var draft_id    = sQuery('#draft_id').val();
	        admin.blog.reject( draft_id );
			return false;
	    }
	    else
	    {
            eblog.editor.toggleSave();
		}
		Joomla.submitform( action );
    }
<?php else : ?>
function submitbutton( action )
{
    if( action == 'rejectBlog')
    {
		var draft_id    = sQuery('#draft_id').val();
        admin.blog.reject( draft_id );
		return false;
    }
    else
    {
		eblog.editor.toggleSave();
	}

	submitform( action );
}
<?php endif; ?>

function insertMember( id , name )
{
	ej('#authorId').val(id);
	ej('#authorName').val(name);
	
	<?php
	if($this->joomlaversion >= '1.6')
	{
	?>
		window.parent.SqueezeBox.close();
	<?php
	}
	else
	{
	?>
		window.parent.document.getElementById('sbox-window').close();
	<?php
	}
	?>
}

sQuery(document).ready( function(){
	sQuery('#title').bind('change', function() {
		eblog.editor.permalink.generate();
	});
	
	// Editor initialization so we can use their methods.
	eblog.editor.getContent = function(){
		return <?php echo JFactory::getEditor( $this->config->get( 'layout_editor' ) )->getContent( 'write_content' ); ?>
	}

	eblog.editor.setContent = function( value ){
		return <?php echo JFactory::getEditor( $this->config->get( 'layout_editor' ) )->setContent( 'write_content' , 'value' ); ?>
	}

	eblog.editor.getIntro = function(){
		return <?php echo JFactory::getEditor( $this->config->get( 'layout_editor' ) )->getContent( 'intro' ); ?>
	}

	eblog.editor.setIntro = function( value ){
		return <?php echo JFactory::getEditor( $this->config->get( 'layout_editor' ) )->setContent( 'intro', 'value' ); ?>
	}

	eblog.editor.toggleSave = function(){
		<?php echo JFactory::getEditor( $this->config->get( 'layout_editor' ) )->save( 'write_content' ); ?>
		<?php echo JFactory::getEditor( $this->config->get( 'layout_editor' ) )->save( 'intro' ); ?>
	}
});

</script>
<div id="eblog-wrapper">
<form name="adminForm" id="blogForm" method="post" action="index.php">
	<table class="noshow" cellpadding="5" cellspacing="5">
		<tr>
			<td valign="top" width="60%">
				<table class="admintable">
					<tr>
						<td>
			    			<div class="clearfix"><label for="title" class="label" style="float: none;"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_TITLE'); ?></label></div>
			    			<input type="text" name="title" id="title" value="<?php echo $blog->title; ?>" class="inputbox write-title full-width" />						
						</td>
					</tr>
					<tr>
						<td>
							<div class="clearfix">
				    			<label for="slug" class="label label-title" style="float: none;"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_PERMALINK'); ?></label>
				    			<input type="text" name="permalink" id="permalink" value="<?php echo $blog->permalink;?>" class="inputbox write-title full-width" />
				  			</div>
				  		</td>
				  	</tr>
				  	<tr>
				  		<td>
							<div>
			    				<small><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_PERMALINK_DESC'); ?></small>
			    			</div>							
						</td>
					</tr>
					<tr>
						<td>
			    			<div class="clearfix"><label for="excerpt" class="label" style="float: none;"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_INTROTEXT'); ?></label></div>
							<div id="editor-intro" class="clearfix mbs">
								<div class="clearfix ui-medialink">
									<span class="fleft mrm mts"><?php echo JText::_( 'COM_EASYBLOG_DASHBOARD_WRITE_INSERT' );?></span>
									<a href="javascript:void(0);" onclick="eblog.dashboard.toggle(this);" class="ir fleft ico-dglobe mrs"><?php echo JText::_('COM_EASYBLOG_DASHBOARD_EDITOR_INSERT_LINK_ADD_TO_CONTENT'); ?></a>
							        <?php
							            $this->editorName = 'intro';
										echo $this->loadTemplate( 'images' );
									?>
									<a href="javascript: void(0);" onclick="eblog.dashboard.videos.showForm('intro');" title="<?php echo JText::_( 'COM_EASYBLOG_DASHBOARD_WRITE_INSERT_VIDEO' );?>" class="ir fleft ico-dvideo"><?php echo JText::_( 'COM_EASYBLOG_DASHBOARD_WRITE_INSERT_VIDEO' );?></a>
								</div>
								<div style="display:none;" class="mts">
									<div id="" class="pas search-field" style="background:#f5f5f5;border:1px solid #ccc">
							        	<span class="fleft mtm"><?php echo JText::_('COM_EASYBLOG_DASHBOARD_WRITE_SEARCH_PREVIOUS_POST'); ?></span>
							            <div class="tablecell pas mrl">
							                <input type="text" id="search-content-intro" class="half" />
							                <input type="button" onclick="eblog.editor.search.load('intro');return false;" value="<?php echo JText::_('COM_EASYBLOG_SEARCH'); ?>" class="ui-button mls" />
							            </div>
									</div>
									<div class="search-results-content"></div>
								</div>
							</div>
							<?php echo $editor->display( 'intro', $blog->intro, '100%', '120', '10', '10' , array('readmore','pagebreak') ); ?>	
							<div><small><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_INTROTEXT_DESC'); ?></small></div>
						</td>
					</tr>
					<tr>
						<td>
			    			<div class="clearfix">
								<label for="content" class="label label-title" style="float: none;"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_CONTENT'); ?></label>
							</div>
							
							<div id="editor-write_content" class="clearfix mbs">
								<div class="clearfix ui-medialink">
									<span class="fleft mrm mts"><?php echo JText::_( 'COM_EASYBLOG_DASHBOARD_WRITE_INSERT' );?></span>
									<a href="javascript:void(0);" onclick="eblog.dashboard.toggle(this);" class="ir fleft ico-dglobe mrs"><?php echo JText::_('COM_EASYBLOG_DASHBOARD_EDITOR_INSERT_LINK_ADD_TO_CONTENT'); ?></a>
							        <?php
							            $this->editorName = 'write_content';
										echo $this->loadTemplate( 'images' );
									?>
									<a class="ir fleft ico-dvideo" href="javascript: void(0);" onclick="eblog.dashboard.videos.showForm('write_content');" title="<?php echo JText::_( 'COM_EASYBLOG_DASHBOARD_WRITE_INSERT_VIDEO' );?>"><?php echo JText::_( 'COM_EASYBLOG_DASHBOARD_WRITE_INSERT_VIDEO' );?></a>
								</div>
								<div style="display:none;" class="mts">
									<div id="" class="pas search-field" style="background:#f5f5f5;border:1px solid #ccc">
							        	<span class="fleft mtm"><?php echo JText::_('COM_EASYBLOG_DASHBOARD_WRITE_SEARCH_PREVIOUS_POST'); ?></span>
							            <div class="tablecell pas mrl">
							                <input type="text" id="search-content-write_content" class="half" />
							                <input type="button" onclick="eblog.editor.search.load('write_content');return false;" value="<?php echo JText::_('COM_EASYBLOG_SEARCH'); ?>" class="ui-button mls" />
							            </div>
									</div>
									<div class="search-results-content"></div>
								</div>
							</div>
							
							<div id="wysiwyg" class="clearfix">
			    				<?php echo $editor->display( 'write_content', $blog->content, '100%', '550', '10', '10' , array('pagebreak','readmore') ); ?>
			    			</div>
						</td>
					</tr>
				</table>
			</td>
			<td valign="top" width="38%">
			<?php
				$pane	= JPane::getInstance('sliders', array('allowAllClose' => true));

				echo $pane->startPane("content-pane");
				echo $pane->startPanel( JText::_( 'COM_EASYBLOG_BLOGS_BLOG_PUBLISHING_OPTIONS' ) , "detail-page" );
				echo $this->loadTemplate( 'publishing' );
				echo $pane->endPanel();
				echo $pane->startPanel( JText::_( 'COM_EASYBLOG_BLOGS_BLOG_METADATA' ), "params-page" );
				echo $this->loadTemplate( 'metadata' );
				echo $pane->endPanel();
				echo $pane->startPanel( JText::_( 'COM_EASYBLOG_BLOGS_BLOG_TRACKBACKS' ), "metadata-page" );
				echo $this->loadTemplate( 'trackbacks' );
				echo $pane->endPanel();
				echo $pane->startPanel( JText::_( 'COM_EASYBLOG_BLOGS_BLOG_TAGS' ), "metadata-page" );
				echo $this->loadTemplate( 'tagging' );
				echo $pane->endPanel();
				echo $pane->endPane();
			?>
			</td>
		</tr>
	</table>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="ispending" value="<?php echo $acl->rules->publish_entry ? '0' : '1'; ?>" />
<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="c" value="blogs" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="metaid" value="<?php echo $this->meta->id; ?>" />
<input type="hidden" name="blogid" value="<?php echo $blog->id;?>" />
<input type="hidden" name="draft_id" id="draft_id" value="<?php echo $draft->id;?>" />
<input type="hidden" name="under_approval" value="<?php echo $this->pending_approval; ?>" />
</form>
</div>