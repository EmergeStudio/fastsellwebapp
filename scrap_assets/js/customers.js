$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_customers/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE

    $fc_add_customers();

    $fc_delete_customer();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ----- ADD CUSTOMERS
    function $fc_add_customers()
    {
        // Add a customer popup
        $('body').sunBox.popup('Add Customer', 'popAddCustomer',
        {
            ajax_path		: $ajax_base_path + 'add_customer_popup',
            close_popup		: false,
            callback 		: function($return){}
        });

        // Show the popup
        $('.btnAddCustomer').live('click', function()
        {
            $('body').sunBox.popup_change_width('popAddCustomer', 1100);
            $('body').sunBox.show_popup('popAddCustomer');
            $('body').sunBox.adjust_popup_height('popAddCustomer');
            $('.popAddCustomer .returnTrue').hide();
        });

        //

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
//            $customer_group     = $('.popAddCustomer input[name="inpCustomerGroup"]').val();
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
//                    $html   += '<td>'+ $customer_group +'</td>';
                    $html   += '<td><div class="loader"></div></td>';

                $html   += '</tr>';

                // Edit DOM
                $('.popAddCustomer table tr:last').after($html);
                $('.popAddCustomer table tr.displayNone').fadeIn('fast');
                $('body').sunBox.adjust_popup_height('popAddCustomer');

                // Insert the customer
                $.post($ajax_base_path + 'add_customer',
                {
                    inpCustomerName			: $customer_name,
                    inpCustomerNumber		: $customer_number,
                    inpName			        : $first_name,
                    inpSurname			    : $surname,
                    inpEmail			    : $email_address
//                    inpCustomerGroup        : $customer_group
                },
                function($data)
                {
                    $data	= jQuery.trim($data);
                    console.log($data);

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
                        if($list_type == 'createShowList')
                        {
                            $('.shifterPane_4 .ajax_customerList').html($data);
                            $('.shifterPane_4 input:checkbox').uniform();
                        }
                        else
                        {
                            $('.singleColumn .content').html($data);
                        }

                        $('.popAddCustomer input').val('');
                        $('.popAddCustomer input:first').focus();
                        $('.'+ $this_row +' .loader').addClass('tick').removeClass('loader');

                        // Refresh the data
                        $fc_refresh_customer_list($list_type);
                    }
                });
            }
        });
    }

    // ----- REFRESH CUSTOMER LIST
    function $fc_refresh_customer_list($list_type)
    {
        if($list_type != 'createShowList')
        {
            $('.singleColumn .listContain').prepend('<div class="ajaxMessage">Refreshing Customer List</div>');
            $('.singleColumn .listContain table').fadeTo('fast', 0.3);
        }

        // Get the customers
        $.post($ajax_base_path + 'get_customers',
        {
            request_check			: 'yupgetit',
            list_type               : $list_type
        },
        function($data)
        {
            $data	= jQuery.trim($data);

            if($data == '9876')
            {
                $.scrap_logout();
            }
            else
            {
                // Edit the DOM
                if($list_type == 'createShowList')
                {
                    $('.shifterPane_4 .ajax_customerList').html($data);
                    $('.shifterPane_4 input:checkbox').uniform();
                }
                else
                {
                    $('.singleColumn .listContain').html($data);
                }
            }
        });
    }

    // ---------- DELETE CUSTOMER
    function $fc_delete_customer()
    {
        $('.btnDeleteCustomer').live('click', function()
        {
            // Some variables
            $parent                     = $(this).parents('tr');
            $customer_id                = $parent.find('.hdCustomerId').text();
            $customer_name              = $parent.find('.fullCell').text();
            $customer_to_show_host_id   = $parent.find('.hdCustomerToShowHostId').text();

            // Validate
            $('body').sunBox.message(
            {
                content			: 'You sure you want to delete the customer <b>"'+ $customer_name +'"</b>?',
                btn_true		: 'Yup I\'m Sure',
                btn_false		: 'Oh Gosh No!',
                message_title	: 'Just Checking',
                callback		: function($return)
                {
                    if($return == true)
                    {
                        // Loader
                        $.scrap_note_loader('Deleting the customer');

                        // Send the delete request
                        $.post($ajax_base_path + 'delete_customer',
                        {
                            customer_to_show_host_id	    : $customer_to_show_host_id
                        },
                        function($data)
                        {
                            $data	= jQuery.trim($data);
                            console.log($data);

                            if($data == '9876')
                            {
                                $.scrap_logout();
                            }
                            else if($data == 'okitsdone')
                            {
                                // Refresh the data
                                $fc_refresh_customer_list('normalList');
                                $.scrap_note_hide();
                            }
                            else
                            {
                                // Edit the DOM
                                $.scrap_note_hide_time($data, 4000, 'cross');
                            }
                        });
                    }
                    $.scrap_remove_overlay();
                }
            });
        });
    }


});