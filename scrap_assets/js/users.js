$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_users/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';
//    var $crt_page_num           = $('input[name="scrap_pageNo"]').val();
//    var $page_max               = $('input[name="scrap_pageMax"]').val();
    var $mouse_x                = false;
    var $mouse_y                = false;
    var $crt_cell               = false;


// ------------------------------------------------------------------------------EXECUTE

    $fc_execute_flexigrid();

    $fc_execute_page_height();

    $(window).resize($fc_execute_page_height);

    $(document).mousemove(function($e)
    {
        $mouse_x                = $e.pageX;
        $mouse_y                = $e.pageY;
    });

    $fc_edit_user();

    $fc_add_a_user();

    $fc_delete_user();

    $fc_edit_profile_image();
	
	
// ------------------------------------------------------------------------------FUNCTIONS

    // ---------- EDIT A PROFILE IMAGE
    function $fc_edit_profile_image()
    {
        $('.editIt_image').mouseup(function()
        {
            if(($("input[type=checkbox].switch.chkboxEdit").is(':checked')) && ($('.scrapEdit2').is(':hidden')))
            {
                // Some variables
                $crt_cell           = $(this);
                //                $(this).addClass('eHighLight');

                // Show the DOM
                $('.scrapEdit2').fadeIn('fast').css({ left : ($mouse_x - 106), top : $mouse_y });
            }
        });

        // Close edit
        $('.scrapEdit2 .btnCancel').click(function()
        {
            $('.scrapEdit2').fadeOut('fast');
        });
        $('.bDiv').scroll(function()
        {
            $('.scrapEdit2').fadeOut('fast');
        });

        // Save edit
        $('.scrapEdit2 .btnSave').click(function()
        {
            $('.scrapEdit2').fadeOut('fast');

            if($.scrap_is_image($('.scrapEdit2 input[name="inpUploadImage"]').val()) == true)
            {
                // Some variables
                $.scrap_note_loader('Uploading the new user image');
                $parent         = $crt_cell.parents('tr');
                $product_id     = $parent.find('td:first div').text();
                $('.scrapEdit2 input[name="hdUserId"]').val($product_id);

                $iframe_name	= 'attachIframe_'+ $.scrap_random_string();
                $('.scrapEdit2').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
                $('.scrapEdit2 .frmUploadUserImage2').attr('target', $iframe_name);
                $('.scrapEdit2 .frmUploadUserImage2').submit();

                $('iframe[name="'+ $iframe_name +'"]').load(function()
                {
                    $data		= jQuery.trim($('.scrapEdit2 iframe[name="'+ $iframe_name +'"]').contents().find('body').html());
                    $parent.find('img').attr({ 'src' : $data });
                    $.scrap_note_time('The user image has been uploaded', 4000, 'tick');
                });
            }
            else
            {
                $.scrap_message('Only image files are accepted for the product picture');
                $('.sunMessage .returnFalse').click(function()
                {
                    $('.sunMessage').remove();
                    $.scrap_remove_overlay();
                });
            }
        });
    }

    // ---------- DELETE A USER
    function $fc_delete_user()
    {
        // Delete
        $('.btnDeleteUser').click(function()
        {
            // Some parents
            $parent             = $(this).parents('td');

            // Edit the DOM
            $parent.find('.btnDeleteUser').hide();
            $parent.find('.btnCheck').show();
        });

        // Cancel
        $('.btnCancel').click(function()
        {
            // Some parents
            $parent             = $(this).parents('td');

            // Edit the DOM
            $parent.find('.btnDeleteUser').show();
            $parent.find('.btnCheck').hide();
        });
    }

    // ---------- EXECUTE PAGE HEIGHT
    function $fc_execute_page_height()
    {
        // Some variables
        $window_h                   = $(window).height();
        $bDiv_h                     = $('.flexigrid .bDiv').height();
        $bDiv_w                     = $('.flexigrid .bDiv').width();
        $bDiv_table_h               = $('.flexigrid .bDiv table').height();
        $bDiv_table_w               = $('.flexigrid .bDiv table').width();

        // Adjust height
        if($bDiv_table_h > $bDiv_h)
        {
            $('.flexigrid .bDiv').height($window_h - 330);
        }
        else
        {
            if(($.browser.mozilla == true) && ($bDiv_table_w > $bDiv_w))
            {
                $('.flexigrid .bDiv').height($bDiv_table_h + 15);
            }
            else
            {
                $('.flexigrid .bDiv').height($bDiv_table_h);
            }
        }
    }

    // ----- ADD A USER
    function $fc_add_a_user()
    {
        // Get the add user popup
        $('body').sunBox.popup('Add A User', 'popAddUser',
        {
            ajax_path		: $ajax_base_path + 'html_view/manage/add_user',
            close_popup		: false,
            callback 		: function($return)
            {
                // Callback
                if($return == false)
                {
                }
            }
        });

        // Open the popup on click
        $('.btnAddUser').live('click', function()
        {
            $('body').sunBox.show_popup('popAddUser');
            $('body').sunBox.adjust_popup_height('popAddUser');
        });

        // Upload new image click
        $('.popAddUser .btnUploadNewProfileImage').live('click', function()
        {
            $('.popAddUser .profileContainer, .popAddUser .btnUploadNewProfileImage').hide();
            $('.popAddUser .uploadContainer').show();
            $('body').sunBox.adjust_popup_height('popAddUser');
        });

        // Upload new image cancel
        $('.popAddUser .btnCancelUpload').live('click', function()
        {
            $('.popAddUser .profileContainer, .popAddUser .btnUploadNewProfileImage').show();
            $('.popAddUser .uploadContainer').hide();
            $('.popAddUser .displayNone').hide();
            $('body').sunBox.adjust_popup_height('popAddUser');
        });

        // Upload new image confirm
        $('.popAddUser .btnDoUpload').live('click', function()
        {
            // Some variables
            $error				= false;
            $actual_image		= $('.popAddUser input[name="inpUploadImage"]').val();

            // Validate
            if($error == false)
            {
                // Check that there is an image
                if($actual_image == '')
                {
                    $error		= true;
                    $.scrap_note_time('Please select a profile image to upload', 4000, 'cross');
                }
            }

            if($error == false)
            {
                // Check that it is an image
                if($.scrap_is_image($actual_image, ['jpg', 'jpeg', 'gif', 'png']) == false)
                {
                    $error		= true;
                    $.scrap_note_time('Your profile image needs to be in a <b>.jpg</b>, <b>.gif</b> or <b>.png</b> format', 4000, 'cross');
                }
            }

            // On success
            if($error == false)
            {
                // Change the DOM
                $('.popAddUser .uploadFormContiner').hide();
                $('.popAddUser .loader').show();
                $('.popAddUser .profileImage').css({ opacity : 0.3 });
                $iframe_name	= 'attachIframe_'+ $.scrap_random_string();
                $('.popAddUser .uploadContainer').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
                $('.popAddUser .frmUploadUserImage').attr('target', $iframe_name);
                $('.popAddUser .frmUploadUserImage').submit();

                $('iframe[name="'+ $iframe_name +'"]').load(function()
                {
                    $data		= $('.popAddUser .uploadContainer iframe[name="'+ $iframe_name +'"]').contents().find('body').html();

                    // Check result
                    if($data == 'error_no_user')
                    {
                        $.scrap_note_time('Hmmm there was a problem loading up your new profile image', 4000, 'cross');
                    }
                    else
                    {
                        $('.popAddUser .profileContainer, .popAddUser .btnUploadNewProfileImage').show();
                        $('.popAddUser .uploadContainer').hide();
                        $('.popAddUser input[name="inpUploadImage"]').val('');
                        $('.popAddUser .uploadFormContiner').show();
                        $('.popAddUser .loader').hide();
                        $('.popAddUser .profileImage').attr({ src : $data }).css({ opacity : 1 });
                        $('.popAddUser .' + $iframe_name).remove();
                        $('.popAddUser .displayNone').hide();
                        $('body').sunBox.adjust_popup_height('popAddUser');
                    }
                });
            }
        });

        // Save user details
        $('.popAddUser .returnTrue').live('click', function()
        {
            // Some variables
            $error				= false;
            $first_name			= $('.popAddUser input[name="inpName"]').val();
            $surname			= $('.popAddUser input[name="inpSurname"]').val();
            $username			= $('.popAddUser input[name="inpUsername"]').val();
            $email_address		= $('.popAddUser input[name="inpEmail"]').val();
            $password			= $('.popAddUser input[name="inpPassword"]').val();
            $password_confirm	= $('.popAddUser input[name="inpPasswordConfirm"]').val();

            // Validate
            // First name
            if($error == false)
            {
                if($first_name == '')
                {
                    $error		= true;
                    $('.popAddUser input[name="inpName"]').addClass('redBorder');
                    $.scrap_note_time('Please provide a name', 4000, 'cross');
                }
            }

            // Surname
            if($error == false)
            {
                if($surname == '')
                {
                    $error		= true;
                    $('.popAddUser input[name="inpSurname"]').addClass('redBorder');
                    $.scrap_note_time('Please provide a surname', 4000, 'cross');
                }
            }

            // Username
            if($error == false)
            {
                if($username.length > 5)
                {
                    if($.scrap_has_white_space($username) == false)
                    {
                    }
                    else
                    {
                        $error	= true;
                        $('.popAddUser input[name="inpUsername"]').addClass('redBorder');
                        $.scrap_note_time('Your username cannot have any spaces', 4000, 'cross');
                    }
                }
                else
                {
                    $error		= true;
                    $('.popAddUser input[name="inpUsername"]').addClass('redBorder');
                    $.scrap_note_time('Your username needs to be longer then 5 characters', 4000, 'cross');
                }
            }

            // Email
            if($error == false)
            {
                if($.scrap_is_email($email_address) == false)
                {
                    $error		= true;
                    $('.popAddUser input[name="inpEmail"]').addClass('redBorder');
                    $.scrap_note_time('Your email address does not check out', 4000, 'cross');
                }
            }

            // Password
            if($error == false)
            {
                if(($.scrap_is_password($password) == false) && ($password != ''))
                {
                    $error		= true;
                    $('.popAddUser input[name="inpPassword"]').addClass('redBorder');
                    $.scrap_note_time('Your password is not valid. It is <b>required</b>, needs to be <b>6 characters or more</b> and must be <b>alphanumeric</b>.', 4000, 'cross');
                }
            }

            // Password confirm
            if($error == false)
            {
                if($password != $password_confirm)
                {
                    $error		= true;
                    $('.popAddUser input[name="inpPassword"], .popAddUser input[name="inpPasswordConfirm"]').addClass('redBorder');
                    $.scrap_note_time('Your passwords do not match', 4000, 'cross');
                }
            }

            // Save the changes
            if($error == false)
            {
                $('.popAddUser .frmNewUser').submit();
            }
        });
    }

    // ---------- EDIT A USER
    function $fc_edit_user()
    {
        $('.editIt').mouseup(function()
        {
            if(($("input[type=checkbox].switch.chkboxEdit").is(':checked')) && ($('.scrapEdit').is(':hidden')))
            {
                // Some variables
                $cell_content       = $(this).find('div:first').text();
                $crt_cell           = $(this);
                $crt_cell.addClass('eHighLight');

                // Show the DOM
                $('.scrapEdit').fadeIn('fast').css({ left : ($mouse_x - 138), top : $mouse_y });
                $('.scrapEdit input[name="inpScrapEdit"]').val('').focus();
                if($(this).hasClass('password') == false)
                {
                    $('.scrapEdit input[name="inpScrapEdit"]').val($cell_content).focus();
                }
            }
        });

        // Close edit
        $('.scrapEdit .btnCancel').click(function()
        {
            $('.scrapEdit').fadeOut('fast');
            $('.flexigrid .eHighLight').removeClass('eHighLight');
        });
        $('.bDiv').scroll(function()
        {
            $('.scrapEdit').fadeOut('fast');
            $('.flexigrid .eHighLight').removeClass('eHighLight');
        });

        // Save the field
        $('.scrapEdit .btnSave').click(function()
        {
            // Some variables
            $new_value              = $('.scrapEdit input[name="inpScrapEdit"]').val();
            $cell                   = $('.flexigrid .eHighLight');
            $user_id                = $cell.parents('tr').find('td:first div').text();

            if($cell.hasClass('firstname'))
            {
                $field_name         = 'firstname';
            }
            else if($cell.hasClass('lastname'))
            {
                $field_name         = 'lastname';
            }
            else if($cell.hasClass('username'))
            {
                $field_name         = 'username';
            }
            else if($cell.hasClass('email'))
            {
                $field_name         = 'email';
            }
            else if($cell.hasClass('password'))
            {
                $field_name         = 'password';
            }

            // Edit the DOM
            $('.scrapEdit').fadeOut('fast');
            if($crt_cell.hasClass('password') == false)
            {
                $('.flexigrid .eHighLight').find('div:first').text($('.scrapEdit input[name="inpScrapEdit"]').val());
                $crt_cell.find('div:first').text($('.scrapEdit input[name="inpScrapEdit"]').val());
            }
            $('.flexigrid .eHighLight').removeClass('eHighLight');

            // Scrap note
            $.scrap_note_loader('Making the changes');
            $.scrap_note_time('The user information has been updated', 4000, 'tick') ;

            // Submit the changes
            $.post($ajax_base_path + 'save_user_changes',
            {
                new_value	        : $new_value,
                field_name          : $field_name,
                user_id             : $user_id
            },
            function($data)
            {
                $data	            = jQuery.trim($data);
                console.log($data);

                if($data == '9876')
                {
                    $.scrap_logout();
                }
                else if($data == 'userhasbeenupdated')
                {
                    $.scrap_note_time('The user information has been updated', 4000, 'tick');
                }
                else
                {
                    $.scrap_note_time($data, 4000, 'cross');
                }
            });
        });

//        $("#flex1").eHighLight(
//        {
//            'elements'      : ['td.editIt'],
//            'onEndDragging' : function()
//            {
//                if(($("input[type=checkbox].switch.chkboxEdit").is(':checked')) && ($('.scrapEdit').is(':hidden')) && ($('.eHighLight').is(':visible')))
//                {
//                    // Some variables
//                    $cell_content       = $(this).find('div:first').text();
//                    $crt_cell           = $(this);
//
//                    // Show the DOM
//                    $('.scrapEdit').fadeIn('fast').css({ left : ($mouse_x - 138), top : $mouse_y });
//                    $('.scrapEdit input[name="inpScrapEdit"]').val($cell_content).focus();
//                }
//            },
//            'onStartDragging' : function()
//            {
//                $('.scrapEdit').fadeOut('fast');
//            }
//        });
    }

    // ---------- EXECUTE FLEXIGRID
    function $fc_execute_flexigrid()
    {
        // Get field headings
        $limit                      = $('input[name="hdLimit"]').val();
        $page                       = $('input[name="scrap_pageNo"]').val();
        $total                      = $('input[name="scrap_pageMax"]').val();
        var $ar_fields              = new Array();

        // Predefined headings
        $ar_fields.push({ display: 'ID', name : 'userId', width : 50, sortable : false, align: 'center' });
        $ar_fields.push({ display: 'Image', name : 'userImage', width : 40, sortable : false, align: 'center' });
        $ar_fields.push({ display: 'State', name : 'userState', width : 50, sortable : false, align: 'left' });
        $ar_fields.push({ display: 'First Name', name : 'firstName', width : 200, sortable : true, align: 'left' });
        $ar_fields.push({ display: 'Last Name', name : 'lastName', width : 200, sortable : true, align: 'left' });
        $ar_fields.push({ display: 'Username', name : 'username', width : 258, sortable : true, align: 'left' });
        $ar_fields.push({ display: 'Email Address', name : 'email', width : 260, sortable : true, align: 'left' });
        $ar_fields.push({ display: 'Password', name : 'password', width : 200, sortable : true, align: 'left' });
        $ar_fields.push({ display: 'Date Created', name : 'dateCreate', width : 100, sortable : true, align: 'left' });
        $ar_fields.push({ display: '', name : 'delete', width : 175, sortable : true, align: 'left' });

        $('#flex1').flexigrid
        ({
            colModel                : $ar_fields,
            onChangeSort            : false,
            showToggleBtn           : false,
            height                  : 600,
            nowrap                  : false,
            usepager                : false,
            resizable               : false
        });

        $('.pcontrol input').val($page);
        $('.pcontrol span').text($total);
    }


});