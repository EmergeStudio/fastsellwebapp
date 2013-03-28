$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_customers/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';
    var $crt_page_num           = $('input[name="scrap_pageNo"]').val();
    var $page_max               = $('input[name="scrap_pageMax"]').val();


// ------------------------------------------------------------------------------EXECUTE

    $fc_add_group();

    $fc_delete_group();

    $fc_edit_group();

    $fc_add_customers();

    $fc_edit_customer();

    $fc_delete_customer();

    $fc_execute_flexigrid();

    $fc_execute_page_height();

    $(window).resize($fc_execute_page_height);

    $(document).mousemove(function($e)
    {
        $mouse_x                = $e.pageX;
        $mouse_y                = $e.pageY;
    });

    $fc_pagenate();

    $fc_resend_invitation();


// ------------------------------------------------------------------------------FUNCTIONS

    // ----- RESEND INVITATION
    function $fc_resend_invitation()
    {
        $('.btnResend').live('click', function()
        {
            // Some variables
            $this               = $(this);
            $parent             = $this.parents('tr');
            $customer_id        = $parent.find('td:first div').text();

            // Validate
            $('body').sunBox.message(
            {
                content			: 'Are you sure you would like to resend this invitation?',
                btn_true		: 'I\'m Sure',
                btn_false		: 'No Don\'t Send',
                message_title	: 'Just Checking',
                callback		: function($return)
                {
                    if($return == true)
                    {
                        // Loader
                        $.scrap_note_loader('Resending invitation');

                        // Send the delete request
                        $.post($ajax_base_path + 'resend_invitation',
                        {
                            customer_id	        : $customer_id
                        },
                        function($data)
                        {
                            $data	            = jQuery.trim($data);

                            if($data == '9876')
                            {
                                $.scrap_logout();
                            }
                            else if($data == 'okitsdone')
                            {
                                // Refresh the data
                                $.scrap_note_hide_time('The invitation has been sent', 4000, 'tick');
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

    // ----- PAGENATE
    function $fc_pagenate()
    {
        // Previous page
        $('.pGroup .pPrev').live('click', function()
        {
            if($crt_page_num > 1)
            {
                $('input[name="hdOffset"]').val(parseInt($crt_page_num) - 1);
                $('.frmSearch').submit();
            }
        });

        // Next page
        $('.pGroup .pNext').live('click', function()
        {
            if($crt_page_num < $page_max)
            {
                $('input[name="hdOffset"]').val(parseInt($crt_page_num) + 1);
                $('.frmSearch').submit();
            }
        });

        // First page
        $('.pGroup .pFirst').live('click', function()
        {
            $('input[name="hdOffset"]').val(1);
            $('.frmSearch').submit();
        });

        // Last page
        $('.pGroup .pLast').live('click', function()
        {
            $('input[name="hdOffset"]').val(parseInt($page_max));
            $('.frmSearch').submit();
        });

        // Change limit
        $('.pGroup select[name="rp"]').live('change', function()
        {
            // Value
            $value                  = $(this).val();
            $('input[name="hdLimit"]').val($value);
            $('.frmSearch').submit();
        });
    }

    // ---------- EDIT A CUSTOMER
    function $fc_edit_customer()
    {
        $('.editIt').mouseup(function()
        {
            if(($("input[type=checkbox].switch.chkboxEdit").is(':checked')) && ($('.scrapEdit').is(':hidden')))
            {
                // Some variables
                $cell_content       = $(this).find('div:first').text();
                $crt_cell           = $(this);
                $crt_cell.addClass('eHighLight');

                // Show the DOM
                $('.scrapEdit').fadeIn('fast').css({ left : ($mouse_x - 138), top : $mouse_y });
                $('.scrapEdit input[name="inpScrapEdit"]').val($cell_content).focus();
            }
        });

        // Close edit
        $('.scrapEdit .btnCancel').click(function()
        {
            $('.scrapEdit').fadeOut('fast');
            $('.flexigrid .eHighLight').removeClass('eHighLight');
        });
        $('.bDiv').scroll(function()
        {
            $('.scrapEdit').fadeOut('fast');
            $('.flexigrid .eHighLight').removeClass('eHighLight');
        });

        // Save the field
        $('.scrapEdit .btnSave').click(function()
        {
            // Some variables
            $new_value              = $('.scrapEdit input[name="inpScrapEdit"]').val();
            $edits                  = '';

            // Get all the edit details
            $('.editIt.eHighLight').each(function()
            {
                $edits              += '['+ $(this).attr('id') +']';
            });

            // Edit the DOM
            $('.scrapEdit').fadeOut('fast');
            $('.flexigrid .eHighLight').find('div:first').text($('.scrapEdit input[name="inpScrapEdit"]').val());
            $crt_cell.find('div:first').text($('.scrapEdit input[name="inpScrapEdit"]').val());
            $('.flexigrid .eHighLight').removeClass('eHighLight');

            // Scrap note
            $.scrap_note_loader('Making the changes');

            // Submit the changes
            $.post($ajax_base_path + 'save_customer_changes',
            {
                new_value	        : $new_value,
                edits               : $edits
            },
            function($data)
            {
                $data	            = jQuery.trim($data);

                if($data == '9876')
                {
                    $.scrap_logout();
                }
                else
                {
                    $.scrap_note_time('The customer information has been updated', 4000, 'tick');
                }
            });
        });

        $("#flex1").eHighLight(
        {
            'elements'      : ['td.editIt'],
            'onEndDragging' : function()
            {
                if(($("input[type=checkbox].switch.chkboxEdit").is(':checked')) && ($('.scrapEdit').is(':hidden')) && ($('.eHighLight').is(':visible')))
                {
                    // Some variables
                    $cell_content       = $(this).find('div:first').text();
                    $crt_cell           = $(this);

                    // Show the DOM
                    $('.scrapEdit').fadeIn('fast').css({ left : ($mouse_x - 138), top : $mouse_y });
                    $('.scrapEdit input[name="inpScrapEdit"]').val($cell_content).focus();
                }
            },
            'onStartDragging' : function()
            {
                $('.scrapEdit').fadeOut('fast');
            }
        });
    }

    function $fc_execute_page_height()
    {
        // Some variables
        $window_h                   = $(window).height();

        // Adjust height
        $('.flexigrid .bDiv').height($window_h - 330);
    }

    function $fc_execute_flexigrid()
    {
        // Get field headings
        $field_headings             = $('.hdFieldHeadings').text();
        $ex_field_headings          = $field_headings.split('][');
        $limit                      = $('input[name="hdLimit"]').val();
        $page                       = $('input[name="scrap_pageNo"]').val();
        $total                      = $('input[name="scrap_pageMax"]').val();
        var $ar_fields              = new Array();

        // Predefined headings
        $ar_fields.push({ display: 'ID', name : 'productId', width : 50, sortable : false, align: 'center' });
        $ar_fields.push({ display: 'Company Name', name : 'companyName', width : 300, sortable : false, align: 'left' });
        $ar_fields.push({ display: 'Customer Number', name : 'customerNumber', width : '200', sortable : true, align: 'left' });
        $ar_fields.push({ display: 'State', name : 'companyState', width : 200, sortable : false, align: 'left' });
        $ar_fields.push({ display: 'First Name', name : 'viewOrders', width : '150', sortable : true, align: 'left' });
        $ar_fields.push({ display: 'Last Name', name : 'viewOrders', width : '150', sortable : true, align: 'left' });
        $ar_fields.push({ display: 'Email Address', name : 'viewOrders', width : '150', sortable : true, align: 'left' });
        $ar_fields.push({ display: 'Groups', name : 'groups', width : '200', sortable : true, align: 'left' });
        $ar_fields.push({ display: '', name : 'viewOrders', width : '80', sortable : true, align: 'left' });

        $("#flex1").flexigrid
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

    // ----- EDIT A GROUP
    function $fc_edit_group()
    {
        // Edit group popup
        $('body').sunBox.popup('Edit A Group', 'popEditGroup',
        {
            ajax_path		: $ajax_base_path + 'edit_group_popup',
            close_popup		: false,
            callback 		: function($return){}
        });

        // Open popup
        $('.btnEditGroup').on('click', function()
        {
            // Some variables
            $parent                     = $(this).parents('.groupContainer');
            $group_name                 = $parent.find('a').text();
            $group_id                   = $parent.find('.hdGroupId').text();

            $.scrap_note_loader('Getting Group Information');

            // Send the delete request
            $.post($ajax_base_path + 'get_group_popup_content',
            {
                group_id	        : $group_id
            },
            function($data)
            {
                $data	            = jQuery.trim($data);

                if($data == '9876')
                {
                    $.scrap_logout();
                }
                else
                {
                    // Show the popup
                    $('.popEditGroup .popup').html($data);
                    $.scrap_uniform_update('.popEditGroup input');
                    $('body').sunBox.show_popup('popEditGroup');
                    $('body').sunBox.adjust_popup_height('popEditGroup');
                }

                $.scrap_note_hide();
            });
        });

        // Edit the customer group
        $('.popEditGroup .returnTrue').live('click', function()
        {
            // Some variables
            $error              = false;

            // Validate
            if($error == false)
            {
                if($('.popEditGroup input[name="inpCustomerGroup"]').val().length < 1)
                {
                    $error      = true;
                    $('.popEditGroup input[name="inpCustomerGroup"]').addClass('redBorder');
                    $.scrap_note_time('Please provide a group name', 4000, 'cross');
                }
            }

            // Submit
            if($error == false)
            {
                $('.popEditGroup input[name="hdReturnUrl"]').val($('#hdReturnUrl').val());
                $('.frmEditGroup').submit();
            }
        });
    }

    // ----- DELETE GROUP
    function $fc_delete_group()
    {
        $('.btnDeleteGroup').live('click', function()
        {
            // Some variables
            $parent                     = $(this).parents('.groupContainer');
            $group_name                 = $parent.find('a').text();
            $group_id                   = $parent.find('.hdGroupId').text();

            // Validate
            $('body').sunBox.message(
            {
                content			: 'You sure you want to delete the <b>'+ $group_name +'</b> group?',
                btn_true		: 'Yup I\'m Sure',
                btn_false		: 'Oh Gosh No!',
                message_title	: 'Just Checking',
                callback		: function($return)
                {
                    if($return == true)
                    {
                        // Loader
                        $.scrap_note_loader('Deleting the group');

                        // Send the delete request
                        $.post($ajax_base_path + 'delete_group',
                        {
                            group_id	        : $group_id
                        },
                        function($data)
                        {
                            $data	            = jQuery.trim($data);

                            if($data == '9876')
                            {
                                $.scrap_logout();
                            }
                            else if($data == 'okitsdone')
                            {
                                // Refresh the data
                                $parent.remove();
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

    // ----- ADD GROUP
    function $fc_add_group()
    {
        // Add group popup
        $('body').sunBox.popup('Add A Group', 'popAddGroup',
        {
            ajax_path		: $ajax_base_path + 'add_customer_group_popup',
            close_popup		: false,
            callback 		: function($return){}
        });

        // Open popup
        $('.btnAddGroup').on('click', function()
        {
            $('body').sunBox.show_popup('popAddGroup');
            $('body').sunBox.adjust_popup_height('popAddGroup');
        });

        // Add the customer group
        $('.popAddGroup .returnTrue').live('click', function()
        {
            // Some variables
            $error              = false;

            // Validate
            if($error == false)
            {
                if($('.popAddGroup input[name="inpCustomerGroup"]').val().length < 1)
                {
                    $error      = true;
                    $('.popAddGroup input[name="inpCustomerGroup"]').addClass('redBorder');
                    $.scrap_note_time('Please provide a group name', 4000, 'cross');
                }
            }

            // Submit
            if($error == false)
            {
                $('.frmAddGroup').submit();
            }
        });
    }

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
            $('body').sunBox.popup_change_width('popAddCustomer', 1200);
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
                $.post($ajax_base_path + 'add_customer',
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
                        $('.'+ $this_row +' .loader').addClass('cross removeFromList').removeClass('loader');
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

                        $('.popAddCustomer .hdGroupIds').text('');
                        $('.popAddCustomer input').val('');
                        $('.popAddCustomer input:first').focus();
                        $('.'+ $this_row +' .loader').addClass('tick').removeClass('loader');

                        // Refresh the data
                        $fc_refresh_customer_list($list_type);
                        $fc_refresh_groups_list();
                        $fc_refresh_groups_autocomplete();
                    }
                });
            }
        });

        // Remove customer from the list
        $('.popAddCustomer .removeFromList').live('click', function()
        {
            $(this).parents('tr').remove();
            $('body').sunBox.adjust_popup_height('popAddCustomer');
        });
    }

    // ----- REFRESH GROUPS AVAILABLE
    function $fc_refresh_groups_autocomplete($list_type)
    {
        // Get the customers
        $.post($ajax_base_path + 'get_groups_for_autocomplete',
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

    // ----- REFRESH GROUPS LIST
    function $fc_refresh_groups_list($list_type)
    {
        // Get the customers
        $.post($ajax_base_path + 'get_groups_list',
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
                $('.rightContent .content').html($data);
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
                    $('.listContain').html($data);
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