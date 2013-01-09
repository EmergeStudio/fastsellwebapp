<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * SCRAP LAYOUT (extends Codeigniter HTML Helper)
 *
 * Library is used to create custom layout elements with ease.  This
 * includes things like option boxes, heading bars and so on.
 * 
 * Main reason for creation is to speed up the layout creation process.
 * 
 * @author		Chris Humboldt (http://www.chrismodem.com)
 * @link		http://www.scrappyworx.com/
 */
	
	
/*
|--------------------------------------------------------------------------
| PAGE NAVIGATION
|--------------------------------------------------------------------------
|
| Create page navigation html.  The controls are run through javascipt as
| these buttons are purely static
|
| @param 1	= Current page
| @param 2	= Total pages
| @param 3	= Total of individual elements (not total pages) - OPTIONAL
| @param 4	= Display limit text (display off and on states) - OPTIONAL
|
*/
function page_nav($crt_page, $pages_total, $element_total = 1, $limit_text = '')
{
	// Initial variables
	$html 			= '';
	$page_loop		= 0;
	
	
	// Control how the number list displays
	if($pages_total > 10)
	{
		$pages_seen	= 10;
		$less_nums	= '';
	}
	else
	{
		$pages_seen = $pages_total;
		$less_nums	= ' lessNums';
	}
	$margin_top	= (25 * $pages_seen) + 2;
	
	
	// HTML
	$html	.= '<div class="pagenation"><div class="pagingState active">';
		
		// Limit text
		if($element_total >= 500)
		{
			$html	.= '<div class="control floatRight btnRemoveLimit">'.$limit_text.'</div>';
		}
		
		$html	.= '<div class="control floatLeft btnPrevPage">Prev</div>';
		$html	.= '<div class="floatLeft">';
		
			// Create the paging list
			$html	.= '<div class="numList displayNone'.$less_nums.'" style="margin-right:5px; margin-top:-'.($margin_top + 4).'px; height:'.($margin_top).'px;">';
			
				while($page_loop < $pages_total)
				{
					$page_loop++;
					if($page_loop == $crt_page)
					{
						$active		= ' active';
					}
					else
					{
						$active		= '';
					}
					$html	.= '<div class="listPageNum'.$less_nums.' pageNum_'.$page_loop.$active.'">'.$page_loop.'</div>';
				}
			
			$html	.= '</div>';
			$html	.= '<div class="control floatLeft btnCrtPage">';
				
				$html	.= $crt_page.nbs(1);
				$html	.= 'of';
				$html	.= nbs(1).$pages_total;
			
			$html	.= '</div>';
		$html	.= '</div>';
		$html	.= '<div class="control floatLeft btnNextPage">Next</div>';
		
		$html	.= '<div class="clearFloat"></div>';
		
		// Some hidden data
		$html	.= '<input type="hidden" name="scrap_pageNo" value="'.$crt_page.'">';
		$html	.= '<input type="hidden" name="scrap_pageMax" value="'.$pages_total.'">';
	
	// End of container	
	$html	.= '</div></div>';
	
	
	// Return the HTML
	return $html;
}
	
	
/*
|--------------------------------------------------------------------------
| DIV HEIGHT
|--------------------------------------------------------------------------
|
| Create a div with a set height
|
| @param 1	= Hieght in pixels
|
*/
function div_height($height = 1)
{
	return '<div class="divHeight" style="height:'.$height.'px"></div>';
}
	
	
/*
|--------------------------------------------------------------------------
| CLEAR FLOAT
|--------------------------------------------------------------------------
|
| Create a div to clear the floating of elements
|
*/
function clear_float()
{
	return '<div class="clearFloat"></div>';
}
	
	
/*
|--------------------------------------------------------------------------
| OPEN DIV
|--------------------------------------------------------------------------
|
| Create an open div tag
|
*/
function open_div($class = '', $title = '', $id = '')
{
	// Id
	if($id == '')
	{
		$id = '';
	}
	else
	{
		$id = ' id="'.$id.'"';
	}
	// Class
	if($class == '')
	{
		$class = '';
	}
	else
	{
		$class = ' class="'.$class.'"';
	}
	// Title
	if($title == '')
	{
		$title = '';
	}
	else
	{
		$title = ' title="'.$title.'"';
	}
	// On click
	$is_iPad		= (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
	$is_iPhone	= (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPhone');
	if(($is_iPad == TRUE) || ($is_iPhone == TRUE))
	{
		$on_click = ' onclick=""';
	}
	else
	{
		$on_click = '';
	}
	
	$html =  '<div'.$id.$class.$title.$on_click.'>';
	return $html;
}
	
	
/*
|--------------------------------------------------------------------------
| CLOSE DIV
|--------------------------------------------------------------------------
|
| Create the closing div tag
|
|
*/
function close_div()
{
	return '</div>';
}
	
	
/*
|--------------------------------------------------------------------------
| CLOSE DIV
|--------------------------------------------------------------------------
|
| Create the closing div tag
|
|
*/
function make_link($content, $class = '', $title = '', $id = '')
{
	// Id
	if($id == '')
	{
		$id = '';
	}
	else
	{
		$id = ' id="'.$id.'"';
	}
	// Class
	$class = ' class="makeLink '.$class.'"';
	// Title
	if($title == '')
	{
		$title = '';
	}
	else
	{
		$title = ' title="'.$title.'"';
	}
	// On click
	$is_iPad		= (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
	$is_iPhone	= (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPhone');
	if(($is_iPad == TRUE) || ($is_iPhone == TRUE))
	{
		$on_click = ' onclick=""';
	}
	else
	{
		$on_click = '';
	}
	
	$html =  '<span'.$id.$class.$title.$on_click.'>';
	$html .= $content;
	$html .= '</span>';
	
	return $html;
}
	
	
/*
|--------------------------------------------------------------------------
| FULL DIV
|--------------------------------------------------------------------------
|
| Create a full div tag
|
*/
function full_div($content, $class = '', $title = '', $id = '')
{
	// Id
	if($id == '')
	{
		$id = '';
	}
	else
	{
		$id = ' id="'.$id.'"';
	}
	// Class
	if($class == '')
	{
		$class = '';
	}
	else
	{
		$class = ' class="'.$class.'';
	}
	// Title
	if($title == '')
	{
		$title = '';
	}
	else
	{
		$title = ' title="'.$title.'"';
		$class .= ' tooltip';
	}
	
	// Close class
	if($class != '')
	{
		$class	.= '"';
	}
	
	$html =  '<div'.$id.$class.$title.'>';
	$html .= $content;
	$html .= '</div>';
	
	return $html;
}
	
	
/*
|--------------------------------------------------------------------------
| HIDDEN DIV
|--------------------------------------------------------------------------
|
| Create a hidden div tag
|
*/
function hidden_div($content, $class = '', $title = '', $id = '')
{
	// Id
	if($id == '')
	{
		$id = '';
	}
	else
	{
		$id = ' id="'.$id.'"';
	}
	// Class
	if($class == '')
	{
		$class = ' class="hiddenDiv';
	}
	else
	{
		$class = ' class="'.$class.' hiddenDiv';
	}
	// Title
	if($title == '')
	{
		$title = '';
	}
	else
	{
		$title = ' title="'.$title.'"';
		$class .= ' tooltip';
	}
	
	// Close class
	if($class != '')
	{
		$class	.= '"';
	}
	
	$html =  '<div'.$id.$class.$title.'>';
	$html .= $content;
	$html .= '</div>';
	
	return $html;
}
	
	
/*
|--------------------------------------------------------------------------
| OPEN SPAN
|--------------------------------------------------------------------------
*/
function open_span($class = '', $title = '', $id = '')
{
	// Id
	if($id == '')
	{
		$id = '';
	}
	else
	{
		$id = ' id="'.$id.'"';
	}
	// Class
	if($class == '')
	{
		$class = '';
	}
	else
	{
		$class = ' class="'.$class.'"';
	}
	// Title
	if($title == '')
	{
		$title = '';
	}
	else
	{
		$title = ' title="'.$title.'"';
	}
	
	$html =  '<span'.$id.$class.$title.'>';
	return $html;
}
	
	
/*
|--------------------------------------------------------------------------
| CLOSE SPAN
|--------------------------------------------------------------------------
*/
function close_span()
{
	return '</span>';
}
	
	
/*
|--------------------------------------------------------------------------
| FULL SPAN
|--------------------------------------------------------------------------
|
| Create a full span tag
|
*/
function full_span($content, $class = '', $title = '', $id = '')
{
	// Id
	if($id == '')
	{
		$id = '';
	}
	else
	{
		$id = ' id="'.$id.'"';
	}
	// Class
	if($class == '')
	{
		$class = '';
	}
	else
	{
		$class = ' class="'.$class;
	}
	// Title
	if($title == '')
	{
		$title = '';
	}
	else
	{
		$title = ' title="'.$title.'"';
		$class .= ' tooltip';
	}
	
	// Close class
	if($class != '')
	{
		$class	.= '"';
	}
	
	$html =  '<span'.$id.$class.$title.'>';
	$html .= $content;
	$html .= '</span>';
	
	return $html;
}
	
	
/*
|--------------------------------------------------------------------------
| MONTH PICKER
|--------------------------------------------------------------------------
*/
function month_picker($month_display = 'long')
{
	// Initial variables
	$html 			= '';
	$crt_month		= date('m');
	$crt_year		= date('Y');
	$year_count		= $crt_year - 5;
	$last_year		= $crt_year + 5;
	$year				= array();
	
	while($year_count <= $last_year)
	{
		$year[$year_count]	= $year_count;
		$year_count++;
	}
	
	if($month_display == 'long')
	{
		$months	= array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
	}
	elseif($month_display == 'short')
	{
		$months	= array(01 => 'Jan', 02 => 'Feb', 03 => 'Mar', 04 => 'Apr', 05 => 'May', 06 => 'Jun', 07 => 'Jul', 08 => 'Aug', 09 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
	}
	elseif($month_display == 'numbers')
	{
		$months	= array(01 => '01', 02 => '02', 03 => '03', 04 => '04', 05 => '05', 06 => '06', 07 => '07', 08 => '08', 09 => '09', 10 => '10', 11 => '11', 12 => '12');
	}
	elseif($month_display == 'both')
	{
		$months	= array(01 => 'Jan (01)', 02 => 'Feb (02)', 03 => 'Mar (03)', 04 => 'Apr (04)', 05 => 'May (05)', 06 => 'Jun (06)', 07 => 'Jul (07)', 08 => 'Aug (08)', 09 => 'Sep (09)', 10 => 'Oct (10)', 11 => 'Nov (11)', 12 => 'Dec (12)');
	}
	
	
	// HTML
	$html	.= form_dropdown('slctScrapMonth', $months, $crt_month);
	$html	.= form_dropdown('slctScrapYear', $year, $crt_year);
	
	
	// Return the HTML
	return $html;
}
	
	
/*
|--------------------------------------------------------------------------
| MAKE BUTTON
|--------------------------------------------------------------------------
*/
function make_button($btn_text, $custom_class = '', $link_path = '', $align = '', $tooltip = '', $display = TRUE)
{
	// Initial variables
	$align 	= strtolower($align);
	$html		= '';
		
		
	// Custom button class
	if($custom_class != '')
	{
		$custom_class	= ' '.$custom_class;
	}
	
	
	// Control the floating alignment
	if($align == 'left')
	{
		$align	= 'floatLeft';
	}
	elseif($align == 'right')
	{
		$align	= 'floatRight';
	}
	else
	{
		$align	= 'nothing';
	}
	
	
	// Display
	if($display == FALSE)
	{
		$display	= ' displayNone';
	}
	else
	{
		$display	= '';
	}
	
	// On click
	$is_iPad		= (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
	$is_iPhone	= (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPhone');
	if(($is_iPad == TRUE) || ($is_iPhone == TRUE))
	{
		$on_click = ' onclick=""';
	}
	else
	{
		$on_click = '';
	}
		
		
	// Create the html based on the link path	
	$html	.= '<div class="'.$align.$display.'">';
	
		if($link_path == '')
		{
			if($tooltip != '')
			{
				$html	.= '<div class="scrapButton tooltip'.$custom_class.'"'.$on_click.'" title="'.$tooltip.'"><div class="btnIcon"></div>'.$btn_text.'</div>';
			}
			else
			{
				$html	.= '<div class="scrapButton'.$custom_class.'"'.$on_click.'"><div class="btnIcon"></div>'.$btn_text.'</div>';
			}
		}
		elseif($link_path != '')
		{
			if(substr($link_path, 0, 4) == 'http')
			{
				$link_path	= $link_path;
			}
			else
			{
				$link_path	= base_url().$link_path;
			}
			
			if($tooltip != '')
			{
				$html	.= '<a href="'.$link_path.'" class="scrapButton2 tooltip'.$custom_class.'" title="'.$tooltip.'"><div class="btnIcon"></div>'.$btn_text.'</a>';
			}
			else
			{
				$html	.= '<a href="'.$link_path.'" class="scrapButton2'.$custom_class.'"><div class="btnIcon"></div>'.$btn_text.'</a>';
			}
		}
	
		$html	.= '<div class="clearFloat"></div>';
		
	$html	.= '</div>';
	
	return $html;
}


/*
|--------------------------------------------------------------------------
| MAKE TOGGLE
|--------------------------------------------------------------------------
*/
function make_toggle($false_text, $true_text, $state = FALSE, $inp_name)
{
	// Some variables
	$false_class        = '';
	$true_class         = ' inactive';
	$toggle_state       = ' left';
	$hd_state           = 'FALSE';
	if($state == TRUE)
	{
		$false_class    = ' inactive';
		$true_class     = '';
		$toggle_state   = ' right';
		$hd_state       = 'TRUE';
	}

	$html   = '';

	$html   .= '<div class="toggle">';

	$html   .= '<div class="textTrue'.$false_class.'">'.$false_text.'</div>';

	$html   .= '<div class="toggleBar">';

	$html   .= '<div class="toggleButton'.$toggle_state.'"></div>';

	$html   .= '</div>';

	$html   .= '<div class="textFalse'.$true_class.'">'.$true_text.'</div>';

	$html   .= '<div class="'.$inp_name.' toggleValue hiddenDiv">'.$hd_state.'</div>';

	$html   .= '</div>';

	return $html;
}
?>