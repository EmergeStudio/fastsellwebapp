<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($products['error'] == FALSE)
{
	// Data
	$json_products          = $products['result'];

	// Table heading
	$this->table->set_heading('', 'Product Name', array('data' => 'Product Fields', 'class' => 'fullCell'), 'Stock Units', 'Price', '');

	// Rows
	foreach($json_products->fastsell_items as $product)
	{
		// Profile image
		$img_properties		= array
		(
			'src'			=> $this->scrap_web->get_profile_image(100000000000000),
			'height'		=> '35',
			'class'			=> 'profileImage'
		);

		// Units input
		$inp_units			= $product->stock_count;

		// Price input
		$inp_price			= '$'.$product->price;

		// Product name
		$product_name       = $product->catalog_item->catalog_item_field_values[0]->value;

		// Product fields
		$loop_cnt               = 0;
		$product_fields         = '';
		foreach($product->catalog_item->catalog_item_field_values as $product_field)
		{
			$loop_cnt++;
			if($loop_cnt > 1)
			{
				$product_fields     .= $product_field->value.', ';
			}
		}
		$product_fields         = $this->scrap_string->remove_lc(trim($product_fields));

		// Table row
		$this->table->add_row(img($img_properties), anchor('products/view/'.$product->id, $product_name), array('data' => $product_fields, 'class' => 'fullCell greyTxt'), $inp_units, $inp_price, make_button('Remove', 'btnRemoveProduct').hidden_div($product->id, 'hdProductId'));
	}

	// Generate table
	echo $this->table->generate();
}
?>