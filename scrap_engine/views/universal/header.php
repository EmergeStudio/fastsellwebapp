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
echo link_tag('scrap_assets/css/master.css', 'stylesheet', 'text/css', 'screen', 'screen'); 
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
<script type="text/javascript" src="<?php echo base_url(); ?>scrap_assets/js/plugin_sunbox.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scrap_assets/js/plugin_tooltip.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scrap_assets/js/plugin_enter_it.js"></script>
<?php
// Extra javascript
if(isset($extra_js))
{ 
	foreach($extra_js as $row)
	{
		echo '<script type="text/javascript" src="'.base_url().'scrap_assets/js/'.$row.'.js"></script>';
	}
}
?>
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
<?php
if($crt_page != 'pageLogin')
{
	// Get custom theme
	$comp_id				= $this->session->userdata('sv_comp_id');
	$this->db->where('thm_comp_id', $comp_id);
	$qry					= $this->db->get('compass.tbl_themes');
	
	if($qry->num_rows == 1)
	{
		$theme				= $qry->row();
		if(($theme->thm_logo != 'none'))
		{
			$logo				= TRUE;
		}
		else
		{
			$logo				= FALSE;
		}
		
		echo '<style>';
		
			if($theme->thm_background != 'none')
			{
				echo 'html{ background-image: url('.base_url().$theme->thm_background.'); background-repeat: no-repeat; background-position:left top; }';
			}
			else
			{
				if($theme->thm_background_color != '')
				{
					echo 'html{ background-image: none; }';
				}
			}
		
			if($theme->thm_background_color != '')
			{
				echo 'html{ background-color: '.$theme->thm_background_color.'; }';
			}
		
			if($theme->thm_link_static != '')
			{
				echo 'a, .makeLink, .middle a, .middle .makeLink { color: '.$theme->thm_link_static.'; }';
			}
		
			if($theme->thm_link_hover != '')
			{
				echo 'a:hover, .makeLink:hover, .middle a:hover, .middle .makeLink:hover { color: '.$theme->thm_link_hover.'; }';
			}
		
		echo '</style>';
	}
	else 
	{
		$logo				= FALSE;
	}
}
?>
</head>
<body>

<!--Scrappy Container-->
<div id="scrappy" class="<?php echo $crt_page; ?>">

	<?php
	// Scrapy note
	echo open_div('scrapNote');
	
		// Loader
		echo full_div('', 'mainLoader');
		
		// Note space
		echo full_div('', 'mainNote');
	
	echo close_div();
	?>

	<!--Header-->
	<div id="header"<?php if(isset($section)){ echo ' class="'.$section.'"'; } ?>>
	
		<!--Top bar-->
		<div id="topBar">
		
			<?php
			echo open_div('innerTop');
			
				// Logo
				if($crt_page == 'pageLogin')
				{
					echo open_div('logoContain centerLogo');
				}
				else
				{
					echo open_div('logoContain leftLogo');
				}
				
					$ar_logo				= array
					(
						'id'				=> 'logo',
						'target'			=> '_self',
						'class'				=> 'floatLeft'
					);
					
					echo anchor('', 'Compass Home Page', $ar_logo);
					
				echo close_div();
				
				// Navigation links
				if($crt_page != 'pageLogin')
				{
					
					// Main navigation
					echo '<ul class="mainNav floatRight">';
				
						// Notifications link
						if($crt_page == 'pageNotifications')
						{
							echo '<li class="superActive">';
						}
						else
						{
							echo '<li>';
						}
							// Counter
							echo open_div('counter blue');
							
								echo full_div('6', 'count');
							
							echo close_div();
						
							echo full_div('<span class="icon-megaphone"></span>Notifications', 'mainNavLink mainNavNotifications');
							
							// Sub navigation
							echo open_div('subNav subNavNotifications');
							
								echo full_div('<b>Chris</b> added a new product <a href="'.base_url().'notification/redirect/1487">Product Name 1</a>', 'notificationLine');
								echo full_div('<b>Chris</b> added a new product <a href="'.base_url().'notification/redirect/1487">Product Name 2</a>', 'notificationLine');
								echo full_div('<b>Chris</b> added a new product <a href="'.base_url().'notification/redirect/1487">Product Name 3</a>', 'notificationLine');
								echo full_div('<b>Chris</b> added a new product <a href="'.base_url().'notification/redirect/1487">Product Name 4</a>', 'notificationLine');
								echo full_div('<b>Chris</b> added a new product <a href="'.base_url().'notification/redirect/1487">Product Name 5</a>', 'notificationLine');
								echo full_div('<b>Chris</b> added a new product <a href="'.base_url().'notification/redirect/1487">Product Name 6</a>', 'notificationLine');
							
								// Veiw all notifications
								echo div_height(15);
								echo make_button('View All Notifications', '', 'notifications');
							
							// End of sub navigation
							echo close_div();					
						
						echo '</li>';
				
						// User link
						if($crt_page == 'pageUserDetails')
						{
							echo '<li class="superActive userNavLink">';
						}
						else
						{
							echo '<li class="userNavLink">';
						}
						
							// Main link
							echo full_div('Welcome '.full_span($this->session->userdata('sv_name'), 'blueLink'), 'mainNavLink noIcon');
							
							// Sub navigation
							echo open_div('subNav');
							
								// Right content
								echo open_div('floatRight');
									
									echo div_height(2);
									
									// Logout
									echo make_button('Logout', '', 'logout', 'right');
								
									echo open_div('profileInner');
									
										// Account type
										echo full_div('Administrator');
										
										// Edit profile
										echo make_link('Change My Information', 'btnEditMyProfile');
										
										echo div_height(1);
										
										// Landing page
										echo div_height(10);
										echo full_div('Landing Page');
			
										// Landing page drop down
										echo div_height(4);
										$ar_landing_page	= array
										(
											'dashboard' 	=> 'Dashboard',
											'events'		=> 'Show Events',
											'my_orders'		=> 'My Orders',
											'manage/users'	=> 'User Accounts'
										);
										$landing_page	= 'dashboard';
										echo form_dropdown('drpdwnLandingPage', $ar_landing_page, $landing_page);
									
									echo close_div();
								
								echo close_div();
								
								echo open_div('profileImageBorder');
									
									$img_properties		= array
									(
										'src'			=> $this->scrap_web->get_profile_image(),
										'width'			=> '100',
										'class'			=> 'profileImage'
									);
									echo img($img_properties);
									
								echo close_div();
								
								echo clear_float();
							
							// End of sub navigation
							echo close_div();
						
						echo '</li>';
				
					echo '</ul>';
				}
			
			echo close_div();
			
			// Clear float
			echo clear_float();
			?>

		</div>
	
	</div>