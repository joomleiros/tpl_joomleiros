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
?>
<table class="paramlist admintable" cellspacing="0" cellpadding="5">
	<tr>
		<td class="paramlist_key" width="10%"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_AUTHOR' ); ?></td>
		<td class="paramlist_value">
			<input type="hidden" name="authorId" id="authorId" value="<?php echo empty($this->author)? $user->id : $this->author->id;?>" />
  			<input type="text" readonly="readonly" name="authorName" id="authorName" value="<?php echo empty($this->author)? $user->getName() : $this->author->getName();?>" class="inputbox" /> 
			[ <a class="modal" rel="{handler: 'iframe', size: {x: 650, y: 375}}" href="index.php?option=com_easyblog&view=users&tmpl=component&browse=1"><?php echo JText::_('COM_EASYBLOG_BROWSE_USERS');?></a> ]</div>
		</td>
	</tr>
	<tr>
		<td class="paramlist_key">
			<label for="category_id" class="label label-title"><?php echo JText::_('COM_EASYBLOG_BLOGS_SELECT_CATEGORY'); ?></label>
		</td>
		<td class="paramlist_value">
			<?php echo $this->nestedCategories; ?>
		</td>
	</tr>
	<tr>
		<td class="paramlist_key">
			<label for="published" class="label label-title"><?php echo JText::_('COM_EASYBLOG_BLOGS_PUBLISHING_STATUS'); ?></label>
		</td>
		<td class="paramlist_value">
			<select name="published" id="published" class="inputbox" style="width: 150px;">
				<option value="1" <?php if($this->blog->published == "1") echo "selected='selected'"; ?>><?php echo JText::_('COM_EASYBLOG_PUBLISHED');?></option>
				<option value="0" <?php if($this->blog->published == "0") echo "selected='selected'"; ?>><?php echo JText::_('COM_EASYBLOG_UNPUBLISHED');?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="paramlist_key">
			<label for="blog_contribute" class="label label-title"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_CONTRIBUTION');?></label>
		</td>
		<td class="paramlist_value">
			<input type="radio" name="blog_contribute" value="0" <?php echo ($this->isSiteWide) ? 'checked' : ''; ?> /> <?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_CONTRIBUTION_SITE'); ?>
			<?php foreach( $this->teamBlogJoined as $team ) {	?>
				<input type="radio" name="blog_contribute" value="<?php echo $team->team_id;?>" <?php echo ($team->selected) ? 'checked' : ''; ?> /><?php echo $team->title;?>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td class="paramlist_key">
			<label for="created" class="label label-title"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_CREATION_DATE'); ?></label>
		</td>
		<td class="paramlist_value">
			<?php
			$now 		=  EasyBlogDateHelper::toFormat( EasyBlogDateHelper::getDate() );
			$displaynow =  EasyBlogDateHelper::toFormat( EasyBlogDateHelper::getDate(), $this->config->get( 'layout_dateformat' ) );
			
			if($this->blog->created != "")
			{
			    $newDate    = EasyBlogDateHelper::getDate($this->blog->created);
				$now 		= EasyBlogDateHelper::toFormat($newDate);
				$displaynow =  EasyBlogDateHelper::toFormat( $newDate, $this->config->get( 'layout_dateformat' ) );
			}

			echo EasyBlogHelper::dateTimePicker('created', $displaynow, $now);
			?> 
		</td>
	</tr>
	<tr>
		<td class="paramlist_key">
			<label for="publish_up" class="label label-title label-block"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_PUBLISHING_DATE'); ?></label>
		</td>
		<td class="paramlist_value">
			<?php
			if($this->blog->publish_up != "")
			{
			    $newDate    = EasyBlogDateHelper::getDate($this->blog->publish_up);
				$now 		= EasyBlogDateHelper::toFormat($newDate);
				$displaynow =  EasyBlogDateHelper::toFormat( $newDate, $this->config->get( 'layout_dateformat' ) );
			}
			else {
			    $newDate    	= EasyBlogDateHelper::getDate();
				$now 			= EasyBlogDateHelper::toFormat( $newDate );
				$displaynow 	= EasyBlogDateHelper::toFormat( $newDate, $this->config->get( 'layout_dateformat' ) );
			}
			
			echo EasyBlogHelper::dateTimePicker('publish_up', $this->blog->publish_up != '' ? $displaynow : JText::_('COM_EASYBLOG_IMMEDIATELY'), $now);
			?> 								
		</td>							
	</tr>
	<tr>
		<td class="paramlist_key">
			<label for="publish_down" class="label label-title label-block"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_UNPUBLISHING_DATE'); ?></label>
		</td>
		<td class="paramlist_value">
			<?php
			$notEmpty = true;
			if ( $this->blog->publish_down == "0000-00-00 00:00:00" || empty($this->blogId))
			{
			    $newDate    	= EasyBlogDateHelper::getDate($this->blog->publish_down);
				$now			= '';
				$displaynow 	= '';
				$nowReset       = EasyBlogDateHelper::toFormat( $newDate );
				$notEmpty 		= false;
			}
			else {
				$newDate    = EasyBlogDateHelper::getDate($this->blog->publish_down);
				$now 		= EasyBlogDateHelper::toFormat($newDate);
				$nowReset   = EasyBlogDateHelper::toFormat( $newDate );
				$displaynow = EasyBlogDateHelper::toFormat( $newDate, $this->config->get( 'layout_dateformat' ) );
				$notEmpty 	= true;
			}
			echo EasyBlogHelper::dateTimePicker('publish_down', $notEmpty ? $now : JText::_('COM_EASYBLOG_NEVER'), $notEmpty ? $now : '', true);
			?> 
			<input type="hidden" name="publish_down_reset" id="publish_down_reset" value="<?php echo $nowReset; ?>"/>
			<input type="hidden" name="publish_down_ori" id="publish_down_ori" value="<?php echo $this->blog->publish_down; ?>"/>
		</td>
	</tr>
	<tr>
		<td class="paramlist_key">
			<label for="private" class="label label-title"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_PERMISSIONS'); ?></label>
		</td>
		<td class="paramlist_value">
			<?php
				$nameFormat = array();
			$nameFormat[] = JHTML::_('select.option', '0', JText::_( 'COM_EASYBLOG_PUBLIC' ) );
			
			if(!empty($this->acl->rules->enable_privacy))
			{
				$nameFormat[] = JHTML::_('select.option', '1', JText::_( 'COM_EASYBLOG_PRIVATE' ) );
			}
			
			$showdet = JHTML::_('select.genericlist', $nameFormat, 'private', 'size="1" class="inputbox" style="width: 150px;"', 'value', 'text', $this->isPrivate );
			echo $showdet;
			?>
		</td>
	</tr>
	<tr>
		<td class="paramlist_key">
			<div><label for="allowcomment" class="label label-title"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_ENABLE_COMMENT'); ?></label></div>
		</td>
		<td class="paramlist_value">
			<?php echo JHTML::_('select.booleanlist', 'allowcomment', 'class="inputbox"', $this->allowComment ); ?>
		</td>
	</tr>
	<tr>
		<td class="paramlist_key">
			<label for="subscription" class="label label-title"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_ENABLE_BLOG_SUBSCRIPTION'); ?></label>
		</td>
		<td class="paramlist_value">
			<?php echo JHTML::_('select.booleanlist', 'subscription', 'class="inputbox"', $this->subscription ); ?>
		</td>
	</tr>
	<tr>
		<td class="paramlist_key">
			<label for="frontpage" class="label label-title"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_SHOW_ON_FRONTPAGE'); ?></label>
		</td>
		<td class="paramlist_value">
			<?php echo JHTML::_('select.booleanlist', 'frontpage', 'class="inputbox"', $this->frontpage	 ); ?>
		</td>
	</tr>
	
	<?php if($this->config->get('main_password_protect') && !$this->blog->isFeatured() ){ ?>
	<tr>
		<td class="paramlist_key" width="120">
			<label for="blogpassword" class="label label-title"><?php echo JText::_('COM_EASYBLOG_BLOGS_BLOG_PUBLISHING_PROTECTION'); ?></label>
		</td>
		<td>
            <div class="paramlist_value">
                <input type="text" name="blogpassword" id="blogpassword" value="<?php echo $this->blog->blogpassword;?>" class="inputbox" />
                <div><?php echo JText::_( 'COM_EASYBLOG_BLOGS_BLOG_PUBLISHING_PROTECTION_INSTRUCTIONS' );?></div>
            </div>
		</td>
	</tr>
	<?php } ?>
	
	
	<?php if( 
			$this->acl->rules->update_facebook && $this->config->get( 'integrations_facebook' ) && EasyBlogHelper::getHelper( 'SocialShare' )->isAssociated( $this->author->id , 'FACEBOOK' ) ||
			$this->acl->rules->update_twitter && $this->config->get( 'integrations_twitter' ) && EasyBlogHelper::getHelper( 'SocialShare' )->isAssociated( $this->author->id , 'TWITTER' ) ||
			$this->acl->rules->update_linkedin && $this->config->get( 'integrations_linkedin' ) && EasyBlogHelper::getHelper( 'SocialShare' )->isAssociated( $this->author->id , 'LINKEDIN' ) ){
	?>
	<tr>
		<td class="paramlist_key">
			<label for="socialshare" class="label label-title"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_ALSO_PUBLISH_TO' );?></label>
		</td>
		<td class="paramlist_value">
			<span class="ui-highlighter publish-to fleft">
			    <?php if( $this->acl->rules->update_facebook && $this->config->get( 'integrations_facebook' ) && EasyBlogHelper::getHelper( 'SocialShare' )->isAssociated( $this->author->id , 'FACEBOOK' ) ){?>
			    <span class="ui-span" style="display: inline-block;">
					<input style="vertical-align: middle;" type="checkbox" name="socialshare[]" value="facebook" id="socialshare-facebook"<?php echo EasyBlogHelper::getHelper( 'SocialShare' )->hasAutoPost( $this->author->id , 'FACEBOOK' ) ? ' checked="checked"' : '';?> onclick="eblog.dashboard.socialshare.setActive( this );" />
					<label style="display: inline-block;vertical-align: middle;" for="socialshare-facebook" title="<?php echo JText::_( 'COM_EASYBLOG_BLOGS_SOCIALSHARE_FACEBOOK' ); ?>"><span class="ir ico-fb"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_SOCIALSHARE_FACEBOOK' ); ?></span></label>
			    </span>
			    <?php } ?>
			    <?php if( $this->acl->rules->update_twitter && $this->config->get( 'integrations_twitter' ) && EasyBlogHelper::getHelper( 'SocialShare' )->isAssociated( $this->author->id , 'TWITTER' ) ){?>
			    <span class="ui-span" style="display: inline-block;">
					<input style="vertical-align: middle;" type="checkbox" name="socialshare[]" value="twitter" id="socialshare-twitter"<?php echo EasyBlogHelper::getHelper( 'SocialShare' )->hasAutoPost( $this->author->id , 'TWITTER' ) ? ' checked="checked"' : '';?> onclick="eblog.dashboard.socialshare.setActive( this );" />
					<label style="display: inline-block;vertical-align: middle;" for="socialshare-twitter" title="<?php echo JText::_( 'COM_EASYBLOG_BLOGS_SOCIALSHARE_TWITTER' ); ?>"><span class="ir ico-tw"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_SOCIALSHARE_TWITTER' ); ?></span></label>
			    </span>
			    <?php } ?>
			    <?php if( $this->acl->rules->update_linkedin && $this->config->get( 'integrations_linkedin' ) && EasyBlogHelper::getHelper( 'SocialShare' )->isAssociated( $this->author->id , 'LINKEDIN' )  ){?>
			    <span class="ui-span" style="display: inline-block;">
					<input style="vertical-align: middle;" type="checkbox" name="socialshare[]" value="linkedin" id="socialshare-linkedin"<?php echo EasyBlogHelper::getHelper( 'SocialShare' )->hasAutoPost( $this->author->id , 'LINKEDIN' ) ? ' checked="checked"' : '';?> onclick="eblog.dashboard.socialshare.setActive( this );" />
					<label style="display: inline-block;vertical-align: middle;" for="socialshare-linkedin" title="<?php echo JText::_( 'COM_EASYBLOG_BLOGS_SOCIALSHARE_LINKEDIN' ); ?>"><span class="ir ico-ln"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_SOCIALSHARE_LINKEDIN' ); ?></span></label>
			    </span>
			    <?php } ?>
		    </span>
		</td>
	</tr>
	<?php } ?>			
</table>