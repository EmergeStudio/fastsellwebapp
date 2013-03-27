$(document).ready(function(){
	
// ------------------------------------------------ STARTUP

	// ---------- SOME VARIABLES
	var $crt_page 				= $('#scrappy').attr('class');
	var $is_iPad 				= navigator.userAgent.match(/iPad/i) != null;	// iPad check
	var $is_iPhone 				= navigator.userAgent.match(/iPhone/i) != null; // iPhone check
	var $done_text				= '';
	var $mouse_is_inside		= false;
	var $mouse_is_inside_2		= false;
	var $mouse_is_inside_3		= false;
	var $fav_html				= '';
	var $fav_container			= '';
	var $manage_fav				= false;
	var $fav_state				= false;
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/manage/';


// ------------------------------------------------ EXECUTE
	
	$fc_btn_scrap();
	
	$fc_tooltip();
	
	$fc_selection_menu_display();
	
	$fc_show_sub_nav();
	
	$fc_adjust_user_sub_nav();

    $.scrap_uniform_all();
	
	$fc_remove_red_border();
	
	$fc_my_details();

    $fc_manage_mode();

    $fc_toggle();

    $fc_input_ghosting();

    $fc_change_timezone();

    $('.hdNotification').each(function()
    {
        $.scrap_note_time($(this).text(), 4000, 'tick');
    });

    $fc_resize_columns_execute();

    $fc_activate_switch();
	
	
// ------------------------------------------------ FUNCTIONS

    // ---------- ACTIVATE SWITCH
    function $fc_activate_switch()
    {
        // Iterate over checkboxes
        $("input[type=checkbox].switch").each(function()
        {
            // Insert mark-up for switch
            $(this).before(
                '<span class="switch">' +
                    '<span class="mask" /><span class="background" />' +
                    '</span>'
            );

            // Hide checkbox
            $(this).hide();

            // Set inital state
            if (!$(this)[0].checked)
            {
                $(this).prev().find(".background").css({left: "-56px"});
            }
        });

        $("span.switch").click(function()
        {
            // If on, slide switch off
            if ($(this).next()[0].checked)
            {
                $(this).find(".background").animate({left: "-56px"}, 200);
                $('.flexigrid').removeClass('editOn').addClass('editOff');
                $('.scrapEdit').hide();
            }
            else
            {
                $(this).find(".background").animate({left: "0px"}, 200);
                $('.flexigrid').removeClass('editOff').addClass('editOn');
                $('.flexigrid .eHighLight').removeClass('eHighLight');
            }

            // Toggle state of checkbox
            $(this).next()[0].checked = !$(this).next()[0].checked;
        });
    }

    // ----- RESIZE COLUMNS EXECUTE
    function $fc_resize_columns_execute()
    {
        $fc_resize_columns();
        $(window).resize(function(){ $fc_resize_columns(); });
    }

    // ----- RESIZE COLUMNS
    function $fc_resize_columns()
    {
        // Some variables
        $window_w               = $(window).width();

        // Columns
        $('.middle .leftColBig, .middle .rightColBig').width($window_w - 350);
        $('.middle .rightContentLarge').width($window_w - 310);
    }

    // ----- CHANGE TIMEZONE
    function $fc_change_timezone()
    {
        $('select[name="drpdwnTimezone"]').live('change', function()
        {
            $('.frmChangeTimezone').submit();
        });
    }

    // ----- INPUT GHOSTING
    function $fc_input_ghosting()
    {
        $('input, textarea').live('focus', function()
        {
            $(this).addClass('ghosting');
        });
        $('input, textarea').live('blur', function()
        {
            $(this).removeClass('ghosting');
        });
    }

    // ----- TOGGLE
    function $fc_toggle()
    {
        // Toggle on click
        $('.toggle .toggleButton').live('click', function()
        {
            // Some variables
            $this               = $(this);
            $parent             = $this.parents('.toggle');

            // Animate
            if($this.hasClass('left'))
            {
                $this.removeClass('left').addClass('right').animate({ marginLeft : '20px' }, 'fast', function()
                {
                    $parent.find('.textTrue').addClass('inactive');
                    $parent.find('.textFalse').removeClass('inactive');
                });
                $parent.find('.toggleValue').text('TRUE');
            }
            else
            {
                $this.removeClass('right').addClass('left').animate({ marginLeft : '-4px' }, 'fast', function()
                {
                    $parent.find('.textTrue').removeClass('inactive');
                    $parent.find('.textFalse').addClass('inactive');
                });
                $parent.find('.toggleValue').text('FALSE');
            }
        });
    }

	// ----- SELECTION MENU DISPLAY
	function $fc_selection_menu_display()
	{
		// Toggle on selection button click
		$('.btnChartChangeDateRange').live('click', function()
		{
			$(this).parents('.selectionMenuContainer').find('.selectionMenu').toggle();
		});
		
		// Hide
		$('.selectionMenuContainer .btnOkGo').live('click', function()
		{
			$('.selectionMenu').hide();
			$.scrap_note_time('<b>PLEASE NOTE:</b> This feature has been disabled in the demo', 4000)
		});
		
		$('.selectionMenu').hover(function()
		{ 
			$mouse_is_inside		= true; 
		}, 
		function()
		{ 
			$mouse_is_inside		= false; 
		});
		
		$('html').mouseup(function()
		{ 
			if(!$mouse_is_inside)
			{
				$('.selectionMenu').hide();
			}
		});
	}
	
	// ---------- TOOLTIP
	function $fc_tooltip()
	{
		$('.tooltip').tooltip(
		{ 
			track		: true, 
			delay		: 800, 
			showURL		: false, 
			fade		: 100,
			left		: -30,
			top			: 25
		});
	}
	
	// ---------- SCRAP BUTTON FUNCTIONS
	function $fc_btn_scrap()
	{
		$('.scrapButton, .pagenation .control').live('mousedown', function()
		{
			if($(this).hasClass('greyButton'))
			{
				$(this).css({ backgroundColor : '#dedede' });
			}
            else if($(this).hasClass('blueButton'))
            {
                $(this).css({ backgroundColor : '#0789b5' });
            }
			else if($(this).hasClass('pinkButton'))
			{
				$(this).css({ backgroundImage : 'url()', backgroundColor : '#f41867' });
			}
			else if($(this).hasClass('greenButton'))
			{
				$(this).css({ backgroundImage : 'url()', backgroundColor : '#1aa602' });
			}
			else
			{
				$(this).css({ backgroundImage : 'url()', backgroundColor : '#ededed' });
			}
		});
		
		$('.scrapButton, .pagenation .control').live('mouseup', function()
		{
			if($(this).hasClass('greyButton'))
			{
				$(this).css({ backgroundColor : '#ebebeb' });
			}
            else if($(this).hasClass('blueButton'))
            {
                $(this).css({ backgroundColor : '#1ba2d0' });
            }
			else if($(this).hasClass('pinkButton'))
			{
				$(this).css({ backgroundImage : 'url('+ $base_path +'scrap_assets/images/universal/btn_pink.jpg)', backgroundPosition : 'bottom'});
			}
			else if($(this).hasClass('greenButton'))
			{
				$(this).css({ backgroundImage : 'url('+ $base_path +'scrap_assets/images/universal/btn_green.jpg)', backgroundPosition : 'bottom'});
			}
			else
			{
				$(this).css({ backgroundImage : 'url('+ $base_path +'scrap_assets/images/universal/btn_white_grey_back.jpg)', backgroundPosition : 'bottom', backgroundColor : '#fff' });
			}
		});
	}
	
	// ---------- SHOW SUB NAVIGATION
	function $fc_show_sub_nav()
	{
		// On click event
		$('#topBar .mainNav .mainNavLink').live('click', function()
		{
			// Some variables
			$this				= $(this);
			$parent				= $this.parent();
			
			// Show the sub navigation
			if($parent.find('.subNav').is(':hidden'))
			{
				$parent.addClass('active');
				$parent.find('.subNav').fadeIn('fast');
			}
		});
		
		// Close sub navigation
		$('#topBar .mainNav .subNav').hover(function()
		{ 
			$mouse_is_inside_2		= true; 
		}, 
		function()
		{ 
			$mouse_is_inside_2		= false; 
		});
		
		$('html').mouseup(function()
		{ 
			if(!$mouse_is_inside_2)
			{
				$('#topBar .mainNav li').removeClass('active');
				$('#topBar .mainNav .subNav').fadeOut('fast');
			}
		});
		
		
		// App - On click event
		$('#header #topBar .appNav li .sectionNavLink').live('click', function()
		{
			// Some variables
			$this				= $(this);
			$parent				= $this.parent();
			
			// Show the sub navigation
			if($parent.find('.subNav').is(':hidden'))
			{
				$parent.find('.subNav').fadeIn('fast');
			}
		});
		
		// App - Close sub navigation
		$('#header #topBar .appNav li .subNav').hover(function()
		{ 
			$mouse_is_inside_3		= true; 
		}, 
		function()
		{ 
			$mouse_is_inside_3		= false; 
		});
		
		$('html').mouseup(function()
		{ 
			if(!$mouse_is_inside_3)
			{
				$('#header #topBar .appNav li .subNav').fadeOut('fast');
			}
		});
	}
	
	// ---------- ADJUST USER SUB NAVIGATION
	function $fc_adjust_user_sub_nav()
	{
		$('#topBar .userNavLink .mainNavLink').live('click', function()
		{
			// Some variables
			$main_user_link_w		= $('#topBar .userNavLink .mainNavLink').width();
			$sub_user_link_w		= $('#topBar .userNavLink .subNav').width();
			
			// Adjust accordingly
			if($sub_user_link_w > $main_user_link_w)
			{
				$('#topBar .userNavLink .subNav').css({ marginLeft: '-'+ ($sub_user_link_w - $main_user_link_w + 11) +'px' });
			}
		});
	}

	// ----- REMOVE THE RED BORDER ON INPUTS
	function $fc_remove_red_border()
	{
		$('input, textarea').live('focus', function()
		{
			$(this).removeClass('redBorder');
		});
	}

	// ----- THE LOGGED IN USERS DETAILS
	function $fc_my_details()
	{
		// Get the my details popup
		$('body').sunBox.popup('My Details', 'popMyDetails',
		{ 
			ajax_path		: $ajax_base_path + 'html_view/universal/quick_view',
			close_popup		: false,
			callback 		: function($return)
			{	
				// Callback
				if($return == false)
				{
				}
			}
		});
		
		// Open the my details popup on click
		$('.btnEditMyProfile').live('click', function()
		{
			// Edit DOM
			$.scrap_note_loader('Getting your details');
			$('#topBar .mainNav .subNav').fadeOut('fast');
			
			// Get user details
			$.post($base_path + 'ajax_handler_users/user_details_popup_content',
			{
				crt_user			: 'true'
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
					$.scrap_note_hide();
					$('.popMyDetails .popup').html($data);
					$('.popMyDetails select, .popAddUser input:checkbox, .popAddUser input:radio').uniform();
					$('body').sunBox.show_popup('popMyDetails');
					$('body').sunBox.adjust_popup_height('popMyDetails');
				}
			});
		});
		
		
		// Change password
		$('.popMyDetails .btnChangePassword').live('click', function()
		{
			// Some variables
			$scroll_pos			= $('body').scrollTop();
			
			// Change the DOM
			$('.popMyDetails .btnPasswordContain').hide();
			$('.popMyDetails .passwordContain').fadeIn();
			$('.popMyDetails input[name="inpPassword"]').val('');
			$('.popMyDetails input[name="inpPasswordConfirm"]').val('');
			$('body').scrollTop($scroll_pos);
			$('body').sunBox.adjust_popup_height('popMyDetails');
		});
		
		
		// Upload new image click
		$('.popMyDetails .btnUploadNewProfileImage').live('click', function()
		{
			$('.popMyDetails .profileContainer, .popMyDetails .btnUploadNewProfileImage').hide();
			$('.popMyDetails .uploadContainer').show();
			//$('.popMyInformation .displayNone').hide();
			$('body').sunBox.adjust_popup_height('popMyDetails');
		});
		
		
		// Upload new image cancel
		$('.popMyDetails .btnCancelUpload').live('click', function()
		{
			$('.popMyDetails .profileContainer, .popMyDetails .btnUploadNewProfileImage').show();
			$('.popMyDetails .uploadContainer').hide();
			$('.popMyDetails .displayNone').hide();
			$('body').sunBox.adjust_popup_height('popMyDetails');
		});
		
		
		// Upload new image confirm
		$('.popMyDetails .btnDoUpload').live('click', function()
		{
			// Some variables
			$error				= false;
			$actual_image		= $('.popMyDetails input[name="inpUploadImage"]').val();
			
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
				$('.popMyDetails .uploadFormContiner').hide();
				$('.popMyDetails .loader').show();
				$('.popMyDetails .profileImage').css({ opacity : 0.3 });
				$iframe_name	= 'attachIframe_'+ $.scrap_random_string();
				$('.popMyDetails .uploadContainer').append('<iframe name="'+ $iframe_name +'" class="displayNone '+ $iframe_name +'" width="5" height="5"></iframe>');
				$('.popMyDetails .frmUploadUserImage').attr('target', $iframe_name);
				$('.popMyDetails .frmUploadUserImage').submit();
				
				$('iframe[name="'+ $iframe_name +'"]').load(function() 
				{
					$data		= $('.popMyDetails .uploadContainer iframe[name="'+ $iframe_name +'"]').contents().find('body').html();
					
					// Check result
					if($data == 'error_no_user')
					{
						$.scrap_note_time('Hmmm there was a problem loading up your new profile image', 4000, 'cross');
					}
					else
					{
						$('.popMyDetails .profileContainer, .popMyDetails .btnUploadNewProfileImage').show();
						$('.popMyDetails .uploadContainer').hide();
						$('.popMyDetails input[name="inpUploadImage"]').val('');
						$('.popMyDetails .uploadFormContiner').show();
						$('.popMyDetails .loader').hide();
						$('.popMyDetails .profileImage').attr({ src : $data }).css({ opacity : 1 });
						$('#topBar .userNavLink .profileImage').attr({ src : $data });
						$('.popMyDetails .' + $iframe_name).remove();
						$('.popMyDetails .displayNone').hide();
						$('body').sunBox.adjust_popup_height('popMyDetails');
						$.scrap_note_time('Your new profile image has been uploaded', 4000, 'tick');
					}
				});
			}
		});

		
		// Save user details
		$('.popMyDetails .returnTrue').live('click', function()
		{
			// Some variables
			$error				= false;
			$user_id			= $('.popMyDetails input[name="hdUserId"]').val();
			$first_name			= $('.popMyDetails input[name="inpName"]').val();
			$surname			= $('.popMyDetails input[name="inpSurname"]').val();
			$username			= $('.popMyDetails input[name="inpUsername"]').val();
			$email_address		= $('.popMyDetails input[name="inpEmail"]').val();
			$password			= $('.popMyDetails input[name="inpPassword"]').val();
			$password_confirm	= $('.popMyDetails input[name="inpPasswordConfirm"]').val();
			
			// Validate
			// First name
			if($error == false)
			{
				if($first_name == '')
				{
					$error		= true;
					$('.popMyDetails input[name="inpName"]').addClass('redBorder');
					$.scrap_note_time('Please provide a name', 4000, 'cross');
				}
			}
			
			// Surname
			if($error == false)
			{
				if($surname == '')
				{
					$error		= true;
					$('.popMyDetails input[name="inpSurname"]').addClass('redBorder');
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
						$('.popMyDetails input[name="inpUsername"]').addClass('redBorder');
						$.scrap_note_time('Your username cannot have any spaces', 4000, 'cross');
					}
				}
				else
				{
					$error		= true;
					$('.popMyDetails input[name="inpUsername"]').addClass('redBorder');
					$.scrap_note_time('Your username needs to be longer then 5 characters', 4000, 'cross');
				}
			}
			
			// Email
			if($error == false)
			{
				if($.scrap_is_email($email_address) == false)
				{
					$error		= true;
					$('.popMyDetails input[name="inpEmail"]').addClass('redBorder');
					$.scrap_note_time('Your email address does not check out', 4000, 'cross');
				}
			}
			
			// Password
			if($error == false)
			{
				if(($.scrap_is_password($password) == false) && ($password != ''))
				{
					$error		= true;
					$('.popMyDetails input[name="inpPassword"]').addClass('redBorder');
					$.scrap_note_time('Your password is not valid. It is <b>required</b>, needs to be <b>6 characters or more</b> and must be <b>alphanumeric</b>.', 4000, 'cross');
				}
			}
			
			// Password confirm
			if($error == false)
			{
				if($password != $password_confirm)
				{
					$error		= true;
					$('.popMyDetails input[name="inpPassword"], .popMyDetails input[name="inpPasswordConfirm"]').addClass('redBorder');
					$.scrap_note_time('Your passwords do not match', 4000, 'cross');
				}
			}
			
			// Save the changes
			if($error == false)
			{
				// Edit the DOM
				$.scrap_note_loader('Saivng your information');
				
				// Post the data
				$.post($base_path + 'ajax_handler_users/update_user_details',
				{
					user_id			: $user_id,
					first_name		: $first_name,
					surname			: $surname,
					username		: $username,
                    email_address   : $email_address,
					password		: $password
				},
				function($data)
				{	
					$data	= jQuery.trim($data);
					
					if($data == '9876')
					{
						$.scrap_logout();
					}
					//else if($data == 'okitsdone')
                    else
					{
						// Close the popup
						$('#topBar .userNavLink .mainNavLink .blueLink').text($first_name + ' ' + $surname);
						$('.popMyDetails .profileContainer, .popMyDetails .btnUploadNewProfileImage').show();
						$('.popMyDetails .passwordContain, .popMyInformation .uploadContainer').hide();
						$('.popMyDetails .displayNone').hide();
						$('body').sunBox.adjust_popup_height('popMyDetails');
						$.scrap_note_time('Your information has been updated', 4000, 'tick');
						$('body').sunBox.close_popup('popMyDetails');
					}
//					else
//					{
//						$.scrap_note_time($data, 4000, 'cross');
//					}
				});
			}
		});
	}

    // ----- MANAGE MODE
    function $fc_manage_mode()
    {
        // Manage mode
        $('.mainNavManageModeOn').live('click', function()
        {
            // Edit the DOM
            $('.mainNavManageModeOn, .appNavigationContainer').hide();
            $('.mainNavManageModeOff, .manageNavigationContainer').fadeIn();

            // Change to manage mode
            $.post($ajax_base_path + '/change_manage_mode',
            {
                manage_mode             : 'true'
            },
            function($data)
            {
                $data	= jQuery.trim($data);

                if($data == '9876')
                {
                    $.scrap_logout();
                }
            });
        });

        // Show event mode
        $('.mainNavManageModeOff').live('click', function()
        {
            // Edit the DOM
            $('.mainNavManageModeOn, .appNavigationContainer').fadeIn();
            $('.mainNavManageModeOff, .manageNavigationContainer').hide();

            // Change to manage mode
            $.post($ajax_base_path + '/change_manage_mode',
            {
                manage_mode             : 'false'
            },
            function($data)
            {
                $data	= jQuery.trim($data);

                if($data == '9876')
                {
                    $.scrap_logout();
                }
            });
        });
    }
	
});