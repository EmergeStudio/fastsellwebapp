$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_products/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';
    var $crt_page_num           = $('input[name="scrap_pageNo"]').val();
    var $page_max               = $('input[name="scrap_pageMax"]').val();


// ------------------------------------------------------------------------------EXECUTE

    $fc_counter();

    $fc_add_products();

    $fc_add_a_product_and_link();

    $fc_remove_product();

    $fc_upload_master_data_file();

    $fc_search();

    $fc_pagenate();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ----- PAGENATE
    function $fc_pagenate()
    {
        // Previous page
        $('.btnPrevPage').live('click', function()
        {
            if($crt_page_num > 1)
            {
                $('input[name="hdOffset"]').val($crt_page_num - 1);
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

    function $fc_upload_master_data_file()
    {
        // Buy products popup
        $('body').sunBox.popup('Upload Master Data File', 'popProductsMasterDataFile',
            {
                ajax_path		    : $base_path + 'ajax_handler_products/add_master_data_file_popup_2',
                close_popup		    : false,
                callback 		    : function($return){}
            });

        // Show the popup
        $('.btnUploadDataFile').live('click', function()
        {
            $('.popProductsMasterDataFile .returnTrue').text('Upload');
            $('body').sunBox.show_popup('popProductsMasterDataFile');
            $('body').sunBox.adjust_popup_height('popProductsMasterDataFile');
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

    // ---------- ADD PRODUCTS
    function $fc_add_products()
    {
        // Buy products popup
        $('body').sunBox.popup('Add Existing Products', 'popAddProducts',
        {
            ajax_path		    : $ajax_base_path + 'add_products_popup',
            close_popup		    : false,
            callback 		    : function($return){}
        });

        // Show the popup
        $('.btnAddProductPopup').live('click', function()
        {
            $('.popAddProducts .returnTrue').text('Add All');
            $('body').sunBox.popup_change_width('popAddProducts', 1200);
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
                console.log($data);

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

    // ---------- ADD A PRODUCT
    function $fc_add_a_product_and_link()
    {
        // Add a document type popup
        $('body').sunBox.popup('Add A New Product', 'popAddProduct',
        {
            ajax_path		: $ajax_base_path + 'add_product_popup_2',
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
            $.post($ajax_base_path + 'get_product_fields_2',
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
                $.post($ajax_base_path + 'add_product_and_link',
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

        // Cancel the new product
        $('.popAddProduct .returnFalse').live('click', function()
        {
            $('.blockProductImage .imagePreview').addClass('icon-camera').html('');
            $('.popAddProduct .frmProductImage').attr({ 'action' : $base_path + 'ajax_handler_products/add_product_image_temp' });
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
        });
    }


});