<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($item_defs['error'] == FALSE)
{
	echo form_open_multipart('ajax_handler_products/products_master_data_upload', 'class="frmProductsMasterDataUpload"');

		echo open_div('inset');

			$inp_data		= array
			(
				'name'		=> 'uploadedFile',
				'class'		=> 'uploadedFile'
			);
			echo form_upload($inp_data);
			echo clear_float();

		echo close_div();

		echo div_height(25);

		echo open_div('inset');

			// Heading
			echo heading('Choose Your Product Group', 3);

			// Content
			echo '<p>You can only upload a master file against one product definition at a time.  Please select your definition below in order to proceed.</p>';

			$json_item_defs             = $item_defs['result'];
			$ar_item_definitions        = array();
			foreach($json_item_defs->fastsell_item_definitions as $item_definition)
			{
				$ex_item_definition     = explode('_', $item_definition->name);
				$ar_item_definitions[$item_definition->id]      = $ex_item_definition[2];
			}
			echo form_dropdown('dropItemDefinitions', $ar_item_definitions);

		echo close_div();

	echo form_close();
}
else
{
	// Heading
	echo heading('There Are Not Product Group', 3);

	// Content
	echo '<p>At least one product group is required.</p>';
	echo make_button('Click Here To Add One', '', 'products/definitions');
}
?>