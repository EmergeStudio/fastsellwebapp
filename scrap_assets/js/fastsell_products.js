$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_products/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE

    $fc_counter();

    $fc_add_products();

    $fc_remove_product();

    $fc_upload_master_data_file();

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

    // ---------- UPLOAD MASTER DATA FILE
    function $fc_upload_master_data_file()
    {
        // Buy products popup
        $('body').sunBox.popup('Upload Master Data File', 'popProductsMasterDataFile',
        {
            ajax_path		    : $ajax_base_path + 'add_master_data_file_popup',
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
            $('.frmProductsMasterDataUpload').submit();
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
        $('body').sunBox.popup('Add More Products', 'popAddProducts',
        {
            ajax_path		    : $ajax_base_path + 'add_products_popup',
            close_popup		    : false,
            callback 		    : function($return){}
        });

        // Show the popup
        $('.btnAddProductPopup').live('click', function()
        {
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


});