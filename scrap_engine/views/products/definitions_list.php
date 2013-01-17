<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Check that we have customers
if($definitions['error'] == FALSE)
{
	// Get the definitions result
	$json_definitions			= $definitions['result'];

	// Table heading
	$this->table->set_heading('', array('data' => 'Group Name', 'class' => 'txtLeft'), array('data' => 'Fields', 'class' => 'fullCell'), '', '');

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
		array_push($ar_fields, array('data' => $definition->name, 'class' => 'txtLeft definitionName'));

		// Fields
		$fields                 = '';
		$loop_count             = 0;
		foreach($definition_fields as $definition_field)
		{
			$loop_count++;
			if($loop_count < 3)
			{
				$fields             .= make_button($definition_field->field_name, 'productField2', '', 'left');
			}
			else
			{
				$fields             .= make_button($definition_field->field_name.hidden_div($definition_field->id, 'hdFieldId').'<span class="icon-cross"></span>', 'productField', '', 'left');
			}
		}
		$fields                 = $this->scrap_string->remove_lc(trim($fields));
		array_push($ar_fields, array('data' => $fields, 'class' => 'fullCell'));

		// View orders
		array_push($ar_fields, anchor('products/by_definition/'.$definition->id, '<span class="icon-box"></span>View Products'));

		// Buttons
		$html   = '';
		$html   .= open_div('extraOptions');

			$html   .= full_div('', 'btnDeleteDefinition icon-cross', 'Delete this product group');

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