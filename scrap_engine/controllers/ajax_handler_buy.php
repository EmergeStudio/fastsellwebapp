<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * AJAX Handler Customers Controller
 *
 * This controller handles all AJAX requests.
 *
 * It also has a special means to login checks to prevent
 * situations where the session returns false and shows a
 * huge error in the message box.
 * 
 * @author	Chris Humboldt (http://www.chrismodem.com)
 * @link	http://www.chrismodem.com/
 */

class Ajax_handler_buy extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}


	/*
	|--------------------------------------------------------------------------
	| INDEX
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		redirect('login');
	}


	/*
	|--------------------------------------------------------------------------
	| HTML VIEW
	|--------------------------------------------------------------------------
	*/
	function html_view()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// URI segment
			$application	= $this->uri->segment(3);
			$view_file		= $this->uri->segment(4);
			
			// Load the view
			$this->load->view($application.'/ajax/'.$view_file);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| BUY PRODUCT POPUP
	|--------------------------------------------------------------------------
	*/
	function buy_product_popup()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Load the view
			$this->load->view('products/ajax/buy_product_popup');
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| BUY PRODUCT CONTENT
	|--------------------------------------------------------------------------
	*/
	function buy_product_content()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$product_id                     = $this->input->post('product_id');
			$customer_id                    = $this->scrap_web->get_customer_org_id();

			// Get the product
			$url_product                    = 'fastsellitems/.json?id='.$product_id;
			$call_product                   = $this->scrap_web->webserv_call($url_product, FALSE, 'get', FALSE, FALSE);
			$dt_body['product']             = $call_product;

			// Get current address
			$url_address                    = 'addresses/.jsons?customerid='.$customer_id;
			$call_address                   = $this->scrap_web->webserv_call($url_address, FALSE, 'get', FALSE, FALSE);
			$dt_body['address']             = $call_address;

			// Load the view
			if($call_address['error'] == FALSE)
			{
				$this->load->view('products/ajax/buy_product_content', $dt_body);
			}
			else
			{
				echo 'noaddress';
			}
		}
		else
		{
			echo 9876;
		}
	}
	
}
/* End of file ajax_handler_buy.php */
/* Location: ./scrap_application/engine/controllers/ajax_handler_buy.php */