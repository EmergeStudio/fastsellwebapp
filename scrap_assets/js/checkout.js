$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_orders/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE

    $fc_print_confirmation();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ----- PRINT CONFIRMATION
    function $fc_print_confirmation()
    {
        $('.btnPrint').live('click', function()
        {
            var printContent    = $('.billingInformation').html();
            var windowUrl       = 'about:blank';
            var uniqueName      = new Date();
            var windowName      = 'Print' + uniqueName.getTime();

            var printWindow     = window.open(windowUrl, windowName, 'width=1100,height=800');

            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        });
    }


});