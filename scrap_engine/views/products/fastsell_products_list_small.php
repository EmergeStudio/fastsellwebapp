<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($products['error'] == FALSE)
{
	// Data
	$json_products          = $products['result'];

	// Table heading
	$this->table->set_heading('', array('data' => 'Product', 'class' => 'fullCell'), 'Stock Units', 'Price', '');

	// Rows
	foreach($json_products->fastsell_items as $product)
	{
		// Build the information array
		$ar_information         = array();
		foreach($product->catalog_item->catalog_item_field_values as $product_field)
		{
			$defintion_field_id                     = $product_field->catalog_item_definition_field->id;
			$defintion_field_name                   = $product_field->catalog_item_definition_field->field_name;
			$product_value                          = $product_field->value;

			$ar_information[$defintion_field_id]    = array($defintion_field_name, $product_value);
		}
		ksort($ar_information);

		// Profile image
		$img_properties		    = array
		(
			'src'			    => 'scrap_assets/images/universal/default_product_image.jpg',
			'height'		    => '50',
			'class'			    => 'profileImage'
		);

		// Units input
		$inp_units			    = $product->stock_count;

		// Price input
		$inp_price			    = full_span('$'.$product->price, 'greenTxt');

		// Product fields
		$loop_cnt               = 0;
		foreach($ar_information as $product_field)
		{
			$loop_cnt++;
			if($loop_cnt == 1)
			{
				$product_name   = $product_field[1];
			}
		}

		// Table row
		$this->table->add_row
		(
			img($img_properties),
			full_div($product_name, 'leftText'),
			$inp_units,
			$inp_price,
			full_div(full_div('', 'btnRemoveProduct icon-cross', 'Remove This Product From The FastSell').hidden_div($product->id, 'hdProductId'), 'extraOptions')
		);
	}

	// Generate table
	echo $this->table->generate();
}
?>