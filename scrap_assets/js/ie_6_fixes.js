$(document).ready(function(){
	
// ------------------------------------------------------------------------------------------------ STARTUP

	// ---------- CURRENT PAGE
	var $crt_page = $('#scrappy').attr('class');
	
	
// ------------------------------------------------------------------------------------------------ EXECUTE
	
	// ---------- Only needed for ie6 and earlier
	if($.browser.msie && parseInt($.browser.version) <= 6)
	{
		DD_belatedPNG.fix('.pngFix, #sunBox div, #header #appNav a, .popup .topBar, .popup .middle, .popup .botBar');
		
		$fc_ie_hovers();
	}
	
	
// ------------------------------------------------------------------------------------------------ FUNCTIONS


	// ---------- IE HOVER FIXES
	function $fc_ie_hovers()
	{
		// Main hover function
		$('.btnWithCorners').hover(function()
		{
			$(this).css({ cursor : 'hand' });
		});
		
		
		// Black corner buttons
		$('.btnWithCorners').hover(function()
		{
			$(this).css({ backgroundColor : '#898989' });
		},
		function()
		{
			$(this).css({ backgroundColor : '#565555' });
		});
	}
	
});