<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
								
<?php
// Get the twitter feed
$username			= "EmergeStudio";
$limit				= 3;
$feed					= 'http://twitter.com/statuses/user_timeline.rss?screen_name='.$username.'&count='.$limit;
$feed					= file_get_contents($feed);
$feed					= stripslashes($feed);
$xml					= new SimpleXMLElement($feed);
$loop_cnt			= 0;
$odd_row				= TRUE;

// Display the feed
// Container
echo open_div('feedContainer');

	foreach($xml->channel->item as $tweet)
	{
		// Some variables
		// Loop_cnt
		$loop_cnt++;
		
		// Class
		if($odd_row == TRUE)
		{
			$class			= ' oddRow';
			$odd_row 		= FALSE;
		}
		else
		{
			$class			= ' evenRow';
			$odd_row 		= TRUE;
		}
		
		// Description
		$raw_desc			= $tweet->description;
		$first_divider		= strpos($raw_desc, ':') + 1;
		$clean_desc			= substr($raw_desc, $first_divider);
		$desc					= auto_link(trim($clean_desc));
		$desc					= trim(str_replace('[Offline]', '', $desc));
		
		// Date
		$raw_date			= $tweet->pubDate;
		$date					= $this->scrap_string->make_date($raw_date);
		
		// HTML
		echo open_div('divRow userCommentsFeed'.$class);
						
			// Tweet description
			echo full_div($desc, 'commentText');
			
			// Tweet date
			echo div_height(5);
			echo full_div($date, 'greyTxt txt11');
			
			// Clear float
			echo clear_float();
		
		echo close_div();
	}
	
// End of container
echo close_div();
?>