$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				    = $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				    = $('#hdPath').val();
	var $ajax_base_path 		    = $base_path + 'ajax_handler_reports/';
	var $ajax_html_path 		    = $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE

    $('.scrap_date').datepicker(
    {
        dateFormat			: 'yy-mm-dd',
        changeYear			: true,
        changeMonth			: true
    });

    $fc_report_by_date();

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

    // ---------- SELECT BY DATE REPORT
    function $fc_report_by_date()
    {
        $('.btnRunReport').live('click', function()
        {
            // Some variables
            $error                  = false;
            $from_date              = $('input[name="inpFromDate"]').val();
            $to_date                = $('input[name="inpToDate"]').val();

            // Validate
            if($error == false)
            {
                if($.scrap_check_date($from_date) == false)
                {
                    $error          = true;
                    $.scrap_note_time('Please provide a from date', 4000, 'cross');
                    $('input[name="inpFromDate"]').addClass('redBorder');
                }
            }
            if($error == false)
            {
                if($.scrap_check_date($to_date) == false)
                {
                    $error          = true;
                    $.scrap_note_time('Please provide a to date', 4000, 'cross');
                    $('input[name="inpToDate"]').addClass('redBorder');
                }
            }

            // On true validation
            $('.ajaxReportContainer').removeClass('messageSelectDateRange').prepend('<div class="ajaxMessage">Getting Report</div>');

            // Post the data
            $.post($ajax_base_path + 'get_orders_by_date_report',
            {
                from_date	        : $from_date,
                to_date             : $to_date
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
        });
    }


});