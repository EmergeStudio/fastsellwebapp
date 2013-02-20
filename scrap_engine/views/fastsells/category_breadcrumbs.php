<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Validate
if($category['error'] == FALSE)
{
	// Data
	$json_categories        = $category['result'];
	$list_category          = $json_categories->fastsell_item_categories[0];
	$ar_categories          = array();
	$main_id                = FALSE;
	$count                  = 0;

	while($count <= 100)
	{
		$count++;

		$ar_categories[$list_category->id]            = $list_category->category;

		if($list_category->parent != null)
		{
			$list_category      = $list_category->parent;
		}
		else
		{
			break;
		}
	}

	ksort($ar_categories);

	// Remove
	echo full_div('', 'btnDeleteCategory icon-cross', 'Remove this category');

	// Display breadcrumb
	$loop_cnt               = 0;
	foreach($ar_categories as $key => $value)
	{
		$loop_cnt++;
		$class              = '';

		if($loop_cnt == 1)
		{
			$class          .= ' first';
		}
		if($loop_cnt == $count)
		{
			$class          .= ' last';
		}

		echo open_div('breadCrumb'.$class);

			echo $value;
			echo hidden_div($key, 'hdCategoryId');

		echo close_div();
	}

	// Clear float
	echo clear_float();
}
else
{
	$json_error             = $category['result'];
	echo $json_error->error_description;
}
?>