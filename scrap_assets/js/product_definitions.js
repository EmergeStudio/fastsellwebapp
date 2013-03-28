$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_products/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';
    var $crt_cell               = false;


// ------------------------------------------------------------------------------EXECUTE

    $fc_add_definition();

    $fc_delete_definition();

    $fc_add_field();

    $fc_remove_field();

    $(document).mousemove(function($e)
    {
        $mouse_x                = $e.pageX;
        $mouse_y                = $e.pageY;
    });

    $fc_copy_definition();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ----- COPY DEFINITION
    function $fc_copy_definition()
    {
        // Show the popup
        $('.btnCopyDefinition').live('click', function()
        {
            // Some variables
            $parent                 = $(this).parents('tr');
            $defintion_name         = $parent.find('.editDefinitionName').text();
            $loop_cnt               = 0;

            // Edit the popup
            $('.popAddDefinition .inpDefinitionName').val($defintion_name + ' (copy)');
            $parent.find('.productField ').each(function()
            {
                $loop_cnt++;
                $existing_field     = $(this).clone().children().remove().end().text();

                // Append the index field
                if($loop_cnt == 1)
                {
                    $('.popAddDefinition .inpDefinitionField:first').val($existing_field);
                }
                else
                {
                    $html			= '<div class="definitionFieldContainer deleteable"><div class="btnDeleteDefinitionField tooltip" title="Delete definition field">Cross Grey</div><div class="floatRight"><div class="divHeight" style="height:2px"></div></div><label>Add Field</label><input type="text" name="inpDefinitionField" value="'+ $existing_field +'" class="inpDefinitionField"></div>';

                    $('.popAddDefinition .allDefinitionFields').append($html);
                }
            });

            // Show the popup
            $('.popAddDefinition .titleText').text('Copy Product Template');
            $('body').sunBox.show_popup('popAddDefinition');
            $('body').sunBox.adjust_popup_height('popAddDefinition');
        });
    }

    // ----- ADD DEFINITION FIELD
    function $fc_add_field()
    {
        $('.scrapEdit input').before('<label>Add Field:</label>');
        $('.scrapEdit2 input').before('<label>Edit Template Name:</label>');

        $('.editDefinitionName').live('mouseup', function()
        {
            $('.scrapEdit').hide();

            // Show the DOM
            $('.scrapEdit2').fadeIn('fast').css({ left : ($mouse_x - 50), top : $mouse_y + 10 });
            $('.scrapEdit2 input').val($(this).text()).focus();
            $crt_cell           = $(this);
        });

        $('.productFieldAdd').live('mouseup', function()
        {
            $('.scrapEdit2').hide();

            // Show the DOM
            $('.scrapEdit').fadeIn('fast').css({ left : ($mouse_x - 138), top : $mouse_y });
            $('.scrapEdit input').val('').focus();
            $crt_cell           = $(this);
        });

        // Close edit
        $('.scrapEdit .btnCancel, .scrapEdit2 .btnCancel').live('click', function()
        {
            $('.scrapEdit').fadeOut('fast');
            $('.scrapEdit2').fadeOut('fast');
        });

        // Save edit
        $('.scrapEdit .btnSave').live('click', function()
        {
            // Some variables
            $def_field              = $('.scrapEdit input').val();
            $def_id                 = $crt_cell.find('.hdDefinitionIdAdd').text();

            // Edit the DOM
            $('.scrapEdit').fadeOut('fast');

            // Submit the new document type for adding
            $.scrap_note_loader('Adding the new template field');

            // Post the data
            $.post($ajax_base_path + 'add_definition_field',
            {
                def_id			    : $def_id,
                def_field		    : $def_field
            },
            function($data)
            {
                $data	= jQuery.trim($data);

                if($data == '9876')
                {
                    $.scrap_logout();
                }
                else if($data == 'wassuccessfullyupdated')
                {
                    $fc_refresh_definitions();
                    $.scrap_note_time('Template field has been successfully added', 4000, 'tick');
                }
                else
                {
                    $.scrap_note_time($data, 4000, 'cross');
                }
            });
        });

        // Save edit
        $('.scrapEdit2 .btnSave').live('click', function()
        {
            // Some variables
            $def_name               = $('.scrapEdit2 input').val();
            $def_id                 = $crt_cell.parent().find('.hdDefinitionIdName').text();

            // Edit the DOM
            $('.scrapEdit2').fadeOut('fast');

            // Submit the new document type for adding
            $.scrap_note_loader('Editing the template name');

            // Post the data
            $.post($ajax_base_path + 'edit_definition_name',
            {
                def_id			    : $def_id,
                def_name		    : $def_name
            },
            function($data)
            {
                $data	= jQuery.trim($data);

                if($data == '9876')
                {
                    $.scrap_logout();
                }
                else if($data == 'wassuccessfullyupdated')
                {
                    $fc_refresh_definitions();
                    $.scrap_note_time('Template name has been successfully updated', 4000, 'tick');
                }
                else
                {
                    $.scrap_note_time($data, 4000, 'cross');
                }
            });
        });
    }

    // ----- ADD DEFINITION
    function $fc_add_definition()
    {
        // Add a document type popup
        $('body').sunBox.popup('Add Product Template', 'popAddDefinition',
        {
            ajax_path		: $ajax_base_path + 'add_definition_popup',
            close_popup		: false,
            callback 		: function($return){}
        });

        // Show the popup
        $('.btnAddDefinition').live('click', function()
        {
            $('.popAddDefinition .titleText').text('Add Product Template');
            $('body').sunBox.show_popup('popAddDefinition');
            $('body').sunBox.adjust_popup_height('popAddDefinition');
        });

        // Add a new definition field
        $('.popAddDefinition .btnAddDefinitionField').live('click', function()
        {
            // Append the new index field
            $html			= '<div class="definitionFieldContainer deleteable"><div class="btnDeleteDefinitionField tooltip" title="Delete definition field">Cross Grey</div><div class="floatRight"><div class="divHeight" style="height:2px"></div></div><label>Add Field</label><input type="text" name="inpDefinitionField" value="" class="inpDefinitionField"></div>';

            $('.popAddDefinition .allDefinitionFields').append($html);
            $('.popAddDefinition .allDefinitionFields input[name="inpDefinitionField"]:last').focus();
            $('.popAddDefinition select:last').uniform();
            $('body').sunBox.adjust_popup_height('popAddDefinition');
            $('.popAddDefinition .popup').scrollTop(10000);
        });

        // Remove a definition field
        $('.popAddDefinition .btnDeleteDefinitionField').live('click', function()
        {
            $(this).parents('.definitionFieldContainer.deleteable').remove();
            $('body').sunBox.adjust_popup_height('popAddDefinition');
        });

        // Hide the popup
        $('.popAddDefinition .returnFalse').live('click', function()
        {
            $('.popAddDefinition input').val('');
            $('.definitionFieldContainer.deleteable').remove();
        });

        // Submit the new item definition
        $('.popAddDefinition .returnTrue').live('click', function()
        {
            // Some variables
            $error					= false;
            $definition_name		= $('.popAddDefinition input[name="inpDefinitionName"]').val();
            $def_fields			    = '';

            // Validate
            if($error == false)
            {
                if($definition_name.length < 3)
                {
                    $error			= true;
                    $.scrap_note_time('Please provide a product definition name that is at least 3 characters long', 4000, 'cross');
                    $('.popAddDefinition input[name="inpDefinitionName"]').addClass('redBorder');
                }
            }

            // Successful validation
            if($error == false)
            {
                // Get the indexing fields
                $('.popAddDefinition .allDefinitionFields input[name="inpDefinitionField"]').each(function()
                {
                    // Validate
                    if($(this).val() != '')
                    {
                        $def_fields	    += '[';
                        $def_fields	    += $(this).val();
                        $def_fields	    += ']';
                    }
                });

                // Submit the new document type for adding
                $.scrap_note_loader('Adding the new product definition');

                // Post the data
                $.post($ajax_base_path + 'add_definition',
                {
                    def_name			: $definition_name,
                    def_fields		    : $def_fields
                },
                function($data)
                {
                    $data	= jQuery.trim($data);
                    //console.log($data);

                    if($data == '9876')
                    {
                        $.scrap_logout();
                    }
                    else if($data == 'wassuccessfullycreated')
                    {
                        // Refresh Data
                        $fc_refresh_definitions();
                        $('.popAddDefinition input').val('');
                        $('.definitionFieldContainer.deleteable').remove();

                        // Close the popup
                        $.scrap_note_time('The new product definition has been added', 4000, 'tick');
                        $('body').sunBox.close_popup('popAddDefinition');
                    }
                    else
                    {
                        $.scrap_note_time($data, 4000, 'cross');
                    }
                });
            }
        });
    }

    // ----- DELETE ITEM DEFINITION
    function $fc_delete_definition()
    {
        $('.btnDeleteDefinition').live('click', function()
        {
            // Some variables
            $parent				    = $(this).parents('.extraOptions');
            $definition_name 		= $parent.find('.hdDefinitionName').text();
            $definition_id		    = $parent.find('.hdDefinitionId').text();

            $('body').sunBox.message(
            {
                content			: 'Delete the product template <b>"'+ $definition_name +'"</b>?<br><br>(<b>Please Note</b> that by doing this you will <b>delete all</b> products associated with this template)',
                btn_true		: 'Yup I\'m Sure',
                btn_false		: 'Oh Gosh No!',
                message_title	: 'Just Checking',
                callback		: function($return)
                {
                    if($return == true)
                    {
                        // Loader
                        $.scrap_remove_overlay();
                        $.scrap_note_loader('Deleting the <b>"'+ $definition_name +'"</b> definition');

                        // Delete
                        $.post($ajax_base_path + 'delete_definition',
                        {
                            definition_id		: $definition_id
                        },
                        function($data)
                        {
                            $data	= jQuery.trim($data);

                            if($data == '9876')
                            {
                                $.scrap_logout();
                            }
                            else if($data == 'wassuccessfullydeleted')
                            {
                                $fc_refresh_definitions();
                                $.scrap_note_hide();
                            }
                            else
                            {
                                $.scrap_note_time($data, 4000, 'cross');
                            }
                        });
                    }
                    else if($return == false)
                    {
                        $.scrap_remove_overlay();
                    }
                }
            });
        });
    }

    // ----- GET PRODUCT DEFINITIONS
    function $fc_refresh_definitions()
    {
        // Refresh the document types screen
        $.post($ajax_base_path + 'get_definitions',
        {
            request			: 'get_it'
        },
        function($data)
        {
            if($data == '9876')
            {
                $.scrap_logout();
            }
            else
            {
                // Refresh the content
                $('.singleColumn .listContain').html($data);
            }
        });
    }

    // ----- REMOVE A DEFINITION FIELD
    function $fc_remove_field()
    {
        // Don't delete
        $('.productField2').live('click', function()
        {
            // Some variables
            $field_name                 = $(this).text();

            // Display message
            $.scrap_note_time('<b>' + $field_name + '</b> is a required field and cannot be deleted', 4000, 'cross');
        });

        // Delete field
        $('.productField').live('click', function()
        {
            // Some variables
            $definition_id              = $(this).parents('tr').find('.hdDefinitionId').text();
            $field_id                   = $(this).find('.hdFieldId').text();

            $('body').sunBox.message(
            {
                content			: 'You sure you want to delete this product field?<br><br>(<b>Please Note</b> that by doing this you will <b>delete all</b> field values for products that have already been inserted into this group.)',
                btn_true		: 'Yup I\'m Sure',
                btn_false		: 'Oh Gosh No!',
                message_title	: 'Just Checking',
                callback		: function($return)
                {
                    if($return == true)
                    {
                        // Loader
                        $.scrap_remove_overlay();
                        $.scrap_note_loader('Deleting the field');

                        // Delete
                        $.post($ajax_base_path + 'delete_definition_field',
                        {
                            definition_id       : $definition_id,
                            field_id		    : $field_id
                        },
                        function($data)
                        {
                            $data	= jQuery.trim($data);
                            console.log($data);

                            if($data == '9876')
                            {
                                $.scrap_logout();
                            }
                            else if($data == 'wassuccessfullyupdated')
                            {
                                $fc_refresh_definitions();
                                $.scrap_note_hide();
                            }
                            else
                            {
                                $.scrap_note_time($data, 4000, 'cross');
                            }
                        });
                    }
                    else if($return == false)
                    {
                        $.scrap_remove_overlay();
                    }
                }
            });
        });
    }

});