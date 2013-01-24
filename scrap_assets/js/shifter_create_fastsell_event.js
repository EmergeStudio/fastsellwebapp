$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP
	
	var $cnt_shifters		= 0;
	var $loop_cnt_1			= 0;
	var $scroll_pos			= 0;
	var $offset 			= $('.shifter').offset();

    // ---------- AJAX PATHS
    var $base_path				= $('#hdPath').val();
    var $ajax_base_path 		= $base_path + 'ajax_handler_fastsells/';


// ------------------------------------------------------------------------------EXECUTE
	
	$fc_set_shifter();
	
	$fc_shifter_navigation();

    $fc_customer_links();

    $fc_add_product();

    $fc_add_products();
	
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
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ---------- UPLOAD TEMP IMAGE
    function $fc_upload_temp_image()
    {
        $('input[name="uploadedFileFastsellImage"]').live('change', function()
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
            $('.popAddProducts .returnTrue').hide();
            $('body').sunBox.popup_change_width('popAddProducts', 1050);
            $('body').sunBox.show_popup('popAddProducts');
            $('body').sunBox.adjust_popup_height('popAddProducts');
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

            console.log($product_id + ' -- ' + $stock + ' -- ' + $price + ' -- ' + $event_id);

            // Clear fields
            $parent.find('input[name="inpUnits"]').val('');
            $parent.find('input[name="inpPrice"]').val('');

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
    }

    // ----- REFRESH PRODUCT LIST
    function $fc_refresh_added_product_list()
    {
        // Some variables
        $event_id               = $('.hdEventId').text();

        // The AJAX call
        $.post($ajax_base_path + 'get_added_products',
        {
            event_id		    : $event_id
        },
        function($data)
        {
            $data	            = jQuery.trim($data);
            //console.log($data);

            $('.ajaxProductsInFastSell').html($data);
        });
    }

    // ----- CUSTOMER LINKS
    function $fc_customer_links()
    {
        $('.userList input[name="checkAddCustomer"]').live('change', function()
        {
            // Some variables
            $this                   = $(this);
            $customer_id            = $this.val();
            $parent				    = $this.parents('tr');
            $event_id               = $('.hdEventId').text();

            // Edit DOM
            $('.chosenUsers p, .chosenUsers .divHeight:first').hide();
            $('.chosenUsers .message').addClass('usersSelected');
            $('.chosenUsers .message .divHeight').css({ height : 20 });
            $('.chosenUsers .chosenUsersList').fadeIn();

            // Link customers
            $('.chosenUsersList .coolTable').prepend($parent);

            // Add the customer
            $.post($ajax_base_path + 'fastsell_customer_link',
            {
                event_id		    : $event_id,
                customer_id			: $customer_id ,
                type                : 'add'
            }, function($data){ console.log($data); });
        });

        $('.chosenUsers input[name="checkAddCustomer"]').live('change', function()
        {
            // Some variables
            $this                   = $(this);
            $parent				    = $this.parents('tr');

            // Unlink customers
            $('.userList .coolTable').prepend($parent);

            // Add the customer
            $.post($ajax_base_path + 'fastsell_customer_link',
            {
                event_id		    : $event_id,
                customer_id			: $customer_id,
                type                : 'remove'
            }, function($data){ console.log($data); });
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
                            event_banner	        : $event_banner
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
                                        $('.blockFastSellImage .frmFastSellImage').attr({ 'action' : $base_path + 'ajax_handler_fastsells' });
                                        $('.blockFastSellImage').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
                                        $('.blockFastSellImage .frmFastSellImage').attr('target', $iframe_name);
                                        $('.blockFastSellImage .frmFastSellImage').submit();

                                        $('iframe[name="'+ $iframe_name +'"]').load(function()
                                        {
                                            $data		= jQuery.trim($('.blockFastSellImage iframe[name="'+ $iframe_name +'"]').contents().find('body').html());
                                            console.log($data);
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
    function $fc_refresh_customer_list()
    {
        // Some variables
        $event_id               = $('.hdEventId').text();

        // The AJAX call
        $.post($base_path + 'ajax_handler_customers/get_added_customers',
        {
            event_id		    : $event_id
        },
        function($data)
        {
            $data	            = jQuery.trim($data);
            //console.log($data);

            $('.chosenUsersList').html($data);
        });
    }

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