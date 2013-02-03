<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($products['error'] == FALSE)
{
	// Data
	$json_products          = $products['result'];

	// Table heading
	$this->table->set_heading('', 'Product Name', array('data' => 'Product Fields', 'class' => 'fullCell'), 'Stock Count', 'Price', '');

	// Rows
	foreach($json_products->catalog_items as $product)
	{
		// Get the image
		$src                    = 'scrap_assets/images/universal/default_product_image.jpg';
		$url_product_image      = 'serverlocalfiles/.jsons?path=scrap_products%2F'.$product->id.'%2Fimage';
		$call_product_image     = $this->scrap_web->webserv_call($url_product_image, FALSE, 'get', FALSE, FALSE);
		if($call_product_image['error'] == FALSE)
		{
			$json_product_image         = $call_product_image['result'];
			if($json_product_image->is_empty == FALSE)
			{
				$image_path             = $json_product_image->server_local_files[0]->path;
				$src                    = $this->scrap_web->image_call('serverlocalfiles/file?path='.$image_path);
			}
		}
		$img_properties		= array
		(
			'src'			=> $src,
			'width'		    => '35',
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

		// Product fields
		$loop_cnt               = 0;
		$product_fields         = '';

		foreach($product->catalog_item_field_values as $product_field)
		{
			$defintion_field_id                     = $product_field->catalog_item_definition_field->id;
			$defintion_field_name                   = $product_field->catalog_item_definition_field->field_name;
			$product_value                          = $product_field->value;
			$product_id                             = $product_field->id;

			$ar_information[$defintion_field_id]    = array($defintion_field_name, $product_value, $product_id);
		}
		ksort($ar_information);

		$loop_cnt_1             = 0;

		foreach($ar_information as $key => $value)
		{
			$loop_cnt_1++;

			if($loop_cnt_1 == 1)
			{
				// Product name
				$product_name       = $value[1];
			}
			else
			{
				$product_fields     .= $value[1].', ';
			}
		}
		$product_fields         = $this->scrap_string->remove_lc(trim($product_fields));

		// Table row
//		$this->table->add_row(img($img_properties), anchor('products/view/'.$product->id, $product_name), array('data' => $product_fields, 'class' => 'fullCell greyTxt'), form_input($inp_units), form_input($inp_price), make_button('Add', 'btnAddProduct').hidden_div($product->id, 'hdProductId'));
		$this->table->add_row(img($img_properties), $product_name, array('data' => $product_fields, 'class' => 'fullCell greyTxt'), form_input($inp_units), form_input($inp_price), make_button('Add', 'btnAddProduct').hidden_div($product->id, 'hdProductId'));
	}

	// Generate table
	echo $this->table->generate();
}
?>