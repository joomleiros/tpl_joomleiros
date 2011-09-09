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
<script language="javascript" type="text/javascript">
	function syncPosition(ele)
	{
	    var position = ele.value;
	    
	    ej('#main_tweetmeme_position').val(position);
	    ej('#main_googlebuzz_position').val(position);
	}
</script>
<table class="noshow">
	<tr>
		<td width="50%" valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BUTTON_SETTINGS_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BUTTON_POSITIONS' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_BUTTON_POSITIONS_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BUTTON_POSITIONS' ); ?>
						</span>
					</td>
					<td valign="top">
					<?php
							$tweetmemestyle = array();
						$tweetmemestyle[] = JHTML::_('select.option', 'left', JText::_( 'COM_EASYBLOG_LEFT_OPTION' ) );
						$tweetmemestyle[] = JHTML::_('select.option', 'right', JText::_( 'COM_EASYBLOG_RIGHT_OPTION' ) );
						$tweetmemestyle[] = JHTML::_('select.option', 'bottom', JText::_( 'COM_EASYBLOG_BOTTOM_OPTION' ) );
						$showdet = JHTML::_('select.genericlist', $tweetmemestyle, 'main_socialbutton_position', 'size="1" class="inputbox" onchange="syncPosition(this);"', 'value', 'text', $this->config->get('main_socialbutton_position' ) );
						echo $showdet;
					?>
					</td>
				</tr>
				
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BUTTON_SHOW_IN_FRONTPAGE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_BUTTON_SHOW_IN_FRONTPAGE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BUTTON_SHOW_IN_FRONTPAGE' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'social_show_frontpage' , $this->config->get( 'social_show_frontpage' ) );?>
					</td>
				</tr>
				
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BUTTON_ENABLE_RTL' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_BUTTON_ENABLE_RTL_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BUTTON_ENABLE_RTL' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'social_rtl' , $this->config->get( 'social_rtl' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BUTTON_PROVIDER' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_BUTTON_PROVIDER_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_BUTTON_PROVIDER' ); ?>
						</span>
					</td>
					<td valign="top">
					<?php
						$provider = array();
						$provider[] = JHTML::_('select.option', 'addthis', JText::_( 'COM_EASYBLOG_ADDTHIS_OPTION' ) );
						$provider[] = JHTML::_('select.option', 'sharethis', JText::_( 'COM_EASYBLOG_SHARETHIS_OPTION' ) );
						$showOption = JHTML::_('select.genericlist', $provider, 'social_provider', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('social_provider' , 'addthis' ) );
						echo $showOption;
					?>
					</td>
				</tr>
				
				</tbody>
			</table>
			</fieldset>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_PINGOMATIC_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_PINGOMATIC_ENABLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_PINGOMATIC_ENABLE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_PINGOMATIC_ENABLE' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'main_pingomatic' , $this->config->get( 'main_pingomatic' ) );?>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_GOOGLEBUZZ_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_GOOGLEBUZZ_ENABLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_GOOGLEBUZZ_ENABLE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_GOOGLEBUZZ_ENABLE' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'main_googlebuzz' , $this->config->get( 'main_googlebuzz' ) );?>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_GOOGLE_PLUS_ONE_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_GOOGLE_PLUS_ONE_ENABLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_GOOGLE_PLUS_ONE_ENABLE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_GOOGLE_PLUS_ONE_ENABLE' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'main_googleone' , $this->config->get( 'main_googleone' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key" style="vertical-align: top;">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_GOOGLE_PLUS_ONE_BUTTON_STYLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_GOOGLE_PLUS_ONE_BUTTON_STYLE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_GOOGLE_PLUS_ONE_BUTTON_STYLE' ); ?>
						</span>
					</td>
				    <td>
						<table width="70%">
							<tr>
								<td valign="top">
									<div>
										<input type="radio" name="main_googleone_layout" id="medium" value="medium"<?php echo $this->config->get('main_googleone_layout') == 'medium' ? ' checked="checked"' : '';?> />
										<label for="small"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_GOOGLE_PLUS_ONE_BUTTON_SMALL');?></label>
									</div>
									<div><img src="<?php echo JURI::root() . 'administrator/components/com_easyblog/assets/images/google/small.png';?>" /></div>
								</td>
							</tr>
							<tr>
								<td valign="top">
									<div>
										<input type="radio" name="main_googleone_layout" id="tall" value="tall"<?php echo $this->config->get('main_googleone_layout') == 'tall' ? ' checked="checked"' : '';?> />
										<label for="large"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_GOOGLE_PLUS_ONE_BUTTON_LARGE');?></label>
									</div>
									<div><img src="<?php echo JURI::root() . 'administrator/components/com_easyblog/assets/images/google/large.png';?>" /></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWEETMEME_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWEETMEME_ENABLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWEETMEME_ENABLE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWEETMEME_ENABLE' ); ?>
						</span>
					</td>
					<td valign="top">
						<?php echo $this->renderCheckbox( 'main_tweetmeme' , $this->config->get( 'main_tweetmeme' ) );?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWEETMEME_BUTTON_STYLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWEETMEME_BUTTON_STYLE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWEETMEME_BUTTON_STYLE' ); ?>
						</span>
					</td>
					<td valign="top">
					<?php
  						$tweetmemestyle = array();
						$tweetmemestyle[] = JHTML::_('select.option', 'normal', JText::_( 'COM_EASYBLOG_NORMAL_OPTION' ) );
						$tweetmemestyle[] = JHTML::_('select.option', 'compact', JText::_( 'COM_EASYBLOG_COMPACT_OPTION' ) );
						$showdet = JHTML::_('select.genericlist', $tweetmemestyle, 'main_tweetmeme_style', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('main_tweetmeme_style' , 'normal' ) );
						echo $showdet;
					?>
					</td>
				</tr>

				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWEETMEME_URLSHORTENER' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWEETMEME_URLSHORTENER_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWEETMEME_URLSHORTENER' ); ?>
						</span>
					</td>
					<td valign="top">
					<?php
  						$tweetmemeurl = array();
						$tweetmemeurl[] = JHTML::_('select.option', 'bit.ly', JText::_( 'bit.ly' ) );
						$tweetmemeurl[] = JHTML::_('select.option', 'awe.sm', JText::_( 'awe.sm' ) );
						$tweetmemeurl[] = JHTML::_('select.option', 'cli.gs', JText::_( 'cli.gs' ) );
						$tweetmemeurl[] = JHTML::_('select.option', 'digg.com', JText::_( 'digg.com' ) );
						$tweetmemeurl[] = JHTML::_('select.option', 'is.gd', JText::_( 'is.gd' ) );
						$tweetmemeurl[] = JHTML::_('select.option', 'tinyurl.com', JText::_( 'tinyurl.com' ) );
						$tweetmemeurl[] = JHTML::_('select.option', 'tr.im', JText::_( 'tr.im' ) );
						$tweetmemeurl[] = JHTML::_('select.option', 'su.pr', JText::_( 'su.pr' ) );
						$tweetmemeurl[] = JHTML::_('select.option', 'ow.ly', JText::_( 'ow.ly' ) );
						$tweetmemeurl[] = JHTML::_('select.option', 'twurl.nl', JText::_( 'twurl.nl' ) );
						$showdet = JHTML::_('select.genericlist', $tweetmemeurl, 'main_tweetmeme_url', 'size="1" class="inputbox"', 'value', 'text', $this->config->get('main_tweetmeme_url' , 'bit.ly' ) );
						echo $showdet;
					?>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWEETMEME_RTSOURCE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWEETMEME_RTSOURCE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWEETMEME_RTSOURCE' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" name="main_tweetmeme_rtsource" class="inputbox" value="<?php echo $this->config->get('main_tweetmeme_rtsource');?>" size="60" />
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
		</td>
		<td width="50%" valign="top">
			<a name="socialintegration_addthis" id="socialintegration_addthis"></a>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_ADDTHIS_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td width="300" class="key" style="vertical-align: top !important;">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_ADDTHIS_STYLE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_ADDTHIS_STYLE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_ADDTHIS_STYLE' ); ?>
						</span>
					</td>
					<td valign="top">
						<div><input type="radio" name="social_addthis_style" value="1" <?php echo ($this->config->get('social_addthis_style') == '1') ? 'checked' : ''; ?>  style="vertical-align: middle;" /> <img style="vertical-align: middle;" src="<?php echo JURI::root() . '/administrator/components/com_easyblog/assets/images/addthis_button1.png'; ?>" /></div>
						<div><input type="radio" name="social_addthis_style" value="2" <?php echo ($this->config->get('social_addthis_style') == '2') ? 'checked' : ''; ?> style="vertical-align: middle;" /> <img style="vertical-align: middle;" src="<?php echo JURI::root() . '/administrator/components/com_easyblog/assets/images/addthis_button2.png'; ?>" /></div>
					</td>
				</tr>	
				<tr>
					<td width="300" class="key" style="vertical-align: top !important;">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_ADDTHIS_PUBLISHING_CODE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_ADDTHIS_PUBLISHING_CODE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_ADDTHIS_PUBLISHING_CODE' ); ?>
						</span>
					</td>
					<td valign="top">
						<input type="text" name="social_addthis_customcode" class="inputbox half-width" style="margin-bottom: 10px;" value="<?php echo $this->config->get('social_addthis_customcode');?>" />
					</td>
				</tr>				
				</tbody>
			</table>
			</fieldset>
			<a name="socialintegration_addthis" id="socialintegration_sharethis"></a>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_SHARETHIS_TITLE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>

				<tr>
					<td width="300" class="key" style="vertical-align: top !important;">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_SHARETHIS_PUBLISHERS_CODE' ); ?>::<?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_SHARETHIS_PUBLISHERS_CODE_DESC'); ?>">
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_SHARETHIS_PUBLISHERS_CODE' ); ?>
						</span>
					</td>
					<td valign="top">
						<textarea name="social_sharethis_publishers" class="inputbox half-width" style="margin-bottom: 10px;height: 75px;"><?php echo $this->config->get('social_sharethis_publishers');?></textarea>
						<div class="notice half-width" style="text-align: left !important;"><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_SHARETHIS_PUBLISHERS_INSTRUCTIONS');?></div>
					</td>
				</tr>
				</tbody>
			</table>
			</fieldset>
		</td>
	</tr>
</table>