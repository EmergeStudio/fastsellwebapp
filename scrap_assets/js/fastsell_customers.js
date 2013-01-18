$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_customers/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';
    var uploader                = document.getElementById('uploader');


// ------------------------------------------------------------------------------EXECUTE

    $fc_counter();

    $fc_add_customers();

    $fc_upload_master_data_file();

    $fc_remove_customer();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ---------- UPLOAD MASTER DATA FILE
    function $fc_upload_master_data_file()
    {
        // Buy products popup
        $('body').sunBox.popup('Upload Master Data File', 'popCustomerMasterDataFile',
        {
            ajax_path		    : $ajax_base_path + 'add_master_data_file_popup',
            close_popup		    : false,
            callback 		    : function($return){}
        });

        // Show the popup
        $('.btnUploadDataFile').live('click', function()
        {
            $('.popCustomerMasterDataFile .returnTrue').text('Upload');
            $('body').sunBox.show_popup('popCustomerMasterDataFile');
            $('body').sunBox.adjust_popup_height('popCustomerMasterDataFile');
        });

        // Submit
        $('.popCustomerMasterDataFile .returnTrue').live('click', function()
        {
            $('.frmCustomerMasterDataUpload').submit();
        });
    }

    // ---------- COUNTER
    function $fc_counter()
    {
        $('.counterTime').each(function()
        {
            // Some variables
            $date                 = $(this).find('.hdDate').text();
            $ex_date              = $date.split('-');
            $time                 = $(this).find('.hdTime').text();
            $hours                = $time.substr(0,2);
            $minutes              = $time.substr(2,2);
            $seconds              = $time.substr(4,2);

            // Set the counter
            $(this).countdown(
                {
                    // Time stamp
                    timestamp	        : (new Date($ex_date[0], ($ex_date[1]-1), ($ex_date[2]-20), $hours, $minutes, $seconds)).getTime() + 20*24*60*60*1000
                });
        });
    }

    // ---------- ADD CUSTOMERS
    function $fc_add_customers()
    {
        // Buy products popup
        $('body').sunBox.popup('Add More Customers', 'popAddCustomers',
        {
            ajax_path		    : $ajax_base_path + 'add_customers_popup',
            close_popup		    : false,
            callback 		    : function($return){}
        });

        // Show the popup
        $('.btnAddCustomersPopup').live('click', function()
        {
            $('body').sunBox.popup_change_width('popAddCustomers', 800);
            $('.popAddCustomers .returnTrue').hide();
            $('body').sunBox.show_popup('popAddCustomers');
            $('body').sunBox.adjust_popup_height('popAddCustomers');
        });

        $('.popAddCustomers input[name="checkAddCustomer"]').live('change', function()
        {
            // Some variables
            $this                   = $(this);
            $customer_id            = $this.val();
            $parent				    = $this.parents('tr');
            $event_id               = $('.hdEventId').text();

            $.scrap_note_loader('Adding the customer to this FastSell');

            // Add the customer
            $.post($base_path + 'ajax_handler_fastsells/fastsell_customer_link',
            {
                event_id		    : $event_id,
                customer_id			: $customer_id ,
                type                : 'add'
            },
            function($data)
            {
                //console.log($data);
                $.scrap_note_time('Customer has been added to this FastSell', 4000, 'tick');
                $fc_refresh_customer_list();
            });
        });
    }

    // ---------- REMOVE A CUSTOMER
    function $fc_remove_customer()
    {
        $('.btnRemoveCustomer').live('click', function()
        {
            // Some variables
            $this                   = $(this);
            $parent                 = $this.parents('.extraOptions');
            $customer_id            = $parent.find('.hdCustomerId').text();
            $event_id               = $('.hdEventId').text();

            // Add the customer
            // Add the customer
            $.post($base_path + 'ajax_handler_fastsells/fastsell_customer_link',
            {
                event_id		    : $event_id,
                customer_id			: $customer_id ,
                type                : 'remove'
            },
            function($data)
            {
                //console.log($data);
                $.scrap_note_time('Customer has been removed from this FastSell', 4000, 'tick');
                $fc_refresh_customer_list();
            });
        });
    }

    // ---------- REFRESH CUSTOMER LIST
    function $fc_refresh_customer_list()
    {
        // Some variables
        $event_id               = $('.hdEventId').text();

        // The AJAX call
        $.post($ajax_base_path + 'get_added_customers',
        {
            event_id		    : $event_id
        },
        function($data)
        {
            $data	            = jQuery.trim($data);
            //console.log($data);

            $('.ajaxCustomersInFastSell').html($data);
        });
    }


});