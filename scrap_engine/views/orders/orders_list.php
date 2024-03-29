<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($orders['error'] == FALSE)
{
	// Data
	$json_orders                    = $orders['result'];

	// Heading
	$this->table->set_heading('Date Created', 'FastSell Event', array('data' => 'Order Number', 'class' => 'fullCell'), 'Total', '');

	// Rows
	foreach($json_orders->fastsell_orders as $order)
	{
		// Some variables
		$grand_total                    = 0;
		$fastsell_name                  = 'FastSell Name';

		// Current order
		$url_crt_order                  = 'fastsellorders/.json?id='.$order->id;
		$call_crt_order                 = $this->scrap_web->webserv_call($url_crt_order, FALSE, 'get', FALSE, FALSE);

		if($call_crt_order['error'] == FALSE)
		{
			$json_crt_order             = $call_crt_order['result'];
			foreach($json_crt_order->fastsell_order_to_items as $order_item)
			{
				// Price
				$quantity               = $order_item->quantity;
				$price                  = $order_item->fastsell_item->price;
				$crt_total              = number_format($quantity * $price, 2);
				$grand_total            = $grand_total + ($quantity * $price);
			}
			$grand_total                = number_format($grand_total, 2);
			$fastsell_name              = $json_crt_order->fastsell_event->name;
		}

		// Date
		$date_created                   = $this->scrap_string->make_date($order->date_created);

		if($json_crt_order->order_state->id == 1)
		{
			$state                      = full_div('Open', 'orderState blueBlock');
		}
		elseif($json_crt_order->order_state->id == 2)
		{
			$state                      = full_div('Checked Out', 'orderState yellowBlock');
		}

		// Add the row
		$this->table->add_row($date_created, $fastsell_name, array('data' => div_height(5).anchor('my_orders/view/'.$order->id, $order->order_number).div_height(5), 'class' => 'fullCell'), array('data' => '$'.$grand_total, 'class' => 'greenTxt'), $state);
	}

	// Generate table
	echo $this->table->generate();
}
else
{
	echo full_div('No Orders', 'messageNoOrders1');
}
?>