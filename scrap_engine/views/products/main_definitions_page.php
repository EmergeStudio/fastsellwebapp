<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
echo open_div('middle');

	// White back
	echo open_div('whiteBack coolScreen singleColumn');

		// Control bar
		echo open_div('controlBar');

			// Add definition
			echo make_button('Add Group', 'btnAddDefinition btnAdd blueButton', '', 'right');

			echo open_div('floatLeft');

				echo heading('Your Product Groups', 3);

//				// Basic search
//				echo open_div('basicSearch floatLeft');
//
//					echo form_input('inpDefinitionSearch', '', 'class="floatLeft"');
//					echo make_button('Search', 'btnDefinitionSearch', '', 'left');
//					echo make_button('Reset', '', 'products/definitions', 'left');
//					echo clear_float();
//
//				// End of basic search
//				echo close_div();

			echo close_div();

			// Clear float
			echo clear_float();

		// End of control bar
		echo close_div();

		// Product definitions list
		echo open_div('listContain');

			$this->load->view('products/definitions_list');

		echo close_div();

	// End of white back
	echo close_div();

echo close_div();
?>