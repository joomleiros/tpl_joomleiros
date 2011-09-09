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
<script type="text/javascript">
function insertMember( id , name )
{
	var elementId	= 'member-' + id;
	
	if( ej('#' + elementId).html() == null )
	{
		ej('#members-container').append('<span id="' + elementId + '" class="members-item"><a class="remove_item" href="javascript:void(0);" onclick="removeMember(\'' + elementId + '\');">X</a><input type="hidden" name="members[]" value="' + id + '" /><span class="normal-member">' + name + '</span></span>');
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
	else
	{
		alert('User is already added');
	}
}

function removeMember( elementId, userId )
{
	ej('#'+elementId).remove();
	
	if(ej('#deletemembers').val() == '')
	{
	    ej('#deletemembers').val(userId);
	}
	else
	{
		var members = ej('#deletemembers').val();
		ej('#deletemembers').val(members + ',' + userId);
	}
}

function submitbutton( action )
{
	if ( typeof( tinyMCE ) == 'object' ) {
		if ( ej('#write_description').is(":visible") ) {
			tinyMCE.execCommand('mceToggleEditor', false, 'write_description');
		}
	}

	submitform( action );
}

</script>
<form name="adminForm" id="adminForm" action="index.php?option=com_easyblog" method="post" enctype="multipart/form-data">
<table width="100%">
	<tr>
		<td width="50%" valign="top">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'COM_EASYBLOG_TEAMBLOGS_DETAILS' ); ?></legend>
				<table  width="100%" class="paramlist admintable" cellspacing="1">
					<tr>
						<td class="paramlist_key">
							<label for="title"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_TEAM_NAME'); ?></label>
						</td>
						<td class="paramlist_value">
							<input class="inputbox" type="text" id="title" name="title" value="<?php echo $this->team->title;?>" style="width: 250px;" />
						</td>
					</tr>
					<tr>
						<td class="paramlist_key">
							<label for="alias"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_TEAM_ALIAS'); ?></label>
						</td>
						<td class="paramlist_value">
							<input class="inputbox" type="text" id="alias" name="alias" value="<?php echo $this->team->alias;?>" style="width: 250px;" />
						</td>
					</tr>
					<tr>
						<td class="paramlist_key" style="vertical-align: top;">
							<label for="description"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_TEAM_DESCRIPTION'); ?></label>
						</td>
						<td class="paramlist_value">
							<?php echo $this->editor->display( 'write_description', $this->team->description, '30%', '280', '10', '10' , array('article', 'image', 'readmore', 'pagebreak') ); ?>
						</td>
					</tr>
					
					<?php if($this->config->get('layout_teamavatar', true)) : ?>
					<tr>
			        	<td class="key">
			        	    <label for="Filedata" class="label"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_AVATAR'); ?></label>
						</td>
						<td>
						    <?php if(! empty($this->team->avatar)) { ?>
							<img style="border-style:solid; float:none;" src="<?php echo $this->team->getAvatar(); ?>" width="60" height="60"/><br />
						    <?php } ?>
							<input id="file-upload" type="file" name="Filedata" class="inputbox" size="33"/>
						</td>
					</tr>
					<?php endif; ?>					
					<tr>
						<td class="paramlist_key">
							<label for="username"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_CREATED'); ?></label>
						</td>
						<td class="paramlist_value">
							<?php echo JHTML::_('calendar', $this->team->created , "created", "created" , '%Y-%m-%d %H:%M:%S' , 'size="35"'); ?>
						</td>
					</tr>
					<tr>
						<td class="paramlist_key">
							<label for="username"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_PUBLISHED'); ?></label>
						</td>
						<td class="paramlist_value">
							<?php echo JHTML::_('select.booleanlist',  'published', 'class="inputbox" size="1"', $this->team->published ); ?>
						</td>
					</tr>
					<tr>
						<td class="paramlist_key">
							<label for="username"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_ACCESS'); ?></label>
						</td>
						<td class="paramlist_value"><?php echo $this->blogAccessList; ?></td>
					</tr>
				</table>
				<div style="clear:both;"></div>
			</fieldset>
		</td>
		<td valign="top">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'COM_EASYBLOG_TEAMBLOGS_MEMBERS' ); ?></legend>
				<div style="margin-top: 5px;"><a class="modal" rel="{handler: 'iframe', size: {x: 650, y: 375}}" href="index.php?option=com_easyblog&view=users&tmpl=component&browse=1"><?php echo JText::_('Add members');?></a></div>
				<div id="members-container" style="margin-top: 15px;">
				<?php
				if( $members = $this->getMembers( $this->team->id ) )
				{
					foreach($members as $member)
					{
						$user	= JFactory::getUser( $member->user_id );
						
						$markAdmin		= '- <a href="javascript:void(0);" onclick="admin.teamblog.markAdmin('.$this->team->id.','.$member->user_id.');">' . JText::_( 'COM_EASYBLOG_TEAMBLOGS_SET_ADMIN' ) . '</a>';
						$removeAdmin	= '- <a href="javascript:void(0);" onclick="admin.teamblog.removeAdmin('.$this->team->id.','.$member->user_id.');">' . JText::_( 'COM_EASYBLOG_TEAMBLOGS_REMOVE_ADMIN' ) . '</a>';
					?>
						<span id="member-<?php echo $user->id;?>" class="members-item">
							<input type="hidden" name="members[]" value="<?php echo $user->id;?>" />
							<a class="remove_item" href="javascript:void(0);" onclick="removeMember('member-<?php echo $user->id;?>', '<?php echo $user->id;?>');">X</a>
							<span class="<?php echo $member->isadmin ? 'admin-member' : 'normal-member'; ?>">
								<?php echo $user->name; ?>
								<?php echo ($member->isadmin) ? $removeAdmin : $markAdmin; ?>
							</span>
							
						</span>
					<?php
					}
				}
				?>
				</div>
			</fieldset>
			
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_META_TAGS'); ?></legend>

	  			<table class="admintable">
	  				<tr>
	  					<td>
			  				<label for="keywords" class="label label-title"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_META_KEYWORDS'); ?></label><br />
			    		</td>
			    	</tr>
			    	<tr>
			    		<td>					  				
			    			<textarea name="keywords" id="keywords" class="inputbox" style="width: 98%;"><?php echo $this->meta->keywords; ?></textarea><br />
							<div><small>( <?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_META_KEYWORDS_INSTRUCTIONS'); ?> )</small></div>
						</td>
					</tr>
					
					<tr>
	  					<td>
			  				<label for="description" class="label label-title"><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_META_DESCRIPTION'); ?></label><br />
			    		</td>
			    	</tr>
			    	<tr>
			    		<td>					  				
			    			<textarea name="description" id="description" class="inputbox" style="width: 98%;"><?php echo $this->meta->description; ?></textarea>							
						</td>
					</tr>
    				<input type="hidden" name="metaid" value="<?php echo $this->meta->id; ?>" />
				</table>
			</fieldset>
		</td>
	</tr>
</table>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_easyblog" />
<input type="hidden" name="c" value="teamblogs" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="id" value="<?php echo $this->team->id;?>" />
<input type="hidden" name="deletemembers" id="deletemembers" value="" />
</form>