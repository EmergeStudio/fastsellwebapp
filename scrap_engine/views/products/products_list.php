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
					foreach($product->catalog_item_field_values as $catalogue_fields)
					{
						$loop_cnt_1++;
						if($loop_cnt_1 == 1)
						{
							echo heading($catalogue_fields->value.' ('.$product->item_number.')', 5);
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
					foreach($product->catalog_item_field_values as $catalogue_fields)
					{
						$loop_cnt_2++;
						if($loop_cnt_2 == 1)
						{
							echo div_height(6);
						}
						if($loop_cnt_2 > 6)
						{
							//echo full_div('<b>'.$catalogue_fields->catalog_item_definition_field->field_name.':</b> '.$catalogue_fields->value, 'extraValue');
							echo full_div('<b>Field Name:</b> '.$catalogue_fields->value, 'extraValue');
						}
					}
					echo clear_float();

				echo '</td>';

				// Stock & MSRP
				echo '<td class="msrp">';

					$loop_cnt_3             = 0;
					foreach($product->catalog_item_field_values as $catalogue_fields)
					{
						if($loop_cnt_3 == 1)
						{
							echo div_height(3);
						}
						$loop_cnt_3++;
						if($loop_cnt_3 == 3)
						{
							//echo full_div('<b>'.$catalogue_fields->catalog_item_definition_field->field_name.'</b>');
							echo full_div('<b>Field Name</b>');
							echo full_div('$'.$catalogue_fields->value);
						}
						elseif($loop_cnt_3 == 4)
						{
							echo div_height(5);
							//echo full_div('<b>'.$catalogue_fields->catalog_item_definition_field->field_name.'</b>');
							echo full_div('<b>Field Name</b>');
							echo full_div($catalogue_fields->value);
						}
					}

				echo '</td>';

			echo '</tr></table>';

			// Product information
			echo open_div('productInformation displayNone');

				// Get product info
				$url_product            = 'catalogitems/.json?id='.$product->id;
				$call_product           = $this->scrap_web->webserv_call($url_product, FALSE, 'get', FALSE, FALSE);
				$json_product           = $call_product['result'];

				// HTML
				$img_properties         = array
				(
					'src'               => 'scrap_assets/images/universal/default_product_image_medium.jpg',
					'width'             => 212
				);

				echo full_div(img($img_properties), 'inset');

				$loop_cnt_1             = 0;
				foreach($json_product->catalog_item_field_values as $catalogue_fields)
				{
					$loop_cnt_1++;
					if($loop_cnt_1 == 1)
					{
						// Name
						echo open_div('fieldContainer');

							$ar_input       = array
							(
								'name'      => 'productField',
								'value'     => $catalogue_fields->value
							);
							echo form_label('Product Name');
							echo form_input($ar_input);
							echo hidden_div($catalogue_fields->catalog_item_definition_field->id, 'hdDefinitionFieldId');

						echo close_div();
					}
					elseif($loop_cnt_1 == 2)
					{
						// Description
						echo open_div('fieldContainer');

							$ar_input       = array
							(
								'name'      => 'productField',
								'value'     => $catalogue_fields->value
							);
							echo form_label('Product Description');
							echo form_textarea($ar_input);
							echo hidden_div($catalogue_fields->catalog_item_definition_field->id, 'hdDefinitionFieldId');

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
						break;
					}
				}

				// Extra fields
				$loop_cnt_2             = 0;
				foreach($json_product->catalog_item_field_values as $catalogue_fields)
				{
					$loop_cnt_2++;

					if($loop_cnt_2 > 6)
					{
						echo open_div('fieldContainer');

							$ar_input       = array
							(
								'name'      => 'productField',
								'value'     => $catalogue_fields->value
							);
							echo form_input($ar_input);
							echo hidden_div($catalogue_fields->catalog_item_definition_field->id, 'hdDefinitionFieldId');

						echo close_div();
					}
				}

				// Stock and MSRP
				$loop_cnt_3             = 0;
				foreach($json_product->catalog_item_field_values as $catalogue_fields)
				{
					$loop_cnt_3++;
					if($loop_cnt_3 == 3)
					{
						// MSRP
						echo open_div('fieldContainer');

							$ar_input       = array
							(
								'name'      => 'productField',
								'value'     => $catalogue_fields->value
							);
							echo form_label('MSRP');
							echo form_input($ar_input);
							echo hidden_div($catalogue_fields->catalog_item_definition_field->id, 'hdDefinitionFieldId');

						echo close_div();
					}
					elseif($loop_cnt_3 == 4)
					{
						// Pack & Size
						echo open_div('fieldContainer');

							$ar_input       = array
							(
								'name'      => 'productField',
								'value'     => $catalogue_fields->value
							);
							echo form_label('Pack & Size');
							echo form_input($ar_input);
							echo hidden_div($catalogue_fields->catalog_item_definition_field->id, 'hdDefinitionFieldId');

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