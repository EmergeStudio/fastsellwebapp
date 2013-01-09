/*
jQuery Star Rating plugin
Created By: Chris Humboldt (www.chrismodem.com)
*/	
$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- SOME VARIABLES
	var $crt_page 				= $('#scrappy').attr('class');
	var $done_text				= '';
	var $star_rating			= 0;
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/fastsell/';


// ------------------------------------------------------------------------------EXECUTE

	$fc_initial_rating();
	
	$fc_star_rating_hover();
	
	$fc_star_rating_click();
	
	
// ------------------------------------------------------------------------------FUNCTIONS
		
	// ----- INITIAL RATING
	function $fc_initial_rating()
	{
		$('.starRatingContain').live('mouseover', function()
		{
			// Some variables
			$this					= $(this);
			$star_rating		= $this.find('input[name="hdStarRating"]').val();
		});
	}

	// ----- HOVER OVER RATING
	function $fc_star_rating_hover()
	{
		$('.starHoverBlock').live('mouseover', function()
		{
			// Some variables
			$this					= $(this);
			$parent				= $this.parents('.starRatingContain');
			$class				= $this.attr('class');
			$star					= parseInt($class.substring(30));
			
			// Edit the DOM
			$parent.find('.starRating').width($star * 20);
		});
	
		// ----- Hover out rating
		$('.starRatingContain').live('mouseout', function()
		{
			// Some variables
			$this					= $(this);
			
			// Edit the DOM
			$this.find('.starRating').width($star_rating * 20);
		});
	}

	// ----- CLICK THE STAR RATING
	function $fc_star_rating_click()
	{
		$('.starHoverBlock').live('click', function()
		{	
			// Demo example
			$.scrap_note_loader('Submitting your rating');
			setTimeout($fc_demo_star_rating_click, 1000);
		});
	}

	// ----- CLICK THE STAR RATING DEMO
	function $fc_demo_star_rating_click()
	{
		$.scrap_note_time('Your rating has been subitted', 2000);
	}
	

});