<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
echo open_div('middle');

	// Main heading
	echo heading('Product Templates', 2, 'class="headingTitle"');

	// Add definition
	echo make_button('Add Template', 'btnAddDefinition btnAdd blueButton btnHeading', '', 'left');

	// Clear float
	echo clear_float();

	// White back
	echo open_div('whiteBack coolScreen singleColumn');

		// Product definitions list
		echo open_div('listContain');

			$this->load->view('products/definitions_list');

		echo close_div();

	// End of white back
	echo close_div();

echo close_div();
?>