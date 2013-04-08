<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle');

	// Content
	echo open_div('whiteBack coolScreen singleColumn');

		// Control bar
		echo open_div('controlBar');

			$ar_fastsells           = array(0 => '- No FastSell Selected');
			if($fastsells['error'] == FALSE)
			{
				// Some variables
				$json_fastsells         = $fastsells['result'];

				foreach($json_fastsells->fastsell_events as $fastsell)
				{
					// Add to the array
					$ar_fastsells[$fastsell->id]    = $fastsell->name;
				}
			}
			echo open_div('dropDownContainer floatRight');

				echo full_div('Select FastSell', 'labelText floatLeft');
				echo form_dropdown('drpDwnFastSellId', $ar_fastsells);
				echo make_button('<div class="icon-download"></div>Download Report', 'btnDownloadReport blueButton', '', 'left', '', FALSE);

			echo close_div();

			// Heading
			echo heading('<span class="icon-bars blue"></span>'.$header_text, 3);

		// End of control bar
		echo close_div();

		// Fastsell list
		echo open_div('listContain');

			if($fastsells['error'] == FALSE)
			{
				echo open_div('ajaxReportContainer messageSelectFastSell');

				echo close_div();
			}
			else
			{
				$json_error             = $fastsells['result'];
				echo $json_error->description;
			}

		echo close_div();

	// End of content
	echo close_div();

// End of middle div
echo close_div();
?>