<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
echo open_div('middle');

	// White back
	echo open_div('whiteBack coolScreen singleColumn');

			// Control bar
			echo open_div('controlBar');

				// Add definition
				if($acc_type == 'show_host')
				{
					if($definitions['error'] == FALSE)
					{
						echo make_button('Create FastSell', 'btnAdd blueButton', 'fastsells/create_event', 'right');
					}
				}

				echo open_div('floatLeft');

				echo div_height(32);

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