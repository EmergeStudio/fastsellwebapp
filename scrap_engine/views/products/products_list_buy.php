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
					foreach($product->catalog_item->catalog_item_field_values as $catalogue_fields)
					{
						$loop_cnt_1++;
						if($loop_cnt_1 == 1)
						{
							echo heading($catalogue_fields->value, 4);
						}
						elseif($loop_cnt_1 == 2)
						{
							echo full_div($catalogue_fields->value, 'greyTxt');
						}
						else
						{
							break;
						}
					}

					// Extra fields
					$loop_cnt_2             = 0;
					foreach($product->catalog_item->catalog_item_field_values as $catalogue_fields)
					{
						$loop_cnt_2++;
						if($loop_cnt_2 == 1)
						{
							echo div_height(6);
						}
						if($loop_cnt_2 > 6)
						{
							echo full_div('<b>'.$catalogue_fields->catalog_item_definition_field->field_name.':</b> '.$catalogue_fields->value, 'extraValue');
						}
					}
					echo clear_float();

				echo '</td>';

				// Stock & MSRP
				echo '<td class="msrp">';

					$loop_cnt_3             = 0;
					foreach($product->catalog_item->catalog_item_field_values as $catalogue_fields)
					{
						if($loop_cnt_3 == 1)
						{
							echo div_height(3);
						}
						$loop_cnt_3++;
						if($loop_cnt_3 == 3)
						{
							echo full_div('<b>'.$catalogue_fields->catalog_item_definition_field->field_name.'</b>');
							echo full_div('$'.$catalogue_fields->value);
							$msrp           = $catalogue_fields->value;
						}
						elseif($loop_cnt_3 == 4)
						{
							echo div_height(5);
							echo full_div('<b>'.$catalogue_fields->catalog_item_definition_field->field_name.'</b>');
							echo full_div($catalogue_fields->value);
						}
					}

				echo '</td>';

				// Current Price & Discount
				echo '<td>';


					echo open_div('priceDiscountStock');

						echo full_div('$'.$product->price, 'price');

						$discount           = round($product->price / $msrp, 2);
						$discount           = 100 - ($discount * 100);
						echo full_div($discount.'% off', 'discount');
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