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

    $fc_change_my_reference();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ---------- CHANGE MY REFERENCE
    function $fc_change_my_reference()
    {
        alert('au');
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


    // ---------- DELETE
    function $fc_remove_product()
    {
        // Select all
        $('input[name="checkRemoveAll"]').live('click', function()
        {
            $('input[name="checkRemoveAll"]').parent().removeClass('checked');

            $('.listContain input[name="checkRemoveProduct"]').each(function()
            {
                $this               = $(this);

                if($this.is(':checked'))
                {}
                else
                {
                    $this.parent().addClass('checked');
                    $this.attr('checked', 'checked');
                }
            });
        });

        // Remove selected
        $('.linkRemove').live('click', function()
        {
            // Some variables
            $ids                    = '';

            // Go through and get the ticked ids
            $('.listContain input[name="checkRemoveProduct"]').each(function()
            {
                $this               = $(this);

                if($this.is(':checked'))
                {
                    $ids            += '[' + $this.val() + ']';
                }
            });

            //Set the value
            $('input[name="hdOrderItemIdSelect"]').val($ids);

            // Submit
            $('.frmOrderDeleteProduct').submit();
        });


//        $('.btnRemoveProduct').live('click', function()
//        {
//            // Some variables
//            $this               = $(this);
//            $parent             = $this.parents('tr');
//            $order_item_id      = $parent.find('.hdOrderItemId').text();
//
//            // Set the value
//            $('input[name="hdOrderItemIdSelect"]').val($order_item_id);
//
//            // Submit
//            $('.frmOrderDeleteProduct').submit();
//        });
    }


});