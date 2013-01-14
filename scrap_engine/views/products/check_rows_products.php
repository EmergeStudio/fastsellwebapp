<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($row_check['error'] == FALSE)
{
	// Get the vendors result
	$json_row_check			= $row_check['result'];
    //echo json_encode($json_row_check);
	
	// List contain
	echo open_div('listContain');
		
		// Users cool table
		echo '<table class="coolTable items">';
		
			// Table headings
			echo '<tr>';

                echo '<th class="ignore"></th>';

                echo '<th class="ignore"></th>';

				foreach($json_row_check->worksheets[0]->headers as $header)
				{
					echo '<th>'.$header.'</th>';
				}
				
			echo '</tr>';
		
			// Content
			$odd_row			= TRUE;

            foreach($json_row_check->worksheets as $worksheet)
            {
                foreach($worksheet->rows as $row)
                {
                    if($row->error_code != null)
                    {
                        // Create row
                        if($odd_row == TRUE)
                        {
                            $class		= 'oddRow';
                            $odd_row 	= FALSE;
                        }
                        else
                        {
                            $class		= 'evenRow';
                            $odd_row 	= TRUE;
                        }
                        echo '<tr class="'.$class.' contain_item">';

                            echo '<td class="ignore">'.full_div('Cross', 'cross').'</td>';

                            echo '<td class="ignore errorWidth">'.full_div($row->error_description, 'redTxt').'</td>';

                            foreach($row->columns as $column)
                            {
                                echo '<td>'.$column->value.'</td>';
                            }

                        echo '</tr>';
                    }
                }
            }
			
			foreach($json_row_check->worksheets as $worksheet)
			{
                foreach($worksheet->rows as $row)
                {
                    if($row->error_code == null)
                    {
                        // Create row
                        if($odd_row == TRUE)
                        {
                            $class		= 'oddRow';
                            $odd_row 	= FALSE;
                        }
                        else
                        {
                            $class		= 'evenRow';
                            $odd_row 	= TRUE;
                        }
                        echo '<tr class="'.$class.' contain_item">';

                            echo '<td class="ignore">'.full_div('Tick', 'tick').'</td>';

                            echo '<td class="ignore"></td>';

                            foreach($row->columns as $column)
                            {
                                echo '<td>'.$column->value.'</td>';
                            }

                        echo '</tr>';
                    }
                }
			}
		
		// End of users cool table
		echo '</table>';
	
		// Some height
		echo div_height(20);
	
	// End of list contain
	echo close_div();
}
else
{
	// Return the error message
	$json				= $row_check['result'];
	echo $json->error_description;
}
?>