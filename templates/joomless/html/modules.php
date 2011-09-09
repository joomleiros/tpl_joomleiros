<?php

function modChrome_nav($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<nav>
			<?php echo $module->content; ?>
		</nav>
	<?php endif;
}

function modChrome_html5($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
    	<?php if ( htmlspecialchars($params->get('moduleclass_sfx')) == "nav") : ?>
		<nav class="box<?php echo htmlspecialchars($params->get('moduleclass_sfx')) ?>">
			<?php if ($module->showtitle) : ?>
                <h3><?php echo $module->title; ?></h3>
            <?php endif; ?>
			<?php echo $module->content; ?>
		</nav>
        <?php elseif ( htmlspecialchars($params->get('moduleclass_sfx')) == "section") : ?>
		<section class="box<?php echo htmlspecialchars($params->get('moduleclass_sfx')) ?>">
			<?php if ($module->showtitle) : ?>
                <h3><?php echo $module->title; ?></h3>
            <?php endif; ?>
			<?php echo $module->content; ?>
		</section>
        <?php elseif ( htmlspecialchars($params->get('moduleclass_sfx')) == "aside") : ?>
		<aside class="box<?php echo htmlspecialchars($params->get('moduleclass_sfx')) ?>">
			<?php if ($module->showtitle) : ?>
                <h3><?php echo $module->title; ?></h3>
            <?php endif; ?>
			<?php echo $module->content; ?>
		</aside>
        <?php elseif ( htmlspecialchars($params->get('moduleclass_sfx')) == "clean") : ?>
		<?php echo $module->content; ?>
        <?php else: ?>
		<div class="box<?php echo htmlspecialchars($params->get('moduleclass_sfx')) ?>">
			<?php if ($module->showtitle) : ?>
                <h3><?php echo $module->title; ?></h3>
            <?php endif; ?>
			<?php echo $module->content; ?>
		</div>
        <?php endif; ?>
	<?php endif;
}
