<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php 
// Some data
$loop_cnt			= 0;
$loop_end			= 10;
?>

<?php
// Some height
echo div_height(50);

// Open middle div
echo open_div('middle notificationContainer');

	// Right column
	echo open_div('rightColSmall');
	
		// Some height
		echo div_height(51);
		
		// Search input
		$inp_data			= array
		(
			'name'			=> 'inpSearchNotifications',
			'class'			=> 'inpSearchNotifications floatLeft',
			'placeholder'	=> 'Search By User'
		);
		echo form_input($inp_data);
		
		// Button
		echo make_button('Search', 'btnSearchNotifications');
		
		// Clear float
		echo clear_float();
		
		// Divider
		echo full_div('', 'divider');
		
		// Some user filters
		/*echo full_div('Chris Humboldt', 'userFilterLine');
		echo full_div('Chris Humboldt', 'userFilterLine');
		echo full_div('Chris Humboldt', 'userFilterLine');
		echo full_div('Chris Humboldt', 'userFilterLine');
		echo full_div('Chris Humboldt', 'userFilterLine');
		echo full_div('Chris Humboldt', 'userFilterLine');*/
	
	// End of right column
	echo close_div();
	
	// Left column
	echo open_div('leftColBig');

		// Back to dashboard
		echo open_div('btnDashboardContainer');
		
			echo make_button('Back To Dashboard', 'btnDashboard', 'dashboard');
			
		echo close_div();
	
		// Loop through notifications
		while($loop_cnt < $loop_end)
		{
			// Inset
			echo '<a href="'.base_url().'notifications/redirect/1">';
			
				if($loop_cnt < 6)
				{
					echo open_div('inset new');
				}
				else 
				{
					echo open_div('inset');
				}
			
					// Increase the loop
					$loop_cnt++;
						
					// Notification line
					echo open_div('notificationLine');
						
						// Notification details
						echo open_div('notificationDetails');
						
							echo full_div('<b>Chris</b> added a new product <b>Product Name '.$loop_cnt.'</b>', 'darkText');
							echo full_div('Yesterday 12:35', 'greyTxt');
				
						echo close_div();
				
						// Notification image
						$img_properties			= array
						(
								'src'			=> $this->scrap_web->get_profile_image(),
								'width'			=> '40',
								'class'			=> 'profileImage'
						);
						echo img($img_properties);
						
					// End of notification line
					echo close_div();
				
				// End of inset
				echo close_div();
				
				// New divider
				if($loop_cnt == 6)
				{
					echo div_height(40);
				}
			
			echo '</a>';
		}

		// Load More
		echo open_div('btnLoadMoreContainer');
		
			echo make_button('More Notifications', 'btnLoadMore');
			
		echo close_div();
	
	// End of left column
	echo close_div();
	
	// Clear float
	echo clear_float();
	
	// Some height
	echo div_height(20);

// End of middle div
echo close_div();
?>