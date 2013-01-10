<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Check that we have customers
if($products['error'] == FALSE)
{
	// Data
	$json_items                             = $products['result'];


	// Product item
	foreach($json_items->catalog_items as $item)
	{
		echo open_div('itemContainer');

			// Table
			echo '<table><tr>';

				echo '<td>';

				$img_properties         = array
				(
					'src'               => 'scrap_assets/images/temp/item_image.jpg'
				);
				echo img($img_properties);

				// Product information
				echo open_div('itemInformation');

					echo full_div($item->catalog_item_field_values[0]->value, 'itemName');
					echo full_div($item->item_number, 'itemNumber greyTxt');
					echo open_div('itemDescription');

						$fields                 = '';
						$loop_fields            = 1;
						foreach($item->catalog_item_field_values as $field_value)
						{
							if($loop_fields > 1)
							{
								//$fields             .= ' '.$field_value->.': '.$field_value->value.',';
								$fields             .= ' '.$field_value->value.'<br>';
							}
							else
							{
								$loop_fields++;
							}
						}
						echo $fields;
						echo close_div();

						$img_properties_2       = array
						(
							'src'               => 'scrap_assets/images/temp/carrots_big.jpg',
							'width'             => 213
						);
						echo full_div(img($img_properties_2), 'inset itemImage');

						// Hidden data
						echo hidden_div($item->id, 'hdItemId');

					echo close_div();

				echo '</td>';

				echo '<td>';

					echo full_div($item->catalog_item_field_values[0]->value, 'itemName');
					echo full_div($item->item_number, 'itemNumber greyTxt');

				echo '</td>';

				echo '<td class="fullCell itemDescription">';

					$fields                 = '';
					$loop_fields            = 1;
					foreach($item->catalog_item_field_values as $field_value)
					{
						if($loop_fields > 1)
						{
							//$fields             .= ' '.$field_value->.': '.$field_value->value.',';
							$fields             .= ' '.$field_value->value.',';
						}
						else
						{
							$loop_fields++;
						}
					}
					echo $this->scrap_string->remove_lc($fields);

				echo '</td>';

				echo '<td></td>';

			echo '</tr></table>';

		// End of product item
		echo close_div();
	}
}
else
{
	echo full_div('Add A Product', 'messageAddAProduct btnAddProduct');
}
?>