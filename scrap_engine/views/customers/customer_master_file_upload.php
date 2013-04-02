<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Slider
echo open_div('middle').open_div('whiteBack coolScreen');

   // Control bar
    echo open_div('controlBar plain');

        // Shifter
        echo open_div('shifter');

            // Active bar
            echo open_div('activeBarContain');

                echo open_div('activeBar');

                    // Active start
                    echo full_div('Shifter Active Start', 'shifterBlockActive shifterStart');

                    // Active end
                    echo full_div('Shifter Active End', 'shifterBlockActive shifterEnd');

                    // Clear float
                    echo clear_float();

                echo close_div();

            echo close_div();

            // Slide nav 1
            echo full_div('Choose Your File', 'shifterBlock shifterStart');

            // Slide nav 2
            echo full_div('Check Your Data', 'shifterBlock');

            // Slide nav 3
            echo full_div('Upload Customers', 'shifterBlock shifterEnd');

            // Clear float
            echo clear_float();

        echo close_div();

    // End of control bar
    echo close_div();

    // Shifter pane 2
    echo open_div('shifterPane shifterPane_1');

        // Upload form
        echo form_open_multipart('ajax_handler_customers/check_customer_upload', 'class="frmCheckCustomerUpload"');

            // Heading
            echo heading('Choose Your File', 2);

            // Content
            echo '<p>Upload your Excel file that will be used to import all your customers.  You can download an <a href="../scrap_assets/files/examples/customer_upload.xlsx">example file here</a> and please note that the following fields are <b>required:</b></p>';

			echo full_div('Customer Name', 'field');
			echo full_div('Customer Number', 'field');
			echo full_div('First Name', 'field');
			echo full_div('Last Name', 'field');
			echo full_div('Address One', 'field');
			echo full_div('Address Two', 'field');
			echo full_div('Address Three', 'field');
			echo full_div('State / Province', 'field');
			echo full_div('City', 'field');
			echo full_div('Zip / Postal Code', 'field');
			echo full_div('Email', 'field');
			echo full_div('Phone Number', 'field');
			echo clear_float();

            echo div_height(40);

            $inp_data		= array
            (
                'name'		=> 'uploadedFile',
                'class'		=> 'uploadedFile'
            );
            echo form_upload($inp_data);
            echo clear_float();

			echo div_height(10).make_button('Download Example File', 'btnDownload btnDownloadExcelFile blueButton', 'scrap_assets/files/examples/customer_upload.xlsx', 'left');
			echo clear_float();

        // Close the form
        echo form_close();

    echo close_div();

    // Shifter pane 3
    echo open_div('shifterPane shifterPane_2');

        // Check data loader
        echo full_div('Checking to see if the upload file is valid', 'loader');

        // Checks pane
        echo open_div('checkDataContainer');

        // End of check pane
        echo close_div();

    echo close_div();

    // Shifter pane 3
    echo open_div('shifterPane shifterPane_3');

        // Heading
        echo heading('Upload Your Customers', 2);

        // Content
        echo '<p>If you have checked your data and happy with the changes that you have made then submit the customers for uploading by clicking the <b>"Upload Customers"</b> button in the bottom right. Alternatively go back a step to modify your data again or go further back to select a different master file to upload.</p>';
        echo '<p>Any customer that already exists will be updated, any customer that is new will be created and any customer that does not check out will not be created at all.</p>';

        // Check data loader
        echo full_div('Uploading Your Customers Now', 'loader displayNone');

        // Errors container
        echo full_div('', 'errorContainer');

    echo close_div();


    // Shifter navigation
    echo open_div('shifterNav');

        echo make_button('Next Step', 'btnShifterNext', '', 'right');

        echo make_button('Upload Customers', 'btnComplete', '', 'right', '', FALSE);

        echo make_button('Go Back', 'btnShifterBack', '', '', '', FALSE);

        // Shifter position
        echo hidden_div(1, 'hdPanePosition');

        // Clear float
        echo clear_float();

    // End of shifter navigation
    echo close_div();

echo close_div().close_div();
?>