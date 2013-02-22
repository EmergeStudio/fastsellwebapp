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

				echo anchor('fastsells', '<span class="icon-ticket yellow"></span>FastSells', 'class="sectionNavLink"');

			echo '</li>';

			// Products link
			if($app_page == 'pageProducts')
			{
				echo '<li class="active">';
			}
			else
			{
				echo '<li>';
			}

			echo full_div('<span class="icon-box blue"></span>Products', 'sectionNavLink');

				// Sub navigation
				echo '<ul class="subNav">';

					echo '<li>';

						echo anchor('products', 'Your Products', 'class="sectionNavSubLink"');

					echo '</li>';

					echo '<li>';

						echo anchor('products/definitions', 'Product Groups', 'class="sectionNavSubLink"');

					echo '</li>';

				echo '</ul>';

			echo '</li>';

			// Customers link
			if($app_page == 'pageCustomers')
			{
				echo '<li class="active">';
			}
			else
			{
				echo '<li>';
			}

				echo full_div('<span class="icon-users blue"></span>Customers', 'sectionNavLink');

				// Sub navigation
				echo '<ul class="subNav">';

					echo '<li>';

						echo anchor('customers', 'Your Customers', 'class="sectionNavSubLink"');

					echo '</li>';

					echo '<li>';

						echo anchor('customers/upload_master_file', 'Import Customers', 'class="sectionNavSubLink lastLink"');

					echo '</li>';

				echo '</ul>';

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

				echo full_div('<span class="icon-bars blue"></span>Reports', 'sectionNavLink');

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

		// Clear float
		echo clear_float();

	echo close_div();

    // Some height
    echo div_height(20);

// Close middle div
echo close_div();
?>