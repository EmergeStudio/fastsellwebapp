<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Check that we have customers
if($products['error'] == FALSE)
{
	// Data
	$json_products              = $products['result'];

	// Loop through products
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

		// An item container
		echo open_div('itemContainer small');

			// Table
			echo '<table><tr>';

				// Banner image
				echo '<td>';

					$img_properties         = array
					(
						'src'               => 'scrap_assets/images/universal/default_product_image.jpg',
						'width'             => 60
					);

					echo full_div(img($img_properties), 'inset');

				echo '</td>';

				// Product details
				echo '<td class="productDetails">';

					// Basic details
					echo div_height(8);
					$loop_cnt_1             = 0;
					$msrp                   = FALSE;
					foreach($ar_information as $key => $value)
					{
						$loop_cnt_1++;
						if($loop_cnt_1 == 1)
						{
							echo heading($value[1], 5);
						}
						elseif($loop_cnt_1 == 2)
						{
							echo full_div($value[1], 'greyTxt');
							echo div_height(6);
						}
						else
						{
							if($value[0] == 'MSRP')
							{
								$msrp       = $value[1];
								echo full_div('<b>'.$value[0].':</b> '.full_span('$'.$value[1], 'greenTxt'), 'extraValue');
							}
							else
							{
								echo full_div('<b>'.$value[0].':</b> '.$value[1], 'extraValue');
							}
						}
					}

				echo '</td>';

				// Current Price & Discount
				echo '<td>';

					echo open_div('priceDiscountStock');

						echo full_div('$'.$product->price, 'price');

						if($msrp != FALSE)
						{
							$discount           = round($product->price / $msrp, 2);
							$discount           = 100 - ($discount * 100);
							echo full_div($discount.'% off', 'discount');
						}
						echo full_div('('.$product->stock_count.' available)', 'quantity');
						echo clear_float();

					echo close_div();

				echo '</td>';

			echo '</tr></table>';

			// Hidden data
			echo hidden_div($product->id, 'hdProductId');

		echo close_div();
	}
}
?>