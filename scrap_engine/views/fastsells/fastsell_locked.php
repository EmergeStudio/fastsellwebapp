<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
echo open_div('middle');

	// Check that we have fastsells
	if($fastsells['error'] == FALSE)
	{
		// Get the result
		$json_fastsells			    = $fastsells['result'];

		foreach($json_fastsells->fastsell_events as $fastsell_event)
		{
//			if()
		}
	}

echo close_div();
?>