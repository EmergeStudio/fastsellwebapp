<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data
if($order == FALSE)
{
	$crt_total              = 0.00;
}
else
{
	$crt_total              = 0;
	foreach($crt_order->fastsell_order_to_items as $order_item)
	{
		$quantity           = $order_item->quantity;
		$price              = $order_item->fastsell_item->price;
		$crt_total          = $crt_total + ($quantity * $price);
	}
	$crt_total              = number_format($crt_total, 2);
}

// HTML
echo open_div('bannerBack');

	// FastSell infor
	echo open_div('middle fastsellInfo');

		// Time left
		echo open_div('timLeft floatRight');

			if($started == TRUE)
			{
				echo open_div('counterTime yellow');

				echo full_div('Event Ending In', 'counterHeading');

				echo hidden_div($this->scrap_string->make_db_date($fastsell_info->event_end_date), 'hdDate');
				echo hidden_div(substr($fastsell_info->event_end_date, 11), 'hdTime');

				echo close_div();
			}
			else
			{
				echo open_div('counterTime blue');

				echo full_div('Going To Start In', 'counterHeading');

				echo hidden_div($this->scrap_string->make_db_date($fastsell_info->event_start_date), 'hdDate');
				echo hidden_div(substr($fastsell_info->event_start_date, 11), 'hdTime');

				echo close_div();
			}

			echo heading(nbs(4).'Days'.nbs(13).'Hours'.nbs(11).'Minutes'.nbs(7).'Seconds', 4, 'class="counterHeadings"');

		// End of time left
		echo close_div();

//		if($started == TRUE)
//		{
//			echo full_div('Ends In:', 'timeHeading');
//		}
//		else
//		{
//			echo full_div('Starts In:', 'timeHeading');
//		}

		// Heading
		echo heading($fastsell_info->name, 2);

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
				if($app_page == 'pageFastSellBuy')
				{
					echo '<li class="active">';
				}
				else
				{
					echo '<li>';
				}

					echo anchor('fastsells/buy', '<span class="icon-dollar blue"></span>Buy', 'class="sectionNavLink"');

				echo '</li>';

				// Orders link
				if($app_page == 'pageFastSellMyOrder')
				{
					echo '<li class="active myOrder">';
				}
				else
				{
					echo '<li class="myOrder">';
				}

					echo anchor('fastsells/my_order', '<span class="icon-shopping-cart yellow"></span>My Order'.nbs(2).'$'.$crt_total, 'class="sectionNavLink"');

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