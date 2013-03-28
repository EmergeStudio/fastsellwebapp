$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_customers/';
    var $ajax_base_path_fs 		= $base_path + 'ajax_handler_fastsells/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';
    var uploader                = document.getElementById('uploader');


// ------------------------------------------------------------------------------EXECUTE

    $fc_counter();

    $fc_add_customers();

    $fc_add_customers_2();

    $fc_add_customers_by_group();

    $fc_upload_master_data_file();

    $fc_remove_customer();

    $fc_execute_flexigrid();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ----- ADD CUSTOMERS BY GROUP
    function $fc_add_customers_by_group()
    {
        // Edit group popup
        $('body').sunBox.popup('Select The Customer Group', 'popAddCustomerByGroup',
            {
                ajax_path		: $ajax_base_path_fs + 'add_customers_by_group_popup',
                close_popup		: false,
                callback 		: function($return){}
            });

        // Show the popup
        $('.btnAddByGroupPopup').on('click', function()
        {
            $('.popAddCustomerByGroup .returnTrue').hide();
            $('body').sunBox.show_popup('popAddCustomerByGroup');
            $('body').sunBox.adjust_popup_height('popAddCustomerByGroup');
        });

        // Select group
        $(document).on('click', '.popAddCustomerByGroup .groupSelection', function()
        {
            // Some variables
            $this                   = $(this);
            $group_id               = $this.find('.hdGroupId').text();
            $group_name             = $this.text();
            $event_id               = $('.hdEventId').text();

            // Add the customers by group id
            $.scrap_note_loader('Adding customers from the '+ $group_name +' group into this FastSell');

            // Close the popup
            $('body').sunBox.close_popup('popAddCustomerByGroup');

            // Refresh the data
            $.post($ajax_base_path_fs + 'add_customers_to_fastsell_by_group',
            {
                group_id			: $group_id,
                event_id            : $event_id
            },
            function($data)
            {
                $data	= jQuery.trim($data);

                if($data == '9876')
                {
                    $.scrap_logout();
                }
                else if($data == 'okitsdone')
                {
                    $.scrap_note_time('The customers have been added to this FastSell', 4000, 'tick');
                    $fc_refresh_customer_list();
                }
                else
                {
                    $.scrap_note_time($data, 4000, 'cross');
                }
            });
        });
    }

    // ---------- EXECUTE FLEXIGRID
    function $fc_execute_flexigrid()
    {
        // Get field headings
        $field_headings             = $('.hdFieldHeadings').text();
        $limit                      = $('input[name="hdLimit"]').val();
        $page                       = $('input[name="scrap_pageNo"]').val();
        $total                      = $('input[name="scrap_pageMax"]').val();
        var $ar_fields              = new Array();

        // Predefined headings
        $ar_fields.push({ display: 'ID', name : 'id', width : 50, sortable : false, align: 'center' });
        $ar_fields.push({ display: 'Company Name', name : 'companyName', width : ($(window).width() - 380), sortable : false, align: 'left' });
        $ar_fields.push({ display: 'Customer Number', name : 'customerNumber', width : 200, sortable : false, align: 'left' });
        $ar_fields.push({ display: '', name : 'remove', width : 50, sortable : false, align: 'left' });

        $('#flex1').flexigrid
        ({
            colModel                : $ar_fields,
            onChangeSort            : false,
            showToggleBtn           : false,
            height                  : 600,
            nowrap                  : false,
            usepager                : true,
            resizable               : false,
            rp                      : $limit
        });

        $('.pcontrol input').val($page);
        $('.pcontrol span').text($total);
    }

    // ----- ADD CUSTOMERS
    function $fc_add_customers_2()
    {
        // Add a customer popup
        $('body').sunBox.popup('Add New Customer', 'popAddCustomer',
        {
            ajax_path		: $ajax_base_path + 'add_customer_popup',
            close_popup		: false,
            callback 		: function($return){}
        });

        // Show the popup
        $('.btnAddNewCustomerPopup').live('click', function()
        {
            $('body').sunBox.popup_change_width('popAddCustomer', 1100);
            $('body').sunBox.show_popup('popAddCustomer');
            $('body').sunBox.adjust_popup_height('popAddCustomer');
            $('.popAddCustomer .returnTrue').hide();
        });

        // Add the customer
        $('.popAddCustomer .btnAddCustomerNow').live('click', function()
        {
            // Some variables
            $error              = false;
            $customer_name      = $('.popAddCustomer input[name="inpCustomerName"]').val();
            $customer_number    = $('.popAddCustomer input[name="inpCustomerNumber"]').val();
            $first_name         = $('.popAddCustomer input[name="inpFirstName"]').val();
            $surname            = $('.popAddCustomer input[name="inpSurname"]').val();
            $email_address      = $('.popAddCustomer input[name="inpEmailAddress"]').val();
            $html               = '';

            // Validate
            if($error == false)
            {
                if($customer_name == '')
                {
                    $error		= true;
                    $('.popAddCustomer input[name="inpCustomerName"]').addClass('redBorder');
                    $.scrap_note_time('Please provide a customer name', 4000, 'cross');
                }
            }

            // Customer number
            if($error == false)
            {
                if($customer_number == '')
                {
                    $error		= true;
                    $('.popAddCustomer input[name="inpCustomerNumber"]').addClass('redBorder');
                    $.scrap_note_time('Please provide a customer number', 4000, 'cross');
                }
            }

            // Email
            if($error == false)
            {
                if($.scrap_is_email($email_address) == false)
                {
                    $error		= true;
                    $('.popAddCustomer input[name="inpEmailAddress"]').addClass('redBorder');
                    $.scrap_note_time('Your email address does not check out', 4000, 'cross');
                }
            }

            if($error == false)
            {
                // Some variables
                $this_row           = 'newLine_' + $.scrap_random_string();
                $list_type          = 'normalList';

                // Table row
                $html   += '<tr class="topLine displayNone '+ $this_row +'">';

                $html   += '<td>'+ $customer_name +'</td>';
                $html   += '<td>'+ $customer_number +'</td>';
                $html   += '<td>'+ $first_name +'</td>';
                $html   += '<td>'+ $surname +'</td>';
                $html   += '<td>'+ $email_address +'</td>';
                $html   += '<td><div class="loader"></div></td>';

                $html   += '</tr>';

                // Edit DOM
                $('.popAddCustomer table tr:last').after($html);
                $('.popAddCustomer table tr.displayNone').fadeIn('fast');
                $('body').sunBox.adjust_popup_height('popAddCustomer');

                // Insert the customer
                $.post($ajax_base_path + 'add_customer_2',
                {
                    inpCustomerName			: $customer_name,
                    inpCustomerNumber		: $customer_number,
                    inpName			        : $first_name,
                    inpSurname			    : $surname,
                    inpEmail			    : $email_address
                },
                function($data)
                {
                    $data	= jQuery.trim($data);

                    if($data == '9876')
                    {
                        $.scrap_logout();
                    }
                    else if($data != 'okitsdone')
                    {
                        $.scrap_note_time($data, 4000, 'cross');
                        $('.'+ $this_row +' .loader').addClass('cross').removeClass('loader');
                    }
                    else
                    {
                        // Edit the DOM
                        $('.'+ $this_row +' .loader').addClass('tick').removeClass('loader');

                        // Refresh the data
                        $.scrap_note_time('Customer has been added to this FastSell', 4000, 'tick');
                        $fc_refresh_customer_list();
                    }
                });
            }
        });
    }

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
            $fc_execute_flexigrid();
        });
    }


});