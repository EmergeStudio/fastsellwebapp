$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_buy/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE

    $fc_counter();

    $fc_buy();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

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


    // ---------- BUY
    function $fc_buy()
    {
        // Buy product popup
        $('body').sunBox.popup('Buy This Product', 'popBuyProduct',
        {
            ajax_path		    : $ajax_base_path + 'buy_product_popup',
            close_popup		    : false,
            callback 		    : function($return){}
        });

        // Show the popup
        $('.itemContainer').live('click', function()
        {
            // Display the loader
            $.scrap_note_loader('Getting product information');

            // Some variables
            $this               = $(this);
            $product_id         = $this.find('.hdProductId').text();

            // Get the product information
            $.post($ajax_base_path + 'buy_product_content',
            {
                product_id		: $product_id
            },
            function($data)
            {
                $data	= jQuery.trim($data);

                if($data == '9876')
                {
                    $.scrap_logout();
                }
                else if($data == 'noaddress')
                {
                    $.scrap_note_hide();
                    $.scrap_message('<h3>Please Edit Your Delivery Address</h3><p>An address is required so that you can place an order.  Click the button below to do it quickly.</p><div class="divHeight" style="height:10px"></div><div class="nothing"><a href="'+ $base_path +'dashboard" class="scrapButton2"><div class="btnIcon"></div>Click Here To Edit Address</a><div class="clearFloat"></div></div>');
                    $('.sunMessage .modalClose').live('click', function()
                    {
                        $('.sunMessage').remove();
                        $.scrap_remove_overlay();
                    });
                }
                else
                {
                    $('.popBuyProduct .popup').html($data);
                    $.scrap_note_hide();
                    $('body').sunBox.popup_change_width('popBuyProduct', 850);
                    $('body').sunBox.show_popup('popBuyProduct');
                    $('body').sunBox.adjust_popup_height('popBuyProduct');
                }
            });
        });

        // Place order
        $('.popBuyProduct .returnTrue').live('click', function()
        {
            // Some variables
            $error              = false;
            $quantity           = parseInt($('.popBuyProduct input[name="inpQuantity"]').val());
            $stock              = parseInt($('.popBuyProduct .hdStockCount').text());
            $return_url         = $('#hdReturnUrl').val();

            // Validate
            if($error == false)
            {
                if($quantity == '')
                {
                    $error      = true;
                    $('.popBuyProduct input[name="inpQuantity"]').addClass('redBorder');
                    $.scrap_note_time('Please specify your desired quantity', 6000, 'cross');
                }
            }
            if($error == false)
            {
                if($quantity > $stock)
                {
                    $error      = true;
                    $('.popBuyProduct input[name="inpQuantity"]').val($stock).addClass('redBorder');
                    $.scrap_note_time('Your requested quantity can not be greater then the available stock', 6000, 'cross');
                }
            }

            // Ok order the product
            if($error == false)
            {
                $.scrap_note_hide();
                $('.popBuyProduct input[name="hdReturnURL"]').val($return_url);
                $.scrap_note_loader('Buying product');
                $('.frmBuyProduct').submit();
            }
        });
    }


});