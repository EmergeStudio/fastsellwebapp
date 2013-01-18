<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($products['error'] == FALSE)
{
	// Data
	$json_products          = $products['result'];

	// Table heading
	$this->table->set_heading('', full_div('Product Name', 'leftText'), array('data' => '', 'class' => 'fullCell'), '', 'Stock', 'Price', '');

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
		$product_fields         = '';
		$msrp                   = '';
		foreach($ar_information as $product_field)
		{
			$loop_cnt++;
			if($loop_cnt == 2)
			{
				if($product_field[1] != 'NOT_SET')
				{
					$product_fields     .= '<b>'.$product_field[0].':</b> '.$product_field[1];
					$product_fields     .= div_height(5);
				}
			}
			elseif($loop_cnt > 2)
			{
				if($product_field[1] != 'NOT_SET')
				{
					if($product_field[0] != 'MSRP')
					{
						$product_fields     .= '<b>'.$product_field[0].':</b> '.$product_field[1].', ';
					}
					else
					{
						$msrp           = $product_field[0].': '.full_span('$'.number_format($product_field[1], 2), 'greenTxt');
					}
				}
			}
			else
			{
				$product_name   = $product_field[1];
			}
		}
		$product_fields         = $this->scrap_string->remove_lc(trim($product_fields));

		// Table row
		$this->table->add_row
		(
			img($img_properties),
			full_div($product_name, 'leftText'),
			array('data' => $product_fields, 'class' => 'fullCell'),
			$msrp,
			$inp_units,
			$inp_price,
			full_div(full_div('', 'btnRemoveProduct icon-cross', 'Remove This Product From The FastSell').hidden_div($product->id, 'hdProductId'), 'extraOptions')
		);
	}

	// Generate table
	echo $this->table->generate();
}
?>