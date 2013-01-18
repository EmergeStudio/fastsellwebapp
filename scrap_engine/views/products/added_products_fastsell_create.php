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
		// Profile image// Get the image
		$src                    = $this->scrap_web->get_profile_image(100000000000000);
		$url_product_image      = 'serverlocalfiles/.jsons?path=scrap_products%2F'.$product->id.'%2Fimage';
		$call_product_image     = $this->scrap_web->webserv_call($url_product_image, FALSE, 'get', FALSE, FALSE);
		if($call_product_image['error'] == FALSE)
		{
			$json_product_image         = $call_product_image['result'];
			if($json_product_image->is_empty == FALSE)
			{
				$src                    = $json_product_image->server_local_files[0]->path;
			}
		}
		$img_properties		= array
		(
			'src'			=> $src,
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