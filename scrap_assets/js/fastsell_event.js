$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE

    $('.scrap_date').datepicker(
    {
        showOn				: 'both',
        buttonImage			: $base_path + 'scrap_assets/images/icons/calendar.png',
        buttonImageOnly		: true,
        dateFormat			: 'yy-mm-dd',
        changeYear			: true,
        changeMonth			: true,
        minDate				: '+0'
    });

    $fc_save_event_changes();

    $fc_remove_customer();

    $fc_remove_product();

    $fc_upload_new_image();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ---------- UPLOAD NEW IMAGE
    function $fc_upload_new_image()
    {
        $('input[name="uploadedFileFastsellImage"]').live('change', function()
        {
            $('.shifterPane_1 .fastSellImage').removeClass('icon-camera').html('<div class="loader">Uploading Your Selected Image</div>');

            $('.frmSaveEventChanges').submit();
        });
    }

    // ---------- SAVE EVENT CHANGES
    function $fc_save_event_changes()
    {
        $('.btnSaveChanges').live('click', function()
        {
            // Submit
            $('.frmSaveEventChanges').submit();
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
        $.post($base_path + 'ajax_handler_customers/get_added_customers',
        {
            event_id		    : $event_id
        },
        function($data)
        {
            $data	            = jQuery.trim($data);
            //console.log($data);

            $('.ajaxCustomersInFastSell').html($data);
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
        $('.ajaxProductsInFastSell').prepend('<div class="ajaxMessage short">Refreshing FastSell Products</div>');
        $('.ajaxProductsInFastSell table').fadeTo('fast', 0.3);

        // The AJAX call
        $.post($base_path + 'ajax_handler_products/get_added_products_2',
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