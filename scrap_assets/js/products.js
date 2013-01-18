$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_products/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE

    $fc_adjust_product_height();

    $fc_add_a_product();

    $fc_view_product();

    $fc_save_product_changes();

    $fc_search();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ----- SAVE PRODUCT CHANGES
    function $fc_search()
    {
        $('.frmSearch input').focus();

        $('.btnSearch').live('click', function()
        {
            $('.frmSearch').submit();
        });
    }

    // ----- SAVE PRODUCT CHANGES
    function $fc_save_product_changes()
    {
        $('.rightContent .itemInformation .btnSaveProductChanges').live('click', function()
        {
            // Some variables
            $product_fields             = '';
            $product_id                 = $('.rightContent .itemInformation .hdProductId').text();
            $product_number             = $('.rightContent .itemInformation input[name="itemNumber"]').val();

            // Get fields
            $('.rightContent .itemInformation .fieldContainer').each(function()
            {
                $field_id               = $(this).find('.hdDefinitionFieldId').text();
                $field_value            = $(this).find('input[name="productField"]').val();
                if($field_id == 2)
                {
                    $field_value        = $(this).find('textarea[name="productField"]').val();
                }

                $product_fields         += '[' + $field_id + '::' + $field_value + ']';
            });

            // Get new item fields
            $.scrap_note_loader('Updating the product');

            $.post($ajax_base_path + 'update_product_fields',
            {
                product_fields		    : $product_fields,
                product_id              : $product_id,
                product_number          : $product_number
            },
            function($data)
            {
                $data                   = jQuery.trim($data);
                //console.log($data);

                if($data == '9876')
                {
                    $.scrap_logout();
                }
                else
                {
                    $.scrap_note_time('The product has been update', 4000, 'tick');
                    $fc_refresh_products_list();
                }
            });
        });

        $('.rightContent .itemInformation input[name="uploadedFileProductImage2"]').live('change', function()
        {
            $.scrap_note_loader('Uploading the new product image');

            $iframe_name	= 'attachIframe_'+ $.scrap_random_string();
            $('.rightContent .itemInformation').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
            $('.rightContent .itemInformation .frmProductImage2').attr('target', $iframe_name);
            $('.rightContent .itemInformation .frmProductImage2').submit();

            $('iframe[name="'+ $iframe_name +'"]').load(function()
            {
                $data		= jQuery.trim($('.rightContent .itemInformation iframe[name="'+ $iframe_name +'"]').contents().find('body').html());
                $('.rightContent .itemInformation img').attr({ 'src' : $data });
                $.scrap_note_time('The product image has been uplaoded', 4000, 'tick');
                $fc_refresh_products_list();
            });
        });
    }

    // ----- VIEW PRODUCT
    function $fc_view_product()
    {
        $('.itemContainer').live('click', function()
        {
            // Some variables
            $this                   = $(this);
            $product_information    = $this.find('.productInformation').html();

            // Edit DOM
            $('.nothingSelected').hide();
            $('.rightContent .itemInformation').html($product_information);
            $fc_adjust_product_height();
        });
    }

    // ----- ADJUST PRODUCT HEIGHT
    function $fc_adjust_product_height()
    {
        // Some variables
        $left_height            = $('.leftContent').height();
        $right_height           = $('.rightContent').height();

        if($right_height > $left_height)
        {
            $('.leftContent').height($right_height);
        }
    }

    // ---------- ADD A PRODUCT
    function $fc_add_a_product()
    {
        // Add a document type popup
        $('body').sunBox.popup('Add A Product', 'popAddProduct',
        {
            ajax_path		: $ajax_base_path + 'add_product_popup',
            close_popup		: false,
            callback 		: function($return){}
        });

        // Show the popup
        $('.btnAddProduct').live('click', function()
        {
            $('body').sunBox.popup_change_width('popAddProduct', 790);
            $('body').sunBox.show_popup('popAddProduct');
            $('body').sunBox.adjust_popup_height('popAddProduct');

            $('.scrap_date').datepicker(
            {
                showOn				: 'both',
                buttonImage			: 'scrap_assets/images/icons/calendar.png',
                buttonImageOnly		: true,
                dateFormat			: 'yy-mm-dd',
                changeYear			: true,
                changeMonth			: true,
                minDate				: '+0'
            });
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
            $.post($ajax_base_path + 'get_product_fields',
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

                    $('.scrap_date').datepicker(
                    {
                        showOn				: 'both',
                        buttonImage			: 'scrap_assets/images/icons/calendar.png',
                        buttonImageOnly		: true,
                        dateFormat			: 'yy-mm-dd',
                        changeYear			: true,
                        changeMonth			: true,
                        minDate				: '+0'
                    });
                }
            });
        });

        // Submit the new item definition
        $('.popAddProduct .returnTrue').live('click', function()
        {
            // Some variables
            $error					    = false;
            $product_number		        = $('.popAddProduct input[name="inpProductNumber"]').val();
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
                $.scrap_note_loader('Adding the new product');

                // Post the data
                $.post($ajax_base_path + 'add_product',
                {
                    product_number			    : $product_number,
                    product_definition			: $product_definition,
                    product_fields_required	    : $product_fields_required,
                    product_fields_extra	    : $product_fields_extra
                },
                function($data)
                {
                    $data	= jQuery.trim($data);
                    $data   = $data.split('::');
                    //console.log($data);

                    if($data[0] == '9876')
                    {
                        $.scrap_logout();
                    }
                    else if($data[0] == 'wassuccessfullycreated')
                    {
                        if($('.popAddProduct input[name="uploadedFileProductImage"]').val() != '')
                        {
                            $('.popAddProduct input[name="hdProductId"]').val($data[1]);
                            $iframe_name	= 'attachIframe_'+ $.scrap_random_string();
                            $('.popAddProduct .popup').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
                            $('.popAddProduct .frmProductImage').attr('target', $iframe_name);
                            $('.popAddProduct .frmProductImage').submit();

                            $('iframe[name="'+ $iframe_name +'"]').load(function()
                            {
                                $data		= jQuery.trim($('.popAddProduct .popup iframe[name="'+ $iframe_name +'"]').contents().find('body').html());
                                console.log($data);

                                // Display error
                                if($data == 'wassuccessfullyuploaded')
                                {
                                    $('.popAddProduct input').val('');
                                }
                            });
                        }
                        else
                        {
                            $('.popAddProduct input').val('');
                        }

                        $fc_refresh_products_list();

                        // Close the popup
                        $.scrap_note_time('The new product has been added', 4000, 'tick');
                        $('body').sunBox.close_popup('popAddProduct');
                    }
                    else
                    {
                        $.scrap_note_time($data[0], 4000, 'cross');
                    }
                });
            }
        });
    }

    // ---------- REFRESH PRODUCTS LIST
    function $fc_refresh_products_list()
    {
        // Refresh the document types screen
        $.post($ajax_base_path + 'get_products',
        {
            request			: 'get_it'
        },
        function($data)
        {
            if($data == '9876')
            {
                $.scrap_logout();
            }
            else
            {
                // Refresh the content
                $('.leftContent .listContain').html($data);
                $('.leftContent .listContain input:file').uniform();
                $fc_adjust_product_height();
            }
        });
    }

});