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

		// ----- SOME VARIABLES -----------------------------
		$show_host_id                   = $this->scrap_web->get_show_host_id();
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	        = 'My Products';
		$dt_header['crt_page']	        = 'pageProducts';
		$dt_header['extra_css']         = array('products');
		$dt_header['extra_js']          = array('products');
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		// Navigation view
		$dt_nav['app_page']		        = 'pageProducts';
		$this->load->view('universal/navigation', $dt_nav);

		// Get the products
		$offset                         = 0;
		$limit                          = 20;
		$search_text                    = '';

		// Search
		if($this->input->post('inpSearchText'))
		{
			$search_text                = $this->input->post('inpSearchText');
		}

		$dt_body['search_text']         = $search_text;

		// Make the call
		$url_products                   = 'catalogitems/.jsons?showhostid='.$show_host_id.'&searchtext='.$search_text;
		$call_products                  = $this->scrap_web->webserv_call($url_products, FALSE, 'get', FALSE, FALSE);
		$dt_body['products']            = $call_products;

		// Get the definitions
		$url_definitions                = 'catalogitemdefinitions/.jsons?showhostid='.$show_host_id;
		$call_definitions               = $this->scrap_web->webserv_call($url_definitions, FALSE, 'get', FALSE, FALSE);
		$dt_body['definitions']         = $call_definitions;

		// Load the view
		$this->load->view('products/main_products_page', $dt_body);
		
		
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


		// ----- SOME VARIABLES ------------------------------
		$show_host_id                   = $this->scrap_web->get_show_host_id();


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	        = 'Product Definitions';
		$dt_header['crt_page']	        = 'pageProductDefinitions';
		$dt_header['extra_js']          = array('product_definitions');
		$dt_header['extra_css']         = array('products');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		// Navigation view
		$dt_nav['app_page']		        = 'pageProducts';
		$this->load->view('universal/navigation', $dt_nav);

		// Get the definitions
		$url_definitions                = 'catalogitemdefinitions/.jsons?showhostid='.$show_host_id;
		$call_definitions               = $this->scrap_web->webserv_call($url_definitions, FALSE, 'get', FALSE, FALSE);
		$dt_body['definitions']         = $call_definitions;

		// Load view
		$this->load->view('products/main_definitions_page', $dt_body);


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| UPLOAD VIA MASTER FILE
	|--------------------------------------------------------------------------
	*/
	function upload_master_file()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FAlSE);


		// Some variables
		$show_host_id				= $this->scrap_web->get_show_host_id();


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 		= 'Customers';
		$dt_header['crt_page']		= 'pageProducts';
		$dt_header['extra_js']		= array('products_upload_shifter');
		$dt_header['extra_css']		= array('items', 'scrap_shifter');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		// Navigation view
		$dt_nav['app_page']		= 'pageProducts';
		$this->load->view('universal/navigation', $dt_nav);

		// Get the definitions
		$url_definitions                = 'catalogitemdefinitions/.jsons?showhostid='.$show_host_id;
		$call_definitions               = $this->scrap_web->webserv_call($url_definitions, FALSE, 'get', FALSE, FALSE);
		$dt_body['item_defs']           = $call_definitions;

		// Content view
		$this->load->view('products/products_master_file_upload', $dt_body);


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| LINK / CREATE PRODUCTS VIA MASTER DATA FILE
	|--------------------------------------------------------------------------
	*/
	function products_master_data_upload()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(TRUE);

		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id               = $this->scrap_web->get_show_host_id();
			$fastsell_id                = $this->session->userdata('sv_show_set');
			$product_definition         = $this->input->post('dropItemDefinitions');

			if(isset($_FILES['uploadedFile']) && !empty($_FILES['uploadedFile']))
			{
				$document_file			= str_replace(' ', '%20', $_FILES['uploadedFile']);
			}
			else
			{
				$document_file			= FALSE;
			}

			// Convert the file
			$url_file_convert           = 'masterdata/multipartmasterdata.xls';
			$call_file_convert          = $this->scrap_web->webserv_call($url_file_convert, array('uploadedFile'	=> '@'.$document_file['tmp_name']), 'post', 'multipart_form');
			$json_file_convert          = $call_file_convert['result'];
			$encode_file_convert        = json_encode($json_file_convert);
			//echo $encode_file_convert.'<br>';

			// Link / Create products
			$url_link                   = 'fastsellitems/masterdata.json?showhostid='.$show_host_id.'&fastselleventid='.$fastsell_id.'&fastsellitemdefid='.$product_definition.'&validateinnercontentonly=false';
			$call_link                  = $this->scrap_web->webserv_call($url_link, $encode_file_convert, 'put', FALSE, FALSE);

			// Validate the result
			if($call_link['error'] == FALSE)
			{
				echo 'wassuccessfullycreated';
			}
			else
			{
				// Return the error message
				$json				        = $call_link['result'];
				echo $json->error_description;
			}

			// Redirect
			redirect('fastsells/products');
		}
		else
		{
			echo 9876;
		}
	}
}

/* End of file products.php */
/* Location: scrap_engine/controllers/products.php */