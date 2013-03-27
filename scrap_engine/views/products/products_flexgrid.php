<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Edit container 2
echo open_div('scrapEdit2');

	// Edit arrow
	echo open_div('arrowContainer');

		echo full_div('', 'arrow');

	echo close_div();

	echo open_div('editContainer');

		echo form_open_multipart('ajax_handler_products/add_product_image_2', 'class="frmProductImage2"');

			$upload_inp	 	= array
			(
				'name'  	=> 'uploadedFileProductImage2',
				'class'		=> 'uploadedFileProductImage2'
			);
			echo form_upload($upload_inp);
			echo form_hidden('hdProductId2', 17);

			echo make_button('Save', 'btnSave blueButton', '', 'left');
			echo make_button('Cancel', 'btnCancel', '', 'left');

		echo form_close();

		echo clear_float();

	echo close_div();

echo close_div();

// Middle content
echo open_div('middle');

	// Right content
	echo open_div('rightContentLarge');

		// Basic search
		echo open_div('basicSearch floatRight');

			echo form_open(current_url(), 'class="frmSearch"');

				if($products['error'] == FALSE)
				{
					// Data
					$json_products                          = $products['result'];

					$crt_page               = ($offset / $limit) + 1;
					$max_page               = floor($json_products->no_limit_count / $limit) + 1;
				}

				echo form_input('inpSearchText', str_replace('%20', ' ', $search_text), 'class="floatLeft" placeholder="search for a product"');
				echo make_button('Reset', '', current_url(), 'left');
				echo form_hidden('hdOffset', $offset);
				echo form_hidden('hdLimit', $limit);
				echo form_hidden('scrap_pageNo', $crt_page);
				echo form_hidden('scrap_pageMax', $max_page);
				echo clear_float();

			echo form_close();

		// End of basic search
		echo close_div();

		// Main heading
		echo heading('My Products', 2, 'class="headingTitle"');

		// Add product
		if($definitions['error'] == FALSE)
		{
			echo make_button('Add A Product', 'btnAddProduct btnAdd blueButton btnHeading', '', 'left');
		}
		else
		{
			echo make_button('Go To Product Groups', 'blueButton btnHeading', 'products/definitions', 'left');
		}

		// Edit toggle
		echo open_div('editToggle');

			echo form_checkbox('chkboxEdit', 'editStuff', FALSE, 'class="chkboxEdit switch"');

		echo close_div();

		// Clear float
		echo clear_float();

		// White back
		echo open_div('whiteBack singleColumn noPadding');

			// Check that we have customers
			if($products['error'] == FALSE)
			{
				// Data
				$json_products                          = $products['result'];
				$json_definitions                       = $definitions['result'];
				$loop_cnt_2                             = 0;

				// Open table
				echo '<table id="flex1">';

					// Product item
					foreach($json_products->catalog_items as $product)
					{
						$loop_cnt_2++;

						// Build the information array
						$ar_information                             = array();
						foreach($product->catalog_item_field_values as $product_field)
						{
							$defintion_field_id                     = $product_field->catalog_item_definition_field->id;
							$defintion_field_name                   = $product_field->catalog_item_definition_field->field_name;
							$product_value                          = $product_field->value;
							$product_id                             = $product_field->id;

							$ar_information[$defintion_field_id]    = array($defintion_field_name, $product_value, $product_id);
						}
						ksort($ar_information);

						// Build the definition
						$ar_definitions                             = array();
						foreach($json_definitions->catalog_item_definitions as $definition)
						{
							$ar_fields                              = array();
							foreach($definition->catalog_item_definition_fields as $field)
							{
								$ar_fields[$field->id]              = $field->field_name;
							}
							$ar_definitions[$definition->id]        = $ar_fields;
						}

						echo '<tr>';

							// ID
							echo '<td>';

								echo $product->id;

							echo '</td>';

							// Banner image
							echo '<td class="editIt_image">';

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

								$img_properties         = array
								(
									'src'               => $src,
									'width'             => 50
								);

								echo img($img_properties);

							echo '</td>';

							// Product details
							$loop_cnt_1             = 0;
							$first_id               = 0;

							foreach($ar_information as $key => $value)
							{
								$loop_cnt_1++;

								if($loop_cnt_1 == 1)
								{
									$first_id       = $key;
									echo '<td id="'.$product->id.'_'.$value[2].'_edit" class="editIt">'.$value[1].'</td><td id="'.$product->id.'_productNumber" class="editIt">'.$product->item_number.'</td>';
								}
								elseif($key == ($first_id + 1))
								{
									echo '<td id="'.$product->id.'_'.$value[2].'_edit" class="editIt">'.$value[1].'</td>';
								}
							}

							foreach($ar_information as $key => $value)
							{
								if($key > ($first_id + 1))
								{
									echo '<td id="'.$product->id.'_'.$value[2].'_edit" class="editIt">'.$value[1].'</td>';
								}
							}

						echo '</tr>';

						if($loop_cnt_2 == 1)
						{
							// Current definition
							$url_catalog_item_info      = 'catalogitems/.json?id='.$product->id;
							$call_catalog_item_info     = $this->scrap_web->webserv_call($url_catalog_item_info);
							$json_catalog_item_info     = $call_catalog_item_info['result'];
						}
					}

				echo '</table>';

				$this_definition                        = $ar_definitions[$json_catalog_item_info->catalog_item_definition->id];
				$field_headings                         = '';
				$loop_cnt_3                             = 0;
				foreach($this_definition as $key => $value)
				{
					$loop_cnt_3++;
					if($loop_cnt_3 > 2)
					{
						$field_headings                 .= '['.$value.']';
					}
				}

				echo hidden_div($this->scrap_string->remove_flc($field_headings), 'hdFieldHeadings');
			}

		// End of white back
		echo close_div();

	// End of right content
	echo close_div();

	// Left content
	echo open_div('leftContentSmall');

		// Main heading
		echo heading('Templates', 2, 'class="headingTitle"');

		// Add group
		echo make_button('Manage', 'blueButton btnHeading', 'products/definitions');

		// Clear float
		echo clear_float();

		echo open_div('whiteBack noPadding');

		if($this->uri->segment(2) != 'by_definition')
		{
			echo anchor('products', 'All Products', 'class="firstLink active"');
		}
		else
		{
			echo anchor('products', 'All Products', 'class="firstLink"');
		}

		if($definitions['error'] == FALSE)
		{
			$json_definitions               = $definitions['result'];
			foreach($json_definitions->catalog_item_definitions as $definition)
			{
				if(($this->uri->segment(2) == 'by_definition') && ($this->uri->segment(3) == $definition->id))
				{
					$active                 = 'class="active"';
				}
				else
				{
					$active                 = '';
				}

				echo open_div('groupContainer');

				echo anchor('products/by_definition/'.$definition->id, $definition->name, $active);
				echo hidden_div($definition->id, 'hdDefinitionId');

				echo close_div();
			}
		}

		echo close_div();

	// End of left content
	echo close_div();

	// Clear float
	echo clear_float();

echo close_div();
?>