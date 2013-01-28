<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// HTML
if($order != FALSE)
{
	// Open middle div
	echo open_div('middle');

		// White back
		echo open_div('whiteBack coolScreen singleColumn');

			// Heading
	        echo heading('Terms And Conditions', 2);

			// Textarea
			$inp_data		= array
			(
				'name'		=> 'inpTermsAndConditions',
				'class'		=> 'inpTermsAndConditions',
				'value'     => $crt_order->fastsell_event->terms_and_conditions
			);
			echo form_textarea($inp_data);

			// Accept
			echo div_height(30);
			echo make_button('I Accept These Terms And Conditions', 'blueButton', 'fastsells/complete_checkout/'.$crt_order->id, 'right');
			echo clear_float();

		// End of white back
		echo close_div();

	// End of middle div
	echo close_div();
}
?>