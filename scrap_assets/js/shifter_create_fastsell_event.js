$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP
	
	var $cnt_shifters		= 0;
	var $loop_cnt_1			= 0;
	var $scroll_pos			= 0;
	var $offset 			= $('.shifter').offset();

    // ---------- AJAX PATHS
    var $base_path				= $('#hdPath').val();
    var $ajax_base_path 		= $base_path + 'ajax_handler_fastsells/';
    var $ajax_base_path_2 		= $base_path + 'ajax_handler_customers/';
    var $ajax_base_path_3 		= $base_path + 'ajax_handler_products/';


// ------------------------------------------------------------------------------EXECUTE
	
	$fc_set_shifter();
	
	$fc_shifter_navigation();

//    $fc_customer_links();

    $fc_add_product();

    $fc_add_products();

    $fc_add_a_product_and_link();

    $fc_add_customers_2();

    $fc_add_customers_by_group();
	
	$('.scrap_date').datepicker(
	{
		showOn				: 'both',
		buttonImage			: '../scrap_assets/images/icons/calendar.png',
		buttonImageOnly		: true,
		dateFormat			: 'yy-mm-dd',
		changeYear			: true,
		changeMonth			: true,
		minDate				: '+0'
	});

    $fc_upload_master_data_file();

    $fc_upload_master_data_file_2();

    $fc_remove_customer();

    $fc_remove_product();

    $fc_upload_temp_image();

    $fc_search_customers();

    $fc_auto_complete_category($('.hdFastSellCategories').text());

    $fc_manage_category();

    $fc_add_customers_single();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ---------- EXECUTE FLEXIGRID
    function $fc_execute_flexigrid()
    {
        // Get field headings
        var $ar_fields              = new Array();

        // Predefined headings
        $ar_fields.push({ display: 'ID', name : 'customerImage', width : 50, sortable : false, align: 'center' });
        $ar_fields.push({ display: 'Company Name', name : 'companyName', width : 300, sortable : false, align: 'left' });
        $ar_fields.push({ display: 'Customer Number', name : 'customerNumber', width : 200, sortable : false, align: 'left' });
        $ar_fields.push({ display: 'First Name', name : 'firstName', width : 150, sortable : false, align: 'left' });
        $ar_fields.push({ display: 'Last Name', name : 'lastName', width : 150, sortable : false, align: 'left' });
        $ar_fields.push({ display: 'Email Address', name : 'emailAddress', width : 200, sortable : false, align: 'left' });
        $ar_fields.push({ display: 'Groups', name : 'groups', width : '200', sortable : true, align: 'left' });
        $ar_fields.push({ display: '', name : 'remove', width : 50, sortable : false, align: 'left' });

        $('.shifterPane_2 #flex1').flexigrid
        ({
            colModel                : $ar_fields,
            onChangeSort            : false,
            showToggleBtn           : false,
            height                  : 600,
            nowrap                  : false,
            resizable               : false
        });

        // Some variables
        $window_h                   = $(window).height();
        $bDiv_h                     = $('.shifterPane_2 .flexigrid .bDiv').height();
        $bDiv_table_h               = $('.shifterPane_2 .flexigrid .bDiv table').height();

        // Adjust height
        if($bDiv_table_h > 500)
        {
            $('.shifterPane_2 .flexigrid .bDiv').height(500);
        }
        else
        {
            $('.shifterPane_2 .flexigrid .bDiv').height($bDiv_table_h);
        }
    }

    // ---------- EXECUTE FLEXIGRID
    function $fc_execute_flexigrid_2()
    {
        // Get field headings
        var $ar_fields              = new Array();

        // Predefined headings
        $ar_fields.push({ display: 'Image', name : 'productImage', width : 50, sortable : false, align: 'center' });
        $ar_fields.push({ display: 'Product Name', name : 'productName', width : 200, sortable : false, align: 'left' });
        $ar_fields.push({ display: 'Product Fields', name : 'productFields', width : ($(window).width() - 800), sortable : false, align: 'left' });
        $ar_fields.push({ display: 'MSRP', name : 'msrp', width : 100, sortable : false, align: 'left' });
        $ar_fields.push({ display: 'Stock Count', name : 'stock', width : 100, sortable : false, align: 'left' });
        $ar_fields.push({ display: 'Price', name : 'price', width : 100, sortable : false, align: 'left' });
        $ar_fields.push({ display: '', name : 'remove', width : 50, sortable : false, align: 'left' });

        $('.shifterPane_3 #flex1').flexigrid
        ({
            colModel                : $ar_fields,
            onChangeSort            : false,
            showToggleBtn           : false,
            height                  : 600,
            nowrap                  : false,
            resizable               : false
        });

        // Some variables
        $window_h                   = $(window).height();
        $bDiv_h                     = $('.shifterPane_3 .flexigrid .bDiv').height();
        $bDiv_table_h               = $('.shifterPane_3 .flexigrid .bDiv table').height();

        // Adjust height
        if($bDiv_table_h > 500)
        {
            $('.shifterPane_3 .flexigrid .bDiv').height(500);
        }
        else
        {
            $('.shifterPane_3 .flexigrid .bDiv').height($bDiv_table_h);
        }
    }

    // ---------- ADD CUSTOMERS
    function $fc_add_customers_single()
    {
        // Buy products popup
        $('body').sunBox.popup('Add Single Customer', 'popAddCustomers',
        {
            ajax_path		    : $ajax_base_path_2 + 'add_customers_popup',
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

    // ----- MANAGE CATEGORY
    function $fc_manage_category()
    {
        $('.btnDeleteCategory').live('click', function()
        {
            $(this).parents('.catBack').remove();
        });
    }

    // ----- AUTOCOMPLETE CATEGORY
    function $fc_auto_complete_category($values)
    {
        var availableTags   = [];

        $ex_values          = $values.split('][');

        $.each($ex_values, function($index, $value)
        {
            $ex_value       = $value.split('::');

            availableTags.push({ value: $ex_value[0], label: $ex_value[1] });
        });

        function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term ) {
            return split( term ).pop();
        }

        $('input[name="inpCategorySearch"]')
            // don't navigate away from the field on tab when selecting an item
            .bind( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                    $( this ).data( "autocomplete" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: $ajax_base_path + 'search_for_category',
                minLength: 3,
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui )
                {
                    $class_name             = 'category_' + $.scrap_random_string(5);

                    $('input[name="inpCategorySearch"]').val('').focus();

                    $('.ajax_fastSellCategories').prepend('<div class="catBack loader '+ $class_name +'"></div>');

                    // Get the category information
                    $.post($ajax_base_path + 'get_fastsell_category',
                    {
                        cat_text			: ui.item.label
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
                            $('.' + $class_name).removeClass('loader').addClass('blue').append($data);
                        }
                    });
                }
            });
    }

    // ----- AUTOCOMPLETE
    function $fc_auto_complete($values)
    {
        var availableTags   = [];

        $('.popAddCustomer .hdGroupIds').text('');
        $ex_values          = $values.split('][');

        $.each($ex_values, function($index, $value)
        {
            $ex_value       = $value.split('::');

            availableTags.push({ value: $ex_value[0], label: $ex_value[1] });
        });

        function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term ) {
            return split( term ).pop();
        }

        $('input[name="inpCustomerGroup"]')
            // don't navigate away from the field on tab when selecting an item
            .bind( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                    $( this ).data( "autocomplete" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                minLength: 0,
                source: function( request, response ) {
                    // delegate back to autocomplete, but extract the last term
                    response( $.ui.autocomplete.filter(
                        availableTags, extractLast( request.term ) ) );
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui ) {
                    var terms = split( this.value );

                    // remove the current input
                    terms.pop();

                    // add the selected item
                    terms.push( ui.item.label );

                    // add placeholder to get the comma-and-space at the end
                    terms.push( "" );
                    this.value = terms.join( ", " );
                    $('.popAddCustomer .hdGroupIds').text($('.popAddCustomer .hdGroupIds').text() + ui.item.value + '-' + ui.item.label + ':');
                    return false;
                }
            });
    }

    // ----- ADD CUSTOMERS BY GROUP
    function $fc_add_customers_by_group()
    {
        // Edit group popup
        $('body').sunBox.popup('Select The Customer Group', 'popAddCustomerByGroup',
        {
            ajax_path		: $ajax_base_path + 'add_customers_by_group_popup',
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
            $.post($ajax_base_path + 'add_customers_to_fastsell_by_group',
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

    // ----- ADD CUSTOMERS
    function $fc_add_customers_2()
    {
        // Add a customer popup
        $('body').sunBox.popup('Add New Customer', 'popAddCustomer',
        {
            ajax_path		: $ajax_base_path_2 + 'add_customer_popup',
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
            $fc_auto_complete($('.popAddCustomer .hdGroupsWithId').text());
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
            $customer_group     = $('.popAddCustomer input[name="inpCustomerGroup"]').val();
            $group_ids          = $('.popAddCustomer .hdGroupIds').text();
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
                $html   += '<td>'+ $customer_group +'</td>';
                $html   += '<td><div class="loader"></div></td>';

                $html   += '</tr>';

                // Edit DOM
                $('.popAddCustomer table tr:last').after($html);
                $('.popAddCustomer table tr.displayNone').fadeIn('fast');
                $('body').sunBox.adjust_popup_height('popAddCustomer');

                // Insert the customer
                $.post($ajax_base_path_2 + 'add_customer_2',
                {
                    inpCustomerName			: $customer_name,
                    inpCustomerNumber		: $customer_number,
                    inpName			        : $first_name,
                    inpSurname			    : $surname,
                    inpEmail			    : $email_address,
                    inpCustomerGroup        : $customer_group,
                    inpGroupIds             : $group_ids
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
                        $('.popAddCustomer .hdGroupIds').text('');
                        $('.popAddCustomer input').val('');
                        $('.popAddCustomer input:first').focus();
                        // Edit the DOM
                        $('.'+ $this_row +' .loader').addClass('tick').removeClass('loader');

                        // Refresh the data
                        $.scrap_note_time('The customer has been added to this FastSell', 4000, 'tick');
                        $fc_refresh_customer_list();
                        $fc_refresh_groups_autocomplete();
                    }
                });
            }
        });
    }

    // ----- REFRESH GROUPS AVAILABLE
    function $fc_refresh_groups_autocomplete($list_type)
    {
        // Get the customers
        $.post($ajax_base_path_2 + 'get_groups_for_autocomplete',
        {
            request_check			: 'yupgetit'
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
                $('.popAddCustomer .hdGroupsWithId').text($data);
                $fc_auto_complete($('.popAddCustomer .hdGroupsWithId').text());
            }
        });
    }

    // ---------- SEARCH CUSTOMERS
    function $fc_search_customers()
    {
        $('.btnSearchCustomer').live('click', function()
        {
            // Some variables
            $customer_name              = $('input[name="inpCustomerName"]').val();

            $('.ajaxCustomerList').prepend('<div class="ajaxMessage short2">Running Searching</div>');
            $('.ajaxCustomerList table').fadeTo('fast', 0.3);

            // Get the customers
            $.post($ajax_base_path + 'search_for_customers',
            {
                customer_name		    : $customer_name
            },
            function($data)
            {
                $data	                = jQuery.trim($data);

                $('.ajaxCustomerList').html($data);
                $.scrap_uniform_update('.ajaxCustomerList input[name="checkAddCustomer"], .ajaxCustomerList input[name="checkAddAllCustomers"]');
            });
        });
    }

    // ---------- UPLOAD TEMP IMAGE
    function $fc_upload_temp_image()
    {
        $('input[name="uploadedFileFastsellImage"]').live('change', function()
        {
            if($.scrap_is_image($('input[name="uploadedFileFastsellImage"]').val()) == true)
            {
                $('.shifterPane_1 .fastSellImage').removeClass('icon-camera').html('<div class="loader">Uploading Your Selected Image</div>');

                $iframe_name	= 'attachIframe_'+ $.scrap_random_string();
                $('.shifterPane_1 .blockFastSellImage').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
                $('.shifterPane_1 .frmFastSellImage').attr('target', $iframe_name);
                $('.shifterPane_1 .frmFastSellImage').submit();

                $('iframe[name="'+ $iframe_name +'"]').load(function()
                {
                    $data		= jQuery.trim($('.shifterPane_1 .blockFastSellImage iframe[name="'+ $iframe_name +'"]').contents().find('body').html());
                    console.log($data);

                    $('.shifterPane_1 .blockFastSellImage .fastSellImage').html('<img src="'+ $data +'" width="250px" alt="">')
                });
            }
            else
            {
                $.scrap_message('Only image files are accepted for the FastSell Image');
                $('.sunMessage .returnFalse').click(function()
                {
                    $('.sunMessage').remove();
                    $.scrap_remove_overlay();
                });
            }
        });
    }

    // ---------- UPLOAD MASTER DATA FILE
    function $fc_upload_master_data_file_2()
    {
        // Buy products popup
        $('body').sunBox.popup('Upload Master Data File', 'popProductsMasterDataFile',
        {
            ajax_path		    : $base_path + 'ajax_handler_products/add_master_data_file_popup_2',
            close_popup		    : false,
            callback 		    : function($return){}
        });

        // Show the popup
        $('.btnUploadProducts').live('click', function()
        {
            $.post($base_path + 'ajax_handler_products/add_master_data_file_popup_3',
            {
                get_it		        : 'get_it'
            },
            function($data)
            {
                $data	            = jQuery.trim($data);

                $('.popProductsMasterDataFile .popup').html($data);
                $('.popProductsMasterDataFile .returnTrue').text('Upload');
                $('body').sunBox.show_popup('popProductsMasterDataFile');
                $('body').sunBox.adjust_popup_height('popProductsMasterDataFile');
            });
        });

        // Change chosen definition
        $('select[name="dropItemDefinitions"]').live('change', function()
        {
            // Some variables
            $fastsell_def_id    = $(this).val();

            // Edit the download link
            $('.popProductsMasterDataFile .downloadTemplate').attr({ href : $base_path + 'fastsells/download_definition/' + $fastsell_def_id });
        });

        // Submit
        $('.popProductsMasterDataFile .returnTrue').live('click', function()
        {
            // Submit the information
            $.scrap_note_loader('Uploading products now');

            $iframe_name	= 'attachIframe_'+ $.scrap_random_string();
            $('.popProductsMasterDataFile .popup').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
            $('.popProductsMasterDataFile .frmProductsMasterDataUpload').attr('target', $iframe_name);
            $('.popProductsMasterDataFile .frmProductsMasterDataUpload').submit();

            $('iframe[name="'+ $iframe_name +'"]').load(function()
            {
                $data		= jQuery.trim($('.popProductsMasterDataFile .popup iframe[name="'+ $iframe_name +'"]').contents().find('body').html());
                //console.log($data);

                // Display error
                if($data == 'wassuccessfullyuploaded')
                {
                    $.scrap_note_time('Products have been uploaded', 4000, 'tick');
                    $('body').sunBox.close_popup('popProductsMasterDataFile');
                    $fc_refresh_added_product_list();
                }
                else
                {
                    $.scrap_note_hide();
                    $.scrap_message($data);
                    $('.sunMessage .returnFalse').live('click', function()
                    {
                        $('.popProductsMasterDataFile').css({ zIndex : '300' });
                        $('.sunMessage').hide();
                    });
                }
            });
        });
    }

    // ---------- UPLOAD MASTER DATA FILE
    function $fc_upload_master_data_file()
    {
        // Buy products popup
        $('body').sunBox.popup('Upload Master Data File', 'popCustomerMasterDataFile',
        {
            ajax_path		    : $base_path + 'ajax_handler_customers/add_master_data_file_popup_2',
            close_popup		    : false,
            callback 		    : function($return){}
        });

        // Show the popup
        $('.btnUploadCustomers').live('click', function()
        {
            $('.popCustomerMasterDataFile .returnTrue').text('Upload');
            $('body').sunBox.show_popup('popCustomerMasterDataFile');
            $('body').sunBox.adjust_popup_height('popCustomerMasterDataFile');
        });

        // Submit
        $('.popCustomerMasterDataFile .returnTrue').live('click', function()
        {
            //$('.frmCustomerMasterDataUpload').submit();

            // Submit the information
            $.scrap_note_loader('Uploading customers now');

            $iframe_name	= 'attachIframe_'+ $.scrap_random_string();
            $('.popCustomerMasterDataFile .popup').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
            $('.popCustomerMasterDataFile .frmCustomerMasterDataUpload').attr('target', $iframe_name);
            $('.popCustomerMasterDataFile .frmCustomerMasterDataUpload').submit();

            $('iframe[name="'+ $iframe_name +'"]').load(function()
            {
                $data		= $('.popCustomerMasterDataFile .popup iframe[name="'+ $iframe_name +'"]').contents().find('body').html();
                //console.log($data);
                $('.chosenUsersList').html($data).show();
                $.scrap_note_time('Customers have been uploaded', 4000, 'tick');
                $('body').sunBox.close_popup('popCustomerMasterDataFile');
            });
        });
    }

    // ----- CUSTOMER LINKS
//    function $fc_customer_links()
//    {
//        $('.userList .btnAddCustomerOne').live('click', function()
//        {
//            // Some variables
//            $this                   = $(this);
//            $parent				    = $this.parents('tr');
//            $customer_id            = $parent.find('.hdCustomerIdOne').text();
//            $event_id               = $('.hdEventId').text();
//            $.scrap_note_loader('Adding customer');
//
//            // Add the customer
//            $.post($ajax_base_path + 'fastsell_customer_link',
//            {
//                event_id		    : $event_id,
//                customer_id			: $customer_id ,
//                type                : 'add'
//            },
//            function($data)
//            {
//                $.scrap_note_hide();
//                $fc_refresh_customer_list();
//            });
//        });
//
//        $('.userList .btnAddAllCustomers').live('click', function()
//        {
//            $.scrap_note_loader('Adding customers');
//            $event_id                   = $('.hdEventId').text();
//
//            $('.userList tbody tr').each(function()
//            {
//                // Some variables
//                $this                   = $(this);
//                $customer_id            = $this.find('.hdCustomerIdOne').text();
////                console.log($customer_id);
//
//                // Add the customer
//                $.post($ajax_base_path + 'fastsell_customer_link',
//                {
//                    event_id		    : $event_id,
//                    customer_id			: $customer_id ,
//                    type                : 'add'
//                },
//                function($data)
//                {
//                });
//            });
//
//            $.scrap_note_hide();
//            $fc_refresh_customer_list();
//        });
//    }

    // ----- REFRESH CUSTOMERS LIST
    function $fc_refresh_customer_list()
    {
        // Some variables
        $event_id               = $('.hdEventId').text();

        $('.chosenUsersList').prepend('<div class="ajaxMessage short2">Refreshing FastSell Customers</div>');
        $('.chosenUsersList table').fadeTo('fast', 0.3);

        // The AJAX call
        $.post($base_path + 'ajax_handler_customers/get_added_customers',
        {
            event_id		    : $event_id
        },
        function($data)
        {
            $data	            = jQuery.trim($data);

            $('.chosenUsersList').html($data);
            $fc_execute_flexigrid();
        });
    }

    // ----- ADD PRODUCT
    function $fc_add_product()
    {
        $('.btnAddProduct').live('click', function()
        {
            // Some variables
            $parent				    = '';
            $error                  = false;
            $this                   = $(this);
            $parent				    = $this.parents('tr');
            $product_id             = $parent.find('.hdProductId').text();
            $stock                  = $parent.find('input[name="inpUnits"]').val();
            $price                  = $parent.find('input[name="inpPrice"]').val();
            $event_id               = $('.hdEventId').text();

            // Clear fields
            $parent.find('input[name="inpUnits"]').val('');
            $parent.find('input[name="inpPrice"]').val('');

            // Add the product
            $.scrap_note_loader('Adding your product');
            $.post($ajax_base_path + 'fastsell_create_product',
            {
                product_id		    : $product_id,
                stock		        : $stock,
                price		        : $price,
                event_id			: $event_id
            },
            function($data)
            {
                $data	            = jQuery.trim($data);

                $.scrap_note_hide('Your product has been added', 4000, 'tick');
                $fc_refresh_added_product_list();
            });
        });
    }

    // ---------- ADD PRODUCTS
    function $fc_add_products()
    {
        // Buy products popup
        $('body').sunBox.popup('Add More Products', 'popAddProducts',
        {
            ajax_path		    : $base_path + 'ajax_handler_products/add_products_popup',
            close_popup		    : false,
            callback 		    : function($return){}
        });

        // Show the popup
        $('.btnAddProductPopup').live('click', function()
        {
            $('.popAddProducts .returnTrue').text('Add All');
            $('body').sunBox.popup_change_width('popAddProducts', 1050);
            $('body').sunBox.show_popup('popAddProducts');
            $('body').sunBox.adjust_popup_height('popAddProducts');

            // Calculate price on percentage
            $('.popAddProducts .inpDiscount').keyup(function()
            {
                // Some variables
                $parents            = $(this).parents('tr');
                $percentage         = $(this).val();
                $msrp               = $parents.find('.hdMSRP').text();

                if($.scrap_is_integer($percentage) == true)
                {
                    // Calculate new value
                    $new_price      = ($msrp * (1 - ($percentage / 100))).toFixed(2);
                    $parents.find('.inpPrice').val($new_price);
                }
            });

            // Clear percent
            $('.popAddProducts .inpPrice').keyup(function()
            {
                // Some variables
                $parents            = $(this).parents('tr');
                $parents.find('.inpDiscount').val('');
            });
        });

        // Add product
        $('.btnAddProduct').live('click', function()
        {
            // Some variables
            $parent				    = '';
            $error                  = false;
            $this                   = $(this);
            $parent				    = $this.parents('tr');
            $product_id             = $parent.find('.hdProductId').text();
            $stock                  = $parent.find('input[name="inpUnits"]').val();
            $price                  = $parent.find('input[name="inpPrice"]').val();
            $event_id               = $('.hdEventId').text();

            // Clear fields
            $parent.find('input[name="inpUnits"]').val('');
            $parent.find('input[name="inpPrice"]').val('');
            $parent.find('input[name="inpDiscount"]').val('');

            // Add the product
            $.scrap_note_loader('Adding your product');
            $.post($base_path + 'ajax_handler_fastsells/fastsell_create_product',
            {
                product_id		    : $product_id,
                stock		        : $stock,
                price		        : $price,
                event_id			: $event_id
            },
            function($data)
            {
                $data	            = jQuery.trim($data);

                $.scrap_note_time('Your product has been added', 4000, 'tick');
                $fc_refresh_added_product_list();
            });
        });

        // Add all
        $('.popAddProducts .returnTrue').live('click', function()
        {
            $.scrap_note_loader('Adding your products');

            $('.popAddProducts .btnAddProduct').each(function()
            {
                // Some variables
                $parent				    = '';
                $error                  = false;
                $this                   = $(this);
                $parent				    = $this.parents('tr');
                $product_id             = $parent.find('.hdProductId').text();
                $stock                  = $parent.find('input[name="inpUnits"]').val();
                $price                  = $parent.find('input[name="inpPrice"]').val();
                $event_id               = $('.hdEventId').text();

                // Clear fields
                $parent.find('input[name="inpUnits"]').val('');
                $parent.find('input[name="inpPrice"]').val('');
                $parent.find('input[name="inpDiscount"]').val('');

                // Add the product
                $.post($base_path + 'ajax_handler_fastsells/fastsell_create_product',
                    {
                        product_id		    : $product_id,
                        stock		        : $stock,
                        price		        : $price,
                        event_id			: $event_id
                    },
                    function($data)
                    {
                        $data	            = jQuery.trim($data);
                        $fc_refresh_added_product_list();
                    });
            });

            $.scrap_note_time('All your products have been added', 4000, 'tick');
        });
    }

    // ---------- ADD A PRODUCT
    function $fc_add_a_product_and_link()
    {
        // Add a document type popup
        $('body').sunBox.popup('Add A New Product', 'popAddProduct',
        {
            ajax_path		: $ajax_base_path_3 + 'add_product_popup_2',
            close_popup		: false,
            callback 		: function($return){}
        });

        // Show the popup
        $('.btnAddProductAndLink').live('click', function()
        {
            $('body').sunBox.popup_change_width('popAddProduct', 790);
            $('body').sunBox.show_popup('popAddProduct');
            $('body').sunBox.adjust_popup_height('popAddProduct');
        });

        // Hide the popup
        $('.popAddProduct .returnFalse').live('click', function()
        {
            $('.popAddProduct input, .popAddProduct textarea').val('');
        });

        // Change item fields
        $('.popAddProduct .definitionSelection').live('click', function()
        {
            // Some variables
            $definition_id          = $(this).find('.hdDefinitionId').text();

            // Edit the DOM
            $('.popAddProduct .definitionSelection.active').removeClass('active');
            $(this).addClass('active');
            $('.popAddProduct .rightColumn').css({ opacity : 0.5 });

            // Get new item fields
            $.post($ajax_base_path_3 + 'get_product_fields_2',
            {
                definition_id		: $definition_id
            },
            function($data)
            {
                if($data == '9876')
                {
                    $.scrap_logout();
                }
                else
                {
                    // Edit DOM
                    $('.popAddProduct .rightColumn').html($data).css({ opacity : 1 });
                    $('body').sunBox.adjust_popup_height('popAddProduct');
                }
            });
        });

        // Upload temp image
        $('.blockProductImage .uploadedFileProductImage').live('change', function()
        {
            if($.scrap_is_image($('.blockProductImage .uploadedFileProductImage').val()) == true)
            {
                $('.blockProductImage .imagePreview').removeClass('icon-camera').html('<div class="loader">Generating Preview</div>');

                $iframe_name	= 'attachIframe_'+ $.scrap_random_string();
                $('.blockProductImage').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
                $('.blockProductImage .frmProductImage').attr('target', $iframe_name);
                $('.blockProductImage .frmProductImage').submit();

                $('iframe[name="'+ $iframe_name +'"]').load(function()
                {
                    $data		= jQuery.trim($('.blockProductImage iframe[name="'+ $iframe_name +'"]').contents().find('body').html());
                    console.log($data);

                    $('.blockProductImage .imagePreview').html('<img src="'+ $data +'" width="312px" alt="">')
                });
            }
            else
            {
                $.scrap_message('Only image files are accepted for the product picture');
                $('.sunMessage .returnFalse').click(function()
                {
                    $('.sunMessage').remove();
                    $('.popAddProduct').css({ zIndex : 300 });
                });
            }
        });

        // Submit the new item definition
        $('.popAddProduct .returnTrue').live('click', function()
        {
            // Some variables
            $error					    = false;
            $product_number		        = $('.popAddProduct input[name="inpProductNumber"]').val();
            $product_stock		        = $('.popAddProduct input[name="inpProductStock"]').val();
            $product_price		        = $('.popAddProduct input[name="inpProductPrice"]').val();
            $product_definition         = $('.popAddProduct .definitionSelection.active').find('.hdDefinitionId').text();
            $product_fields_required    = '';
            $product_fields_extra       = '';

            // Validate
            if($error == false)
            {
                if($product_number.length < 1)
                {
                    $error			= true;
                    $.scrap_note_time('Please provide an product number', 4000, 'cross');
                    $('.popAddProduct input[name="inpProductNumber"]').addClass('redBorder');
                }
            }

            if($error == false)
            {
                if($('.blockProductImage .uploadedFileProductImage').val() != '')
                {
                    if($.scrap_is_image($('.blockProductImage .uploadedFileProductImage').val()) == false)
                    {
                        $error			= true;
                        $.scrap_message('Only image files are accepted for the product picture');
                        $('.sunMessage .returnFalse').click(function()
                        {
                            $('.sunMessage').remove();
                            $('.popAddProduct').css({ zIndex : 300 });
                        });
                    }
                }
            }

            // Successful validation
            if($error == false)
            {
                // Get the indexing fields
                $loop_cnt                   = 0;

                $('.popAddProduct .fieldContainerRequired').each(function()
                {
                    $loop_cnt++;
                    // Some variables
                    $this                   = $(this);
                    if($loop_cnt == 2)
                    {
                        $field_value        = $this.find('textarea').val();
                    }
                    else
                    {
                        $field_value        = $this.find('input').val();
                    }
                    $field_id               = $this.find('.hiddenDiv').text();

                    // Validate
                    if($field_value != '')
                    {
                        $product_fields_required	    += '[';
                        $product_fields_required	    += $field_value + ':';
                        $product_fields_required	    += $field_id;
                        $product_fields_required	    += ']';
                    }
                    else
                    {
                        $product_fields_required	    += '[';
                        $product_fields_required	    += 'NOT_SET:';
                        $product_fields_required	    += $field_id;
                        $product_fields_required	    += ']';
                    }
                });

                $('.popAddProduct .fieldContainerExtra').each(function()
                {
                    $loop_cnt++;
                    // Some variables
                    $this                   = $(this);
                    $field_value            = $this.find('input').val();
                    $field_id               = $this.find('.hiddenDiv').text();

                    // Validate
                    if($field_value != '')
                    {
                        $product_fields_extra	    += '[';
                        $product_fields_extra	    += $field_value + ':';
                        $product_fields_extra	    += $field_id;
                        $product_fields_extra	    += ']';
                    }
                });

                // Submit the new document type for adding
                $.scrap_note_loader('Adding the new product and linking it to the FastSell');

                // Post the data
                $.post($ajax_base_path_3 + 'add_product_and_link',
                {
                    product_number			    : $product_number,
                    product_stock			    : $product_stock,
                    product_price			    : $product_price,
                    product_definition			: $product_definition,
                    product_fields_required	    : $product_fields_required,
                    product_fields_extra	    : $product_fields_extra
                },
                function($data)
                {
                    $data	= jQuery.trim($data);
                    $data   = $data.split('::');

                    if($data[0] == '9876')
                    {
                        $.scrap_logout();
                    }
                    else if($data[0] == 'wassuccessfullycreated')
                    {
                        if($('.popAddProduct input[name="uploadedFileProductImage"]').val() != '')
                        {
                            $.scrap_note_loader('Uploading the product image (This may take a few moments depending on the image size)');

                            $('.popAddProduct input[name="hdProductId"]').val($data[1]);
                            $iframe_name	= 'attachIframe_'+ $.scrap_random_string();
                            $('.popAddProduct .frmProductImage').attr({ 'action' : $base_path + 'ajax_handler_products/add_product_image' });
                            $('.popAddProduct .popup').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
                            $('.popAddProduct .frmProductImage').attr('target', $iframe_name);
                            $('.popAddProduct .frmProductImage').submit();

                            $('iframe[name="'+ $iframe_name +'"]').load(function()
                            {
                                $data		= jQuery.trim($('.popAddProduct .popup iframe[name="'+ $iframe_name +'"]').contents().find('body').html());

                                // Display error
                                if($data == 'wassuccessfullyuploaded')
                                {
                                    $('.popAddProduct input, .popAddProduct textarea').val('');

                                    $fc_refresh_added_product_list();

                                    // Close the popup
                                    $.scrap_note_time('The new product has been added and linked', 4000, 'tick');
                                    $('body').sunBox.close_popup('popAddProduct');
                                    $('.blockProductImage .imagePreview').addClass('icon-camera').html('');
                                    $('.popAddProduct .frmProductImage').attr({ 'action' : $base_path + 'ajax_handler_products/add_product_image_temp' });
                                }
                            });
                        }
                        else
                        {
                            $('.popAddProduct input, .popAddProduct textarea').val('');

                            $fc_refresh_added_product_list();

                            // Close the popup
                            $.scrap_note_time('The new product has been added and linked', 4000, 'tick');
                            $('body').sunBox.close_popup('popAddProduct');
                            $('.blockProductImage .imagePreview').addClass('icon-camera').html('');
                            $('.popAddProduct .frmProductImage').attr({ 'action' : $base_path + 'ajax_handler_products/add_product_image_temp' });
                        }
                    }
                    else
                    {
                        $.scrap_note_time($data[0], 4000, 'cross');
                    }
                });
            }
        });
    }

    // ----- REFRESH PRODUCT LIST
    function $fc_refresh_added_product_list()
    {
        // Some variables
        $event_id               = $('.hdEventId').text();

        $('.ajaxProductsInFastSell').prepend('<div class="ajaxMessage">Refreshing FastSell Products</div>');
        $('.ajaxProductsInFastSell table').fadeTo('fast', 0.3);

        // The AJAX call
        $.post($ajax_base_path + 'get_added_products',
        {
            event_id		    : $event_id
        },
        function($data)
        {
            $data	            = jQuery.trim($data);

            $('.ajaxProductsInFastSell').html($data);
            $fc_execute_flexigrid_2();
        });
    }

	// ----- SETUP THE SHIFTER
	function $fc_set_shifter()
	{
		// Count how many shifters there are
		$('.shifter .shifterBlock').each(function()
		{
			$cnt_shifters++;
		});
		
		// Set the shifter width
		$new_width			= $cnt_shifters * 154;
		$('.shifter').width($new_width);
		
		// Insert the active shifter
		$ac_middle_shifters	= $cnt_shifters - 2;
		
		while($loop_cnt_1 < $ac_middle_shifters)
		{
			// Increase the loop
			$loop_cnt_1++;
			
			// Insert the DOMS
			$('.shifter .activeBar .shifterStart').after('<div class="shifterBlockActive">Shifter Active End</div>');
		}
	}
	
	// ----- THE SHIFTER NAVIGATION
	function $fc_shifter_navigation()
	{
		// Next pane
		$('.shifterNav .btnShifterNext').live('click', function()
		{
            // Some variables
            $this					= $(this);
            $pane_position		    = jQuery.trim($('.hdPanePosition').text());

            // Check that you cant go further
            if($pane_position != $cnt_shifters)
            {
                // Increase the pane position
                $pane_position++;
                $('.hdPanePosition').text($pane_position);

                // Get the scroll position
                $scroll_pos			= $('body').scrollTop();

                // Create / Update the event
                if($pane_position == 2)
                {
                    // Some variables
                    $error                      = false;
                    $fastsell_name              = $('input[name="inpShowName"]').val();
                    $fastsell_description       = $('textarea[name="inpShowDescription"]').val();
                    $terms_and_conditions       = $('textarea[name="inpTermsAndConditions"]').val();
                    $start_date                 = $('input[name="inpStartDate"]').val();
                    $start_hour                 = $('select[name="startHoursSelect"]').val();
                    $start_minute               = $('select[name="startMinutesSelect"]').val();
                    $end_date                   = $('input[name="inpEndDate"]').val();
                    $end_hour                   = $('select[name="endHoursSelect"]').val();
                    $end_minute                 = $('select[name="endMinutesSelect"]').val();
                    $event_id                   = $('.hdEventId').text();
                    $event_banner               = $('.hdBannerImagePath').text();
                    $categories                 = '';

                    if($error == false)
                    {
                        if($fastsell_name.length < 1)
                        {
                            $error			= true;
                            $.scrap_note_time('Please provide a title for your FastSell event', 4000, 'cross');
                            $('input[name="inpShowName"]').addClass('redBorder');
                        }
                    }
                    if($error == false)
                    {
                        if($.scrap_check_date($start_date) == false)
                        {
                            $error			= true;
                            $.scrap_note_time('You need a start date for your FastSell', 4000, 'cross');
                            $('input[name="inpStartDate"]').addClass('redBorder');
                        }
                    }
                    if($error == false)
                    {
                        if($.scrap_check_date($end_date) == false)
                        {
                            $error			= true;
                            $.scrap_note_time('You need an end date for your FastSell', 4000, 'cross');
                            $('input[name="inpEndDate"]').addClass('redBorder');
                        }
                    }
                    if($error == false)
                    {
                        $('.shifterPane_1 .catBack .breadCrumb.last').each(function()
                        {
                            $categories         += '[' + $(this).find('.hdCategoryId').text() + ']';
                        });

                        if($categories == '')
                        {
                            $error			= true;
                            $.scrap_note_time('At least 1 FastSell category is required', 4000, 'cross');
                            $('input[name="inpCategorySearch"]').addClass('redBorder').focus();
                        }
                    }

                    if($error == false)
                    {
                        // Create / Update fastsell call
                        if($event_id == 'no_id')
                        {
                            $fastsell_path          = 'create_fastsell';
                            $.scrap_note_loader('Creating a basic FastSell event');
                        }
                        else
                        {
                            $fastsell_path          = 'update_fastsell';
                            $.scrap_note_loader('Updating your FastSell event');
                        }
                        $.post($ajax_base_path + $fastsell_path,
                        {
                            fastsell_name	        : $fastsell_name,
                            fastsell_description	: $fastsell_description,
                            terms_and_conditions    : $terms_and_conditions,
                            start_date		        : $start_date,
                            start_hour			    : $start_hour,
                            start_minute			: $start_minute,
                            end_date			    : $end_date,
                            end_hour			    : $end_hour,
                            end_minute			    : $end_minute,
                            event_id			    : $event_id,
                            event_banner	        : $event_banner,
                            categories              : $categories
                        },
                        function($data)
                        {
                            $data	= jQuery.trim($data);
                            console.log($data);

                            if($data == '9876')
                            {
                                $.scrap_logout();
                            }
                            else
                            {
                                $ex_data            = $data.split(':');

                                if($ex_data[0] == 'okitsbeencreated')
                                {
                                    $.scrap_note_time('This FastSell event has been created', 4000, 'tick');
                                    $('.hdEventId').text($ex_data[1]);

                                    if($('input[name="uploadedFileFastsellImage"]').val() != '')
                                    {
                                        $iframe_name	= 'attachIframe_'+ $.scrap_random_string();
                                        $('.blockFastSellImage .frmFastSellImage').attr({ 'action' : $base_path + 'ajax_handler_fastsells/add_event_image' });
                                        $('.blockFastSellImage').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
                                        $('.blockFastSellImage .frmFastSellImage').attr('target', $iframe_name);
                                        $('.blockFastSellImage .frmFastSellImage').submit();

                                        $('iframe[name="'+ $iframe_name +'"]').load(function()
                                        {
                                            $data		= jQuery.trim($('.blockFastSellImage iframe[name="'+ $iframe_name +'"]').contents().find('body').html());
                                        });
                                    }
                                }
                                else if($ex_data[0] == 'okitsbeenupdated')
                                {
                                    $.scrap_note_time('This FastSell event has been updated', 4000, 'tick');
                                }
                                else
                                {
                                    $.scrap_note_time($data, 4000, 'cross');

                                    // Edit the DOM
                                    $('.hdPanePosition').text(1);
                                    $pane_position          = 1;
                                    $('.shifterNav').show();
                                    $('.shifterPane_1').show();
                                    $('.shifterPane_2').hide();
                                }
                            }
                        });
                    }
                    else
                    {
                        // Edit the DOM
                        $('.hdPanePosition').text(1);
                        $pane_position          = 1;
                        $('.shifterNav').show();
                    }
                }

                // Edit the variables
                if($pane_position == $cnt_shifters)
                {
                    // Hide next button
                    $this.parent().hide();

                    // Show the complete button
                    $('.shifterNav .btnComplete').parent().show();
                }

                // Shifter the indicator
                $fc_shift_indicator();

                // Scroll pane
                $fc_fix_position();

                // Modify button DOMs
                $('.shifterNav .btnShifterBack').parent().show();
            }
		});
		
		// Previous pane
		$('.shifterNav .btnShifterBack').live('click', function()
		{
			// Some variables
			$this					= $(this);
            $pane_position		    = jQuery.trim($('.hdPanePosition').text());
			
			// Check that you aren't at the beginning
			if($pane_position > 1)
			{
				// Decrease pane position
                $pane_position--;
                $('.hdPanePosition').text($pane_position);
				
				// Get the scroll position
				$scroll_pos			= $('body').scrollTop();
				
				// Scroll pane
				$fc_fix_position();
				
				// Modify button DOMs
				$('.shifterNav .btnShifterNext').parent().show();
				$('.shifterNav .btnComplete').parent().hide();
				
				if($pane_position == 1)
				{
					// Hide back button
					$this.parent().hide();
				}
				
				// Shifter the indicator
				$fc_shift_indicator();
			}
		});
	}

	// ----- FIX POSITION
	function $fc_fix_position()
	{
		// Make the adjustment
		$('body').scrollTop($offset.top - 50);

        // Change which panes show
        $pane_position		    = jQuery.trim($('.hdPanePosition').text());
        $('.shifterPane').hide();
        $('.shifterPane_' + $pane_position).fadeIn();
	}

	// ----- SHIFT THE INDICATOR
	function $fc_shift_indicator()
	{
		// Some variables
		if($pane_position == 1)
		{
			$new_width					= 83;
		}
		else
		{
			$new_width					= ($pane_position * 154) - 71;
		}
		
		// Animate the completed indicator
		$('.shifter .activeBarContain').delay(100).animate({ width : $new_width }, 500, 'easeOutCubic');
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
//    function $fc_refresh_customer_list()
//    {
//        // Some variables
//        $event_id               = $('.hdEventId').text();
//
//        // The AJAX call
//        $.post($base_path + 'ajax_handler_customers/get_added_customers',
//        {
//            event_id		    : $event_id
//        },
//        function($data)
//        {
//            $data	            = jQuery.trim($data);
//            //console.log($data);
//
//            $('.chosenUsersList').html($data);
//        });
//    }

    // ---------- REMOVE A PRODUCT
    function $fc_remove_product()
    {
        $('.btnRemoveProduct').live('click', function()
        {
            // Some variables
            $this               = $(this);
            $parent             = $this.parents('.extraOptions');
            $product_id         = $parent.find('.hdProductId').text();
            $event_id           = $('.hdEventId').text();

            $.scrap_note_loader('Removing your product');
            $.post($base_path + 'ajax_handler_fastsells/fastsell_remove_product',
                {
                    product_id		    : $product_id,
                    event_id			: $event_id
                },
                function($data)
                {
                    $data	            = jQuery.trim($data);

                    if($data == 'okitsbeenremoved')
                    {
                        $.scrap_note_time('Your product has been removed', 4000, 'tick');
                        $fc_refresh_added_product_list();
                    }
                    else
                    {
                        $.scrap_note_time($data, 4000, 'cross');
                    }
                });
        });
    }

});