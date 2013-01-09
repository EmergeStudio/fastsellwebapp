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

class Ajax_handler_products extends CI_Controller
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
	| ADD DEFINITION POPUP
	|--------------------------------------------------------------------------
	*/
	function add_definition_popup()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Get the view
			$this->load->view('products/ajax/add_definition_popup');
		}
		else
		{
			echo 9876;
		}
	}
}

/* End of file ajax_handler_products.php */
/* Location: scrap_engine/controllers/ajax_handler_products.php */