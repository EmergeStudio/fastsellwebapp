<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
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
		echo heading('Choose Your Item Definition', 3);

		// Content
		echo '<p>You can only upload a master file against one product definition at a time.  Please select your definition below in order to proceed.</p>';

		$json_item_defs             = $item_defs['result'];
		$ar_item_definitions        = array();
		foreach($json_item_defs->catalog_item_definitions as $item_definition)
		{
			$ar_item_definitions[$item_definition->id]      = $item_definition->name;
		}
		echo form_dropdown('dropItemDefinitions', $ar_item_definitions);

	echo close_div();

echo form_close();
?>