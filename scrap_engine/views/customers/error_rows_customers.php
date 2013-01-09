<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($row_check['error'] == FALSE)
{

	// Get the vendors result
	$json_row_check			= $row_check['result'];
    //echo json_encode($json_row_check);

	$error              = FALSE;
	foreach($json_row_check->worksheets as $worksheet)
	{
		foreach($worksheet->rows as $row)
		{
			if($row->error_code != -1)
			{
				$error  = TRUE;
			}
		}
	}

    // Some height
    echo div_height(10);

	// List contain
	echo open_div('listContain');

        echo full_div('', 'line');
        echo div_height(30);

		if($error == TRUE)
		{
	        echo heading('Upload Complete', 3, 'class="greenTxt"');
	        echo '<p>If there are any errors that prevented customers from being uploaded, the you will see the failed ones listed below.</p>';

	        echo div_height(5);
	        echo make_button('View Customers Now', '', 'customers');
	        echo div_height(30);

			// Users cool table
			echo '<table class="coolTable customers">';

				// Table headings
				echo '<tr>';

	                echo '<th></th>';

	                echo '<th></th>';

					echo '<th>Email</th>';

					echo '<th>First Name</th>';

					echo '<th>Last Name</th>';

					echo '<th>Phone No.</th>';

					echo '<th>Address 1</th>';

					echo '<th>Address 2</th>';

					echo '<th>Address 3</th>';

	                echo '<th>City</th>';

	                echo '<th>State</th>';

	                echo '<th>Postal Code</th>';

	                echo '<th>Customer Name</th>';

	                echo '<th>Customer No.</th>';

				echo '</tr>';

				// Content
				$odd_row			= TRUE;

	            foreach($json_row_check->worksheets as $worksheet)
	            {
	                foreach($worksheet->rows as $row)
	                {
	                    if($row->error_code != -1)
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
	                        echo '<tr class="'.$class.' contain_customer">';

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

			// End of users cool table
			echo '</table>';

			// Some height
			echo div_height(20);
		}
		else
		{
			echo heading('Upload Complete', 3, 'class="greenTxt"');
			echo '<p>There were no errors with your upload. Everything went smoothly</p>';

			echo div_height(5);
			echo make_button('View Customers Now', '', 'customers');
		}
	
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