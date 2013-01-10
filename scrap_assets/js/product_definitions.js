$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_products/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE

    $fc_add_definition();

    $fc_delete_definition();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ----- ADD DEFINITION
    function $fc_add_definition()
    {
        // Add a document type popup
        $('body').sunBox.popup('Add Product Definition', 'popAddDefinition',
        {
            ajax_path		: $ajax_base_path + 'add_definition_popup',
            close_popup		: false,
            callback 		: function($return){}
        });

        // Show the popup
        $('.btnAddDefinition').live('click', function()
        {
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
//            if($error == false)
//            {
//                if($('.popAddDefinition .allDefinitionFields input[name="inpDefinitionField"]:first').val() == '')
//                {
//                    $error			= true;
//                    $.scrap_note_time('Please provide at least one product definition field', 4000, 'cross');
//                    $('.popAddDefinition .allDefinitionFields input[name="inpDefinitionField"]:first').addClass('redBorder');
//                }
//            }

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
                content			: 'Delete the product definition <b>"'+ $definition_name +'"</b>?<br><br>(<b>Please Note</b> that by doing this you will <b>delete all</b> products associated with this definition)',
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

});