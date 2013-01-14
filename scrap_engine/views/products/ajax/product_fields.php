<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($crt_definition['error'] == FALSE)
{
	// Data
	$cnt_defaultFields          = 6;
	$crt_definition             = $crt_definition['result'];

	echo open_div('floatLeft spacing');

		// Product details
		echo open_div('inset productDetails');

			$loop_cnt_4           = 0;
			foreach($crt_definition->catalog_item_definition_fields as $definition_field)
			{
				$loop_cnt_4++;
				if($loop_cnt_4 == 1)
				{
					echo open_div('fieldContainer');

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
					echo open_div('fieldContainer');

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
					echo open_div('fieldContainer');

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

	// Included fields
	echo open_div('inset includedFields');

	$loop_cnt_3           = 0;
	foreach($crt_definition->catalog_item_definition_fields as $definition_field)
	{
		$loop_cnt_3++;
		if(($loop_cnt_3 <= $cnt_defaultFields) && ($loop_cnt_3 > 2))
		{
			echo open_div('fieldContainer');

			echo form_label($definition_field->field_name);

			if(($loop_cnt_3 == $cnt_defaultFields - 1) || ($loop_cnt_3 == $cnt_defaultFields))
			{
				$class          = 'inpProductFieldAdded scrap_date';
			}
			else
			{
				$class          = 'inpProductFieldAdded';
			}

			$inp_data			= array
			(
				'name'			=> 'inpProductFieldAdded',
				'class'			=> $class
			);
			echo form_input($inp_data);
			echo hidden_div($definition_field->id);

			echo close_div();
		}
	}

	echo close_div();
}
?>