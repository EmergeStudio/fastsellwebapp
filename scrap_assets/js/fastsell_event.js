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
        buttonImage			: '../../scrap_assets/images/icons/calendar.png',
        buttonImageOnly		: true,
        dateFormat			: 'yy-mm-dd',
        changeYear			: true,
        changeMonth			: true,
        minDate				: '+0'
    });

    $fc_save_event_changes();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ---------- SAVE EVENT CHANGES
    function $fc_save_event_changes()
    {
        $('.btnSaveChanges').live('click', function()
        {
            // Submit
            $('.frmSaveEventChanges').submit();
        });
    }


});