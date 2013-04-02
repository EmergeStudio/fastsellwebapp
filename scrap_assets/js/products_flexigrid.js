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
    var $mouse_x                = false;
    var $mouse_y                = false;
    var $crt_cell               = false;


// ------------------------------------------------------------------------------EXECUTE

    $fc_execute_flexigrid();

    $fc_execute_page_height();

    $(window).resize($fc_execute_page_height);

    $fc_add_a_product();

    $fc_edit_product();

    $fc_edit_product_image();

    $(document).mousemove(function($e)
    {
        $mouse_x                = $e.pageX;
        $mouse_y                = $e.pageY;
    });

    $fc_pagenate();


// ------------------------------------------------------------------------------FUNCTIONS

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

    // ---------- EDIT A PRODUCT IMAGE
    function $fc_edit_product_image()
    {
        $('.editIt_image').mouseup(function()
        {
            if(($("input[type=checkbox].switch.chkboxEdit").is(':checked')) && ($('.scrapEdit2').is(':hidden')))
            {
                // Some variables
                $crt_cell           = $(this);
//                $(this).addClass('eHighLight');

                // Show the DOM
                $('.scrapEdit2').fadeIn('fast').css({ left : ($mouse_x - 106), top : $mouse_y });
            }
        });

        // Close edit
        $('.scrapEdit2 .btnCancel').click(function()
        {
            $('.scrapEdit2').fadeOut('fast');
        });
        $('.bDiv').scroll(function()
        {
            $('.scrapEdit2').fadeOut('fast');
        });

        // Save edit
        $('.scrapEdit2 .btnSave').click(function()
        {
            $('.scrapEdit2').fadeOut('fast');

            if($.scrap_is_image($('.scrapEdit2 input[name="uploadedFileProductImage2"]').val()) == true)
            {
                // Some variables
                $.scrap_note_loader('Uploading the new product image');
                $parent         = $crt_cell.parents('tr');
                $product_id     = $parent.find('td:first div').text();
                $('.scrapEdit2 input[name="hdProductId2"]').val($product_id);

                $iframe_name	= 'attachIframe_'+ $.scrap_random_string();
                $('.scrapEdit2').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
                $('.scrapEdit2 .frmProductImage2').attr('target', $iframe_name);
                $('.scrapEdit2 .frmProductImage2').submit();

                $('iframe[name="'+ $iframe_name +'"]').load(function()
                {
                    $data		= jQuery.trim($('.scrapEdit2 iframe[name="'+ $iframe_name +'"]').contents().find('body').html());
                    $parent.find('img').attr({ 'src' : $data });
                    $.scrap_note_time('The product image has been uploaded', 4000, 'tick');
                });
            }
            else
            {
                $.scrap_message('Only image files are accepted for the product picture');
                $('.sunMessage .returnFalse').click(function()
                {
                    $('.sunMessage').remove();
                    $.scrap_remove_overlay();
                });
            }
        });
    }

    // ---------- EDIT A PRODUCT
    function $fc_edit_product()
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
            $.post($ajax_base_path + 'save_product_changes',
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
                    $.scrap_note_time('The product information has been updated', 4000, 'tick');
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

    // ---------- ADD A PRODUCT
    function $fc_add_a_product()
    {
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
                $('body').sunBox.adjust_popup_height('popAddProduct');

                $('iframe[name="'+ $iframe_name +'"]').load(function()
                {
                    $data		= jQuery.trim($('.blockProductImage iframe[name="'+ $iframe_name +'"]').contents().find('body').html());

                    $('.blockProductImage .imagePreview').html('<img src="'+ $data +'" width="312px" alt="">');
                    $('body').sunBox.adjust_popup_height('popAddProduct');

                    // Adjust the height
                    $('.blockProductImage .imagePreview img').load(function()
                    {
                        $('body').sunBox.adjust_popup_height('popAddProduct');
                    });
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

        // Submit the new product
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

                                    $fc_refresh_products_list();

                                    // Close the popup
                                    $.scrap_note_time('The new product has been added', 4000, 'tick');
                                    $('body').sunBox.close_popup('popAddProduct');
                                    $('.blockProductImage .imagePreview').addClass('icon-camera').html('');
                                    $('.popAddProduct .frmProductImage').attr({ 'action' : $base_path + 'ajax_handler_products/add_product_image_temp' });
                                }
                            });
                        }
                        else
                        {
                            $('.popAddProduct input, .popAddProduct textarea').val('');

                            $fc_refresh_products_list();

                            // Close the popup
                            $.scrap_note_time('The new product has been added', 4000, 'tick');
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

    // ---------- REFRESH PRODUCTS LIST
    function $fc_refresh_products_list()
    {
        location.reload(true);
        $fc_execute_page_height();
    }

    // ---------- EXECUTE PAGE HEIGHT
    function $fc_execute_page_height()
    {
        // Some variables
        $window_h                   = $(window).height();
        $bDiv_h                     = $('.flexigrid .bDiv').height();
        $bDiv_table_h               = $('.flexigrid .bDiv table').height();

        // Adjust height
        if($bDiv_table_h > $bDiv_h)
        {
            $('.flexigrid .bDiv').height($window_h - 330);
        }
        else
        {
            $('.flexigrid .bDiv').height($bDiv_table_h);
        }
    }

    // ---------- EXECUTE FLEXIGRID
    function $fc_execute_flexigrid()
    {
        // Get field headings
        $field_headings             = $('.hdFieldHeadings').text();
        $limit                      = $('input[name="hdLimit"]').val();
        $page                       = $('input[name="scrap_pageNo"]').val();
        $total                      = $('input[name="scrap_pageMax"]').val();
        $ex_field_headings          = $field_headings.split('][');
        var $ar_fields              = new Array();

        // Predefined headings
        $ar_fields.push({ display: 'ID', name : 'productId', width : 50, sortable : false, align: 'center' });
        $ar_fields.push({ display: 'Image', name : 'productImage', width : 50, sortable : false, align: 'left' });
        $ar_fields.push({ display: 'Product Name', name : 'productName', width : '200', sortable : true, align: 'left' });
        $ar_fields.push({ display: 'Product Number', name : 'productNumber', width : '100', sortable : true, align: 'left' });
        $ar_fields.push({ display: 'Description', name : 'productDescription', width : 400, sortable : true, align: 'left' });

        if($field_headings != '')
        {
            for($i = 0; $i < $ex_field_headings.length; $i++)
            {
                $ar_fields.push({ display: $ex_field_headings[$i], name : 'field_' + $i, width : 118, sortable : true, align: 'left' });
            }
        }

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

});