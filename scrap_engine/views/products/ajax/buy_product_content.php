<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($address['error'] == FALSE)
{
	// Data
	$json_product               = $product['result'];

	// Build the information array
	$ar_information         = array();
	$msrp                   = FALSE;
	foreach($json_product->catalog_item->catalog_item_field_values as $product_field)
	{
		$defintion_field_id                     = $product_field->catalog_item_definition_field->id;
		$defintion_field_name                   = $product_field->catalog_item_definition_field->field_name;
		$product_value                          = $product_field->value;
		$product_id                             = $product_field->id;

		$ar_information[$defintion_field_id]    = array($defintion_field_name, $product_value, $product_id);

		if($defintion_field_name == 'MSRP')
		{
			$msrp                               = $product_value;
		}
	}
	ksort($ar_information);

	// Form
	echo form_open('fastsells/buy_product', 'class="frmBuyProduct"');

		// Right column
		echo open_div('rightColumn');

			// Product information
			echo open_div('inset');

				// Price / discount / stock
				echo open_div('priceDiscountStock');

					echo full_div('$'.$json_product->price, 'price');

						if($msrp != FALSE)
						{
							$discount           = round($json_product->price / $msrp, 2);
							$discount           = 100 - ($discount * 100);
							echo full_div($discount.'% off', 'discount');
						}

					echo full_div('('.$json_product->stock_count.' available)', 'stock');

				echo close_div();

				echo '<table class="productInformation">';

					$loop_cnt_1         = 0;
					foreach($ar_information as $key => $value)
					{
						$loop_cnt_1++;
						if($loop_cnt_1 == 1)
						{
							echo '<tr>';

								echo '<td class="icon">'.full_div('', 'icon-box').'</td>';
								echo '<td class="value">'.heading($value[1], 3).'</td>';

							echo '</tr>';
						}
					}

					echo '<tr>';

						echo '<td class="icon">'.full_div('', 'icon-clipboard').'</td>';
						echo '<td class="value">';

							$loop_cnt_2         = 0;
							foreach($ar_information as $key => $value)
							{
								$loop_cnt_2++;
								if($loop_cnt_2 > 1)
								{
									if($loop_cnt_2 == 2)
									{
										echo full_div('<b>'.$value[0].':</b>');
										echo full_div($value[1]);
										echo div_height(10);
									}
									else
									{
										echo full_div('<b>'.$value[0].':</b> '.$value[1]);
									}
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
				'width'             => 170
			);
			echo open_div('inset').img($img_properties).close_div();

		// End of side column
		echo close_div();

		// Clear float
		echo clear_float();

	echo form_close();
}
else
{
	// Heading
	echo heading('Please Edit Your Delivery Address', 3);

	// Content
	echo '<p>An address is required in order to place an order.  Click the button below to go do it quickly.</p>';
	echo div_height(10);
	echo make_button('Click Here To Edit Address', '', 'dashboard');
}
?>