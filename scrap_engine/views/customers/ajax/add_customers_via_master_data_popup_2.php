<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
echo open_div('inset');

	echo form_open_multipart('ajax_handler_fastsells/customer_master_data_upload', 'class="frmCustomerMasterDataUpload"');

		$inp_data		= array
		(
			'name'		=> 'uploadedFile',
			'class'		=> 'uploadedFile'
		);
		echo form_upload($inp_data);
		echo clear_float();

		echo div_height(15);
		echo make_button('Download Example File', 'btnDownload btnDownloadExcelFile blueButton', 'scrap_assets/files/examples/customer_upload.xlsx');

	echo form_close();

echo close_div();
?>