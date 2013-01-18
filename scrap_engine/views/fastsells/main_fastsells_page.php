<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
echo open_div('middle');

	// White back
	echo open_div('whiteBack coolScreen singleColumn');

			// Control bar
			echo open_div('controlBar');

				// Add definition
				if($this->scrap_web->get_show_host_id() != FALSE)
				{
					echo make_button('Create FastSell', 'btnAdd blueButton', 'fastsells/create_event', 'right');
				}

				echo open_div('floatLeft');

				echo div_height(32);

//				// Basic search
//				echo open_div('basicSearch floatLeft');
//
//					echo form_input('inpFastSellSearch', '', 'class="floatLeft"');
//					echo make_button('Search', 'btnFastSellSearch', '', 'left');
//					echo make_button('Reset', '', 'fastsells', 'left');
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

				$this->load->view('fastsells/fastsells_list');

			echo close_div();

	// End of white back
	echo close_div();

echo close_div();
?>