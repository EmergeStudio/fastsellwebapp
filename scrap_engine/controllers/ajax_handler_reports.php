<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * AJAX Handler Controller
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

class Ajax_handler_reports extends CI_Controller
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
	| GET ORDERS SUMMARY REPORT
	|--------------------------------------------------------------------------
	*/
	function get_orders_summary_report()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$fastsell_id                = $this->input->post('fastsell_id');
			$show_host_id               = $this->scrap_web->get_show_host_id();
			$download_file              = FALSE;

			// Get the report
			$url_report                 = 'reports/fastsellorders/ordersummariesbyevent/.json?fastselleventid='.$fastsell_id;
			$call_report                = $this->scrap_web->webserv_call($url_report, FALSE, 'get', FALSE, FALSE);
			$dt_body['report']          = $call_report;

			// Create the report download file
			if($call_report['error'] == FALSE)
			{
				$json_report            = json_encode($call_report['result']);
				$url_create_report      = 'masterdata/masterdata.json?toformat=csv';
				$call_create_report     = $this->scrap_web->webserv_call($url_create_report, $json_report, 'post', FALSE, FALSE, FALSE, TRUE);

				if($call_create_report['error'] == FALSE)
				{
					// Load the zipping library
					$this->load->library('zip');

					// Some variables
					$user_dir                   = $this->scrap_web->get_user_dir(FALSE, 'local');
					$name                       = 'report_orders_summary_'.date('Ymd_His');

					// Delete old zips
					delete_files($user_dir.'/download/');

					// Write file
					$file_name                  = $name.'.csv';
					$this->zip->add_data($file_name, $call_create_report['result']);
					$this->zip->archive($user_dir.'/download/'.$name.'.zip');

					// Change download
					$download_file              = $name.'.zip';
				}
			}

			$dt_body['download_file']           = $download_file;

			// Load the view
			$this->load->view('reports/basic_table', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| GET ORDERS BY EVENT REPORT
	|--------------------------------------------------------------------------
	*/
	function get_orders_by_event_report()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$fastsell_id                = $this->input->post('fastsell_id');
			$show_host_id               = $this->scrap_web->get_show_host_id();
			$download_file              = FALSE;

			// Get the report
			$url_report                 = 'reports/fastsellorders/ordersbyshowhostandevent/.json?showhostid='.$show_host_id.'&fastselleventid='.$fastsell_id;
			$call_report                = $this->scrap_web->webserv_call($url_report, FALSE, 'get', FALSE, FALSE);
			$dt_body['report']          = $call_report;

			// Create the report download file
			if($call_report['error'] == FALSE)
			{
				$json_report            = json_encode($call_report['result']);
				$url_create_report      = 'masterdata/masterdata.json?toformat=csv';
				$call_create_report     = $this->scrap_web->webserv_call($url_create_report, $json_report, 'post', FALSE, FALSE, FALSE, TRUE);

				if($call_create_report['error'] == FALSE)
				{
					// Load the zipping library
					$this->load->library('zip');

					// Some variables
					$user_dir                   = $this->scrap_web->get_user_dir(FALSE, 'local');
					$name                       = 'report_orders_by_fastsell_'.date('Ymd_His');

					// Delete old zips
					delete_files($user_dir.'/download/');

					// Write file
					$file_name                  = $name.'.csv';
					$this->zip->add_data($file_name, $call_create_report['result']);
					$this->zip->archive($user_dir.'/download/'.$name.'.zip');

					// Change download
					$download_file              = $name.'.zip';
				}
			}

			$dt_body['download_file']           = $download_file;

			// Load the view
			$this->load->view('reports/basic_table', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| GET ORDERS BY DATE REPORT
	|--------------------------------------------------------------------------
	*/
	function get_orders_by_date_report()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id               = $this->scrap_web->get_show_host_id();
			$from_date                  = $this->input->post('from_date');
			$to_date                    = $this->input->post('to_date');
			$download_file              = FALSE;

			// Get the report
			$url_report                 = 'reports/fastsellorders/ordersbyshowhostanddate/.json?showhostid='.$show_host_id.'&fromdate='.$from_date.'%20000001&todate='.$to_date.'%20235959';
			$call_report                = $this->scrap_web->webserv_call($url_report, FALSE, 'get', FALSE, FALSE);
			$dt_body['report']          = $call_report;

			// Create the report download file
			if($call_report['error'] == FALSE)
			{
				$json_report            = json_encode($call_report['result']);
				$url_create_report      = 'masterdata/masterdata.json?toformat=csv';
				$call_create_report     = $this->scrap_web->webserv_call($url_create_report, $json_report, 'post', FALSE, FALSE, FALSE, TRUE);

				if($call_create_report['error'] == FALSE)
				{
					// Load the zipping library
					$this->load->library('zip');

					// Some variables
					$user_dir                   = $this->scrap_web->get_user_dir(FALSE, 'local');
					$name                       = 'report_orders_by_date_'.date('Ymd_His');

					// Delete old zips
					delete_files($user_dir.'/download/');

					// Write file
					$file_name                  = $name.'.csv';
					$this->zip->add_data($file_name, $call_create_report['result']);
					$this->zip->archive($user_dir.'/download/'.$name.'.zip');

					// Change download
					$download_file              = $name.'.zip';
				}
			}

			$dt_body['download_file']           = $download_file;

			// Load the view
			$this->load->view('reports/basic_table', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| GET ORDERS BY CUSTOMER REPORT
	|--------------------------------------------------------------------------
	*/
	function get_orders_customer_report()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$customer_user_id           = $this->scrap_web->get_customer_user_id();
			$fastsell_id                = $this->input->post('fastsell_id');
			$download_file              = FALSE;

			// Get the report
			$url_report                 = 'reports/fastsellorders/ordersbycustomerandevent/.json?customeruserid='.$customer_user_id.'&fastselleventid='.$fastsell_id;
			$call_report                = $this->scrap_web->webserv_call($url_report, FALSE, 'get', FALSE, FALSE);
			$dt_body['report']          = $call_report;

			// Create the report download file
			if($call_report['error'] == FALSE)
			{
				$json_report            = json_encode($call_report['result']);
				$url_create_report      = 'masterdata/masterdata.json?toformat=csv';
				$call_create_report     = $this->scrap_web->webserv_call($url_create_report, $json_report, 'post', FALSE, FALSE, FALSE, TRUE);

				if($call_create_report['error'] == FALSE)
				{
					// Load the zipping library
					$this->load->library('zip');

					// Some variables
					$user_dir                   = $this->scrap_web->get_user_dir(FALSE, 'local');
					$name                       = 'report_customer_orders_by_fastsell_'.date('Ymd_His');

					// Delete old zips
					delete_files($user_dir.'/download/');

					// Write file
					$file_name                  = $name.'.csv';
					$this->zip->add_data($file_name, $call_create_report['result']);
					$this->zip->archive($user_dir.'/download/'.$name.'.zip');

					// Change download
					$download_file              = $name.'.zip';
				}
			}

			$dt_body['download_file']           = $download_file;

			// Load the view
			$this->load->view('reports/basic_table', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}
	
}
/* End of file ajax_handler_reports.php */
/* Location: ./scrap_application/engine/controllers/ajax_handler_reports.php */