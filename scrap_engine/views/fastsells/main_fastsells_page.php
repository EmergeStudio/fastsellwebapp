<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
echo open_div('middle');

	// Main heading
	echo heading('Your FastSells', 2, 'class="headingTitle"');

	echo open_div('dropSelectFastSellTypeContainer floatLeft');

		$ar_fastsell_types          = array
		(
			'all'                   => 'All FastSells',
			'current'               => 'Currently Running FastSells',
			'upcoming'              => 'FastSells Coming Up',
			'passed'                => 'Passed FastSells',
			'archived'              => 'Archived FastSells'
		);

		echo form_open('fastsells', 'class="frmFastSellsType"');

			echo form_dropdown('dropFastSellType', $ar_fastsell_types, $crt_fastsell_view);

		echo form_close();

	echo close_div();

	if($this->session->userdata('sv_acc_type') == 'show_host')
	{
		// Create a FastSell
		echo make_button('Create FastSell', 'blueButton btnAdd btnHeading', 'fastsells/create_event');
	}

	// Clear float
	echo clear_float();

	// White back
	echo open_div('whiteBack coolScreen singleColumn');

		// Product definitions list
		echo open_div('listContain');

			echo full_div('', 'countdownTest');

			$this->load->view('fastsells/fastsells_list');

		echo close_div();

	// End of white back
	echo close_div();

echo close_div();
?>