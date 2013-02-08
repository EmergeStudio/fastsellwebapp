<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($groups['error'] == FALSE)
{
	echo open_div('inset');

		// Get the groups result
		$json_groups			= $groups['result'];

		// Loop through and display group name
		foreach($json_groups->fastsell_customer_groups as $group)
		{
			echo open_div('groupSelection');

				echo full_div('', 'icon-users-2 icon');
				echo $group->name;
				echo hidden_div($group->id, 'hdGroupId');

			echo close_div();
		}

	echo close_div();
}
?>