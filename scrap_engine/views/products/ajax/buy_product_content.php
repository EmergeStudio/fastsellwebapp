<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data
$json_product               = $product['result'];
$loop_cnt_4         = 0;
foreach($json_product->catalog_item->catalog_item_field_values as $product_field)
{
	$loop_cnt_4++;
	if($loop_cnt_4 == 3)
	{
		$msrp       = $product_field->value;
	}
}

// Form
echo form_open('fastsells/buy_product', 'class="frmBuyProduct"');

	// Right column
	echo open_div('rightColumn');

		// Product information
		echo open_div('inset');

			// Price / discount / stock
			echo open_div('priceDiscountStock');

				echo full_div('$'.$json_product->price, 'price');

				$discount           = round($json_product->price / $msrp, 2);
				$discount           = 100 - ($discount * 100);
				echo full_div($discount.'% off', 'discount');

				echo full_div('('.$json_product->stock_count.' available)', 'stock');

			echo close_div();

			echo '<table class="productInformation">';

				$loop_cnt_1         = 0;
				foreach($json_product->catalog_item->catalog_item_field_values as $product_field)
				{
					$loop_cnt_1++;
					if($loop_cnt_1 == 1)
					{
						echo '<tr>';

							echo '<td class="icon">'.full_div('', 'icon-box').'</td>';
							echo '<td class="value">'.heading($product_field->value, 3).'</td>';

						echo '</tr>';
					}
					elseif($loop_cnt_1 == 2)
					{
						echo '<tr>';

							echo '<td class="icon">'.full_div('', 'icon-clipboard').'</td>';
							echo '<td class="value">';

								echo $product_field->value.div_height(10);

								$loop_cnt_2         = 0;
								foreach($json_product->catalog_item->catalog_item_field_values as $product_field_2)
								{
									$loop_cnt_2++;
									if($loop_cnt_2 > 6)
									{
										echo full_div('<b>'.$product_field_2->catalog_item_definition_field->field_name.':</b> '.$product_field_2->value);
									}
								}

							echo '</td>';

						echo '</tr>';
					}
				}

				echo '<tr>';

					echo '<td class="icon">'.full_div('', 'icon-dollar').'</td>';

						echo '<td class="value">';

							$loop_cnt_3         = 0;
							foreach($json_product->catalog_item->catalog_item_field_values as $product_field)
							{
								$loop_cnt_3++;
								if(($loop_cnt_3 > 2) && ($loop_cnt_3 < 7))
								{
									echo full_div('<b>'.$product_field->catalog_item_definition_field->field_name.':</b> '.$product_field->value);
								}
							}

						echo '</td>';

				echo '</tr>';

				echo '<tr>';

					echo '<td class="icon">'.full_div('', 'icon-shopping-cart').'</td>';

						echo '<td class="value" style="padding-bottom:0px;">';

	                        $ar_input           = array
		                    (
			                    'name'          => 'inpQuantity',
			                    'class'         => 'floatLeft',
			                    'style'         => 'width:150px; margin:0px;'
		                    );
							echo form_label('Quantity');
							echo form_input($ar_input);
							echo full_div('('.$json_product->stock_count.' available)', 'floatLeft quantity');
							echo hidden_div($json_product->stock_count, 'hdStockCount');
							echo form_hidden('hdProductId', $json_product->id);
							echo form_hidden('hdReturnURL', '');
							echo clear_float();

						echo '</td>';

				echo '</tr>';

			echo '</table>';

		echo close_div();

	// End of right column
	echo close_div();

	// Left column
	echo open_div('leftColumn');

		// Add user icon
		$img_properties		    = array
		(
			'src'               => 'scrap_assets/images/universal/default_product_image_medium.jpg',
			'width'             => 170
		);
		echo open_div('inset').img($img_properties).close_div();

	// End of side column
	echo close_div();

	// Clear float
	echo clear_float();

echo form_close();
?>