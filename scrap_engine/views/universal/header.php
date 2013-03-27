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
<script type="text/javascript" src="<?php echo base_url(); ?>scrap_assets/js/jquery_ui_v1.10.0.js"></script>
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
//if($crt_page != 'pageLogin')
//{
//	// Get custom theme
//	$comp_id				= $this->session->userdata('sv_comp_id');
//	$this->db->where('thm_comp_id', $comp_id);
//	$qry					= $this->db->get('compass.tbl_themes');
//
//	if($qry->num_rows == 1)
//	{
//		$theme				= $qry->row();
//		if(($theme->thm_logo != 'none'))
//		{
//			$logo				= TRUE;
//		}
//		else
//		{
//			$logo				= FALSE;
//		}
//
//		echo '<style>';
//
//			if($theme->thm_background != 'none')
//			{
//				echo 'html{ background-image: url('.base_url().$theme->thm_background.'); background-repeat: no-repeat; background-position:left top; }';
//			}
//			else
//			{
//				if($theme->thm_background_color != '')
//				{
//					echo 'html{ background-image: none; }';
//				}
//			}
//
//			if($theme->thm_background_color != '')
//			{
//				echo 'html{ background-color: '.$theme->thm_background_color.'; }';
//			}
//
//			if($theme->thm_link_static != '')
//			{
//				echo 'a, .makeLink, .middle a, .middle .makeLink { color: '.$theme->thm_link_static.'; }';
//			}
//
//			if($theme->thm_link_hover != '')
//			{
//				echo 'a:hover, .makeLink:hover, .middle a:hover, .middle .makeLink:hover { color: '.$theme->thm_link_hover.'; }';
//			}
//
//		echo '</style>';
//	}
//	else
//	{
//		$logo				= FALSE;
//	}
//}
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

	// Edit popup
	echo open_div('scrapEdit');

		// Edit arrow
		echo open_div('arrowContainer');

			echo full_div('', 'arrow');

		echo close_div();

		echo open_div('editContainer');

			$inp_edit           = array
			(
				'name'	        => 'inpScrapEdit'
			);
			echo form_input($inp_edit);

			echo make_button('Save', 'btnSave blueButton', '', 'left');
			echo make_button('Cancel', 'btnCancel', '', 'left');

			echo clear_float();

		echo close_div();

	echo close_div();
	?>

	<!--Header-->
	<div id="header"<?php if(isset($section)){ echo ' class="'.$section.'"'; } ?>>
	
		<!--Top bar-->
		<div id="topBar">
		
			<?php
			echo open_div('innerTop');
			
				// Logo
				if(($crt_page == 'pageLogin') || ($crt_page == 'pageSignup') || ($crt_page == 'pageForgotPassword'))
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
					
					echo anchor('dashboard', 'FastSell Dashboard', $ar_logo);
					
				echo close_div();
				
				// Navigation links
				if(($crt_page != 'pageLogin') && ($crt_page != 'pageSignup') && ($crt_page != 'pageForgotPassword'))
				{
					// Application navigation
					if($this->session->userdata('sv_acc_type') == 'show_host')
					{
						echo '<ul class="appNav">';

							// Shows link
							if($crt_page == 'pageFastSells')
							{
								echo '<li class="active">';
							}
							else
							{
								echo '<li>';
							}

							echo anchor('fastsells', '<span class="icon-ticket yellow"></span>FastSells', 'class="sectionNavLink"');

							echo '</li>';

							// Products link
							if($crt_page == 'pageProducts')
							{
								echo '<li class="active">';
							}
							else
							{
								echo '<li>';
							}

							echo full_div('<span class="icon-box"></span>Products', 'sectionNavLink');

							// Sub navigation
							echo '<ul class="subNav">';

								echo '<li>';

									echo anchor('products', 'My Products', 'class="sectionNavSubLink"');

								echo '</li>';

								echo '<li>';

									echo anchor('products/definitions', 'Product Templates', 'class="sectionNavSubLink"');

								echo '</li>';

								echo '<li>';

									echo anchor('products/upload_master_file', 'Import Products', 'class="sectionNavSubLink"');

								echo '</li>';

							echo '</ul>';

							echo '</li>';

							// Customers link
							if($crt_page == 'pageCustomers')
							{
								echo '<li class="active">';
							}
							else
							{
								echo '<li>';
							}

							echo full_div('<span class="icon-users"></span>Address Book', 'sectionNavLink');

								// Sub navigation
								echo '<ul class="subNav">';

									echo '<li>';

										echo anchor('customers', 'My Customers', 'class="sectionNavSubLink"');

									echo '</li>';

									echo '<li>';

										echo anchor('customers/upload_master_file', 'Import Customers', 'class="sectionNavSubLink lastLink"');

									echo '</li>';

								echo '</ul>';

							echo '</li>';

							// Reports link
							if($crt_page == 'pageReports')
							{
								echo '<li class="active">';
							}
							else
							{
								echo '<li>';
							}

							echo full_div('<span class="icon-bars"></span>Reports', 'sectionNavLink');

								// Sub navigation
								echo '<ul class="subNav">';

									echo '<li>';

										echo anchor('reports/orders_summary', 'Orders Summary', 'class="sectionNavSubLink firstLink"');

									echo '</li>';

									echo '<li>';

										echo anchor('reports/orders_by_event', 'Orders By FastSell', 'class="sectionNavSubLink"');

									echo '</li>';

									echo '<li>';

										echo anchor('reports/orders_by_date', 'Orders By Date', 'class="sectionNavSubLink lastLink"');

									echo '</li>';

								echo '</ul>';

							echo '</li>';

						echo '</ul>';
					}
					elseif($this->session->userdata('sv_acc_type') == 'customer')
					{
						echo '<ul class="appNav">';

							// Dashboard link
							if($crt_page == 'pageDashboard')
							{
								echo '<li class="homeLinkLi active">';
							}
							else
							{
								echo '<li class="homeLinkLi">';
							}

								echo anchor('dashboard', 'Dashboard', 'class="sectionNavLink homeLink"');

							echo '</li>';

							// Shows link
							if($crt_page == 'pageFastSells')
							{
								echo '<li class="active">';
							}
							else
							{
								echo '<li>';
							}

								echo anchor('fastsells', '<span class="icon-ticket yellow"></span>FastSells', 'class="sectionNavLink fastsells"');

							echo '</li>';

							// Orders link
							if($crt_page == 'pageMyOrders')
							{
								echo '<li class="active">';
							}
							else
							{
								echo '<li>';
							}

								echo anchor('my_orders', '<span class="icon-shopping-cart blue"></span>My Orders', 'class="sectionNavLink"');

							echo '</li>';

							// Buying preferences link
							if($crt_page == 'pageBuyingPrefs')
							{
								echo '<li class="active">';
							}
							else
							{
								echo '<li>';
							}

								echo anchor('buying_preferences', '<span class="icon-basket blue"></span>Buying Preferences', 'class="sectionNavLink"');

							echo '</li>';

							// Reports link
							if($crt_page == 'pageReports')
							{
								echo '<li class="active">';
							}
							else
							{
								echo '<li>';
							}

								echo anchor('reports/customer_orders', '<span class="icon-bars blue"></span>Reports', 'class="sectionNavLink"');

							echo '</li>';

						echo '</ul>';
					}

					
					// Main navigation
					echo '<ul class="mainNav floatRight">';
				
						// User link
						if($this->session->userdata('sv_acc_type') == 'show_host')
						{
							echo full_div('<span class="icon icon-sale"></span>Seller', 'accountType');
						}
						else
						{
							echo full_div('<span class="icon icon-basket"></span>Buyer', 'accountType green');
						}

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
									
									echo div_height(10);
									
									// Logout
									echo make_button('Logout', '', 'logout', 'right');
								
									echo open_div('profileInner');

										echo full_div('<b>'.$this->session->userdata('sv_name').'</b>');
										
										// Edit profile
										echo make_link('Change My Information', 'btnEditMyProfile').'<br>';

										// Manage accounts
										echo anchor('manage/users', 'Manage User Accounts');

										echo div_height(1);

										// Timezone selection
										echo div_height(10);
										echo full_div('Timezone');

										// Landing page drop down
										echo form_open('timezone_change', 'class="frmChangeTimezone"');

											$ar_timezone	= array
											(
												'1' 	=> '(GMT -12:00) Eniwetok, Kwajalein',
												'2'		=> '(GMT -11:00) Midway Island, Samoa',
												'3'		=> '(GMT -10:00) Hawaii',
												'4'		=> '(GMT -9:00) Alaska',
												'5'		=> '(GMT -8:00) Pacific Time (US &amp; Canada)',
												'6'		=> '(GMT -7:00) Mountain Time (US &amp; Canada)',
												'7'		=> '(GMT -6:00) Central Time (US &amp; Canada), Mexico City',
												'8'		=> '(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima',
												'9'		=> '(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz',
												'10'	=> '(GMT -3:00) Brazil, Buenos Aires, Georgetown',
												'11'	=> '(GMT -2:00) Mid-Atlantic',
												'12'	=> '(GMT -1:00) Azores, Cape Verde Islands',
												'13'	=> '(GMT) Western Europe Time, London, Lisbon, Casablanca',
												'14'	=> '(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris',
												'15'	=> '(GMT +2:00) Kaliningrad, South Africa',
												'16'	=> '(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg',
												'17'	=> '(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi',
												'18'	=> '(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent',
												'19'	=> '(GMT +6:00) Almaty, Dhaka, Colombo',
												'20'	=> '(GMT +7:00) Bangkok, Hanoi, Jakarta',
												'21'	=> '(GMT +8:00) Beijing, Perth, Singapore, Hong Kong',
												'22'	=> '(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk',
												'23'	=> '(GMT +10:00) Eastern Australia, Guam, Vladivostok',
												'24'	=> '(GMT +11:00) Magadan, Solomon Islands, New Caledonia',
												'25'	=> '(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka'
											);

											$url_time               = 'timezones/.json';
											$call_time              = $this->scrap_web->webserv_call($url_time, FALSE, 'get', FALSE, FALSE);
											$json_time              = $call_time['result'];
											$timezone	            = $json_time->id;

											echo form_dropdown('drpdwnTimezone', $ar_timezone, $timezone);
											echo form_hidden('hdReturnTimeUrl', current_url());
											echo hidden_div($json_time->hour, 'hdTimeDiff');

										echo form_close();
									
									echo close_div();
								
								echo close_div();
								
								echo open_div('profileImageBorder');

									// Get the image
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