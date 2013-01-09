<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data
$loop_cnt       = 1;

// Table
// Heading
$this->table->set_heading('Due Date', array('data' => 'Order Number', 'class' => 'fullCell'), 'FastSell Event', 'For Customer', 'Total Value');

// Rows
while($loop_cnt <= 5)
{
	$this->table->add_row('Today', array('data' => anchor('orders/view/'.$loop_cnt, 'Order FSON-123456'.$loop_cnt).full_div('34 Products In Total', 'greyTxt'), 'class' => 'fullCell'), 'FastSell Name', 'Customer Name', array('data' => '$153.00', 'class' => 'greenTxt'));
	$loop_cnt++;
}

// Generate table
echo $this->table->generate();
?>