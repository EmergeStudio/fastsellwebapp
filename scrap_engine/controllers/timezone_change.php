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
	| FUNCTION NAME
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(TRUE);

		// Some variables
		$user_id			= $this->session->userdata('sv_user_id');
		$timezone			= $this->input->post('drpdwnTimezone');
		$return_url			= $this->input->post('hdReturnTimeUrl');

		// Scrappy web call
		$url				= 'time/.json?id='.$user_id;
		$call_user			= $this->scrap_web->webserv_call($url, FALSE, 'get', FALSE, TRUE, TRUE);
	}
}

/* End of file timezone_change.php */
/* Location: scrap_engine/controllers/timezone_change.php */