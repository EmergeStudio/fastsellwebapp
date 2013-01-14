<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Scrap_wall
{
	/*
	|--------------------------------------------------------------------------
	| CODEIGNITER REQUIREMENTS
	|--------------------------------------------------------------------------
	*/
	var $CI = null;
	
	function Scrap_wall()
	{
		$this->CI =& get_instance();
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| LOGIN USER
	|--------------------------------------------------------------------------
	*/
	function login_user($pv_username, $pv_password, $pvEncrypt)
	{	
		if(isset($pv_username) && isset($pv_password))
		{
			// ----- APPLICATION PROFILER --------------------------------
			$this->CI->output->enable_profiler(FALSE);
					
			// Some variables
			if($pvEncrypt == 'yes')
			{
				$password 		= sha1($pv_password);
			}
			else
			{
				$password 		= $pv_password;
			}
			
			// Authenticate user
			$url				= 'auth/.json?username='.$pv_username.'&password='.$password;
			$auth_user			= $this->CI->scrap_web->webserv_call($url);
			if($auth_user['error'] == FALSE)
			{
 				$json				= $auth_user['result'];
				
				$user_id			= trim($json->id);
				$full_name			= trim($json->firstname).' '.trim($json->lastname);
				$user_date			= trim($json->create_date);
				$java_id			= trim($json->session_id);
				$username			= trim($json->username);
				$role				= trim($json->role_summary);
				$exp_role			= explode('[', $this->CI->scrap_string->remove_lc($role));
				$acc_type			= $exp_role[0];
				$role_type			= $exp_role[1];
				if($acc_type == 'ShowHost')
				{
					$acc_type		= 'show_host';
				}
				elseif($acc_type == 'Customer')
				{
					$acc_type       = 'customer';
				}
				else
				{
					$acc_type		= 'user';
				}
				if($role_type == 'Owner')
				{
					$admin			= TRUE;
				}
				else
				{
					$role_type		= FALSE;
				}
				if($json->is_sysadmin == 1)
				{
					$sys_admin		= 'TRUE';
				}
				else
				{
					$sys_admin		= 'FALSE';
				}
					
				// Set name
				$this->CI->session->set_userdata('sv_user_id', $user_id);
				$this->CI->session->set_userdata('sv_name', $full_name);
				$this->CI->session->set_userdata('sv_user_date', $user_date);
				$this->CI->session->set_userdata('sv_java_id', $java_id);
				$this->CI->session->set_userdata('sv_logged_in', 'loggedInTrueTradeShow');
				$this->CI->session->set_userdata('sv_username', $username);
				$this->CI->session->set_userdata('sv_acc_type', $acc_type);
				$this->CI->session->set_userdata('sv_admin', $admin);	
				$this->CI->session->set_userdata('sv_sys_admin', $sys_admin);
                $this->CI->session->set_userdata('sv_manage_mode', FALSE);
				$this->CI->session->set_userdata('sv_show_set', FALSE);
					
				// Successful login
				echo 'userloginsuccess';
			}
			else
			{
				// Failed condition
				echo 'error1';
			}
		}
		else
		{
			// Direct access of function is not allowed
			redirect('login');
		}
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| LOGIN CHECK
	|--------------------------------------------------------------------------
	*/
	function login_check($redirect)
	{
		// Manual redirect
		if($redirect == 'redirect')
		{
			// Redirect back to login page
			redirect('login');
		}
		elseif($redirect == 'check')
		{
			// Get variable
			$sv_logged_in 	= $this->CI->session->userdata('sv_logged_in');
			
			// Validate user is logged in
			$url				= 'auth/sessionvalid.json';
			$check_session		= $this->CI->scrap_web->webserv_call($url);
			$json_check			= $check_session['result'];
			
			// Check that the user is in
			if(($sv_logged_in != 'loggedInTrueTradeShow') || ($json_check->message == 'false'))
			{
				// Redirect back to login page
				redirect('login');
			}
		}
		elseif($redirect == 'open')
		{
			return 'nowdonothing';
		}
		else
		{
			// Redirect back to login page
			redirect('login');
		}
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| LOGIN CHECK AJAX
	|--------------------------------------------------------------------------
	*/
	function login_check_ajax()
	{
		// Some loads
		$this->CI->scrap_string;
		
		// Validate user is logged in
		$sv_logged_in		= $this->CI->session->userdata('sv_logged_in');
			
		// Validate user is logged in
		$url				= 'auth/sessionvalid.json';
		$check_session		= $this->CI->scrap_web->webserv_call($url);
		$json_check			= $check_session['result'];
		
		// Check that the user is in
		if(($sv_logged_in == 'loggedInTrueTradeShow') && ($json_check->message == 'true'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}