$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_buy/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE

    $fc_save_buying_changes();

    $fc_manage_category();

    $fc_auto_complete_category();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ---------- SAVE BUYING CHANGES
    function $fc_save_buying_changes()
    {
        $('.btnSaveChanges').live('click', function()
        {
            // Some variables
            $error                      = false;
            $categories                 = '';

            if($error == false)
            {
                $('.catBack .breadCrumb.last').each(function()
                {
                    $categories         += '[' + $(this).find('.hdCategoryId').text() + ']';
                });

                if($categories == '')
                {
                    $error			= true;
                    $.scrap_note_time('At least 1 FastSell category is required', 4000, 'cross');
                    $('input[name="inpCategorySearch"]').addClass('redBorder');
                }
                else
                {
                    $('input[name="hdCategories"]').val($categories);
                }
            }

            // Submit
            if($error == false)
            {
                $('.frmSaveBuyingPreferenceChanges').submit();
            }
        });
    }

    // ----- MANAGE CATEGORY
    function $fc_manage_category()
    {
        $('.btnDeleteCategory').live('click', function()
        {
            $(this).parents('.catBack').remove();
        });
    }

    // ----- AUTOCOMPLETE CATEGORY
    function $fc_auto_complete_category()
    {
        $('input[name="inpCategorySearch"]')
            // don't navigate away from the field on tab when selecting an item
            .bind( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                    $( this ).data( "autocomplete" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: $base_path + 'ajax_handler_fastsells/search_for_category',
                minLength: 3,
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui )
                {
                    $class_name             = 'category_' + $.scrap_random_string(5);

                    $('input[name="inpCategorySearch"]').val('').focus();

                    $('.ajax_fastSellCategories').prepend('<div class="catBack loader '+ $class_name +'"></div>');

                    // Get the category information
                    $.post($base_path + 'ajax_handler_fastsells/get_fastsell_category',
                    {
                        cat_text			: ui.item.label
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
                            // Edit the DOM
                            $('.' + $class_name).removeClass('loader').addClass('blue').append($data);
                        }
                    });
                }
            });
    }


});