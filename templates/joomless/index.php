<?php
/**
 * @version		$Id: index.php 1.0 2011-03-08 19:09:53 
 * @package		JoomLess Teplate Pack
 * @copyright	Copyright (C) 1998 - 2011 Ideas Net Studio. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die; 

require_once( JPATH_THEMES.DS.$this->template.DS.'includes'.DS.'head.php' );
?>

<!doctype html>
<?php 
//Identify of the visitor's Browser is IE and what version
$br =	strtolower($_SERVER['HTTP_USER_AGENT']); // Browser ?
if(preg_match("/msie 6/", $br)) : ?>
<html class="no-js ie6" lang="<?php echo $this->language; ?>">
<?php elseif (preg_match("/msie 7/", $br)): ?>
<html class="no-js ie7" lang="<?php echo $this->language; ?>">
<?php elseif (preg_match("/msie 8/", $br)): ?>
<html class="no-js ie8" lang="<?php echo $this->language; ?>">
<?php elseif (preg_match("/msie 9/", $br)): ?>
<html class="no-js ie9" lang="<?php echo $this->language; ?>">
<?php else: ?>
<html class="no-js" lang="<?php echo $this->language; ?>">
<?php endif; ?>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="format-detection" content="telephone=no" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" media="all" />
<?php if ($this->params->get('mediaqueries') == 1): ?>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" media="only screen and (min-width: 960px)" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/768px.css" media="only screen and (min-width: 751px) and (max-width: 959px)" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/320px.css" media="only screen and (max-width: 479px)" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/480px.css" media="only screen and (min-width: 480px) and (max-width: 750px)" />
<?php endif; ?>
<link rel="apple-touch-icon" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/apple-touch-icon.png">
</head>

<body>
<div id="pagewrapper">
  <header id="pageheader">
    <jdoc:include type="modules" name="topmenu" style="nav" />
    <h1><a href="index.php">Joomleiros</a></h1>
  </header>
  <section id="pagenav">
    <jdoc:include type="modules" name="mainmenu" style="nav" />
    <jdoc:include type="modules" name="search" style="raw" />
    <div class="clr">&nbsp;</div>
  </section>
  <div id="pagecontent">
    <?php if($this->countModules('right')) : ?>
    <aside id="right">
      <jdoc:include type="modules" name="right" style="box" />
    </aside>
    <div id="pagecontentarea">
      <?php if($this->countModules('banner-big')) : ?>
      <jdoc:include type="modules" name="banner-big" style="raw" />
      <?php endif; ?>
      <?php endif; ?>
      <jdoc:include type="component" />
	<?php if($this->countModules('right')) : ?>
    </div>
    <?php endif; ?>
    <div class="clr">&nbsp;</div>
  </div>
  <footer id="pagefooter"> 
    <section>
      <jdoc:include type="modules" name="footer" style="html5" />
      <div class="clr">&nbsp;</div>
    </section>
    <p>O nome Joomla é de propriedade da OSM e não temos qualquer vínculo com a tal. Amém!</p>
  </footer>
</div>
<style>
<?php 
if ($this->params->get('csspie')):
$style = "
".$this->params->get('csspie')." {
	behavior: url(".$this->baseurl."/PIE.htc);
}"; 
echo $style;
endif; 
?>
</style>
</body>
</html>
