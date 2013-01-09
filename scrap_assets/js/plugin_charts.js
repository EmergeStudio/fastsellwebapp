jQuery.scrapChart = { 
	
	// ----- CREATE STORAGE CHART
	storage			: function($selector, $size)
	{	
		// ---------- EXECUTE
		if($size == 'large')
		{
			$chart_width	= $($selector).width() - 20;
			$chart_height	= 320;
		}
		else
		{
			$chart_width	= $($selector).width() - 10;
			$chart_height	= 220;
		}
		
		$($selector).css({ overflow:'hidden', height:$chart_height});
		$.scrapChart.loader($selector);
		
		$chart_src		= $('#hdPath').val() + 'charts/show/storage/' + $chart_width + '/' + $chart_height;
		
		$($selector).append('<iframe class="scrapChartFrame1 displayNone" width="'+ $chart_width +'px" height="'+ $chart_height +'px" src="'+ $chart_src +'" onload="$.scrapChart.show_iframe(\'.scrapChartFrame1\')" style="overflow:hidden;" ></iframe>');
	},
	
	
	// ----- CREATE LOGIN COUNT CHART
	login_count 	: function($selector, $size)
	{
		// ---------- EXECUTE
		if($size == 'large')
		{
			$chart_width	= $($selector).width() - 30;
			$chart_height	= 320;
		}
		else
		{
			$chart_width	= $($selector).width() - 10;
			$chart_height	= 220;
		}
		
		$($selector).css({ overflow:'hidden', height:$chart_height});
		$.scrapChart.loader($selector);
		
		$chart_src		= $('#hdPath').val() + 'charts/login_count/' + $chart_width + '/' + $chart_height;
		
		
		$($selector).append('<iframe class="scrapChartFrame2 displayNone" width="'+ $chart_width +'" height="'+ $chart_height +'px" src="'+ $chart_src +'" onload="$.scrapChart.show_iframe(\'.scrapChartFrame2\')" style="overflow:hidden;" ></iframe>');
	},
	
	
	// ----- CREATE CLAIM INFO CHART
	claim_info 	: function($selector, $size)
	{
		// ---------- EXECUTE
		if($size == 'large')
		{
			$chart_width	= $($selector).width() - 30;
			$chart_height	= 320;
		}
		else
		{
			$chart_width	= $($selector).width() - 10;
			$chart_height	= 220;
		}
		
		$($selector).css({ overflow:'hidden', height:$chart_height});
		$.scrapChart.loader($selector);
		
		$chart_src		= $('#hdPath').val() + 'charts/claim_info/' + $chart_width + '/' + $chart_height + '/' + $('input[name="hdClaimId1"]').val();
		
		$(window).bind("load", function()
		{
			$($selector).append('<iframe class="scrapChartFrame3 displayNone" width="'+ $chart_width +'" height="'+ $chart_height +'px" src="'+ $chart_src +'" onload="$.scrapChart.show_iframe(\'.scrapChartFrame3\')" style="overflow:hidden;" ></iframe>');
		});
	},
	
	
	// ----- CREATE FILE COUNT CHART
	file_count 	: function($selector, $size)
	{
		// ---------- EXECUTE
		if($size == 'large')
		{
			$chart_width	= $($selector).width() - 30;
			$chart_height	= 320;
		}
		else
		{
			$chart_width	= $($selector).width() - 10;
			$chart_height	= 220;
		}
		
		$($selector).css({ overflow:'hidden', height:$chart_height});
		$.scrapChart.loader($selector);
		
		$chart_src		= $('#hdPath').val() + 'charts/show/file_count/' + $chart_width + '/' + $chart_height;
		
		
		$($selector).append('<iframe class="scrapChartFrame3 displayNone" width="'+ $chart_width +'" height="'+ $chart_height +'px" src="'+ $chart_src +'" onload="$.scrapChart.show_iframe(\'.scrapChartFrame3\')" style="overflow:hidden;" ></iframe>');
	},
	
	
	//----------------------------------------------------------------------------- LOADER
	loader 			: function($selector)
	{
		// Attach loader
		if($('.chartLoader').length == 0)
		{
			$($selector).append('<div class="chartLoader">Generating Report...</div>');
		}
	},
	
	
	//----------------------------------------------------------------------------- SHOW IFRAME
	show_iframe 	: function($selector)
	{	
		$($selector).parent().find('.chartLoader').remove();
		$($selector).fadeIn();
	}
	
};