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

class Ajax_handler_users extends CI_Controller 
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
	| GET USER DETAILS POPUP CONTENT
	|--------------------------------------------------------------------------
	*/
	function user_details_popup_content()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$crt_user					= $this->input->post('crt_user');
			if($crt_user == 'true')
			{
				$user_id				= $this->session->userdata('sv_user_id');
			}
			else
			{
				$user_id				= $this->input->post('user_id');
			}
			
			// Scrappy web call
			$url						= 'users/.json?id='.$user_id;
			$call_user					= $this->scrap_web->webserv_call($url, FALSE, 'get', FALSE, FALSE);
			
			// Validate
			if($call_user['error'] == FALSE)
			{
				// Data
				$dt_body['json_user']	= $call_user['result'];
				$dt_body['crt_user']	= $crt_user;
				
				// Load the view
				$this->load->view('manage/ajax/user_details', $dt_body);
			}
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| UPLOAD THE USERS NEW PROFILE IMAGE
	|--------------------------------------------------------------------------
	*/
	function upload_user_image()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);
		
		// Some loads
		$this->load->library('upload');
		
		// Some variables
		$user_id				= $this->input->post('hdUserId');
		
		// Set the variables
		if(isset($_FILES['inpUploadImage']) && !empty($_FILES['inpUploadImage']))
		{
			$profile_image		= str_replace(' ', '%20', $_FILES['inpUploadImage']);
		}
		else
		{
			$profile_image		= FALSE;
		}
		
		// Get the user details
		$url					= 'users/.json?id='.$user_id;
		$call_user				= $this->scrap_web->webserv_call($url);
		
		// Redirect if error
		if(($call_user['error'] == TRUE) || ($profile_image == FALSE))
		{
			echo 'error_no_user';
		}
		else
		{
			// Load the main view
			$json_user				= $call_user['result'];
			$profile_image_folder	= 'scrap_people/';
			$user_date_folder		= $this->scrap_string->folder_date($json_user->create_date).'/'.$user_id.'/';
			$profile_image_folder	.= $user_date_folder;
			$profile_image_folder	.= 'profile/image/';

			// Delete existing files
			//delete_files($profile_image_folder);
			
			// Create the profile folder
			$url_sample_path                = 'serverlocalfiles/sample.json';
			$call_sample_path               = $this->scrap_web->webserv_call($url_sample_path);
			$json_sample_path               = $call_sample_path['result'];

			// Edit DOM
			$json_sample_path->path         = $profile_image_folder;
			$json_new_folder                = json_encode($json_sample_path);

			// Create directory
			$new_directory                  = $this->scrap_web->webserv_call('serverlocalfiles/folder.json', $json_new_folder, 'put');
	
			// Configuration for the image import
			if(($profile_image != FALSE) || (!empty($profile_image['name'])))
			{
				if(!empty($_FILES))
				{
					$url_image              = 'serverlocalfiles/.jsons?path='.$profile_image_folder;
					$call_image             = $this->scrap_web->webserv_call($url_image, FALSE, 'get', FALSE, FALSE);
					$json_image             = $call_image['result'];

					if($json_image->is_empty == FALSE)
					{
						foreach($json_image->server_local_files as $file_info)
						{
							$url_delete                 = 'serverlocalfiles/.json?path='.$file_info->path;
							$call_delete                = $this->scrap_web->webserv_call($url_delete, FALSE, 'delete', FALSE, FALSE);
						}
					}

					// Upload the file
					$url_file_upload        = 'serverlocalfiles/.json?path=scrap_people/'.$user_date_folder.'profile/image/'.$profile_image['name'];
					$call_file_upload       = $this->scrap_web->webserv_call($url_file_upload, array('uploadedFile'	=> '@'.$profile_image['tmp_name']), 'post', 'multipart_form', FALSE);
				}
			}
			
			// The return
			echo $this->config->item('scrap_web_address').'serverlocalfiles/file?path=scrap_people/'.$user_date_folder.'profile/image/'.$profile_image['name'];
		}
	}


	/*
	|--------------------------------------------------------------------------
	| UPDATE USER DETAILS
	|--------------------------------------------------------------------------
	*/
	function update_user_details()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$user_id			= $this->input->post('user_id');
			$acc_type			= $this->input->post('acc_type');
			$first_name			= $this->input->post('first_name');
			$surname			= $this->input->post('surname');
			$username			= $this->input->post('username');
			$email_address		= $this->input->post('email_address');
			$password			= $this->input->post('password');
			
			// Scrappy web call
			$url				= 'users/.json?id='.$user_id;
			$call_user			= $this->scrap_web->webserv_call($url, FALSE, 'get', FALSE, FALSE, TRUE);
			
			// Validate
			if($call_user['error'] == FALSE)
			{
				// Data
				$json_user					= $call_user['result'];
				
				// Change the data
				$json_user['firstname']		= $first_name;
				$json_user['lastname']		= $surname;
				$json_user['username']		= $username;
				$json_user['user_emails'][0]['email']   = $email_address;
				if(!empty($password))
				{
					$json_user['password']	= sha1($password);
				}
				
				// Recode
				$new_json					= json_encode($json_user);
				
				// Submit the changes
				$update_user				= $this->scrap_web->webserv_call('users/.json', $new_json);
				
				// Validate the result
				if($update_user['error'] == FALSE)
				{
					// Set the session variables
					$this->session->set_userdata('sv_name', $first_name.' '.$surname);
					
					// Change his account type
					$url_acc_type			= 'showhostusers/.json?userid='.$user_id;
					$call_user_acc_type		= $this->scrap_web->webserv_call($url_acc_type);
					$json_user_acc_type		= $call_user_acc_type['result'];
					
					// Change the data
					$json_user_acc_type->show_host_role->id		= $acc_type;
				
					// Recode
					$new_json_acc_type		= json_encode($json_user_acc_type);
					//echo $new_json_acc_type;
					
					// Submit the changes
					$update_user			= $this->scrap_web->webserv_call('showhostusers/.json', $new_json_acc_type);
					
					// Return
					echo 'okitsdone';
				}
				else
				{
					// Return the error message
					$json					= $update_user['result'];
					echo $json->errorDescription;
				}
			}
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| RESET PASSWORD BY USERNAME
	|--------------------------------------------------------------------------
	*/
	function reset_password_by_username()
	{
		// Some variables
		$username				= $this->input->post('username');
			
		// Scrappy web call
		$url					= 'auth/forgotpassword.json?username='.$username;
		$username_reset			= $this->scrap_web->webserv_call($url);
			
		// Validate
		if($username_reset['error'] == FALSE)
		{
			// Return
			echo 'okitsdone';
		}
		else
		{
			// Return the error message
			$json				= $username_reset['result'];
			echo $json->error_description;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| RESET PASSWORD WITH TOKEN
	|--------------------------------------------------------------------------
	*/
	function reset_password_with_token()
	{
		// Some variables
		$password				= sha1($this->input->post('password'));
		$token					= $this->input->post('token');
			
		// Scrappy web call
		$url					= 'auth/resetpassword.json?token='.$token.'&password='.$password;
		$password_reset			= $this->scrap_web->webserv_call($url);
			
		// Validate
		if($password_reset['error'] == FALSE)
		{
			// Return
			echo 'okitsdone';
		}
		else
		{
			// Return the error message
			$json				= $password_reset['result'];
			echo $json->error_description;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD USER POPUP
	|--------------------------------------------------------------------------
	*/
	function add_user_popup()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Get the view
			$this->load->view('manage/ajax/add_user_popup');
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD USER POPUP
	|--------------------------------------------------------------------------
	*/
	function add_user_popup_content()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Get the view
			$this->load->view('manage/ajax/add_user_content');
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD USER
	|--------------------------------------------------------------------------
	*/
	function add_user()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$user_id				= $this->session->userdata('sv_user_id');
			$acc_type				= $this->input->post('drpdwnUserType');
			$first_name				= $this->input->post('inpName');
			$surname				= $this->input->post('inpSurname');
			$email					= $this->input->post('inpEmail');
			$username				= $this->input->post('inpUsername');
			$password				= $this->input->post('inpPassword');
			
			// Scrappy web call
			$url_sample				= 'showhostusers/sample.json';
			$call_sample			= $this->scrap_web->webserv_call($url_sample);
			
	 		// Validate
	 		if($call_sample['error'] == FALSE)
	 		{
	 			// Get show host id
	 			$url_show_host_id		= 'showhostusers/.json?userid='.$user_id; 
	 			$call_show_host_id		= $this->scrap_web->webserv_call($url_show_host_id);
	 			$json_show_host_id		= $call_show_host_id['result'];
	 			$show_host_id			= $json_show_host_id->show_host_organization->id;
	 			
	 			// Sample
	 			$json_sample			= $call_sample['result'];
	 			
	 			// Change the data
	 			$ar_emails										= array();
	 			$ar_emails['is_primary']                      	= TRUE;
	 			$ar_emails['email']								= $email;
	 			
	 			$json_sample->user->user_emails					= array($ar_emails);
	 			$json_sample->user->firstname					= $first_name;
	 			$json_sample->user->lastname					= $surname;
	 			$json_sample->user->username					= $username;
	 			$json_sample->user->password					= sha1($password);
	 			
	 			$json_sample->show_host_organization->id		= $show_host_id;
	 			$json_sample->show_host_role->id				= $acc_type;
	 				 			
	 			// Recode
	 			$new_json				= json_encode($json_sample);
	 			//echo $new_json;
	 			
	 			// Submit the new vendor
	 			$new_user				= $this->scrap_web->webserv_call('showhostusers/.json', $new_json, 'put');

	 			// Validate the result
	 			if($new_user['error'] == FALSE)
	 			{
	 				echo 'okitsdone';
	 			}
	 			else
	 			{
	 				// Return the error message
					$json				= $new_user['result'];
					echo $json->error_description;
	 			}
	 		}
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| GET ALL USERS
	|--------------------------------------------------------------------------
	*/
	function get_all_users()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$acc_view				= $this->session->userdata('sv_acc_view');
			$user_id				= $this->session->userdata('sv_user_id');
		
	 		// Get show host id
	 		$url_show_host_id		= 'showhostusers/.json?userid='.$user_id; 
	 		$call_show_host_id		= $this->scrap_web->webserv_call($url_show_host_id);
	 		$json_show_host_id		= $call_show_host_id['result'];
	 		$show_host_id			= $json_show_host_id->show_host_organization->id;
			
			// Get the user view
			$url					= 'users/view/xml?page=manage_users';
			//$view_setting			= $this->scrap_web->webserv_call($url);
			$view_setting			= FALSE;
			
			// Check the setting
			if($view_setting['error'] == FALSE)
			{
				$dt_body['view_setting']	= 'no_view';
			}
			else
			{
				$dt_body['view_setting']	= 'no_view';
			}
	 		
			// Get the users
			$url_users				= 'showhostusers/.jsons?showhostid='.$show_host_id;
			$json_users				= $this->scrap_web->webserv_call($url_users);
			$dt_body['users']		= $json_users;
			
			// Content view
			$this->load->view('manage/users_list', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}
	
}
/* End of file ajax_handler_users.php */
/* Location: ./scrap_application/engine/controllers/ajax_handler_users.php */