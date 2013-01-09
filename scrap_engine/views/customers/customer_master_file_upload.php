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
            echo full_div('Before You Start', 'shifterBlock shifterStart');

            // Slide nav 2
            echo full_div('Choose Your File', 'shifterBlock');

            // Slide nav 3
            echo full_div('Check Your Data', 'shifterBlock');

            // Slide nav 4
            echo full_div('Upload Vendors', 'shifterBlock shifterEnd');

            // Clear float
            echo clear_float();

        echo close_div();

    // End of control bar
    echo close_div();


    // Shifter pane 1
    echo open_div('shifterPane shifterPane_1');

        // Heading
        echo heading('Uploading Customers Via A Master File', 2);

        // Content
        echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In vel magna eget diam sodales vestibulum. Nullam dignissim neque in lorem faucibus pellentesque faucibus tellus vehicula. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc pretium hendrerit elit at condimentum. Aliquam non vestibulum metus. Aenean consectetur nisi ac libero faucibus vel pulvinar ligula viverra. Ut in nibh dui.</p>';

        echo '<p>Mauris nec ante posuere magna accumsan suscipit in vitae sem. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec porttitor nulla sed tortor convallis imperdiet. Sed eu libero turpis, at dictum nibh. Cras eu neque libero. Mauris a est diam. Phasellus quis risus purus. Vestibulum ligula mauris, eleifend malesuada cursus lacinia, pulvinar eu neque. Maecenas vitae ultrices erat.</p>';

        echo div_height(20).make_button('Download Example Excel File', 'btnDownload', 'scrap_assets/files/examples/customer_create_master_data.xlsx', 'left');
        echo clear_float();

    echo close_div();

    // Shifter pane 2
    echo open_div('shifterPane shifterPane_2');

        // Upload form
        echo form_open_multipart('ajax_handler_customers/check_customer_upload', 'class="frmCheckCustomerUpload"');

            // Heading
            echo heading('Choose Your File', 2);

            // Content
            echo '<p>Upload your Excel file that will be used to import all your customers.</p>';

            echo div_height(20);

            $inp_data		= array
            (
                'name'		=> 'uploadedFile',
                'class'		=> 'uploadedFile'
            );
            echo form_upload($inp_data);
            echo clear_float();

        // Close the form
        echo form_close();

    echo close_div();

    // Shifter pane 3
    echo open_div('shifterPane shifterPane_3');

        // Check data loader
        echo full_div('Checking to see if the upload file is valid', 'loader');

        // Checks pane
        echo open_div('checkDataContainer');

        // End of check pane
        echo close_div();

    echo close_div();

    // Shifter pane 3
    echo open_div('shifterPane shifterPane_4');

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