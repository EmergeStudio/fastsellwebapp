<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// HTML
echo open_div('bannerBack');

	// FastSell infor
	echo open_div('middle fastsellInfo');

		// Time left
		echo open_div('timLeft floatRight');

			echo open_div('counterTime');

				echo open_div('counterText');

					echo full_div('DAYS', 'counterDays');
					echo full_div('HOURS', 'counterHours');
					echo full_div('MINUTES', 'counterMinutes');
					echo full_div('SECONDS', 'counterSeconds');

				echo close_div();

				if($started == TRUE)
				{
					echo hidden_div($this->scrap_string->make_db_date($fastsell_info->event_end_date), 'hdDate');
					echo hidden_div(substr($fastsell_info->event_end_date, 11), 'hdTime');
				}
				else
				{
					echo hidden_div($this->scrap_string->make_db_date($fastsell_info->event_start_date), 'hdDate');
					echo hidden_div(substr($fastsell_info->event_start_date, 11), 'hdTime');
				}

			echo close_div();

			//echo heading(nbs(6).'Days'.nbs(14).'Hours'.nbs(12).'Minutes'.nbs(10).'Seconds', 4, 'class="counterHeadings"');

		// End of time left
		echo close_div();

		if($started == TRUE)
		{
			echo full_div('Ends In:', 'timeHeading');
		}
		else
		{
			echo full_div('Starts In:', 'timeHeading');
		}

		// Heading
		echo heading('FastSell: '.$fastsell_info->name, 2);

		// Clear float
		echo clear_float();

	echo close_div();

    // Navigatin
	echo open_div('appNavigationContainer middle');

		// Section navigation
		echo open_div('blackStrip');

			echo make_button('<span class="icon-arrow-left yellow"></span>Back To FastSells', 'blueButton', 'fastsells', 'left');

			echo '<ul class="appNav">';

				// Shows link
				if($app_page == 'pageFastSellInfo')
				{
					echo '<li class="active">';
				}
				else
				{
					echo '<li>';
				}

					echo anchor('fastsells/event/'.$this->session->userdata('sv_show_set'), '<span class="icon-ticket blue"></span>FastSell Info', 'class="sectionNavLink"');

				echo '</li>';

				// Products link
				if($app_page == 'pageFastSellProducts')
				{
					echo '<li class="active">';
				}
				else
				{
					echo '<li>';
				}

					echo anchor('fastsells/products', '<span class="icon-box blue"></span>FastSell Products', 'class="sectionNavLink"');

				echo '</li>';

				// Customers link
				if($app_page == 'pageFastSellCustomers')
				{
					echo '<li class="active">';
				}
				else
				{
					echo '<li>';
				}

					echo anchor('fastsells/customers', '<span class="icon-users blue"></span>FastSell Customers', 'class="sectionNavLink"');

				echo '</li>';

			echo '</ul>';

			// Clear float
			echo clear_float();

		echo close_div();

	// Close middle div
	echo close_div();

// Close banner back
echo close_div();

// Some height
echo div_height(20);
?>