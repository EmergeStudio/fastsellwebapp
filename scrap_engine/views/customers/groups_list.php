<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($groups['error'] == FALSE)
{
	$json_groups                = $groups['result'];

	echo open_div('groupContainer');

		echo anchor('customers', 'All Customers');

	echo close_div();

	foreach($json_groups->fastsell_customer_groups as $group)
	{
		echo open_div('groupContainer');

			echo full_div('', 'btnDeleteGroup icon-cross floatRight', 'Delete this group');
			echo full_div('', 'btnEditGroup icon-wrench floatRight', 'Edit this group');

			echo anchor('customers/by_group/'.$group->id, $group->name);
			echo hidden_div($group->id, 'hdGroupId');

		echo close_div();
	}
}
else
{
	echo 'There are currently no customer groups';
}
?>