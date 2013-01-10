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

class Ajax_handler_fastsells extends CI_Controller
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
			// Some variables
			$fastsell_name                  = $this->input->post('fastsell_name');
			$fastsell_description           = $this->input->post('fastsell_description');
			$start_date                     = $this->input->post('start_date');
			$start_time                     = $this->input->post('start_time');
			$end_date                       = $this->input->post('end_date');
			$end_time                       = $this->input->post('end_time');
			$event_id                       = $this->input->post('event_id');
			$event_banner                   = $this->input->post('event_banner');
			$fastsell_name                  = $this->input->post('fastsell_name');
		}
		else
		{
			echo 9876;
		}
	}
	
}
/* End of file ajax_handler_fastsells.php */
/* Location: ./scrap_application/engine/controllers/ajax_handler_fastsells.php */