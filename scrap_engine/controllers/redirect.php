<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Redirect extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
	}


	/*
	|--------------------------------------------------------------------------
	| NO DIRECT ACCESS ALLOWED
	|--------------------------------------------------------------------------
	*/
	function index()
	{		
		// Redirect
		redirect('login');
	}


	/*
	|--------------------------------------------------------------------------
	| CHANGE THE CURRENTLY SELECTED COMPANY
	|--------------------------------------------------------------------------
	*/
	function company()
	{
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('check');
		
		// Some variables
		$user_id			= $this->session->userdata('sv_user_id');
		$new_comp_id		= $this->uri->segment(3);
		$crt_comp_id		= $this->session->userdata('sv_comp_id');
		$landing_page		= $this->session->userdata('sv_landing_page');
		$comp_check			= FALSE;
		$user_role			= 3;
		
		// Check that it isn't the current company
		if($crt_comp_id != $new_comp_id)
		{
			// Get the current user details
			$url			= 'users/xml?id='.$user_id;
			$user_details	= $this->scrap_web->webserv_call($url);
			
			if($user_details['error'] == FALSE)
			{
				// User xml
				$user_xml	= new SimpleXMLElement($user_details['result']);
				
				// Check that he has access to the selected company
				foreach($user_xml->user_company_permission_roles->user_company_permission_role as $comp_perm)
				{
					if(trim($comp_perm->company['id']) == $new_comp_id)
					{
						// Set some of the variables
						$comp_check		= TRUE;
						$user_role			= trim($comp_perm->role['id']);
						if(($user_role == 1) || ($user_role == 2))
						{
							$admin			= 'TRUE';
						}
						else
						{
							$admin			= 'FALSE';
						}
						$comp_name			= trim($comp_perm->company->name);
						
						// Break the loop
					}
				}
				
				// Set the sessions if successful
				if($comp_check == TRUE)
				{
					$this->session->set_userdata('sv_admin', $admin);
					$this->session->set_userdata('sv_user_role', $user_role);
					$this->session->set_userdata('sv_comp_id', $new_comp_id);
					$this->session->set_userdata('sv_comp_name', $comp_name);
				}
				
				// Redirect
				redirect($landing_page);
			}
			else
			{
				// Redirect
				redirect($landing_page);
			}
		}
		else
		{
			// Redirect
			redirect($landing_page);
		}
	}


	/*
	|--------------------------------------------------------------------------
	| USER WENT TO A PAGE THEY WERENT ALLOWED TO
	|--------------------------------------------------------------------------
	*/
	function page_restricted()
	{	
		// Check that the user has a landing page
		if($this->session->userdata('sv_landing_page'))
		{
			// Redirect
			redirect($this->session->userdata('sv_landing_page'));
		}
		else
		{	
			// Redirect
			redirect('login');
		}
	}
}

/* End of file redirect.php */
/* Location: ././scrap_application/engine/controllers/redirect.php */