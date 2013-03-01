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
			$user_id                    = $this->session->userdata('sv_user_id');
			$download_file              = FALSE;
			$name                       = 'report_orders_summary_'.date('Ymd_His').'.csv';

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
					$url_download               = 'serverlocalfiles/.jsons?path=scrap_downloads%2F'.$user_id;
					$call_download              = $this->scrap_web->webserv_call($url_download, FALSE, 'get', FALSE, FALSE);

					if($call_download['error'] == FALSE)
					{
						$json_download          = $call_download['result'];

						if($json_download->is_empty == TRUE)
						{
							// Create the folder
							$url_sample_path                = 'serverlocalfiles/sample.json';
							$call_sample_path               = $this->scrap_web->webserv_call($url_sample_path);
							$json_sample_path               = $call_sample_path['result'];

							// Edit DOM
							$json_sample_path->path         = 'scrap_downloads/'.$user_id;
							$json_new_folder                = json_encode($json_sample_path);

							// Create directory
							$new_directory                  = $this->scrap_web->webserv_call('serverlocalfiles/folder.json', $json_new_folder, 'put');
						}
						else
						{
							foreach($json_download->server_local_files as $file_info)
							{
								$url_delete                 = 'serverlocalfiles/.json?path=scrap_downloads%2F'.$user_id.'%2F'.str_replace(' ', '%20', $file_info->name);
								$call_delete                = $this->scrap_web->webserv_call($url_delete, FALSE, 'delete');
							}
						}
					}

					// Store the file
					$url_file_store                 = 'serverlocalfiles/.json?path=scrap_downloads/'.$user_id.'/'.$name;
					$call_file_store                = $this->scrap_web->webserv_call($url_file_store, $call_create_report['result'], 'put', FALSE, FALSE);

					// Change download
					$download_file                  = $name;
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
			$user_id                    = $this->session->userdata('sv_user_id');
			$download_file              = FALSE;
			$name                       = 'report_orders_by_fastsell_'.date('Ymd_His').'.csv';

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
					$url_download               = 'serverlocalfiles/.jsons?path=scrap_downloads%2F'.$user_id;
					$call_download              = $this->scrap_web->webserv_call($url_download, FALSE, 'get', FALSE, FALSE);

					if($call_download['error'] == FALSE)
					{
						$json_download          = $call_download['result'];

						if($json_download->is_empty == TRUE)
						{
							// Create the folder
							$url_sample_path                = 'serverlocalfiles/sample.json';
							$call_sample_path               = $this->scrap_web->webserv_call($url_sample_path);
							$json_sample_path               = $call_sample_path['result'];

							// Edit DOM
							$json_sample_path->path         = 'scrap_downloads/'.$user_id;
							$json_new_folder                = json_encode($json_sample_path);

							// Create directory
							$new_directory                  = $this->scrap_web->webserv_call('serverlocalfiles/folder.json', $json_new_folder, 'put');
						}
						else
						{
							foreach($json_download->server_local_files as $file_info)
							{
								$url_delete                 = 'serverlocalfiles/.json?path=scrap_downloads%2F'.$user_id.'%2F'.str_replace(' ', '%20', $file_info->name);
								$call_delete                = $this->scrap_web->webserv_call($url_delete, FALSE, 'delete');
							}
						}
					}

					// Store the file
					$url_file_store                 = 'serverlocalfiles/.json?path=scrap_downloads/'.$user_id.'/'.$name;
					$call_file_store                = $this->scrap_web->webserv_call($url_file_store, $call_create_report['result'], 'put', FALSE, FALSE);

					// Change download
					$download_file                  = $name;
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
			$user_id                    = $this->session->userdata('sv_user_id');
			$download_file              = FALSE;
			$name                       = 'report_orders_by_date_'.date('Ymd_His').'.csv';

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
					$url_download               = 'serverlocalfiles/.jsons?path=scrap_downloads%2F'.$user_id;
					$call_download              = $this->scrap_web->webserv_call($url_download, FALSE, 'get', FALSE, FALSE);

					if($call_download['error'] == FALSE)
					{
						$json_download          = $call_download['result'];

						if($json_download->is_empty == TRUE)
						{
							// Create the folder
							$url_sample_path                = 'serverlocalfiles/sample.json';
							$call_sample_path               = $this->scrap_web->webserv_call($url_sample_path);
							$json_sample_path               = $call_sample_path['result'];

							// Edit DOM
							$json_sample_path->path         = 'scrap_downloads/'.$user_id;
							$json_new_folder                = json_encode($json_sample_path);

							// Create directory
							$new_directory                  = $this->scrap_web->webserv_call('serverlocalfiles/folder.json', $json_new_folder, 'put');
						}
						else
						{
							foreach($json_download->server_local_files as $file_info)
							{
								$url_delete                 = 'serverlocalfiles/.json?path=scrap_downloads%2F'.$user_id.'%2F'.str_replace(' ', '%20', $file_info->name);
								$call_delete                = $this->scrap_web->webserv_call($url_delete, FALSE, 'delete');
							}
						}
					}

					// Store the file
					$url_file_store                 = 'serverlocalfiles/.json?path=scrap_downloads/'.$user_id.'/'.$name;
					$call_file_store                = $this->scrap_web->webserv_call($url_file_store, $call_create_report['result'], 'put', FALSE, FALSE);

					// Change download
					$download_file                  = $name;
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
			$user_id                    = $this->session->userdata('sv_user_id');
			$fastsell_id                = $this->input->post('fastsell_id');
			$download_file              = FALSE;
			$name                       = 'report_customer_orders_by_fastsell_'.date('Ymd_His').'.csv';

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
					$url_download               = 'serverlocalfiles/.jsons?path=scrap_downloads%2F'.$user_id;
					$call_download              = $this->scrap_web->webserv_call($url_download, FALSE, 'get', FALSE, FALSE);

					if($call_download['error'] == FALSE)
					{
						$json_download          = $call_download['result'];

						if($json_download->is_empty == TRUE)
						{
							// Create the folder
							$url_sample_path                = 'serverlocalfiles/sample.json';
							$call_sample_path               = $this->scrap_web->webserv_call($url_sample_path);
							$json_sample_path               = $call_sample_path['result'];

							// Edit DOM
							$json_sample_path->path         = 'scrap_downloads/'.$user_id;
							$json_new_folder                = json_encode($json_sample_path);

							// Create directory
							$new_directory                  = $this->scrap_web->webserv_call('serverlocalfiles/folder.json', $json_new_folder, 'put');
						}
						else
						{
							foreach($json_download->server_local_files as $file_info)
							{
								$url_delete                 = 'serverlocalfiles/.json?path=scrap_downloads%2F'.$user_id.'%2F'.str_replace(' ', '%20', $file_info->name);
								$call_delete                = $this->scrap_web->webserv_call($url_delete, FALSE, 'delete');
							}
						}
					}

					// Store the file
					$url_file_store                 = 'serverlocalfiles/.json?path=scrap_downloads/'.$user_id.'/'.$name;
					$call_file_store                = $this->scrap_web->webserv_call($url_file_store, $call_create_report['result'], 'put', FALSE, FALSE);

					// Change download
					$download_file                  = $name;
				}
			}

			$dt_body['download_file']               = $download_file;

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