$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP
	
	var $cnt_shifters		= 0;
	var $loop_cnt_1			= 0;
	var $scroll_pos			= 0;
	var $offset 			= $('.shifter').offset();


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
				
				// Scroll pane
				$fc_fix_position();
				
				// Modify button DOMs
				$('.shifterNav .btnShifterBack').parent().show();
				
				if($pane_position == $cnt_shifters)
				{
					// Hide next button
					$this.parent().hide();
					
					// Show the complete button
					$('.shifterNav .btnComplete').parent().show();
				}
				
				// Shifter the indicator
				$fc_shift_indicator();
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