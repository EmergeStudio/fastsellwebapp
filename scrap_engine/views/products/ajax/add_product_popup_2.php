<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($definitions['error'] == FALSE)
{
	// Data
	$cnt_defaultFields          = 2;
	$crt_definition             = $crt_definition['result'];

	// Right column
	echo open_div('rightColumn');

		echo open_div('floatLeft spacing');

			// Product details
			echo open_div('inset productDetails');

				$loop_cnt_4           = 0;
				foreach($crt_definition->catalog_item_definition_fields as $definition_field)
				{
					$loop_cnt_4++;
					if($loop_cnt_4 == 1)
					{
						echo open_div('fieldContainerRequired');

							echo form_label('Product Name');
							$inp_data			= array
							(
								'name'			=> 'inpProductName',
								'class'			=> 'inpProductName'
							);
							echo form_input($inp_data);
							echo hidden_div($definition_field->id);

						echo close_div();
					}
					else if($loop_cnt_4 == 2)
					{
						echo open_div('fieldContainerRequired');

							echo form_label('Description');
							$txt_data			= array
							(
								'name'			=> 'inpProductDescription',
								'class'			=> 'inpProductDescription'
							);
							echo form_textarea($txt_data);
							echo hidden_div($definition_field->id);

						echo close_div();
					}
				}

				echo form_label('Product Number');
				$inp_data			= array
				(
					'name'			=> 'inpProductNumber',
					'class'			=> 'inpProductNumber'
				);
				echo form_input($inp_data);

				echo form_label('Stock Count');
				$inp_data			= array
				(
					'name'			=> 'inpProductStock',
					'class'			=> 'inpProductStock inpText50'
				);
				echo form_input($inp_data);

				echo form_label('Price');
				$inp_data			= array
				(
					'name'			=> 'inpProductPrice',
					'class'			=> 'inpProductPrice inpText50'
				);
				echo form_input($inp_data);

			echo close_div();

			// Some height
			echo div_height(25);

			// Added fields
			$loop_cnt_2           = 0;
			foreach($crt_definition->catalog_item_definition_fields as $definition_field)
			{
				$loop_cnt_2++;
			}
			if($loop_cnt_2 > $cnt_defaultFields)
			{
				echo open_div('inset addedFields');

					$loop_cnt_1           = 0;
			        foreach($crt_definition->catalog_item_definition_fields as $definition_field)
				    {
					    $loop_cnt_1++;
					    if($loop_cnt_1 > $cnt_defaultFields)
					    {
						    echo open_div('fieldContainerExtra');

							    echo form_label($definition_field->field_name);
							    $inp_data			= array
							    (
								    'name'			=> 'inpProductFieldAdded',
								    'class'			=> 'inpProductFieldAdded'
							    );
							    echo form_input($inp_data);
						        echo hidden_div($definition_field->id);

						    echo close_div();
					    }
				    }

				echo close_div();
			}

		echo close_div();

	// End of right column
	echo close_div();


	// Side column
	echo open_div('leftColumn');

		echo open_div('inset');

			// Heading
			echo form_label('Select Your Product Group');
			echo div_height(10);

			// Get the definitions result
			$json_definitions			= $definitions['result'];

			// Loop through and display customer
			foreach($json_definitions->catalog_item_definitions as $definition)
			{
				if($crt_definition->id == $definition->id)
				{
					echo open_div('definitionSelection active');
				}
				else
				{
					echo open_div('definitionSelection');
				}

					echo full_div('', 'icon-clipboard icon');
					echo full_div('', 'icon-checkmark-2 icon');
					echo $definition->name;
					echo hidden_div($definition->id, 'hdDefinitionId');

				echo close_div();
			}

		echo close_div();

		// Product image
		echo div_height(25);
		echo open_div('blockProductImage inset').form_open_multipart('ajax_handler_products/add_product_image_temp', 'class="frmProductImage"');

			echo full_div('', 'icon-camera imagePreview');

			$inp_data		= array
			(
				'name'		=> 'uploadedFileProductImage',
				'class'		=> 'uploadedFileProductImage',
				'accept'    => 'image/*'
			);
			echo form_hidden('hdProductId');
			echo form_upload($inp_data);
			echo clear_float();

		echo form_close().close_div();

	// End of left column
	echo close_div();
}
?>