<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Edit popup
echo open_div('scrapEdit2');

	// Edit arrow
	echo open_div('arrowContainer');

		echo full_div('', 'arrow');

	echo close_div();

	echo open_div('editContainer');

		$inp_edit           = array
		(
			'name'	        => 'inpScrapEdit'
		);
		echo form_input($inp_edit);

		echo make_button('Save', 'btnSave blueButton', '', 'left');
		echo make_button('Cancel', 'btnCancel', '', 'left');

		echo clear_float();

	echo close_div();

echo close_div();

// Check that we have customers
if($definitions['error'] == FALSE)
{
	// Get the definitions result
	$json_definitions			= $definitions['result'];

	// Table heading
	$this->table->set_heading('', array('data' => 'Template Name', 'class' => 'txtLeft'), array('data' => 'Fields', 'class' => 'fullCell'), 'Download', '');

	// Loop through and display customer
	foreach($json_definitions->catalog_item_definitions as $definition)
	{
		// Some variables
		$definition_fields      =   $definition->catalog_item_definition_fields;

		// Table data
		$ar_fields              = array();

		// Icon
		array_push($ar_fields, ''/*full_div('', 'icon-clipboard definitionIcon')*/);

		// Definition name
		array_push($ar_fields, array('data' => make_link($definition->name, 'editDefinitionName').hidden_div($definition->id, 'hdDefinitionIdName'), 'class' => 'txtLeft definitionName'));

		// Fields
		$fields                 = '';
		$loop_count             = 0;
		foreach($definition_fields as $definition_field)
		{
			$loop_count++;
			if($loop_count < 3)
			{
				$fields             .= make_button($definition_field->field_name, 'productField2', '', 'left');

				if($loop_count == 2)
				{
					$fields         .= make_button('Item Number', 'productField2', '', 'left');
					$fields         .= make_button('Price', 'productField2', '', 'left');
					$fields         .= make_button('Stock Count', 'productField2', '', 'left');
				}
			}
			else
			{
				$fields             .= make_button($definition_field->field_name.hidden_div($definition_field->id, 'hdFieldId').'<span class="icon-cross"></span>', 'productField', '', 'left');
			}
		}
		$fields                 .= make_button('<span class="icon-plus"></span>'.hidden_div($definition->id, 'hdDefinitionIdAdd'), 'productFieldAdd blueButton', '', 'left');
		$fields                 = $this->scrap_string->remove_lc(trim($fields));
		array_push($ar_fields, array('data' => $fields, 'class' => 'fullCell'));

		// View orders
		array_push($ar_fields, make_button('<span class="icon-download"></span>Excel Template', 'blueButton downloadTemplate', 'products/download_definition/'.$definition->id));

		// Buttons
		$html   = '';
		$html   .= open_div('extraOptions');

			$html   .= full_div('', 'btnCopyDefinition icon-copy', 'Copy this product template');
			$html   .= full_div('', 'btnDeleteDefinition icon-cross', 'Delete this product template');
			$html   .= clear_float();

			// Hidden data
			$html   .= hidden_div($definition->name, 'hdDefinitionName');
			$html   .= hidden_div($definition->id, 'hdDefinitionId');

		$html   .= close_div();
		array_push($ar_fields, $html);

		// Table row
		$this->table->add_row($ar_fields);
	}

	// Generate table
	echo $this->table->generate();
}
else
{
	echo full_div('No Definitions', 'messageNoDefinitions btnAddDefinition');
}
?>