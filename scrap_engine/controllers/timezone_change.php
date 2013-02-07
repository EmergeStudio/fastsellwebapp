<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Timezone_change extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('check');
	}


	/*
	|--------------------------------------------------------------------------
	| CHANGE TIMEZONE
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(TRUE);

		// Some variables
		$acc_type                   = $this->session->userdata('sv_acc_type');
		$user_id			        = $this->session->userdata('sv_user_id');
		$timezone			        = $this->input->post('drpdwnTimezone');
		$return_url			        = $this->input->post('hdReturnTimeUrl');

		// Scrappy web call
		if($acc_type == 'show_host')
		{
			$show_host_id           = $this->scrap_web->get_show_host_id();
			$url	                = 'showhosts/.json?id='.$show_host_id;
			$call		            = $this->scrap_web->webserv_call($url, FALSE, 'get', FALSE, FALSE);

			if($call['error'] == FALSE)
			{
				$json                   = $call['result'];
				$json->time_zone->id    = $timezone;
				$update_json            = json_encode($json);

				$call_update            = $this->scrap_web->webserv_call('showhosts/.json', $update_json, 'post');
			}
		}
		else
		{
			$customer_ord_id            = $this->scrap_web->get_customer_org_id();
			$customer_user_id           = $this->scrap_web->get_customer_user_id();

			// Get all the customer
			$url_customer               = 'customers/.json?id='.$customer_ord_id;
			$call_customers             = $this->scrap_web->webserv_call($url_customer, FALSE, 'get', FALSE, FALSE);

			if($call_customers['error'] == FALSE)
			{
				$json                   = $call_customers['result'];
				$json->time_zone->id    = $timezone;
				$update_json            = json_encode($json);

				$call_update            = $this->scrap_web->webserv_call('customers/.json', $update_json, 'post');
			}
		}

		redirect($return_url);
	}
}

/* End of file timezone_change.php */
/* Location: scrap_engine/controllers/timezone_change.php */