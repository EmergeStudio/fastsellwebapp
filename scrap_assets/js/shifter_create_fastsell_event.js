$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP
	
	var $cnt_shifters		= 0;
	var $loop_cnt_1			= 0;
	var $scroll_pos			= 0;
	var $offset 			= $('.shifter').offset();

    // ---------- AJAX PATHS
    var $base_path				= $('#hdPath').val();
    var $ajax_base_path 		= $base_path + 'ajax_handler_fastsells/';


// ------------------------------------------------------------------------------EXECUTE
	
	$fc_set_shifter();
	
	$fc_shifter_navigation();
	
	$('.scrap_date').datepicker(
	{
		showOn				: 'both',
		buttonImage			: '../scrap_assets/images/icons/calendar.png',
		buttonImageOnly		: true,
		dateFormat			: 'yy-mm-dd',
		changeYear			: true,
		changeMonth			: true,
		minDate				: '+0'
	});
	
	
// ------------------------------------------------------------------------------FUNCTIONS

	// ----- SETUP THE SHIFTER
	function $fc_set_shifter()
	{
		// Count how many shifters there are
		$('.shifter .shifterBlock').each(function()
		{
			$cnt_shifters++;
		});
		
		// Set the shifter width
		$new_width			= $cnt_shifters * 154;
		$('.shifter').width($new_width);
		
		// Insert the active shifter
		$ac_middle_shifters	= $cnt_shifters - 2;
		
		while($loop_cnt_1 < $ac_middle_shifters)
		{
			// Increase the loop
			$loop_cnt_1++;
			
			// Insert the DOMS
			$('.shifter .activeBar .shifterStart').after('<div class="shifterBlockActive">Shifter Active End</div>');
		}
	}
	
	
	// ----- THE SHIFTER NAVIGATION
	function $fc_shifter_navigation()
	{
		// Next pane
		$('.shifterNav .btnShifterNext').live('click', function()
		{
            // Some variables
            $this					= $(this);
            $pane_position		    = jQuery.trim($('.hdPanePosition').text());

            // Check that you cant go further
            if($pane_position != $cnt_shifters)
            {
                // Increase the pane position
                $pane_position++;
                $('.hdPanePosition').text($pane_position);

                // Get the scroll position
                $scroll_pos			= $('body').scrollTop();

                // Create / Update the event
                if($pane_position == 2)
                {
                    // Some variables
                    $error                      = false;
                    $fastsell_name              = $('input[name="inpShowName"]').val();
                    $fastsell_description       = $('textarea [name="inpShowDescription"]').val();
                    $start_date                 = $('input[name="inpStartDate"]').val();
                    $start_time                 = $('select[name="startHoursSelect"]').val() + $('select[name="startMinutesSelect"]') + '00';
                    $end_date                   = $('input[name="inpEndDate"]').val();
                    $end_time                   = $('select[name="endHoursSelect"]').val() + $('select[name="endMinutesSelect"]') + '00';
                    $event_id                   = $('.hdEventId').text();
                    $event_banner               = $('.hdBannerImagePath').text();

                    if($error == false)
                    {
                        if($fastsell_name.length < 1)
                        {
                            $error			= true;
                            $.scrap_note_time('Please provide a title for your FastSell event', 4000, 'cross');
                            $('input[name="inpShowName"]').addClass('redBorder');
                        }
                    }
                    if($error == false)
                    {
                        if($.scrap_check_date($start_date) == false)
                        {
                            $error			= true;
                            $.scrap_note_time('You need a start date for your FastSell', 4000, 'cross');
                            $('input[name="inpStartDate"]').addClass('redBorder');
                        }
                    }
                    if($error == false)
                    {
                        if($.scrap_check_date($end_date) == false)
                        {
                            $error			= true;
                            $.scrap_note_time('You need an end date for your FastSell', 4000, 'cross');
                            $('input[name="inpEndDate"]').addClass('redBorder');
                        }
                    }

                    if($error == false)
                    {
                        $fastsell_path              = 'create_fastsell'
                        if($event_id != 'no_id')
                        {
                            $fastsell_path          = 'update_fastsell'
                        }
                        $.post($ajax_base_path + $fastsell_path,
                        {
                            fastsell_name	        : $fastsell_name,
                            fastsell_description	: $fastsell_description,
                            start_date		        : $start_date,
                            start_time			    : $start_time,
                            end_date			    : $end_date,
                            end_time			    : $end_time,
                            event_id			    : $event_id,
                            event_banner	        : $event_banner
                        },
                        function($data)
                        {
                            $data	= jQuery.trim($data);
                            console.log($data);

                            if($data == '9876')
                            {
                                $.scrap_logout();
                            }
                            else
                            {
                            }
                        });
                    }
                    else
                    {
                        // Edit the DOM
                        $('.hdPanePosition').text(1);
                        $pane_position          = 1;
                        $('.shifterNav').show();
                    }
                }

                // Edit the variables
                if($pane_position == $cnt_shifters)
                {
                    // Hide next button
                    $this.parent().hide();

                    // Show the complete button
                    $('.shifterNav .btnComplete').parent().show();
                }

                // Shifter the indicator
                $fc_shift_indicator();

                // Scroll pane
                $fc_fix_position();

                // Modify button DOMs
                $('.shifterNav .btnShifterBack').parent().show();
            }
		});
		
		// Previous pane
		$('.shifterNav .btnShifterBack').live('click', function()
		{
			// Some variables
			$this					= $(this);
            $pane_position		    = jQuery.trim($('.hdPanePosition').text());
			
			// Check that you aren't at the beginning
			if($pane_position > 1)
			{
				// Decrease pane position
                $pane_position--;
                $('.hdPanePosition').text($pane_position);
				
				// Get the scroll position
				$scroll_pos			= $('body').scrollTop();
				
				// Scroll pane
				$fc_fix_position();
				
				// Modify button DOMs
				$('.shifterNav .btnShifterNext').parent().show();
				$('.shifterNav .btnComplete').parent().hide();
				
				if($pane_position == 1)
				{
					// Hide back button
					$this.parent().hide();
				}
				
				// Shifter the indicator
				$fc_shift_indicator();
			}
		});
	}
	
	
	// ----- FIX POSITION
	function $fc_fix_position()
	{
		// Make the adjustment
		$('body').scrollTop($offset.top - 50);

        // Change which panes show
        $pane_position		    = jQuery.trim($('.hdPanePosition').text());
        $('.shifterPane').hide();
        $('.shifterPane_' + $pane_position).fadeIn();
	}
	
	
	// ----- SHIFT THE INDICATOR
	function $fc_shift_indicator()
	{
		// Some variables
		if($pane_position == 1)
		{
			$new_width					= 83;
		}
		else
		{
			$new_width					= ($pane_position * 154) - 71;
		}
		
		// Animate the completed indicator
		$('.shifter .activeBarContain').delay(100).animate({ width : $new_width }, 500, 'easeOutCubic');
	}

});