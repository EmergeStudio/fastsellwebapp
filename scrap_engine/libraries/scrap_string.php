<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Scrap_string
{
	/*
	|--------------------------------------------------------------------------
	| CODEIGNITER REQUIREMENTS
	|--------------------------------------------------------------------------
	*/
	var $CI = null;
	
	function Scrap_string()
	{
		$this->CI =& get_instance();
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| LAST CHARACTER REMOVAL
	|--------------------------------------------------------------------------
	*/
	function remove_lc($string)
	{
		$string = substr($string, 0, -1);
		return $string;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| FIRST CHARACTER REMOVAL
	|--------------------------------------------------------------------------
	*/
	function remove_fc($string)
	{
		$string = substr($string, 1);
		return $string;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| FIRST AND LAST CHARACTER REMOVAL
	|--------------------------------------------------------------------------
	*/
	function remove_flc($string)
	{
		$string = substr($string, 1);
		$string = substr($string, 0, -1);
		return $string;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MAKE CSS FRIENDLY STRING
	|--------------------------------------------------------------------------
	*/
	function css_friendly($string)
	{
		$string = str_replace(' ','',$string);
		$string{0} = strtolower($string{0});
		return $string;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MAKE FOLDER DATE
	|--------------------------------------------------------------------------
	*/
	function folder_date($date)
	{
		$year_date		= date("Y",(strtotime($date)));
		$month_date		= date("m",(strtotime($date)));
		$day_date		= date("d",(strtotime($date)));
		return $year_date.'/'.$month_date.'/'.$day_date;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MAKE FOLDER YEAR DATE
	|--------------------------------------------------------------------------
	*/
	function folder_year_date($date)
	{
		$year_date		= date("Y",(strtotime($date)));
		return $year_date;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MAKE FOLDER YEAR DATE
	|--------------------------------------------------------------------------
	*/
	function folder_month_date($date)
	{
		$month_date		= date("m",(strtotime($date)));
		return $month_date;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MAKE FOLDER MONTH / DAY DATE
	|--------------------------------------------------------------------------
	*/
	function folder_month_day_date($date)
	{
		$month_date			= date("m",(strtotime($date)));
		$day_date			= date("d",(strtotime($date)));
		return $month_date.'/'.$day_date;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MAKE SHORT FOLDER DATE
	|--------------------------------------------------------------------------
	*/
	function short_folder_date($date)
	{
		$year_date		= date("Y",(strtotime($date)));
		$month			= date("m",(strtotime($date)));
		return $year_date.'/'.$month;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MAKE LONG DATE
	|--------------------------------------------------------------------------
	*/
	function make_long_date($date)
	{
		return date("d M Y, H:i",(strtotime($date)));
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MAKE SHORT DATE
	|--------------------------------------------------------------------------
	*/
	function make_short_date($date)
	{
		return date("d M, H:i",(strtotime($date)));
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MAKE DATE
	|--------------------------------------------------------------------------
	*/
	function make_date($date)
	{
		return date("d M Y",(strtotime($date)));
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MAKE DATE WITH DAY
	|--------------------------------------------------------------------------
	*/
	function make_day_date($date)
	{
		return date("l, d M Y",(strtotime($date)));
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MAKE EVENT TIME
	|--------------------------------------------------------------------------
	*/
	function make_event_time($date)
	{
		return date("H:i",(strtotime($date))).nbs(2).'<span class="greyTxt">('.date("l, d M Y",(strtotime($date))).')</span>';
	}


	/*
	|--------------------------------------------------------------------------
	| MAKE LONG DATE
	|--------------------------------------------------------------------------
	*/
	function make_micro_time($date)
	{
		return microtime($date);
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MAKE SHORT DATE
	|--------------------------------------------------------------------------
	*/
	function short_date($date)
	{
		return date("d M",(strtotime($date)));
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MONTH YEAR DATE
	|--------------------------------------------------------------------------
	*/
	function month_year_date($date)
	{
		return date("F Y",(strtotime($date)));
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| SHORT MONTH YEAR DATE
	|--------------------------------------------------------------------------
	*/
	function short_month_year_date($date)
	{
		return date("M Y",(strtotime($date)));
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MAKE DATABASE DATE AND TIME
	|--------------------------------------------------------------------------
	*/
	function make_db_date_time($date)
	{
		return date("Y-m-d H:i:s",(strtotime($date)));
	}


	/*
	|--------------------------------------------------------------------------
	| MAKE DATABASE DATE
	|--------------------------------------------------------------------------
	*/
	function make_db_date($date)
	{
		return date("Y-m-d",(strtotime($date)));
	}


	/*
	|--------------------------------------------------------------------------
	| MAKE HOUR
	|--------------------------------------------------------------------------
	*/
	function make_hours($date)
	{
		return date("H",(strtotime($date)));
	}


	/*
	|--------------------------------------------------------------------------
	| MAKE HOUR BY AM/PM
	|--------------------------------------------------------------------------
	*/
	function make_hours_ampm($date)
	{
		return date("h",(strtotime($date)));
	}


	/*
	|--------------------------------------------------------------------------
	| MAKE AM/PM
	|--------------------------------------------------------------------------
	*/
	function make_ampm($date)
	{
		if(date("H",(strtotime($date))) >= 12)
		{
			return 'pm';
		}
		else
		{
			return 'am';
		}
	}


	/*
	|--------------------------------------------------------------------------
	| MAKE MINUTES
	|--------------------------------------------------------------------------
	*/
	function make_minutes($date)
	{
		return date("i",(strtotime($date)));
	}


	/*
	|--------------------------------------------------------------------------
	| CURRENT DATE
	|--------------------------------------------------------------------------
	*/
	function crt_date()
	{
		return date('d M Y');
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| TOMORROWS DATE
	|--------------------------------------------------------------------------
	*/
	function tomorrow_date()
	{
		return date('d M Y', mktime(0,0,0,date('m'), date('d')+1, date('Y')));
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| CURRENT DATABASE DATE AND TIME
	|--------------------------------------------------------------------------
	*/
	function crt_db_date_time()
	{
		return date('Y-m-d H:i:s');
	}


	/*
	|--------------------------------------------------------------------------
	| CURRENT DATABASE DATE AND TIME
	|--------------------------------------------------------------------------
	*/
	function crt_db_date_time_2()
	{
		return date('Y-m-d His');
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| CURRENT DATABASE DATE
	|--------------------------------------------------------------------------
	*/
	function crt_db_date()
	{
		return date('Y-m-d');
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| CURRENT DATABASE TIME
	|--------------------------------------------------------------------------
	*/
	function crt_db_time()
	{
		return date('H:i:s');
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| SIZE IN MB
	|--------------------------------------------------------------------------
	*/
	function size_in_mb($size, $kb = FALSE)
	{
		if($kb == FALSE)
		{
			$size		=	round($size/(1024*1024),1);
		}
		else
		{
			$size		=	round($size/1024,1);
		}
		return $size;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| HOURS SELECT
	|--------------------------------------------------------------------------
	*/
	function hours_select($class = 'hoursSelect', $selected = null)
	{
		// Hours range
		$r = range(1, 24);
		
		// Current hours
		$selected = is_null($selected) ? date('H') : $selected;
		
		$select = "<select name=\"$class\" class=\"$class\">\n";
		foreach ($r as $hour)
		{
			$select .= "<option value=\"$hour\"";
			$select .= ($hour==$selected) ? ' selected="selected"' : '';
			$select .= '>'.$hour.'</option>\n';
		}
		$select .= '</select>';
		
		// Return
		return $select;
	}


	/*
	|--------------------------------------------------------------------------
	| HOURS SELECT SHORT
	|--------------------------------------------------------------------------
	*/
	function hours_select_short($class = 'hoursSelect', $selected = null)
	{
		// Hours range
		$r = range(1, 12);

		// Current hours
		$selected = is_null($selected) ? date('h') : $selected;

		$select = "<select name=\"$class\" class=\"$class\">\n";
		foreach ($r as $hour)
		{
			$select .= "<option value=\"$hour\"";
			$select .= ($hour==$selected) ? ' selected="selected"' : '';
			$select .= '>'.$hour.'</option>\n';
		}
		$select .= '</select>';

		// Return
		return $select;
	}


	/*
	|--------------------------------------------------------------------------
	| AM / PM SELECT
	|--------------------------------------------------------------------------
	*/
	function ampm_select($class = 'hoursSelect', $selected = null)
	{
		// Current hours
		if($selected == null)
		{
			$selected       = date('a');
		}

		$select     = "<select name=\"$class\" class=\"$class\">\n";

			$select .= '<option value="am"';

				if($selected == 'am')
				{
					$select .= ' selected="selected"';
				};

			$select .= '>';

				$select .= 'AM';

			$select .= '</option>';

			$select .= '<option value="pm"';

			if($selected == 'pm')
			{
				$select .= ' selected="selected"';
			};

			$select .= '>';

				$select .= 'PM';

			$select .= '</option>';

		$select .= '</select>';

		// Return
		return $select;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| MINUTES SELECT
	|--------------------------------------------------------------------------
	*/
	function minutes_select($class = 'minuteSelect', $selected = null)
	{
		// Array of mins
		$minutes 		= array(0, 15, 30, 45);
		
		$selected 		= in_array($selected, $minutes) ? $selected : 0;
		
		$select 			= "<select name=\"$class\" class=\"$class\">\n";
		foreach($minutes as $min)
		{
			$select 		.= "<option value=\"$min\"";
			$select 		.= ($min==$selected) ? ' selected="selected"' : '';
			$select 		.= ">".str_pad($min, 2, '0')."</option>\n";
		}
		$select 			.= '</select>';
		
		// Return
		return $select;
	} 
	
	
	/*
	|--------------------------------------------------------------------------
	| MINUTES SELECT
	|--------------------------------------------------------------------------
	*/
	function month_select_long($class = 'monthSelect', $selected = null)
	{
		// Array of months
		$months 			= array('January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		
		$selected 		= in_array($selected, $months) ? $selected : 0;
		
		$select 			= '<select name="'.$class.'" class="'.$class.'">\n';
		foreach($months as $month)
		{
			$select 		.= '<option value="'.$month.'"';
			$select 		.= ($month==$selected) ? ' selected="selected"' : '';
			$select 		.= ">".str_pad($month, 2, 'January')."</option>\n";
		}
		$select 			.= '</select>';
		
		// Return
		return $select;
	} 
	
	
	/*
	|--------------------------------------------------------------------------
	| YEAR SELECT
	|--------------------------------------------------------------------------
	*/
	function year_select($class = 'yearSelect', $selected = null)
	{
		// Array of months
		$years	 		= array(2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017, 2018);
		
		$selected 		= in_array($selected, $years) ? $selected : 0;
		
		$select 			= '<select name="'.$class.'" class="'.$class.'">\n';
		foreach($years as $year)
		{
			$select 		.= '<option value="'.$year.'"';
			$select 		.= ($year==$selected) ? ' selected="selected"' : '';
			$select 		.= ">".str_pad($year, 2, 'January')."</option>\n";
		}
		$select 			.= '</select>';
		
		// Return
		return $select;
	} 
	
	
	/*
	|--------------------------------------------------------------------------
	| SIZE IN KB
	|--------------------------------------------------------------------------
	*/
	function size_in_kb($size)
	{
		$size		=	ceil($size/(1024));
		return $size;
	} 


	/*
	|--------------------------------------------------------------------------
	| RANDOM STRING
	|--------------------------------------------------------------------------
	*/
	function random_string($length = 10)
	{
		$chars      = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ023456789";
		$chars_2    = "023456789";
		srand((double)microtime()*1000000); 
		$i          = 0;
		$string     = '';
		
		while ($i <= ($length - 2))
		{ 
			$num 		= rand() % 66; 
			$tmp 		= substr($chars, $num, 1); 
			$string	= $string . $tmp; 
			$i++; 
		}

		while ($i <= ($length))
		{
			$num 		= rand() % 66;
			$tmp 		= substr($chars_2, $num, 1);
			$string	= $string . $tmp;
			$i++;
		}
		
		return $string;
	}


	/*
	|--------------------------------------------------------------------------
	| RANDOM STRING  LETTER
	|--------------------------------------------------------------------------
	*/
	function random_string_letter($length = 10)
	{
		$chars      = "abcdefghijkmnopqrstuvwxyz";
		$chars_2    = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		srand((double)microtime()*1000000);
		$i          = 0;
		$string     = '';

		while ($i <= ($length - 2))
		{
			$num 		= rand() % 66;
			$tmp 		= substr($chars, $num, 1);
			$string	= $string . $tmp;
			$i++;
		}

		while ($i <= ($length))
		{
			$num 		= rand() % 66;
			$tmp 		= substr($chars_2, $num, 1);
			$string	= $string . $tmp;
			$i++;
		}

		return $string;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| GET EXTENSION
	|--------------------------------------------------------------------------
	*/
	function get_extension($string)
	{
		$last_part		= strtolower(substr($string, -7));
		$ex_last_part	= explode('.', $last_part);
		return array_pop($ex_last_part);
	}   
	
	
	/*
	|--------------------------------------------------------------------------
	| IS INDEXING
	|--------------------------------------------------------------------------
	*/
	function is_indexing($ext)
	{
		$ar_allowed_ext = array
		(
			'.txt',
			'.dat',
			'.xml'
		);
		
		if(in_array($ext, $ar_allowed_ext))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	} 
	
	
	/*
	|--------------------------------------------------------------------------
	| IS IMAGE
	|--------------------------------------------------------------------------
	*/
	function is_image($ext)
	{
		$ar_allowed_ext = array
		(
			'.tif',
			'.bmp',
			'.jpg',
			'.png',
			'.gif',
			'.tiff'
		);
		
		if(in_array($ext, $ar_allowed_ext))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| DAYS BETWEEN TWO DATES
	|--------------------------------------------------------------------------
	*/
	function days_between($start_date, $end_date)
	{
		$ex_start_date		= explode('-', $start_date);
		$start_year			= $ex_start_date[0];
		
		$ex_end_date		= explode('-', $end_date);
		$end_year			= $ex_end_date[0];
		
		$year_diff			= $end_year - $start_year;
		
		// Time between
		$remove_days		= $year_diff * 365;
		
		$days_between		= floor(strtotime($end_date) - strtotime($start_date));
		$days_between		= $days_between / (60 * 60 * 24);
		$days_between		= $days_between - $remove_days;
		
		// Return
		$return				= '';
		
		if($year_diff > 0)
		{
			$return			.= $year_diff.' years ';
		}
		
		$return				.= $days_between.' days';
		
		return $return;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| TIME LEFT
	|--------------------------------------------------------------------------
	*/
	function time_left($end_date, $start_date = FALSE, $in_days = FALSE)
	{
		// Start date
		if($start_date == FALSE)
		{
			$start_date	= date("Y-m-d");
		}
		
		// Do the checks
		if(empty($end_date))
		{
			return 'indefinite';
		}
		elseif($start_date > $end_date)
		{
			return 'expired';
		}
		else
		{
			$diff 			= abs(strtotime($end_date) - strtotime($start_date));
			
			$years 			= floor($diff / (365*60*60*24));
			$months 		= floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days 			= floor(($diff - $years * 365*60*60*24)/ (60*60*24));
			
			// Output string
			$out_string		= '';
			if($years != 0)
			{
				$out_string		.= $years.' yr'.nbs(2);
			}
			$out_string		.= $days.' days';
			
			// Return
			return $out_string;
		}
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| TIME DIFFERENCE
	|--------------------------------------------------------------------------
	*/
	function time_difference($start_date, $end_date, $hours_only = TRUE)
	{
		$diff 			= abs(strtotime($end_date) - strtotime($start_date)); 
		
		if($hours_only == FALSE)
		{
			$years   		= floor($diff / (365*60*60*24)); 
			$months  		= floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
			$days    		= floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			
			$hours   		= floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
			
			$minutes  		= floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
			
			$seconds 		= floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 
		}
		else
		{
			$hours   		= floor(($diff) / (60*60)); 
			
			$minutes  		= floor(($diff - $hours*60*60)/ 60); 
			
			$seconds 		= floor(($diff)); 
		}
		
		// Return
		$ar_return		= array();
		$ret_human		= '';
		
		if($hours_only == FALSE)
		{
			if($years != 0)
			{
				if($years == 1)
				{
					$ret_human	.= $years.' yr ';
				}
				else
				{
					$ret_human	.= $years.' yrs ';
				}
			}
			
			if($months != 0)
			{
				if($months == 1)
				{
					$ret_human	.= $months.' month ';
				}
				else
				{
					$ret_human	.= $months.' months ';
				}
			}
			
			if($days != 0)
			{
				if($days == 1)
				{
					$ret_human	.= $days.' day ';
				}
				else
				{
					$ret_human	.= $days.' days ';
				}
			}
		}
		
		if($hours != 0)
		{
			$ret_human	.= $hours.' hrs ';
		}
		
		if($minutes != 0)
		{
			$ret_human	.= $minutes.' mins ';
		}
		
		$ar_return['human']		= $ret_human;
		$ar_return['seconds']	= $diff;
		
		return $ar_return; 
	}
}