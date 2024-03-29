<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Check box properties all
$all_check_box                  = '';
if($crt_order->order_state->id == 1)
{
	$checkbox_remove_all        = array
	(
		'name'                  => 'checkRemoveAll',
		'class'                 => 'checkRemoveAll tooltip',
		'checked'               => FALSE,
		'value'                 => 'removeAll'
	);
	$all_check_box              = form_checkbox($checkbox_remove_all);
}

// Table heading
$this->table->set_heading($all_check_box, '', array('data' => 'Product', 'class' => 'leftText'), array('class' => 'fullCell'), 'Quantity', 'Price', 'Total');

// Loop through
$grand_total                = 0;
foreach($crt_order->fastsell_order_to_items as $order_item)
{
	// Some variables
	$ar_fields              = array();
	$product_name           = '';
	$product_fields         = '';

	// Get the product
	$url_product            = 'fastsellitems/.json?id='.$order_item->fastsell_item->id;
	$call_product           = $this->scrap_web->webserv_call($url_product, FALSE, 'get', FALSE, FALSE);
	$json_product           = $call_product['result'];

	// Check box properties
	if($crt_order->order_state->id == 1)
	{
		$checkbox_remove_product   = array
		(
			'name'                  => 'checkRemoveProduct',
			'class'                 => 'checkRemoveProduct tooltip',
			'checked'               => FALSE,
			'value'                 => $order_item->id
		);
		array_push($ar_fields, form_checkbox($checkbox_remove_product));
	}
	else
	{
		array_push($ar_fields, '');
	}

	// Product image
//	$img_properties         = array
//	(
//		'src'               => 'scrap_assets/images/universal/default_product_image.jpg',
//		'width'             => 50
//	);

	// Get the image
	$src                    = 'scrap_assets/images/universal/default_product_image.jpg';
	$url_product_image      = 'serverlocalfiles/.jsons?path=scrap_products%2F'.$json_product->catalog_item->id.'%2Fimage';
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

	$img_properties         = array
	(
		'src'               => $src,
		'width'             => 50
	);
	array_push($ar_fields, img($img_properties));

	$loop_cnt               = 0;

	// Build the information array
	$ar_information         = array();
	foreach($json_product->catalog_item->catalog_item_field_values as $product_field)
	{
		$defintion_field_id                     = $product_field->catalog_item_definition_field->id;
		$defintion_field_name                   = $product_field->catalog_item_definition_field->field_name;
		$product_value                          = $product_field->value;
		$product_id                             = $product_field->id;

		$ar_information[$defintion_field_id]    = array($defintion_field_name, $product_value, $product_id);
	}
	ksort($ar_information);

	foreach($ar_information as $key => $value)
	{
		$loop_cnt++;
		if($loop_cnt == 1)
		{
			$product_name   = $value[1];
		}
		elseif($loop_cnt == 2)
		{
			$product_fields .= $value[1].div_height(5);
		}
		else
		{
			if($value[1] != 'NOT_SET')
			{
				$product_fields .= '<b>'.$value[0].': </b>'.$value[1].', ';
			}
		}
	}
	$product_fields         = $this->scrap_string->remove_lc(trim($product_fields));

	// Product name
	array_push($ar_fields, array('data' => $product_name, 'class' => 'leftText'));

	// Product fields
	array_push($ar_fields, array('data' => $product_fields, 'class' => 'fullCell'));

	// Quantity
	array_push($ar_fields, $order_item->quantity);

	// Price
	$quantity               = $order_item->quantity;
	$price                  = $order_item->fastsell_item->price;
	$crt_total              = number_format($quantity * $price, 2);
	$grand_total            = $grand_total + ($quantity * $price);

	array_push($ar_fields, array('data' => '$'.$price, 'class' => 'productPrice'));
	array_push($ar_fields, array('data' => '$'.$crt_total,'class' => 'productPrice'));

	// Table row
	$this->table->add_row($ar_fields);
}

// Table row
$remove_selected            = '';
if($crt_order->order_state->id == 1)
{
	$remove_selected        = make_link('Remove Selected', 'linkRemove');
}
$this->table->add_row(array('data' => $remove_selected, 'colspan' => '2'), array('data' => '<b>Grand Total:</b>', 'class' => 'rightText', 'colspan' => '3'), array('data' => '$'.number_format($grand_total, 2), 'class' => 'productPrice leftText', 'colspan' => '2'));

// Generate table
echo $this->table->generate();

// Delete form
echo form_open('fastsells/delete_product_2', 'class="frmOrderDeleteProduct"');

	echo form_hidden('hdOrderId', $crt_order->id);
	echo form_hidden('hdReturnUrl', current_url());
	echo form_hidden('hdOrderItemIdSelect', '');

echo form_close();
?>