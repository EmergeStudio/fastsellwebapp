<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Hidden group ids
$ar_groups                  = '';
if($groups['error'] == FALSE)
{
	$json_groups            = $groups['result'];
	foreach($json_groups->fastsell_customer_groups as $group_item)
	{
		$ar_groups          .= '['.$group_item->id.'::'.$group_item->name.']';
	}
}
echo $this->scrap_string->remove_flc($ar_groups);
?>