$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE

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
	
	
// ------------------------------------------------------------------------------FUNCTIONS


});