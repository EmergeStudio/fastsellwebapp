<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Buying_preferences extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('check');
	}


	/*
	|--------------------------------------------------------------------------
	| MAIN BUYING PREFERENCES PAGE
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARiABLES ------------------------------
		$acc_type                       = $this->session->userdata('sv_acc_type');
		$customer_org_id                = $this->scrap_web->get_customer_org_id();
		$customer_user_id               = $this->scrap_web->get_customer_user_id();


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	        = 'FastSells';
		$dt_header['crt_page']	        = 'pageBuyingPrefs';
		$dt_header['extra_css']         = array('buying_preferences');
		$dt_header['extra_js']          = array('buying_preferences');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		// Orders
		$url_buying_prefs               = 'customerusers/.json?id='.$customer_user_id;
		$call_buying_prefs              = $this->scrap_web->webserv_call($url_buying_prefs, FALSE, 'get', FALSE, FALSE);
		$dt_body['buying_prefs']        = $call_buying_prefs;

		// Load the view
		$this->load->view('customers/buying_preferences', $dt_body);


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| SAVE BUYING PREFERENCES
	|--------------------------------------------------------------------------
	*/
	function save_changes()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(TRUE);

		// Some variables
		$customer_user_id               = $this->scrap_web->get_customer_user_id();
		$categories                     = $this->input->post('hdCategories');

		// Buying preferences
		$url_buying_prefs               = 'customerusers/.json?id='.$customer_user_id;
		$call_buying_prefs              = $this->scrap_web->webserv_call($url_buying_prefs, FALSE, 'get', FALSE, FALSE);

		if($call_buying_prefs['error'] == FALSE)
		{
			// Decode JSON
			$json_buying_prefs          = $call_buying_prefs['result'];

			// Set the categories
			$ex_categories                      = explode('][', $this->scrap_string->remove_flc($categories));
			$ar_categories                      = array();
			foreach($ex_categories as $category)
			{
				array_push($ar_categories, array('id' => $category));
			}
			$json_buying_prefs->fastsell_item_categories                    = $ar_categories;

			// Recode
			$update_json		                = json_encode($json_buying_prefs);

			// Update
			$update_json                        = $this->scrap_web->webserv_call('customerusers/.json', $update_json, 'post');
		}

		// Redirect
		redirect('buying_preferences');
	}
}

/* End of file buying_preferences.php */
/* Location: scrap_engine/controllers/buying_preferences.php */