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

    $fc_search_customers();

    $fc_pagenate();

    $fc_search();

    $fc_pagenate();


// ------------------------------------------------------------------------------FUNCTIONS

    // ---------- SEARCH CUSTOMERS
    function $fc_search_customers()
    {
        var delay = (function()
        {
            var timer = 0;
            return function(callback, ms)
            {
                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();

        $('.popAddGroup input[name="inpSearchCustomerText"]').live('keyup', function()
        {
            delay(function()
                {
                    $input		= $('.popAddGroup input[name="inpSearchCustomerText"]').val().toLowerCase();

                    if($input.length > 2)
                    {
                        // Show all
                        $('.popAddGroup .customerCheckContain').show();

                        // Hide all that don't check out
                        $('.popAddGroup .customerCheckContain').each(function()
                        {
                            $emp_name	= $(this).find('.customerName').text().toLowerCase();

                            if($emp_name.match($input) == null)
                            {
                                $(this).hide();
                            }
                        });

                        $('body').sunBox.adjust_popup_height('popAddGroup');
                    }
                    else
                    {
                        // Hide all that don't check out
                        $loop_cnt_customer              = 0;
                        $('.popAddGroup .customerCheckContain').hide();
                        $('.popAddGroup .customerCheckContain').each(function()
                        {
                            $loop_cnt_customer++;
                            if($loop_cnt_customer <= 50)
                            {
                                $(this).show();
                            }
                            else
                            {
                                return false;
                            }
                        });
                        $('body').sunBox.adjust_popup_height('popAddGroup');
                    }
                },
                500
            );
        });

        $('.popEditGroup input[name="inpSearchCustomerText"]').live('keyup', function()
        {
            delay(function()
                {
                    $input		= $('.popEditGroup input[name="inpSearchCustomerText"]').val().toLowerCase();

                    if($input.length > 2)
                    {
                        // Show all
                        $('.popEditGroup .customerCheckContain').show();

                        // Hide all that don't check out
                        $('.popEditGroup .customerCheckContain').each(function()
                        {
                            $emp_name	= $(this).find('.customerName').text().toLowerCase();

                            if($emp_name.match($input) == null)
                            {
                                $(this).hide();
                            }
                        });

                        $('body').sunBox.adjust_popup_height('popEditGroup');
                    }
                    else
                    {
                        // Show all
                        // Hide all that don't check out
                        $loop_cnt_customer              = 0;
                        $('.popEditGroup .customerCheckContain').hide();
                        $('.popEditGroup .customerCheckContain').each(function()
                        {
                            $loop_cnt_customer++;
                            if($loop_cnt_customer <= 50)
                            {
                                $(this).show();
                            }
                            else
                            {
                                return false;
                            }
                        });
                        $('body').sunBox.adjust_popup_height('popEditGroup');
                    }
                },
                500
            );
        });
    }

    // ----- PAGENATE
    function $fc_pagenate()
    {
        // Previous page
        $('.btnPrevPage').live('click', function()
        {
            if($crt_page_num > 1)
            {
                $('input[name="hdOffset"]').val(parseInt($crt_page_num) - 1);
                $('.frmSearch').submit();
            }
        });


        // Next page
        $('.btnNextPage').live('click', function()
        {
            if($crt_page_num < $page_max)
            {
                $('input[name="hdOffset"]').val(parseInt($crt_page_num) + 1);
                $('.frmSearch').submit();
            }
        });


        // Number list click
        $('.btnCrtPage').live('click', function()
        {
            if($('.pagingState').hasClass('active'))
            {
                if($('.numList').is(":visible") == false)
                {
                    $('.numList').fadeIn(200);
                }
                else
                {
                    $('.numList').fadeOut(200);
                }
            }

            $('.numList').hover(function()
                {
                    $mouse_is_inside_3	= true;
                },
                function()
                {
                    $mouse_is_inside_3	= false;
                });

            $('body').mouseup(function()
            {
                if(!$mouse_is_inside_3)
                {
                    $('.numList').fadeOut(200);
                }
            });
        });

        // Number list selection
        $('.listPageNum').live('click', function()
        {
            $list_num			= parseInt($(this).text());
            $('input[name="hdOffset"]').val($list_num);
            $('.frmSearch').submit();
        })
    }

    // ----- SAVE PRODUCT CHANGES
    function $fc_search()
    {
        $('.frmSearch input').focus();

        $('.btnSearch').live('click', function()
        {
            $('.frmSearch').submit();
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

    // ----- EDIT A CUSTOMER
    function $fc_edit_customer()
    {
        // Edit group popup
        $('body').sunBox.popup('Edit A Customer', 'popEditCustomer',
        {
            ajax_path		: $ajax_base_path + 'edit_customer_popup',
            close_popup		: false,
            callback 		: function($return){}
        });

        // Open popup
        $('.btnEditCustomer').on('click', function()
        {
            // Some variables
            $parent                     = $(this).parents('tr');
            $customer_id                = $parent.find('.hdCustomerId').text();
            $customer_name              = $parent.find('.fullCell').text();
            $customer_to_show_host_id   = $parent.find('.hdCustomerToShowHostId').text();

            // Send the delete request
            $.post($ajax_base_path + 'get_customer_popup_content',
            {
                customer_to_show_host_id	        : $customer_to_show_host_id
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
                    $('.popEditCustomer .popup').html($data);
                    $.scrap_uniform_update('.popEditCustomer input');
                    $('body').sunBox.show_popup('popEditCustomer');
                    $('body').sunBox.adjust_popup_height('popEditCustomer');
                }
            });
        });

        // Edit the customer
        $('.popEditCustomer .returnTrue').live('click', function()
        {
            // Some variables
            $error              = false;

            // Validate
            if($error == false)
            {
                if($('.popEditCustomer input[name="inpCustomerName"]').val().length < 1)
                {
                    $error      = true;
                    $('.popEditCustomer input[name="inpCustomerName"]').addClass('redBorder');
                    $.scrap_note_time('Please provide a customer name', 4000, 'cross');
                }
            }

            // Submit
            if($error == false)
            {
                $('.popEditCustomer input[name="hdReturnUrl"]').val($('#hdReturnUrl').val());
                $('.frmEditCustomer').submit();
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