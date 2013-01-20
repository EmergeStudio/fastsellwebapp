<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle');

	// Current orders
	echo open_div('whiteBack coolScreen singleColumn');

		// Control bar
		echo open_div('controlBar');

			// Add product
			echo make_button('Upload Master Data File', 'btnUploadDataFile btnAdd blueButton', '', 'right');
			echo make_button('Add More Product', 'btnAddProductPopup btnAdd blueButton', '', 'right');

			echo open_div('floatLeft');

				// Basic search
				echo open_div('basicSearch floatLeft');

					echo form_open('fastsells/products', 'class="frmSearch"');

						echo form_input('inpSearchText', str_replace('%20', ' ', $search_text), 'class="floatLeft"');
						echo make_button('Search', 'btnSearch blueButton', '', 'left');
						echo make_button('Reset', '', 'products', 'left');
						echo clear_float();

					echo form_close();

				// End of basic search
				echo close_div();

			echo close_div();

			// Clear float
			echo clear_float();

		// End of control bar
		echo close_div();

		// Order list - quick
		echo open_div('listContain ajaxProductsInFastSell');

			$this->load->view('products/fastsell_products_list');

		echo close_div();

	echo close_div();

// End of middle div
echo close_div();

// Some hidden data
echo hidden_div($this->session->userdata('sv_show_set'), 'hdEventId');
?>