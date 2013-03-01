$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE

    $fc_counter();

    $fc_switch_address();

//    $fc_save_address();
//
//    $fc_save_address_2();

    $fc_save_addresses();

	
// ------------------------------------------------------------------------------FUNCTIONS

    // ---------- COUNTER
    function $fc_counter()
    {
        $('.counterTime').each(function()
        {
            // Some variables
            $start_date           = $(this).find('.hdStartDate').text();
            $ex_start_date        = $start_date.split('-');
            $start_time           = $(this).find('.hdStartTime').text();
            $hours                = $start_time.substr(0,2);
            $minutes              = $start_time.substr(2,2);
            $seconds              = $start_time.substr(4,2);

            // Set the counter
            $(this).countdown(
            {
                // Time stamp
                timestamp	        : (new Date($ex_start_date[0], ($ex_start_date[1]-1), ($ex_start_date[2]-20), $hours, $minutes, $seconds)).getTime() + 20*24*60*60*1000
            });
        });
    }

    // ---------- SWITCH ADDRESS
    function $fc_switch_address()
    {
        $('.btnDeliveryAddress').live('click', function()
        {
            $(this).removeClass('greyButton').addClass('blueButton').css({ backgroundColor : '#1ba2d0' });
            $('.btnBillingAddress').removeClass('blueButton').addClass('greyButton').css({ backgroundColor : '#ebebeb', borderColour : '#ebebeb' });
            $('.deliveryAddress').fadeIn('fast');
            $('.billingAddress').hide();
        });

        $('.btnBillingAddress').live('click', function()
        {
            $(this).removeClass('greyButton').addClass('blueButton').css({ backgroundColor : '#1ba2d0' });
            $('.btnDeliveryAddress').removeClass('blueButton').addClass('greyButton').css({ backgroundColor : '#ebebeb', borderColour : '#ebebeb' });
            $('.deliveryAddress').hide();
            $('.billingAddress').fadeIn('fast');
        });
    }

//    // ---------- SAVE ADDRESS
//    function $fc_save_address()
//    {
//        $('.deliveryAddress .btnSaveAddress').live('click', function()
//        {
//            // Some variables
//            $error              = false;
//            $address            = $('.deliveryAddress .addressForm textarea[name="address1"]').val();
//
//            // Validate
//            if($error == false)
//            {
//                if($address == '')
//                {
//                    $error      = true;
//                    $('.deliveryAddress .addressForm textarea[name="address1"]').focus();
//                    $.scrap_note_time('Please provide an address', 6000, 'cross');
//                }
//            }
//
//            // Submite new address
//            if($error == false)
//            {
//                $('.deliveryAddress .frmSaveAddress').submit();
//            }
//        });
//    }

    // ---------- SAVE ADDRESSES
    function $fc_save_addresses()
    {
        $('.btnSaveAddresses').live('click', function()
        {
            // Some variables
            $error              = false;
            $address            = $('.billingAddress .addressForm textarea[name="address1"]').val();

            // Validate
            if($error == false)
            {
                if($address == '')
                {
                    $error      = true;
                    $('.billingAddress .addressForm textarea[name="address1"]').focus();
                    $.scrap_note_time('Please provide an address', 6000, 'cross');
                }
            }

            // Submite new address
            if($error == false)
            {
                $('.frmSaveAddresses').submit();
            }
        });
    }

//    // ---------- SAVE ADDRESS 2
//    function $fc_save_address_2()
//    {
//        $('.billingAddress .btnSaveAddress2').live('click', function()
//        {
//            // Some variables
//            $error              = false;
//            $address            = $('.billingAddress .addressForm textarea[name="address1"]').val();
//
//            // Validate
//            if($error == false)
//            {
//                if($address == '')
//                {
//                    $error      = true;
//                    $('.billingAddress .addressForm textarea[name="address1"]').focus();
//                    $.scrap_note_time('Please provide an address', 6000, 'cross');
//                }
//            }
//
//            // Submite new address
//            if($error == false)
//            {
//                $('.billingAddress .frmSaveAddress').submit();
//            }
//        });
//    }


});