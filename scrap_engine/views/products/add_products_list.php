<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data
$loop_cnt       = 1;

// Table heading
$this->table->set_heading('', 'Product Name', array('data' => 'Product Fields', 'class' => 'fullCell'), 'Stock Units', 'Price', '');

// Rows
while($loop_cnt <= 5)
{
	// Profile image
	$img_properties		= array
	(
		'src'			=> $this->scrap_web->get_profile_image(100000000000000),
		'height'		=> '35',
		'class'			=> 'profileImage'
	);

	// Units input
	$inp_units			= array
	(
		'name'			=> 'inpUnits',
		'class'         => 'inpUnits'
	);

	// Price input
	$inp_price			= array
	(
		'name'			=> 'inpPrice',
		'class'         => 'inpPrice'
	);

	// Table row
	$this->table->add_row(img($img_properties), anchor('products/view/'.$loop_cnt, 'Product Name '.$loop_cnt), array('data' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce condimentum ipsum dolor sit amet, consectetur.', 'class' => 'fullCell greyTxt'), form_input($inp_units), form_input($inp_price), make_button('Add', 'btnAddProduct'));
	$loop_cnt++;
}

// Generate table
echo $this->table->generate();
?>