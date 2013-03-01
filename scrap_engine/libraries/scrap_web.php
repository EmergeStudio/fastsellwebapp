<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Scrap_web
{
	/*
	|--------------------------------------------------------------------------
	| CODEIGNITER REQUIREMENTS
	|--------------------------------------------------------------------------
	*/
	var $CI = null;
	
	function Scrap_web()
	{
		$this->CI =& get_instance();
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| WEB SERVICE CALL
	|--------------------------------------------------------------------------
	*/
	function webserv_call($url, $post_data = FALSE, $protocol = 'post', $marshaller = FALSE, $display_url = FALSE, $as_array = FALSE, $stream = FALSE)
	{
		// Some variables
        if($marshaller == FALSE)
        {
		    $marshaller			= $this->CI->config->item('marshaller');
        }
		
		// Setup
		$ch					= curl_init();
		$exp_url			= explode('?', $url, 2);
		
		$new_url			= $this->CI->config->item('scrap_web_address').$exp_url[0];
		if($this->CI->session->userdata('sv_java_id'))
		{
			$new_url		.= ';jsessionid='.$this->CI->session->userdata('sv_java_id');
		}
		if(!empty($exp_url[1]))
		{
			$new_url		.= '?'.$exp_url[1];
		}
		
		// Display the url as a string
		if($display_url == TRUE)
		{
			echo $new_url;
		}
		
		curl_setopt($ch, CURLOPT_URL, trim($new_url));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		if(($post_data == TRUE) && ($protocol == 'post'))
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}
		else if(($post_data == TRUE) && ($protocol == 'put'))
		{
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}
		else if(($post_data == TRUE) && ($protocol == 'delete'))
		{
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}
		else if($protocol == 'delete')
		{
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)');
		
		// Check the marshaller
		switch($marshaller)
		{
			// XML option
			case 'xml'	:
				
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml'));
					
				// Result
				$result 			= curl_exec($ch);
				$curl_info	 		= curl_getinfo($ch);
				curl_close($ch);
					
				// Return
				$ar_return['curl_info']		= $curl_info;
				$ar_return['result']		= $result;
					
				// Error check
				$xml						= new SimpleXmlElement($result);
				if($xml->error_code)
				{
					$ar_return['error']		= TRUE;
				}
				else
				{
					$ar_return['error']		= FALSE;
				}

				break;
			
			// JSON option
			case 'json'	:
				
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
					
				// Result
				$result 			= curl_exec($ch);
				$curl_info	 		= curl_getinfo($ch);
				curl_close($ch);
					
				// Return
				$ar_return['curl_info']		= $curl_info;
				if($as_array == FALSE)
				{
					if($stream == FALSE)
					{
						$ar_return['result']    = json_decode($result);
					}
					else
					{
						$ar_return['result']    = $result;
					}
				}
				else
				{
					$ar_return['result']    = json_decode($result, TRUE);
				}

				// Error check
				$json						= $ar_return['result'];

//				foreach($curl_info as $key => $value)
//				{
//					echo $key.' -- '.$value.'<br>';
//				}

				if($curl_info['http_code'] != 200)
				{
					$url_time               = 'timezones/.json';
					$call_time              = $this->webserv_call($url_time);
					$json_time              = $call_time['result'];

					$ar_return['error']		= TRUE;
					$ar_return['result']    = 'Unable to process your request: '.$curl_info['http_code'].' (Time: '.$json_time->time.')';
				}
				else
				{
					if($as_array == FALSE)
					{
						if(isset($json->error_code))
						{
							$ar_return['error']		= TRUE;
						}
						else
						{
							$ar_return['error']		= FALSE;
						}
					}
					else
					{
						if(isset($json['error_code']))
						{
							$ar_return['error']		= TRUE;
						}
						else
						{
							$ar_return['error']		= FALSE;
						}
					}
				}
				
				break;

			// JSON option
			case 'straight'	:

				//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

				// Result
				$result 			= curl_exec($ch);
				$curl_info	 		= curl_getinfo($ch);
				curl_close($ch);

				// Return
				$ar_return['curl_info']		= $curl_info;
				$ar_return['result']		= $result;
				$ar_return['error']		    = FALSE;

				break;

            // JSON option
            case 'multipart_form'	:

                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: multipart/form-data'));

                // Result
                $result 			= curl_exec($ch);
                $curl_info	 		= curl_getinfo($ch);
                curl_close($ch);

                // Return
                $ar_return['curl_info']		= $curl_info;
                $ar_return['result']		= json_decode($result);

                // Error check
                $json						= $ar_return['result'];
                if($json->error_code != null)
                {
                    $ar_return['error']		= TRUE;
                }
                else
                {
                    $ar_return['error']		= FALSE;
                }

                break;
		}
		
		return $ar_return;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| BYTE CALL
	|--------------------------------------------------------------------------
	*/
	function byte_call($url)
	{
		// Setup
		$ch							= curl_init();
		$exp_url					= explode('?', $url, 2);
		
		$new_url					= $this->CI->config->item('scrap_web_address').$exp_url[0];
		if($this->CI->session->userdata('sv_java_id'))
		{
			$new_url				.= ';jsessionid='.$this->CI->session->userdata('sv_java_id');
		}
		if(!empty($exp_url[1]))
		{
			$new_url				.= '?'.$exp_url[1];
		}
		
		// Run cURL
		$ch							= curl_init();
		curl_setopt($ch, CURLOPT_URL, trim($new_url));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)');
		
		// Result
		$result 					= curl_exec($ch);
		$curl_info	 				= curl_getinfo($ch);
		curl_close($ch);
		
		// Return
		$ar_return['content_type']	= $curl_info['content_type'];
		$ar_return['result']		= $result;
		
		return $ar_return;
	}


	/*
	|--------------------------------------------------------------------------
	| IMAGE CALL
	|--------------------------------------------------------------------------
	*/
	function image_call($url)
	{
		// Setup
		$exp_url					= explode('?', $url, 2);

		$new_url					= $this->CI->config->item('scrap_web_address').$exp_url[0];
//		if($this->CI->session->userdata('sv_java_id'))
//		{
//			$new_url				.= ';jsessionid='.$this->CI->session->userdata('sv_java_id');
//		}
		if(!empty($exp_url[1]))
		{
			$new_url				.= '?'.$exp_url[1];
		}

		return $new_url;
	}


	/*
	|--------------------------------------------------------------------------
	| RANDOM STRING
	|--------------------------------------------------------------------------
	*/
	function random_string($length = 10)
	{
		$chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ023456789"; 
		srand((double)microtime()*1000000); 
		$i = 0; 
		$string = '' ; 
		
		while ($i <= $length) 
		{ 
			$num 		= rand() % 66; 
			$tmp 		= substr($chars, $num, 1); 
			$string	= $string . $tmp; 
			$i++; 
		} 
		
		return $string;
	}


	/*
	|--------------------------------------------------------------------------
	| GET CURRENT USER PROFILE IMAGE
	|--------------------------------------------------------------------------
	*/
	function get_profile_image($user_id = FALSE)
	{
		$src                    = base_url().'scrap_people/defaults/images/profile_pic.jpg';

		if($user_id == FALSE)
		{
			$user_id			= $this->CI->session->userdata('sv_user_id');
		}

        //Get user information
		$user_data				= $this->webserv_call('users/.json?id='.$user_id);

		// Check for errors
		if($user_data['error'] == FALSE)
		{
			// Setup the path to check
			$user_json			= $user_data['result'];
			$user_date_created	= $user_json->create_date;
			$folder_date		= $this->CI->scrap_string->folder_date($user_date_created);
			$image_path			= $folder_date.'/'.$user_id.'/profile/image/';

			$url_product_image      = 'serverlocalfiles/.jsons?path=scrap_people/'.$image_path;
			$call_product_image     = $this->webserv_call($url_product_image, FALSE, 'get', FALSE, FALSE);
			if($call_product_image['error'] == FALSE)
			{
				$json_product_image         = $call_product_image['result'];
				if($json_product_image->is_empty == FALSE)
				{
					$image_path             = $json_product_image->server_local_files[0]->path;
					$src                    = $this->image_call('serverlocalfiles/file?path='.$image_path);
				}
			}
		}

		return $src;
	}


	/*
	|--------------------------------------------------------------------------
	| GET CURRENT USER DIRECTORY
	|--------------------------------------------------------------------------
	*/
	function get_user_dir($user_id = FALSE, $path_type = 'url')
	{
		// Some variables
		if($user_id == FALSE)
		{
			$user_id			= $this->CI->session->userdata('sv_user_id');
		}
		
		// Get user information
		$user_data				= $this->webserv_call('users/.json?id='.$user_id);
		
		// Check for errors
		if($user_data['error'] == FALSE)
		{
			// Setup the path to check
			$user_json			= $user_data['result'];
			$user_date_created	= $user_json->create_date;
			$folder_date		= $this->CI->scrap_string->folder_date($user_date_created);
			$user_path			= $folder_date.'/'.$user_id;

			// Return
			if($path_type == 'url')
			{
				return base_url().'scrap_people/'.$user_path;
			}
			elseif($path_type == 'local')
			{
				return $this->CI->config->item('people_root').'/'.$user_path;
			}
		}
		else
		{
			return FALSE;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| GET SHOW HOST ID
	|--------------------------------------------------------------------------
	*/
    function get_show_host_id($user_id = FALSE)
    {
        // Some variables
        if($user_id == FALSE)
        {
            $user_id			= $this->CI->session->userdata('sv_user_id');
        }

        // Get show host id
        $url_show_host_id		= 'showhostusers/.json?userid='.$user_id;
        $call_show_host_id		= $this->webserv_call($url_show_host_id, FALSE, 'get', FALSE, FALSE);

        // Return
	    if($call_show_host_id['error'] == FALSE)
	    {
		    $json_show_host_id		= $call_show_host_id['result'];
            return $json_show_host_id->show_host_organization->id;
	    }
	    else
	    {
		    return FALSE;
	    }
    }


	/*
	|--------------------------------------------------------------------------
	| GET CATALOGUE ITEM INFORMATION
	|--------------------------------------------------------------------------
	*/
	function get_catalogue_item_fields($product_id = FALSE)
	{
		// Make the call
		if($product_id != FALSE)
		{
			$url_catalogue_item             = 'catalogitems/.json?id='.$product_id;
			$call_catalogue_item            = $this->webserv_call($url_catalogue_item, FALSE, 'get', FALSE, FALSE);

			// Return
			if($call_catalogue_item['error'] == FALSE)
			{
				$json_catalogue_item        = $call_catalogue_item['result'];
				$ar_fields                  = array();

				foreach($json_catalogue_item->catalog_item_field_values as $field)
				{
					$ar_fields[$field->catalog_item_definition_field->id]   = $field->catalog_item_definition_field->field_name;
				}

				return $ar_fields;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| GET CUSTOMER USER ID
	|--------------------------------------------------------------------------
	*/
	function get_customer_user_id($user_id = FALSE)
	{
		// Some variables
		if($user_id == FALSE)
		{
			$user_id			= $this->CI->session->userdata('sv_user_id');
		}

		// Get show host id
		$url_cust_user_id		= 'customerusers/.json?userid='.$user_id;
		$call_cust_usert_id		= $this->webserv_call($url_cust_user_id, FALSE, 'get', FALSE, FALSE);
		$json_cust_user_id		= $call_cust_usert_id['result'];

		// Return
		return $json_cust_user_id->id;
	}


	/*
	|--------------------------------------------------------------------------
	| GET CUSTOMER ORGANIZATION ID
	|--------------------------------------------------------------------------
	*/
	function get_customer_org_id($user_id = FALSE)
	{
		// Some variables
		if($user_id == FALSE)
		{
			$user_id			    = $this->CI->session->userdata('sv_user_id');
		}

		// Get the id
		$url_customer_org_id	    = 'customerusers/.json?userid='.$user_id;
		$call_customer_org_id	    = $this->webserv_call($url_customer_org_id, FALSE, 'get', FALSE, FALSE);
		$json_customer_org_id		= $call_customer_org_id['result'];

		// Return
		return $json_customer_org_id->customer_organization->id;
	}


	/*
	|--------------------------------------------------------------------------
	| GET CURRENT USER DIRECTORY
	|--------------------------------------------------------------------------
	*/
	function get_show_banner($show_id, $create_date, $path_type = 'url')
	{
		// Some variables
		$year_dir						= 'scrap_shows/content/'.$this->CI->scrap_string->folder_year_date($create_date);
		$month_dir						= $year_dir.'/'.$this->CI->scrap_string->folder_month_date($create_date);
		$show_dir						= $month_dir.'/'.$show_id;
		$show_banner_dir				= $show_dir.'/banner';
		
		$filenames						= get_filenames($show_banner_dir);
			
		// Return
		if($filenames != false)
		{
			if($path_type == 'url')
			{
				return base_url().$show_banner_dir.'/'.$filenames[0];
			}
			elseif($path_type == 'local')
			{
				return $show_banner_dir.'/'.$filenames[0];
			}
		}
		else
		{
			if($path_type == 'url')
			{
				return base_url().'scrap_shows/default/default_banner.png';
			}
			elseif($path_type == 'local')
			{
				return 'scrap_shows/default/default_banner.png';
			}
		}
	}


	/*
	|--------------------------------------------------------------------------
	| GET THE CURRENT TIME
	|--------------------------------------------------------------------------
	*/
	function get_current_time()
	{
		$url_time               = 'timezones/.json';
		$call_time              = $this->webserv_call($url_time);
		$json_time              = $call_time['result'];

		return $json_time->time;
	}
}