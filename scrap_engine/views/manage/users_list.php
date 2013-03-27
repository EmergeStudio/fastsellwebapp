<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($users['error'] == FALSE)
{
	// Data
	$json_users             = $users['result'];

	// Table heading
	$this->table->set_heading('', full_div('Product Name', 'leftText'), array('data' => '', 'class' => 'fullCell'), '', 'Stock Count', 'Price', '');

}
?>