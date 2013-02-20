<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data
$json_buying_prefs          = $buying_prefs['result'];

// Open middle div
echo open_div('middle');

	// Current orders
	echo open_div('whiteBack');

		// Fastsell categories
		echo open_div('inset fastsellCategories');

			// Message
			echo full_div('', 'messageBuyingPreferences');

			// Heading
			echo full_div('FastSell Categories', 'mainHeading');

			// Text
			echo '<p>Start typing in the textbox below and find your desired category.</p>';

			$inp_data		= array
			(
				'name'		=> 'inpCategorySearch',
				'class'		=> 'inpCategorySearch inpBigText'
			);
			echo form_input($inp_data);

		echo close_div();

		// Ajax container
		echo div_height(20);
		echo open_div('ajax_fastSellCategories');

			foreach($json_buying_prefs->fastsell_item_categories as $item_category)
			{
				// Get the category
				$url_fs_cat                 = 'fastsellitemcategories/.jsons?categorytext='.urlencode($item_category->category).'&includerelationships=true';
				$call_fs_cat                = $this->scrap_web->webserv_call($url_fs_cat, FALSE, 'get', FALSE, FALSE);
				$dt_inner_body['category']  = $call_fs_cat;

				echo open_div('catBack blue');

					$this->load->view('fastsells/category_breadcrumbs', $dt_inner_body);

				echo close_div();
			}

			// Hidden data
			echo form_hidden('hdFastSellCategories', '');

		echo close_div();

		echo div_height(30);
        echo make_button('Save Changes', 'btnSaveChanges blueButton', '', 'left');
		echo make_button('Cancel Changes', '', 'buying_preferences', '', 'left');
		echo clear_float();

		// Save form
		echo form_open('buying_preferences/save_changes', 'class="frmSaveBuyingPreferenceChanges"');

			echo form_hidden('hdCategories', '');

		echo form_close();

	echo close_div();

// End of middle div
echo close_div();
?>