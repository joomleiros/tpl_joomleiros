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
	ej('#cid').val(id);
	ej('#aclid').html(id);
	ej('#aclname').val(name);
	
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
</script>
<form action="index.php" method="post" name="adminForm" autocomplete="off">
	<div class="col width-45">
		<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_EASYBLOG_ACL_RULE_SET' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tr>
					<td width="150" class="key">
						<label for="cid"><?php echo JText::_( 'COM_EASYBLOG_ID' ); ?></label>
					</td>
					<td>
						<div id="aclid"><?php echo !empty($this->rulesets->id)? $this->rulesets->id : ''; ?></div>
					</td>
				</tr>
				<tr>
					<td width="150" class="key">
						<label for="name"><?php echo JText::_( 'COM_EASYBLOG_ACL_NAME' ); ?></label>
					</td>
					<td>
						<input type="text" readonly="readonly" class="inputbox" id="aclname" value="<?php echo !empty($this->rulesets->name)? $this->rulesets->name : ''; ?>">						
						<?php if ( $this->type == 'assigned' ) : ?>
						[ <a class="modal" rel="{handler: 'iframe', size: {x: 650, y: 375}}" href="index.php?option=com_easyblog&view=users&tmpl=component&browse=1"><?php echo JText::_('COM_EASYBLOG_BROWSE_USERS');?></a> ]
						<?php endif; ?>						
					</td>

				</tr>
			<?php
			foreach($this->rulesets->rules as $key=>$data)
			{
			?>
				<tr>
					<td width="150" class="key">
						<label for="name">
							<?php echo JText::_( 'COM_EASYBLOG_ACL_OPTION_' . $key ); ?>
						</label>
					</td>
					<td>
						<?php echo $this->renderCheckbox( $key , $data ); ?>
					</td>
				</tr>
			<?php
			}
			?>
			</table>
		</fieldset>
	</div>
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_easyblog" />
	<input type="hidden" name="c" value="acl" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="cid" id="cid" value="<?php echo !empty($this->rulesets->id)? $this->rulesets->id : ''; ?>" />
	<input type="hidden" name="name" value="<?php echo !empty($this->rulesets->name)? $this->rulesets->name : ''; ?>" />
	<input type="hidden" name="type" value="<?php echo $this->type; ?>" />
	<input type="hidden" name="add" value="<?php echo $this->add; ?>" />
</form>