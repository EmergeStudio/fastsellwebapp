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

    $fc_remove_product();
	
	
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


    // ---------- DELETE
    function $fc_remove_product()
    {
        $('.btnRemoveProduct').live('click', function()
        {
            // Some variables
            $this               = $(this);
            $parent             = $this.parents('tr');
            $order_item_id      = $parent.find('.hdOrderItemId').text();

            // Set the value
            $('input[name="hdOrderItemIdSelect"]').val($order_item_id);

            // Submit
            $('.frmOrderDeleteProduct').submit();
        });
    }


});