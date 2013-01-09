<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('check');
	}


	/*
	|--------------------------------------------------------------------------
	| MAIN PRODUCTS PAGE
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	        = 'My Products';
		$dt_header['crt_page']	        = 'pageProducts';
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		// Navigation view
		$dt_nav['app_page']		        = 'pageProducts';
		$this->load->view('universal/navigation', $dt_nav);
		
		
		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| DEFINITIONS PAGE
	|--------------------------------------------------------------------------
	*/
	function definitions()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	        = 'Product Definitions';
		$dt_header['crt_page']	        = 'pageProducts';
		$dt_header['extra_js']          = array('product_definitions');
		$dt_header['extra_css']         = array('products');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		// Navigation view
		$dt_nav['app_page']		        = 'pageProducts';
		$this->load->view('universal/navigation', $dt_nav);

		// Load view
		$this->load->view('products/main_definitions_page');


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}
}

/* End of file products.php */
/* Location: scrap_engine/controllers/products.php */