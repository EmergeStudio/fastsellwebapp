<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle');

	// Content
	echo open_div('whiteBack coolScreen singleColumn');

		// Control bar
		echo open_div('controlBar');

			// Date selection
			echo open_div('floatRight');

				echo full_div('From Date', 'labelText floatLeft');
				echo form_input('inpFromDate', '', 'class="floatLeft scrap_date"');
				echo full_div('To Date', 'labelText floatLeft');
				echo form_input('inpToDate', '', 'class="floatLeft scrap_date"');
				echo make_button('Run Report', 'btnRunReport blueButton', '', 'left');
				echo make_button('<span class="icon-download"></span>Download Report', 'btnDownloadReport blueButton', '', 'left', '', FALSE);
				echo clear_float();

			echo close_div();

			// Heading
			echo heading('<span class="icon-bars blue"></span>'.$header_text, 3);

		// End of control bar
		echo close_div();

		// Fastsell list
		echo open_div('listContain');

			echo open_div('ajaxReportContainer messageSelectDateRange');

			echo close_div();

		echo close_div();

	// End of content
	echo close_div();

// End of middle div
echo close_div();
?>