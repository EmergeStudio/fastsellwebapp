<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
echo open_div('inset');

	echo form_open_multipart('fastsells/customer_master_data_upload', 'class="frmCustomerMasterDataUpload"');

		$inp_data		= array
		(
			'name'		=> 'uploadedFile',
			'class'		=> 'uploadedFile'
		);
		echo form_upload($inp_data);
		echo clear_float();

	echo form_close();

echo close_div();
?>