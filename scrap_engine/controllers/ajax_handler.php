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

class Ajax_handler extends CI_Controller 
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
	| LOG ME IN
	|--------------------------------------------------------------------------
	|
	| Here the user is logged in, else an error mesage is sent back to notify
	| them of the failure.
	|
	*/
	function log_me_in()
	{
		// Check to see if there is any direct access through the url
		if($this->input->post('inp_username') && $this->input->post('inp_password'))
		{			
			// Posted variables
			$pv_username	= $this->input->post('inp_username');
			$pv_password	= $this->input->post('inp_password');
			$pv_encrypt		= $this->input->post('encrypt');
			
			// Login user into ithawt app
			$this->scrap_wall->login_user($pv_username, $pv_password, $pv_encrypt);
		}
		else
		{
			// No direct access is allowed !
			redirect('login');
		}
	}


	/*
	|--------------------------------------------------------------------------
	| LOG ME IN STATIC
	|--------------------------------------------------------------------------
	|
	| Here the user is logged in, else an error mesage is sent back to notify
	| them of the failure.
	|
	*/
	function log_in_static()
	{
		// Check to see if there is any direct access through the url
		if($this->input->post('inp_username') && $this->input->post('inp_password'))
		{
			// Posted variables
			$pv_username	= trim($this->input->post('inp_username'));
			$pv_password	= trim($this->input->post('inp_password'));
			$pv_encrypt		= 'yes';
			//echo $this->input->post('user[username]').' -- '.$this->input->post('user[password]');

			// Login user into ithawt app
			$login_user     = $this->scrap_wall->login_user($pv_username, $pv_password, $pv_encrypt, FALSE);

			if($login_user == 'userloginsuccess')
			{
				// Login success
				redirect('http://www.fastsellfoods.com/app/dashboard');
			}
			else
			{
				// Login failure
				redirect('http://www.fastsellfoods.com/index.php?error=true');
			}
		}
		else
		{
			//echo 'error 3';
			// No direct access is allowed !
			redirect('http://www.fastsellfoods.com/');
		}
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
			// URI segment
			$application	= $this->uri->segment(3);
			$view_file		= $this->uri->segment(4);
			
			// Load the view
			$this->load->view($application.'/ajax/'.$view_file);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| SHOW HOST SIGN UP
	|--------------------------------------------------------------------------
	*/
	function show_host_signup()
	{
		// Some variables
		$acc_name					= $this->input->post('acc_name');
		$address					= $this->input->post('address');
		$first_name					= $this->input->post('first_name');
		$surname					= $this->input->post('surname');
		$username					= $this->input->post('username');
		$password					= $this->input->post('password');
		$email_address				= $this->input->post('email_address');
			
		// Scrappy web call
		$url_sample					= 'showhosts/sample.json';
		$call_sample				= $this->scrap_web->webserv_call($url_sample);
			
 		// Validate
 		if($call_sample['error'] == FALSE)
 		{
 			// Sample
 			$json_sample			= $call_sample['result'];
 			
 			// Change the data
 			$ar_emails														= array();
 			$ar_emails['is_primary']                                     	= TRUE;
 			$ar_emails['email']												= $email_address;
 			
			$json_sample->name												= $acc_name;
			$json_sample->show_host_owner->user->user_emails				= array($ar_emails);
			$json_sample->show_host_owner->user->firstname					= $first_name;
			$json_sample->show_host_owner->user->lastname					= $surname;
			$json_sample->show_host_owner->user->username					= $username;
			$json_sample->show_host_owner->user->password					= sha1($password);
			$json_sample->show_host_owner->user->clear_password			    = $password;
			$json_sample->time_zone->id                                     = 6;
				
 			// Recode
 			$new_json				= json_encode($json_sample);
 			//echo $new_json;
				
 			// Submit the changes
 			$new_show_host			= $this->scrap_web->webserv_call('showhosts/.json', $new_json, 'put');
			//echo 'userloginsuccess';

 			// Validate the result
 			if($new_show_host['error'] == FALSE)
 			{
 				// New show host
				$encrypt				= 'yes';

				// Login user into ithawt app
				$this->scrap_wall->login_user($username, $password, $encrypt);
 			}
 			else
 			{
 				// Return the error message
 				$json					= $new_show_host['result'];
 				echo $json->error_description;
 			}
 		}
 		else
 		{
 			// Return the error message
 			$json_error				= $call_sample['result'];
 			echo $json->error_description;
 		}
	}


    /*
     |--------------------------------------------------------------------------
     | CHANGE MANAGE MODE
     |--------------------------------------------------------------------------
     */
    function change_manage_mode()
    {
        if($this->scrap_wall->login_check_ajax() == TRUE)
        {
            // Some variables
            $manage_mode            = $this->input->post('manage_mode');

            // Set the session
            if($manage_mode == 'true')
            {
                $this->session->set_userdata('sv_manage_mode', TRUE);
            }
            else
            {
                $this->session->set_userdata('sv_manage_mode', FALSE);
            }
        }
        else
        {
            echo 9876;
        }
    }
	
}
/* End of file ajax_handler.php */
/* Location: ./scrap_application/engine/controllers/ajax_handler.php */