<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($products['error'] == FALSE)
{
	// Data
	$json_products          = $products['result'];

	// Table heading
	$this->table->set_heading('', 'Product Name', array('data' => 'Product Fields', 'class' => 'fullCell'), 'Stock', 'Price', '');

	// Rows
	foreach($json_products->catalog_items as $product)
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

		// Product name
		$product_name       = $product->catalog_item_field_values[0]->value;

		// Product fields
		$loop_cnt               = 0;
		$product_fields         = '';
		foreach($product->catalog_item_field_values as $product_field)
		{
			$loop_cnt++;
			if($loop_cnt > 1)
			{
				if($product_field->value != 'NOT_SET')
				{
					$product_fields     .= $product_field->value.', ';
				}
			}
		}
		$product_fields         = $this->scrap_string->remove_lc(trim($product_fields));

		// Table row
		$this->table->add_row(img($img_properties), anchor('products/view/'.$product->id, $product_name), array('data' => $product_fields, 'class' => 'fullCell greyTxt'), form_input($inp_units), form_input($inp_price), make_button('Add', 'btnAddProduct').hidden_div($product->id, 'hdProductId'));
	}

	// Generate table
	echo $this->table->generate();
}
?>