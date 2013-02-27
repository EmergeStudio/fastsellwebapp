$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				    = $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				    = $('#hdPath').val();
	var $ajax_base_path 		    = $base_path + 'ajax_handler_reports/';
	var $ajax_html_path 		    = $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE

    $fc_select_fastsell_report();

    $fc_download_report();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ---------- DOWNLOAD REPORT
    function $fc_download_report()
    {
        $('.btnDownloadReport').live('click', function()
        {
            $('.frmDownloadReport').submit();
        });
    }

    // ---------- SELECT A FASTSELL REPORT
    function $fc_select_fastsell_report()
    {
        $('select[name="drpDwnFastSellId"]').live('change', function()
        {
            // Some variables
            $fastsell_id                = $(this).val();

            // Validate
            if($fastsell_id != 0)
            {
                // Display the loading message
                $('.ajaxReportContainer').removeClass('messageSelectFastSell').prepend('<div class="ajaxMessage">Getting Report</div>');

                // Post the data
                $.post($ajax_base_path + 'get_orders_summary_report',
                {
                    fastsell_id	            : $fastsell_id
                },
                function($data)
                {
                    $data	= jQuery.trim($data);

                    if($data == '9876')
                    {
                        $.scrap_logout();
                    }
                    else
                    {
                        $('.ajaxReportContainer .ajaxMessage').remove();
                        $('.ajaxReportContainer').html($data);
                        if($('.hdReportStatus').text() == 'yesReport')
                        {
                            $('.btnDownloadReport').parent().show();
                        }
                        else
                        {
                            $('.btnDownloadReport').parent().hide();
                        }
                    }
                });
            }
            else
            {
                $.scrap_note_time('Please select a FastSell', 4000, 'cross');
            }
        });
    }


});