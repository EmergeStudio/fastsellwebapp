<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Right column
echo open_div('rightColumn');

	// Item definition
	echo open_div('inset');

		echo form_label('Definition Name');
		$inp_data			= array
		(
			'name'			=> 'inpDefinitionName',
			'class'			=> 'inpDefinitionName'
		);
		echo form_input($inp_data);

	echo close_div();

	// Some height
	echo div_height(25);

	// Included fields
	echo open_div('inset includedFields');

		echo form_label('Included Fields');
		echo full_div('The following fields will automatically be available on all items that are based on this definition.');
		echo div_height(15);

		echo full_div('<span class="icon-checkmark-2"></span>Product Name');
		echo full_div('<span class="icon-checkmark-2"></span>Description');
		echo full_div('<span class="icon-checkmark-2"></span>MSRP');
		echo full_div('<span class="icon-checkmark-2"></span>Pack & Size');
		echo full_div('<span class="icon-checkmark-2"></span>Expiry Date');
		echo full_div('<span class="icon-checkmark-2"></span>Shelf Date');

	echo close_div();

	// Some height
	echo div_height(25);

	// Index fields
	echo open_div('inset');

		// All item definition fields
		echo open_div('allDefinitionFields');

			echo open_div('definitionFieldContainer');

				// Index name
				echo form_label('Add Field');
				$inp_data			= array
				(
					'name'			=> 'inpDefinitionField',
					'class'			=> 'inpDefinitionField'
				);
				echo form_input($inp_data);

			echo close_div();

		echo close_div();

		// Add new index field button
		echo make_button('Add Another Field', 'btnAddDefinitionField');

	echo close_div();

// End of right column
echo close_div();


// Side column
echo open_div('leftColumn');

	// Document type icon inset
	echo open_div('inset');

		// Document type icon
		echo full_div('', 'icon-clipboard-2 popupIcon');

	echo close_div();

// End of left column
echo close_div();
?>