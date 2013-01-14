$(document).ready(function(){

    // ------------------------------------------------------------------------------STARTUP

    var $cnt_shifters		= 0;
    var $loop_cnt_1			= 0;
    var $scroll_pos			= 0;
    var $offset 			= $('.shifter').offset();

    // ---------- AJAX PATHS
    var $base_path				= $('#hdPath').val();
    var $ajax_base_path 		= $base_path + 'ajax_handler_items/';
    var $ajax_html_path 		= $ajax_base_path + 'html_view/';


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

    $fc_edit_cell();

    $fc_upload_items();


    // ------------------------------------------------------------------------------FUNCTIONS


    // ----- UPLOAD ITEMS
    function $fc_upload_items()
    {
        // On successful click
        $('.btnComplete').live('click', function()
        {
            // Hide the navigation
            $('.shifterNav').hide();
            $('.shifterPane_4 .loader').show();

            // Some variables
            $rows               = '';
            $headers            = '';
            $definition_id      = $('.shifterPane_2 select[name="dropItemDefinitions"]').val();

            // Loop through and gather info
            $('.shifterPane_3 .coolTable.items tr th').each(function()
            {
                // Some variables
                $this           = $(this);

                // Get cell data
                if($(this).hasClass('ignore') == false)
                {
                    // Get headers
                    $headers           += '[' + $(this).text() + ']';
                }
            });

            // Loop through and gather info
            $('.shifterPane_3 .coolTable.items tr.contain_item').each(function()
            {
                // Some variables
                $this           = $(this);

                // Get rows
                $rows           += '[';

                    // Get cell data
                    $(this).children('td').each(function()
                    {
                        if($(this).hasClass('ignore') == false)
                        {
                            $rows   += '{' + $(this).text() + '}';
                        }
                    });

                $rows           += ']';
            });

            // Send data if not empty
            if($rows != '')
            {
                // Upload vendors
                $.post($ajax_base_path + 'upload_products',
                {
                    headers         : $headers,
                    rows			: $rows,
                    definition_id   : $definition_id
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
                        //console.log($data);
                        $('.shifterPane_4 .loader').hide();
                        $('.shifterPane_4 .errorContainer').html($data).show();
                        $('.shifterNav').show();
                    }
                });
            }
            else
            {
                // Hide the navigation
                $('.shifterNav').show();
                $('.shifterPane_4 .loader').hide();
                $.scrap_note_time('There is no item data to upload', 4000, 'cross');
            }
        });
    }

    // ----- EDIT CELL
    function $fc_edit_cell()
    {
        // On click
        $('.checkDataContainer .coolTable.items td').live('click', function()
        {
            // Some variables
            $this               = $(this);
            $value              = $this.html();

            // Edit the DOM
            if($this.hasClass('editingField') || $this.hasClass('ignore'))
            {}
            else
            {
                $this.addClass('editingField');
                if($this.width() > 70)
                {
                    $width          = $this.width();
                }
                else
                {
                    $width          = 70;
                }
                $this.html('<input type="text" name="newValue" value="" style="width:'+ $width +'px;" />');
                $this.find('input').focus().val($value);
            }
        });

        // On blur
        $('.checkDataContainer .coolTable.items td input').live('blur', function()
        {
            // Some variables
            $this               = $(this);
            $parent             = $this.parents('td');
            $value              = $this.val();

            // Edit the DOM
            $parent.removeClass('editingField');
            $parent.html($value);
        });

        // Delete row
        $('.btnRemoveError').live('click', function()
        {
            // Remove the row
            $(this).parents('.contain_item').remove();

            // Reset table
            $odd_row			= false;
            $('.shifterPane_3 .coolTable tr:visible').each(function()
            {
                // Remove all row styles
                $(this).removeClass('evenRow').removeClass('oddRow');

                // Add new style
                if($odd_row == true)
                {
                    // Add class
                    $(this).addClass('oddRow');

                    // Set the row variables
                    $odd_row	= false;
                }
                else
                {
                    // Add class
                    $(this).addClass('evenRow');

                    // Set the row variables
                    $odd_row	= true;
                }
            });
        });
    }

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

                // Validate file
                if($pane_position == 3)
                {
                    // Hide the navigation
                    $('.shifterNav').hide();
                    $('.shifterPane_3 .loader').show();
                    $('.shifterPane_3 .checkDataContainer').hide();

                    // Some variables
                    $extension      = $.scrap_get_extension($('.shifterPane_2 input[name="uploadedFile"]').val());

                    // Check
                    if(($extension == 'xls') || ($extension == 'xlsx'))
                    {
                        // Submit the upload file
                        $iframe_name	= 'attachIframe_'+ $.scrap_random_string();
                        $('.shifterPane_2').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
                        $('.shifterPane_2 .frmCheckItemUpload').attr('target', $iframe_name);
                        $('.shifterPane_2 .frmCheckItemUpload').submit();

                        $('iframe[name="'+ $iframe_name +'"]').load(function()
                        {
                            $data		= $('.shifterPane_2 iframe[name="'+ $iframe_name +'"]').contents().find('body').html();

                            // Check result
                            if($data != '5678')
                            {
                                $('.shifterPane_3 .loader').hide();
                                $('.shifterPane_3 .checkDataContainer').html($data).show();
                            }

                            // Show the navigation
                            $('.shifterNav').show();
                        });
                    }
                    else
                    {
                        // Edit the DOM
                        $('.hdPanePosition').text(2);
                        $pane_position          = 2;
                        $('.shifterNav').show();

                        // Error message
                        $.scrap_note_time('The file you supplied is not in the correct format. Please upload a correct Excel file (.xls or .xlsx)', 6000, 'cross');
                    }
                }
                if($pane_position == 4)
                {
                    $('.shifterPane_4 .errorContainer').html('').hide();
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
        $pane_position		    = jQuery.trim($('.hdPanePosition').text());
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