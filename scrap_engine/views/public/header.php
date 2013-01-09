<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--Favicon-->
<link rel="shortcut icon" href="<?php echo base_url(); ?>scrap_assets/images/universal/fav_icon_3.ico" type="image/vnd.microsoft.icon" />
<link rel="icon" href="<?php echo base_url(); ?>scrap_assets/images/universal/fav_icon_3.ico" type="image/vnd.microsoft.icon" /> 

<!--Title-->
<title><?php if(isset($title)){ echo $title; }else{ echo 'YOU NEED A TITLE'; } ?></title>

<!--Include Style Sheets-->
<?php 
echo link_tag('scrap_assets/css/custom/public.css', 'stylesheet', 'text/css', 'screen', 'screen');
echo link_tag('scrap_assets/css/library/print.css', 'stylesheet', 'text/css', 'print', 'print'); 
echo link_tag('scrap_assets/css/library/accessible.css', 'alternate stylesheet', 'text/css', 'accessible', 'screen');
?>
<!--[if IE 7]>
<?php echo link_tag('scrap_assets/css/custom/ie7.css', 'stylesheet', 'text/css', 'screen', 'screen'); ?>
<![endif]-->
<!--[if IE 6]>
<?php echo link_tag('scrap_assets/css/custom/ie6.css', 'stylesheet', 'text/css', 'screen', 'screen'); ?>
<![endif]-->
<?php 
	if(isset($extra_css))
	{ 
		foreach($extra_css as $row)
		{
			echo link_tag('scrap_assets/css/custom/'.$row.'.css', 'stylesheet', 'text/css', 'screen', 'screen');
		}
	}
?>

<!--Include Javascript-->
<script type="text/javascript" src="<?php echo base_url(); ?>scrap_assets/js/<?php echo $this->config->item('js_framework'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scrap_assets/js/jquery_ui_v1.9.0.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scrap_assets/js/scrap_tools.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scrap_assets/js/scrap.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scrap_assets/js/plugin_uniform.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scrap_assets/js/plugin_tooltip.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scrap_assets/js/plugin_sunbox.js"></script>
<!--[if IE 6]> 
<script type="text/javascript" src="<?php echo base_url(); ?>scrap_assets/js/plugin_png_fix.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scrap_assets/js/ie_6_fixes.js"></script>
<style type="text/css">
div.newOverlay {
  /* IE5.5+/Win - this is more specific than the IE 5.0 version */
	position: absolute; left: 20px; top: 10px;
  	left: expression( ( 0 + ( ignoreMe2 = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft ) ) + 'px' );
  	top: expression( ( 0 + ( ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop ) ) + 'px' );
}
</style>
<![endif]-->
</head>
<body>

<!--Scrappy Container-->
<div id="scrappy" class="<?php echo $crt_page; ?>">

	<?php
	// Header
	echo open_div('header');

		// Login link
		echo anchor('signup', 'SIGN UP', 'class="signup link"');
		echo anchor('login', 'LOGIN', 'class="login link"');

		// Logo
		echo anchor('welcome', 'Fastsell Logo', 'class="logo"');

	// End of header
	echo close_div();
	?>

