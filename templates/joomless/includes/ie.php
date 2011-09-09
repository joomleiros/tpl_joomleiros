
<?php 
$doc =& JFactory::getDocument();



if ($this->params->get('iecss') == 1): 
	$doc->addStyleSheet( $this->baseurl."/templates/".$this->template."/css/ie.css");
endif; 

if ($this->params->get('mediaqueries') == 1): 
	$doc->addScript( $this->baseurl."/templates/".$this->template."/js/respond.min.js");
endif;

if ($this->params->get('sandpaper') == 1): 
	$doc->addScript( $this->baseurl."/templates/".$this->template."/js/EventHelpers.js");
	$doc->addScript( $this->baseurl."/templates/".$this->template."/js/textshadow.js");
	$doc->addScript( $this->baseurl."/templates/".$this->template."/js/cssQuery-p.js");
	$doc->addScript( $this->baseurl."/templates/".$this->template."/js/sylvester.js");
	$doc->addScript( $this->baseurl."/templates/".$this->template."/js/cssSandpaper.js");
endif;
?>
