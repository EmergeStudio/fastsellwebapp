<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data
$ar_index_values					= array();
$ar_available_index_fields			= array();
foreach($doc_xml->document_index_values->document_index_value as $index_info)
{
	// Set the values
	$index_field					= trim($index_info->document_index_field['id']);
	$index_value					= trim($index_info->value);
	
	if(isset($index_info['archive_id']))
	{
		$index_id					= trim($index_info['archive_id']);
		$index_archive				= 'true';
	}
	elseif(isset($index_info['id']))
	{
		$index_id					= trim($index_info['id']);
		$index_archive				= 'false';
	}
	else
	{
		$index_id					= 0;
		$index_archive				= 'false';
	}
	
	// Set the array
	$ar_index_values[$index_field]	= array($index_id, $index_value, $index_archive);
	array_push($ar_available_index_fields, $index_field);
}

// First field
$first_field						= TRUE;

// Viewer back
echo '<div class="viewerBack telescopeViewer" style="height:'.$height.'px; width:'.$width.'px">';

	// Control menu
	echo '<div class="viewerMenu" style="width:'.$width.'px;">';
	
		// Viewer controls
		echo open_div('viewControls');
		
			// Close viewer
			echo full_div('Close', 'viewerClose', 'Close the viewer');
			
			// Line
			echo full_div('', 'divider');
		
			// Viewer information
			echo full_div('Information', 'viewerInfo', 'Toggle Information View');
		
			// Viewer downloads
			echo anchor('telescope/download_viewer_files', 'Download', 'class="viewerDownload" title="Download Document"');
			//echo full_div('Download', 'viewerDownload', 'Download Document');
			
			// Line
			echo full_div('', 'divider');
			
			// Pagenation
			if($cnt_images > 1)
			{
				echo full_div('Next', 'viewerNext', 'Next page');
			}
			
			// Page counter
			echo full_div('1 / '.$cnt_images, 'viewerPageCount');
			
			if($cnt_images > 1)
			{
				echo full_div('Back', 'viewerPrev', 'Previous page');
			}
			
			// Line
			echo full_div('', 'divider');
			
			// Rotate right
			echo full_div('Rotate Right', 'viewerRotateRight', 'Rotate page right');
			
			// Rotate left
			echo full_div('Rotate Left', 'viewerRotateLeft', 'Rotate page left');
			
			// Line
			echo full_div('', 'divider');
			
			// Zoom slider
			echo full_div('plus', 'btnPlus');
			
			echo open_div('controlBackBlack');
			
				echo full_div('', 'zoomSlider');
		
			echo close_div();
			
			echo full_div('minus', 'btnMinus');
			
			// Line
			echo full_div('', 'divider');
		
		// End of viewer controls
		echo close_div();
		
		// Document heading
		echo full_div($doc_xml->document_index_values->document_index_value->value, 'documentHeading');
		
		// Clear float
		echo clear_float();
	
	// End of control menu
	echo close_div();
	
	// Information container
	if($info_open == TRUE)
	{
		$info_disp_class		= '';
		$info_disp_value		= 'open';
	}
	else
	{
		$info_disp_class		= ' displayNone';
		$info_disp_value		= 'closed';
	}
	echo '<div class="infoContainer'.$info_disp_class.'" style="height:'.($height - 47).'px;">';
	
		// Indexing container
		echo '<div class="indexingContainer">';
		
			echo open_div('indexingInner');
			
				// Some height
				echo div_height(26);

				// Document type
				echo heading($doc_type->name, 4).div_height(3);
	
				// Display the document type fields with the values
				foreach($doc_type->document_index_fields->document_index_field as $index_field)
				{
					// Input class
					if($first_field == TRUE)
					{
						$first_field		= FALSE;
						$inp_class			= 'inpIndexField primary';
					}
					else
					{
						$inp_class			= 'inpIndexField';
					}
					
					// Indexing value
					$inp_data				= array
					(
						'name'				=> 'inpIndexField_'.$index_field['id'],
						'class'				=> $inp_class,
						'placeholder'		=> $index_field->name,
					);
					
					// Add the value
					if(in_array(trim($index_field['id']), $ar_available_index_fields))
					{
						$inp_data['value']	= $ar_index_values[trim($index_field['id'])][1];
					}
					
					echo open_div('indexFieldContainer');
					
						// Show the input
						echo form_input($inp_data);
						
						// Hidden data
						if(in_array(trim($index_field['id']), $ar_available_index_fields))
						{
							echo form_hidden('hdIndexId', $ar_index_values[trim($index_field['id'])][0]);
							echo form_hidden('hdIndexArchive', $ar_index_values[trim($index_field['id'])][2]);
						}
						else
						{
							echo form_hidden('hdIndexId', 'new_index_value');
							echo form_hidden('hdIndexArchive', 'false');
						}
						
					echo close_div();
				}
		
				// Buttons
				echo make_button('Save Changes', 'btnSaveChanges', '', 'left');
				echo make_button('Clear All Fields', 'btnClearFields', '', 'left');
				echo clear_float();


				// Some hidden data
				echo form_hidden('hdDocId', $doc_xml['id']);
			
			echo close_div();
		
		echo close_div();
		
		// Divider
		echo open_div('dividerContainer');
		
			echo open_div('dividerOuter');
			
				echo full_div('', 'divider');
		
			echo close_div();
		
		echo close_div();
	
		// Thumnails container
		echo '<div class="thumbsContainer">';
		
			echo open_div('thumbsInner');
			
				echo $images;
			
			echo close_div();
		
		echo close_div();
	
	echo close_div();
	
	// Image container
	echo open_div('imageContainer');
	
		echo $images;
	
	echo close_div();
	
	// Some hidden data
	echo form_hidden('hdInfoDisplay', $info_disp_value);
	
// End of viewer back
echo close_div();
?>