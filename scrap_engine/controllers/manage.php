<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('check');
	}


	/*
	|--------------------------------------------------------------------------
	| MAIN INDEX PAGE
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		// ----- REDIRECT --------------------------------
		redirect('users');
	}


	/*
	|--------------------------------------------------------------------------
	| USERS
	|--------------------------------------------------------------------------
	*/
	function users()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);
		
		
		// Some variables
		$acc_type                       = $this->session->userdata('sv_acc_type');
		$user_id				        = $this->session->userdata('sv_user_id');
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	= 'Manage Users';
		$dt_header['crt_page']	= 'pageUserDetails';
		$dt_header['extra_css']	= array('flexigrid', 'users');
		$dt_header['extra_js']	= array('plugin_flexigrid_pack', 'plugin_ehighlight', 'users');
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		// Validate the account type
		if($acc_type == 'show_host')
		{	
	 		// Get show host id
	 		$url_show_host_id		= 'showhostusers/.json?userid='.$user_id; 
	 		$call_show_host_id		= $this->scrap_web->webserv_call($url_show_host_id);
	 		$json_show_host_id		= $call_show_host_id['result'];
	 		$show_host_id			= $json_show_host_id->show_host_organization->id;
		
			// Get the users
			$url_users				= 'showhostusers/.jsons?showhostid='.$show_host_id;
			$json_users				= $this->scrap_web->webserv_call($url_users, FALSE, 'get', FALSE, FALSE);
			$dt_body['users']		= $json_users;

			// Content view
			$this->load->view('manage/show_host_users', $dt_body);
		}
		
		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| DELETE A USER
	|--------------------------------------------------------------------------
	*/
	function user_delete()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(TRUE);

		// Some variables
		$user_id                        = $this->uri->segment(3);

		// Call delete
		$url_delete                     = 'showhostusers/.json?id='.$user_id;
		$call_delete                    = $this->scrap_web->webserv_call($url_delete, FALSE, 'delete', FALSE, FALSE);

		// Validate the result
//		if($call_delete['error'] == FALSE)
//		{
//			echo 'okitsdone';
//		}
//		else
//		{
//			// Return the error message
//			$json				        = $call_delete['result'];
//			echo $json->error_description;
//		}

		// Redirect
		redirect('manage/users');
	}



	/*
	|--------------------------------------------------------------------------
	| ADD A USER
	|--------------------------------------------------------------------------
	*/
	function add_a_user()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(TRUE);

		// Some variables
		$show_host_id                   = $this->scrap_web->get_show_host_id();
		$user_type                      = $this->input->post('drpdwnUserType');
		$first_name                     = $this->input->post('inpName');
		$surname                        = $this->input->post('inpSurname');
		$email                          = $this->input->post('inpEmail');
		$username                       = $this->input->post('inpUsername');
		$password                       = $this->input->post('inpPassword');

		// Get sample user
		$url_sample                     = 'showhostusers/sample.json';
		$call_sample                    = $this->scrap_web->webserv_call($url_sample, FALSE, 'get', FALSE, FALSE, TRUE);

		// Edit the user
		$json_sample                            = $call_sample['result'];
		$ar_emails							    = array();
		$ar_emails['is_primary']                = TRUE;
		$ar_emails['email']					    = $email;

		$json_sample['user']['firstname']               = $first_name;
		$json_sample['user']['lastname']                = $surname;
		$json_sample['user']['user_emails']             = array($ar_emails);
		$json_sample['user']['username']                = $username;
		$json_sample['user']['password']                = sha1($password);
		$json_sample['user']['clear_password']          = $password;
		$json_sample['show_host_role']['id']            = $user_type;
		$json_sample['show_host_organization']['id']    = $show_host_id;

		// Recode
		$new_json				        = json_encode($json_sample);
		//echo $new_json;

		// Create new user
		$new_user				        = $this->scrap_web->webserv_call('showhostusers/.json', $new_json, 'put');

		redirect('manage/users');
//		// Validate the result
//		if($new_user['error'] == FALSE)
//		{
//			echo 'okitsdone';
//		}
//		else
//		{
//			// Return the error message
//			$json				        = $new_user['result'];
//			echo $json->error_description;
//		}
	}


	/*
	|--------------------------------------------------------------------------
	| ORGANIZATION LEVELS
	|--------------------------------------------------------------------------
	*/
	function organization_levels()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// Some variables
		$acc_view				= $this->session->userdata('sv_acc_view');
		$show_host_id           = $this->scrap_web->get_show_host_id();


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	= 'Manage Organization Levels';
		$dt_header['crt_page']	= 'pageManageOrganizationLevels';
		$dt_header['extra_css']	= array('organization_levels', 'users');
		$dt_header['extra_js']	= array('base_organization_levels');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		// Validate the account type
		if($acc_view == 'show_host')
		{
			// Navigation view
			$dt_nav['app_page']	= 'pageManageOrganizationLevels';
			$this->load->view($acc_view.'/navigation', $dt_nav);

			// Get levels
			$url_levels                     = 'customerrepresentativelevels/.jsons?showhostid='.$show_host_id;
			$call_levels                    = $this->scrap_web->webserv_call($url_levels);
			$dt_body['levels']              = $call_levels;

			// Get the customer representatives
			$url_customer_reps          = 'customerrepresentatives/.jsons?showhostid='.$show_host_id;
			$call_customer_reps         = $this->scrap_web->webserv_call($url_customer_reps, FALSE, 'get', FALSE, FALSE);
			$dt_body['customer_reps']   = $call_customer_reps;

			// Load the content view
			$this->load->view('manage/organization_levels', $dt_body);
		}



		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	 |--------------------------------------------------------------------------
	 | CUSTOMER REPRESENTATIVES
	 |--------------------------------------------------------------------------
	 */
    function customer_representatives()
    {
        // ----- APPLICATION PROFILER --------------------------------
        $this->output->enable_profiler(FALSE);


        // Some variables
        $acc_view				        = $this->session->userdata('sv_acc_view');


        // ----- HEADER ------------------------------------
        // Some variables
        $dt_header['title'] 	        = 'Manage Customer Representatives';
        $dt_header['crt_page']	        = 'pageManageCustomerReps';
        $dt_header['extra_css']	        = array('customer_representatives');
        $dt_header['extra_js']	        = array('base_customer_representatives');

        // Load header
        $this->load->view('universal/header', $dt_header);


        // ----- CONTENT ------------------------------------
        // Validate the account type
        if($acc_view == 'show_host')
        {
            // Get show host id
            $show_host_id			    = $this->scrap_web->get_show_host_id();

            // Navigation view
            $dt_nav['app_page']	        = 'pageManageCustomerReps';
            $this->load->view($acc_view.'/navigation', $dt_nav);

            // Get the customer representatives
            $url_customer_reps          = 'customerrepresentatives/.jsons?showhostid='.$show_host_id;
            $call_customer_reps         = $this->scrap_web->webserv_call($url_customer_reps);
            $dt_body['customer_reps']   = $call_customer_reps;

            // Get levels
            $url_levels                     = 'customerrepresentativelevels/.jsons?showhostid='.$show_host_id;
            $call_levels                    = $this->scrap_web->webserv_call($url_levels);
            $dt_body['levels']              = $call_levels;

            // Load the content view
            $this->load->view('manage/customer_representatives', $dt_body);
        }



        // ----- FOOTER ------------------------------------
        $this->load->view('universal/footer');
    }


	/*
	|--------------------------------------------------------------------------
	| CUSTOMER REP UPLOAD VIA MASTER FILE
	|--------------------------------------------------------------------------
	*/
	function upload_customer_rep_master_file()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FAlSE);


		// Some variables
		$acc_view					= $this->session->userdata('sv_acc_view');
		$user_id					= $this->session->userdata('sv_user_id');


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 		= 'Customer Reps Master File Upload';
		$dt_header['crt_page']		= 'pageManageCustomerReps';
		$dt_header['extra_js']		= array('base_customer_reps_upload_shifter');
		$dt_header['extra_css']		= array('customer_representatives', 'scrap_shifter');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		// Validate the account type
		if($acc_view == 'show_host')
		{
			// Navigation view
			$dt_nav['app_page']		= 'pageManageCustomerReps';
			$this->load->view($acc_view.'/navigation', $dt_nav);

			// Content view
			$this->load->view('manage/customer_representative_master_file_upload');
		}


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}
}

/* End of file manage.php */
/* Location: scrap_engine/controllers/manage.php */