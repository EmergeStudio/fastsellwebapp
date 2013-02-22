<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
echo open_div('appNavigationContainer middle');

	// Section navigation
	echo open_div('blackStrip');

		echo '<ul class="appNav">';

			// Dashboard link
			if($app_page == 'pageDashboard')
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
			if($app_page == 'pageFastSells')
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
			if($app_page == 'pageMyOrders')
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
			if($app_page == 'pageBuyingPrefs')
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
			if($app_page == 'pageReports')
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

		// Clear float
		echo clear_float();

	echo close_div();

    // Some height
    echo div_height(20);

// Close middle div
echo close_div();
?>