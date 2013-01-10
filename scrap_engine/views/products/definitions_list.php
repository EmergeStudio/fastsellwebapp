<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Check that we have customers
if($definitions['error'] == FALSE)
{
	// Get the definitions result
	$json_definitions			= $definitions['result'];

	// Table heading
	$this->table->set_heading(array('data' => 'Definition Name', 'class' => 'txtLeft'), array('data' => 'Fields', 'class' => 'fullCell'), '', '');

	// Loop through and display customer
	foreach($json_definitions->catalog_item_definitions as $definition)
	{
		// Some variables
		$definition_fields      =   $definition->catalog_item_definition_fields;

		// Table data
		$ar_fields              = array();

		// Definition name
		array_push($ar_fields, array('data' => $definition->name, 'class' => 'txtLeft definitionName'));

		// Fields
		$fields                 = '';
		foreach($definition_fields as $definition_field)
		{
			$fields             .= $definition_field->field_name.', ';
		}
		$fields                 = $this->scrap_string->remove_lc(trim($fields));
		array_push($ar_fields, array('data' => $fields, 'class' => 'fullCell'));

		// View orders
		array_push($ar_fields, anchor('products/by_definition/'.$definition->id, '<span class="icon-box"></span>View Products'));

		// Buttons
		$html   = '';
		$html   .= open_div('extraOptions');

			$html   .= make_button('', 'btnEditDefinition btnEdit iconOnly', '', 'left', 'Edit this product definition');
			$html   .= make_button('', 'btnDeleteDefinition btnCross iconOnly', '', 'left', 'Delete this product definition');

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
?>