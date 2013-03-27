$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_buy/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';
    var $crt_page_num           = $('input[name="scrap_pageNo"]').val();
    var $page_max               = $('input[name="scrap_pageMax"]').val();


// ------------------------------------------------------------------------------EXECUTE

    $fc_counter();

    $fc_buy();

    $fc_search();

    $fc_pagenate();

    $fc_change_width();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ----- CHANGE WIDTH
    function $fc_change_width()
    {
        // Some variables
        $window_w               = $(window).width();
        $('.coolScreen .leftContent').width($window_w - 340);

        $(window).resize(function()
        {
            // Some variables
            $window_w               = $(window).width();
            $('.coolScreen .leftContent').width($window_w - 340);
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
                    $.scrap_message('<h3>Please Edit Your Address Information</h3><p>An address is required to place an order.  Click the button below to do it quickly.</p><div class="divHeight" style="height:10px"></div><div class="nothing"><a href="'+ $base_path +'dashboard" class="scrapButton2"><div class="btnIcon"></div>Click Here To Edit Address</a><div class="clearFloat"></div></div>');
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