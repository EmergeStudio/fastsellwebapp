<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Check that we have customers
if($products['error'] == FALSE)
{
	// Data
	$json_products                          = $products['result'];

	// Product item
	foreach($json_products->catalog_items as $product)
	{
		// Build the information array
		$ar_information         = array();
		foreach($product->catalog_item_field_values as $product_field)
		{
			$defintion_field_id                     = $product_field->catalog_item_definition_field->id;
			$defintion_field_name                   = $product_field->catalog_item_definition_field->field_name;
			$product_value                          = $product_field->value;
			$product_id                             = $product_field->id;

			$ar_information[$defintion_field_id]    = array($defintion_field_name, $product_value, $product_id);
		}
		ksort($ar_information);

		// An item container
		echo open_div('itemContainer small');

			// Table
			echo '<table><tr>';

				// Banner image
				echo '<td>';

					// Get the image
					$src                    = 'scrap_assets/images/universal/default_product_image.jpg';
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

					$img_properties         = array
					(
						'src'               => $src,
						'width'             => 60
					);

					echo full_div(img($img_properties), 'inset');

				echo '</td>';

				// Product details
				echo '<td class="productDetails">';

					// Basic details
					echo div_height(8);
					$loop_cnt_1             = 0;
					foreach($ar_information as $key => $value)
					{
						$loop_cnt_1++;
						if($loop_cnt_1 == 1)
						{
							echo heading($value[1].' ('.$product->item_number.')', 5);
						}
						elseif($loop_cnt_1 == 2)
						{
							echo full_div($value[1], 'greyTxt');
							echo div_height(6);
						}
						else
						{
							echo full_div('<b>'.$value[0].':</b> '.$value[1], 'extraValue');
						}
					}

					echo clear_float();

				echo '</td>';

			echo '</tr></table>';

			// Product information
			echo open_div('productInformation displayNone');

				// HTML
				$img_properties         = array
				(
					'src'               => $src,
					'width'             => 212
				);

				echo open_div('inset');

					echo img($img_properties);

						echo open_div('blockProductImage2').form_open_multipart('ajax_handler_products/add_product_image_2', 'class="frmProductImage2"');

							$inp_data		= array
							(
								'name'		=> 'uploadedFileProductImage2',
								'class'		=> 'uploadedFileProductImage2'
							);
							echo form_hidden('hdProductId2', $product->id);
							echo form_upload($inp_data);
							echo clear_float();

						echo form_close().close_div();

				echo close_div();
		        echo div_height(10);

				$loop_cnt_2             = 0;
				foreach($ar_information as $key => $value)
				{
					$loop_cnt_2++;
					if($loop_cnt_2 == 2)
					{
						// Description
						echo open_div('fieldContainer');

							$ar_input       = array
							(
								'name'      => 'productField',
								'value'     => $value[1]
							);
							echo form_label($value[0]);
							echo form_textarea($ar_input);
							echo hidden_div($key, 'hdDefinitionFieldId');

						echo close_div();

						// Item number
						$ar_input       = array
						(
							'name'      => 'itemNumber',
							'value'     => $product->item_number
						);
						echo form_label('Product Number');
						echo form_input($ar_input);
					}
					else
					{
						echo open_div('fieldContainer');

							$ar_input       = array
							(
								'name'      => 'productField',
								'value'     => $value[1]
							);
							echo form_label($value[0]);
							echo form_input($ar_input);
							echo hidden_div($key, 'hdDefinitionFieldId');

						echo close_div();
					}
				}

				echo div_height(20);
				echo make_button('Save Changes', 'btnSaveProductChanges blueButton');

				// Hidden data
				echo hidden_div($product->id, 'hdProductId');

			echo close_div();

		echo close_div();
	}
}
else
{
	if($definitions['error'] == FALSE)
	{
		echo full_div('Add A Product', 'messageAddAProduct btnAddProduct');
	}
	else
	{
		echo anchor('products/definitions', 'Create Product Defnition', 'class="messageNoProductDefinitions"');
	}
}
?>