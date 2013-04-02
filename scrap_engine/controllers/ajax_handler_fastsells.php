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
	| CREATE A FASTSELL
	|--------------------------------------------------------------------------
	*/
	function create_fastsell()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$user_id                        = $this->session->userdata('sv_user_id');
			$show_host_id                   = $this->scrap_web->get_show_host_id();
			$fastsell_name                  = $this->input->post('fastsell_name');
			$fastsell_description           = $this->input->post('fastsell_description');
			$fastsell_terms_and_conditions  = $this->input->post('terms_and_conditions');
			$start_date                     = $this->input->post('start_date');
			$start_hour                     = $this->input->post('start_hour');
			$start_minute                   = $this->input->post('start_minute');
			$end_date                       = $this->input->post('end_date');
			$end_hour                       = $this->input->post('end_hour');
			$end_minute                     = $this->input->post('end_minute');
			$event_id                       = $this->input->post('event_id');
			$event_banner                   = $this->input->post('event_banner');
			$categories                     = $this->input->post('categories');

			// Edit time
			if(strlen($start_hour) == 1)
			{
				$start_hour             = '0'.$start_hour;
			}
			if(strlen($start_minute) == 1)
			{
				$start_minute          = '0'.$start_minute;
			}
			if(strlen($end_hour) == 1)
			{
				$end_hour               = '0'.$end_hour;
			}
			if(strlen($end_minute) == 1)
			{
				$end_minute            = '0'.$end_minute;
			}

			// Check that its an event create
			if($event_id == 'no_id')
			{
				// Get sample
				$url_sample                     = 'fastsellevents/sample.json';
				$call_sample                    = $this->scrap_web->webserv_call($url_sample);

				if($call_sample['error'] == FALSE)
				{
					$json_sample                = $call_sample['result'];

					// Edit the values
					$json_sample->name                                      = $fastsell_name;
					$json_sample->description                               = $fastsell_description;
					$json_sample->terms_and_conditions                      = $fastsell_terms_and_conditions;
					$json_sample->user->id                                  = $user_id;
					$json_sample->currency->id                              = 1;
					$json_sample->location                                  = null;
					$json_sample->event_end_date                            = $end_date.' '.$end_hour.$end_minute.'00';
					$json_sample->event_start_date                          = $start_date.' '.$start_hour.$start_minute.'00';
					$json_sample->fastsell_event_type->id                   = 1;
					$json_sample->show_host_organization->id                = $show_host_id;
					$json_sample->customer_organizations                    = null;
					$json_sample->fastsell_event_to_fastsell_event_options  = null;

					// Set the categories
					$ex_categories                      = explode('][', $this->scrap_string->remove_flc($categories));
					$ar_categories                      = array();
					foreach($ex_categories as $category)
					{
						array_push($ar_categories, array('id' => $category));
					}
					$json_sample->fastsell_item_categories                  = $ar_categories;

					// Recode
					$new_json				            = json_encode($json_sample);
					//echo $new_json;

					// Submit the new fastsell event
					$new_fastsell		                = $this->scrap_web->webserv_call('fastsellevents/.json', $new_json, 'put', FALSE, FALSE, TRUE);

					if($new_fastsell['error'] == FALSE)
					{
						// Some variables
						$json_fastsell                  = $new_fastsell['result'];

						// Set the session variables
						$this->session->set_userdata('sv_show_set', $json_fastsell['id']);

						// Create the banner folder
						$url_sample_path                = 'serverlocalfiles/sample.json';
						$call_sample_path               = $this->scrap_web->webserv_call($url_sample_path);
						$json_sample_path               = $call_sample_path['result'];

						// Edit DOM
						$json_sample_path->path         = 'scrap_shows/'.$json_fastsell['id'].'/banner';
						$json_new_folder                = json_encode($json_sample_path);

						// Create directory
						$new_directory                  = $this->scrap_web->webserv_call('serverlocalfiles/folder.json', $json_new_folder, 'put');

						// Clone the definitions
						$url_definitions                = 'catalogitemdefinitions/.jsons?showhostid='.$show_host_id;
						$call_definitions               = $this->scrap_web->webserv_call($url_definitions, FALSE, 'get', FALSE, FALSE);

						if($call_definitions['error'] == FALSE)
						{
							// Get JSON
							$json_definitions           = $call_definitions['result'];

							// Get sample jason
							$url_sample                 = 'fastsellitemdefinitions/sample.json';
							$call_sample                = $this->scrap_web->webserv_call($url_sample);

							// Clone each definition
							// Validate
							if($call_sample['error'] == FALSE)
							{
								// Sample
								$json_sample			        = $call_sample['result'];

								foreach($json_definitions->catalog_item_definitions as $product_definition)
								{
									// Change the data
									$json_sample->name                              = 'fsed_'.$json_fastsell['id'].'_'.$product_definition->name;
									$json_sample->user->id                          = $user_id;
									$json_sample->show_host_organization->id        = $show_host_id;
									$json_sample->catalog_item_definition->id       = $product_definition->id;
									$json_sample->fastsell_event->id                = $json_fastsell['id'];
									$json_sample->fastsell_item_definition_fields   = null;

									// Recode
									$new_show_definition_json                       = json_encode($json_sample);

									// Submit the insert
									$new_show_definition                            = $this->scrap_web->webserv_call('fastsellitemdefinitions/.json', $new_show_definition_json, 'put', FALSE, FALSE);
								}
							}
						}

						// Return
						$return                         = 'okitsbeencreated:';
						$return                         .= $json_fastsell['id'];
						echo $return;
					}
					else
					{
						// Return the error message
						$json				= $new_fastsell['result'];
						echo $json['error_description'];
					}
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
	| SEARCH FOR CUSTOMERS
	|--------------------------------------------------------------------------
	*/
	function search_for_customers()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();
			$offset                         = 0;
			$limit                          = 20;
			$search_text                    = urlencode($this->input->post('customer_name'));

			// Get all the customers
			$url_customers                  = 'customertoshowhosts/.jsons?showhostid='.$show_host_id.'&searchtext='.$search_text.'&limit='.$limit.'&offset='.$offset;
			$call_customers                 = $this->scrap_web->webserv_call($url_customers, FALSE, 'get', FALSE, FALSE);
			$dt_body['customers']           = $call_customers;

			// Load the view
			$this->load->view('customers/customers_list', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| UPDATE A FASTSELL
	|--------------------------------------------------------------------------
	*/
	function update_fastsell()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$user_id                        = $this->session->userdata('sv_user_id');
			$show_host_id                   = $this->scrap_web->get_show_host_id();
			$fastsell_name                  = $this->input->post('fastsell_name');
			$fastsell_description           = $this->input->post('fastsell_description');
			$fastsell_terms_and_conditions  = $this->input->post('terms_and_conditions');
			$start_date                     = $this->input->post('start_date');
			$start_hour                     = $this->input->post('start_hour');
			$start_minute                   = $this->input->post('start_minute');
			$end_date                       = $this->input->post('end_date');
			$end_hour                       = $this->input->post('end_hour');
			$end_minute                     = $this->input->post('end_minute');
			$event_id                       = $this->input->post('event_id');
			$event_banner                   = $this->input->post('event_banner');
			$categories                     = $this->input->post('categories');

			// Edit time
			if(strlen($start_hour) == 1)
			{
				$start_hour             = '0'.$start_hour;
			}
			if(strlen($start_minute) == 1)
			{
				$start_minute          = '0'.$start_minute;
			}
			if(strlen($end_hour) == 1)
			{
				$end_hour               = '0'.$end_hour;
			}
			if(strlen($end_minute) == 1)
			{
				$end_minute            = '0'.$end_minute;
			}

			// Check that its an event create
			if(is_numeric($event_id))
			{
				// Get fastsell
				$url_fastsell                   = 'fastsellevents/.json?id='.$event_id;
				$call_fastsell                  = $this->scrap_web->webserv_call($url_fastsell, FALSE, 'get', FALSE, FALSE);

				if($call_fastsell['error'] == FALSE)
				{
					$json_fastsell              = $call_fastsell['result'];

					// Edit the values
					$json_fastsell->name                                        = $fastsell_name;
					$json_fastsell->description                                 = $fastsell_description;
					$json_fastsell->terms_and_conditions                        = $fastsell_terms_and_conditions;
					$json_fastsell->user->id                                    = $user_id;
					$json_fastsell->currency->id                                = 1;
					$json_fastsell->location                                    = null;
					$json_fastsell->event_end_date                              = $end_date.' '.$end_hour.$end_minute.'00';
					$json_fastsell->event_start_date                            = $start_date.' '.$start_hour.$start_minute.'00';
					$json_fastsell->fastsell_event_type->id                     = 1;
					$json_fastsell->show_host_organization->id                  = $show_host_id;
					$json_fastsell->customer_organizations                      = null;
					$json_fastsell->fastsell_event_to_fastsell_event_options    = null;

					// Set the categories
					$ex_categories                      = explode('][', $this->scrap_string->remove_flc($categories));
					$ar_categories                      = array();
					foreach($ex_categories as $category)
					{
						array_push($ar_categories, array('id' => $category));
					}
					$json_fastsell->fastsell_item_categories                    = $ar_categories;

					// Recode
					$update_json		        = json_encode($json_fastsell);

					// Submit the fastsell event update
					$update_fastsell		    = $this->scrap_web->webserv_call('fastsellevents/.json', $update_json, 'post');

					// Validate the result
					if($update_fastsell['error'] == FALSE)
					{
						$json_fastsell          = $update_fastsell['result'];
						$return                 = 'okitsbeenupdated:';
						$return                 .= $json_fastsell->id;
						echo $return;
					}
					else
					{
						// Return the error message
						$json				= $update_fastsell['result'];
						echo $json->error_description;
					}
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
	| FASTSELL TO CUSTOMER LINK
	|--------------------------------------------------------------------------
	*/
	function fastsell_customer_link()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$event_id                       = $this->input->post('event_id');
			$customer_id                    = $this->input->post('customer_id');
			$type                           = $this->input->post('type');

			// Sample code
			$json_link                      = array
			(
				'customer_organizations'    => array(array('id' => $customer_id))
			);

			// Recode
			$link_json		                = json_encode($json_link);

			// Submit the fastsell customer link
			if($type == 'add')
			{
				$call_type                  = 'put';
				$link_customer		        = $this->scrap_web->webserv_call('fastsellevents/customers/.jsons?sendnotification=true&fastselleventid='.$event_id, $link_json, $call_type, FALSE, FALSE);
			}
			elseif($type == 'remove')
			{
				$call_type                  = 'post';
				$link_customer		        = $this->scrap_web->webserv_call('fastsellevents/customers/.jsons?fastselleventid='.$event_id, $link_json, $call_type, FALSE, FALSE);
			}
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| FASTSELL TO CUSTOMER LINK BY GROUP
	|--------------------------------------------------------------------------
	*/
	function add_customers_to_fastsell_by_group()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$event_id                       = $this->input->post('event_id');
			$group_id                       = $this->input->post('group_id');

			// Get all the customers by group
			$url_customers                  = 'fastsellcustomergroups/.json?id='.$group_id;
			$call_customers                 = $this->scrap_web->webserv_call($url_customers, FALSE, 'get', FALSE, FALSE);

			if($call_customers['error'] == FALSE)
			{
				$json_customers             = $call_customers['result'];
				$ar_customer_ids            = array();

				foreach($json_customers->customer_organizations as $customer_organization)
				{
					array_push($ar_customer_ids, array('id' => $customer_organization->id));
				}

				$json_link                      = array
				(
					'customer_organizations'    => $ar_customer_ids
				);

				// Recode
				$link_json		                = json_encode($json_link);

				// Link
				$link_customers		            = $this->scrap_web->webserv_call('fastsellevents/customers/.jsons?sendnotification=true&fastselleventid='.$event_id, $link_json, 'put', FALSE, FALSE);

				if($link_customers['error'] == FALSE)
				{
					echo 'okitsdone';
				}
				else
				{
					$json_error                 = $link_customers['result'];
					echo $json_error->error_description;
				}
			}
			else
			{
				$json_error                 = $call_customers['result'];
				echo $json_error->error_description;
			}
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| CREATE FASTSELL PRODUCT
	|--------------------------------------------------------------------------
	*/
	function fastsell_create_product()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$user_id                        = $this->session->userdata('sv_user_id');
			$product_id                     = $this->input->post('product_id');
			$stock                          = $this->input->post('stock');
			$price                          = $this->input->post('price');
			$event_id                       = $this->input->post('event_id');

			// Get fastsell definition
			$url_fs_definition              = 'fastsellitemdefinitions/.jsons?catalogitemid='.$product_id.'&fastselleventid='.$event_id;
			$call_fs_definition             = $this->scrap_web->webserv_call($url_fs_definition, FALSE, 'get', FALSE, FALSE);

			if($call_fs_definition['error'] == FALSE)
			{
				// Get definition
				$fs_definition                  = $call_fs_definition['result']->fastsell_item_definitions[0]->id;

				// Sample
				$url_sample                     = 'fastsellitems/sample.json';
				$call_sample                    = $this->scrap_web->webserv_call($url_sample);

				if($call_sample['error'] == FALSE)
				{
					$json_sample                                = $call_sample['result'];

					// Edit JSON
					$json_sample->price                         = $price;
					$json_sample->user->id                      = $user_id;
					$json_sample->stock_count                   = $stock;
					$json_sample->catalog_item->id              = $product_id;
					$json_sample->fastsell_event->id            = $event_id;
					$json_sample->fastsell_item_field_values    = null;
					$json_sample->fastsell_item_definition->id  = $fs_definition;

					// Recode
					$json_create_product		                = json_encode($json_sample);

					// Create product
					$new_fastsell_product   = $this->scrap_web->webserv_call('fastsellitems/.json', $json_create_product, 'put', FALSE, FALSE);

					if($new_fastsell_product['error'] == FALSE)
					{
						$return             = 'okitsbeencreated';
					}
					else
					{
						// Return the error message
						$json				= $new_fastsell_product['result'];
						echo $json->error_description;
					}
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
	| REMOVE FASTSELL PRODUCT
	|--------------------------------------------------------------------------
	*/
	function fastsell_remove_product()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$user_id                        = $this->session->userdata('sv_user_id');
			$product_id                     = $this->input->post('product_id');
			$event_id                       = $this->input->post('event_id');

			// Create product
			$remove_fastsell_product        = $this->scrap_web->webserv_call('fastsellitems/.json?id='.$product_id, FALSE, 'delete');

			if($remove_fastsell_product['error'] == FALSE)
			{
				echo 'okitsbeenremoved';
			}
			else
			{
				// Return the error message
				$json				        = $remove_fastsell_product['result'];
				echo $json->error_description;
			}
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| REFRESH ADDED PRODUCT LIST
	|--------------------------------------------------------------------------
	*/
	function get_added_products()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$event_id                       = $this->input->post('event_id');
			$limit                          = 50000;
			$offset                         = 0;

			// Get fastsell products
			$url_fs_products                = 'fastsellitems/.jsons?fastselleventid='.$event_id.'&includevalues=true&includecatalogvalues=true';
			$call_fs_products               = $this->scrap_web->webserv_call($url_fs_products, FALSE, 'get', FALSE, FALSE);
			$dt_body['products']            = $call_fs_products;

			$dt_body['limit']               = $limit;
			$dt_body['offset']              = $offset;

			// Load the view
			$this->load->view('products/fastsell_products_list', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| LINK CUSTOMERS VIA MASTER DATA FILE
	|--------------------------------------------------------------------------
	*/
	function customer_master_data_upload()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);

		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id               = $this->scrap_web->get_show_host_id();
			$dt_body['show_host_id']    = $show_host_id;
			$fastsell_id                = $this->session->userdata('sv_show_set');

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

			// Create customer - If needed
			$url_insert                 = 'customertoshowhosts/masterdata.json?showhostid='.$show_host_id.'&validateinnercontentonly=false';
			$call_insert                = $this->scrap_web->webserv_call($url_insert, $encode_file_convert, 'put');

			// Link customer
			$url_link                   = 'fastsellevents/customers/masterdata.json?fastselleventid='.$fastsell_id.'&validateinnercontentonly=false';
			$call_link                  = $this->scrap_web->webserv_call($url_link, $encode_file_convert, 'put');

			// Get all the customers
			$url_customers              = 'customers/.jsons?fastselleventid='.$fastsell_id;
			$call_customers             = $this->scrap_web->webserv_call($url_customers, FALSE, 'get', FALSE, FALSE);
			$dt_body['customers']       = $call_customers;

			// Load the view
			$this->load->view('customers/fastsell_customers_list', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| LINK CUSTOMERS
	|--------------------------------------------------------------------------
	*/
	function get_linked_customers()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);

		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$fastsell_id                = $this->input->post('event_id');

			// Get all the customers
			$url_customers              = 'customers/.jsons?fastselleventid='.$fastsell_id;
			$call_customers             = $this->scrap_web->webserv_call($url_customers, FALSE, 'get', FALSE, FALSE);
			$dt_body['customers']       = $call_customers;

			// Load the view
			$this->load->view('customers/fastsell_customers_list', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| UPLOAD FASTSELL IMAGE
	|--------------------------------------------------------------------------
	*/
	function add_event_image()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);

		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$fastsell_id                = $this->session->userdata('sv_show_set');

			if(isset($_FILES['uploadedFileFastsellImage']) && !empty($_FILES['uploadedFileFastsellImage']))
			{
				$document_file			= str_replace(' ', '%20', $_FILES['uploadedFileFastsellImage']);

				// Upload the file
				$url_file_upload            = 'serverlocalfiles/.bodyexclude_json?path=scrap_shows%2F'.$fastsell_id.'%2Fbanner%2F'.$document_file['name'];
				$call_file_upload           = $this->scrap_web->webserv_call($url_file_upload, array('uploadedFile'	=> '@'.$document_file['tmp_name']), 'post', 'multipart_form', TRUE);
			}
			else
			{
				$document_file			= FALSE;
			}
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| UPLOAD FASTSELL IMAGE 2
	|--------------------------------------------------------------------------
	*/
	function add_event_image_2()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);

		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$fastsell_id                = $this->session->userdata('sv_show_set');

			if(isset($_FILES['uploadedFileFastsellImage']) && !empty($_FILES['uploadedFileFastsellImage']))
			{
				$document_file			= str_replace(' ', '%20', $_FILES['uploadedFileFastsellImage']);

				// Upload the file
				$url_file_upload            = 'serverlocalfiles/.bodyexclude_json?path=scrap_shows%2F'.$fastsell_id.'%2Fbanner%2F'.$document_file['name'];
				$call_file_upload           = $this->scrap_web->webserv_call($url_file_upload, array('uploadedFile'	=> '@'.$document_file['tmp_name']), 'post', 'multipart_form', TRUE);
			}
			else
			{
				$document_file			= FALSE;
			}
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| UPLOAD TEMPORARY FASTSELL IMAGE
	|--------------------------------------------------------------------------
	*/
	function add_temp_event_image()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);

		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$random_folder              = $this->scrap_string->random_string_letter();

			// Create the banner folder
			$url_sample_path                = 'serverlocalfiles/sample.json';
			$call_sample_path               = $this->scrap_web->webserv_call($url_sample_path);
			$json_sample_path               = $call_sample_path['result'];

			// Edit DOM
			$json_sample_path->path         = 'scrap_shows_temp/temp_'.$random_folder.'_temp';
			$json_new_folder                = json_encode($json_sample_path);

			// Create directory
			$new_directory                  = $this->scrap_web->webserv_call('serverlocalfiles/folder.json', $json_new_folder, 'put');

			if(isset($_FILES['uploadedFileFastsellImage']) && !empty($_FILES['uploadedFileFastsellImage']))
			{
				$document_file			    = str_replace(' ', '%20', $_FILES['uploadedFileFastsellImage']);

				// Upload the file
				$url_file_upload            = 'serverlocalfiles/.bodyexclude_json?path=scrap_shows_temp/temp_'.$random_folder.'_temp/'.$document_file['name'];
				$call_file_upload           = $this->scrap_web->webserv_call($url_file_upload, array('uploadedFile'	=> '@'.$document_file['tmp_name']), 'post', 'multipart_form', FALSE);

				echo $this->config->item('scrap_web_address').'serverlocalfiles/file?path=scrap_shows_temp/temp_'.$random_folder.'_temp/'.$_FILES['uploadedFileFastsellImage']['name'];
			}
			else
			{
				$document_file			= FALSE;
			}
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD CUSTOMERS BY GROUP POPUP
	|--------------------------------------------------------------------------
	*/
	function add_customers_by_group_popup()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);

		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Show host id
			$show_host_id               = $this->scrap_web->get_show_host_id();

			// Get all the groups
			$url_groups                     = 'fastsellcustomergroups/.jsons?showhostid='.$show_host_id;
			$call_groups                    = $this->scrap_web->webserv_call($url_groups, FALSE, 'get', FALSE, FALSE);
			$dt_body['groups']              = $call_groups;

			// Load the view
			$this->load->view('customers/ajax/select_customer_group', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| GET FASTSELL CATEGORY
	|--------------------------------------------------------------------------
	*/
	function get_fastsell_category()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);

		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$category_text                  = $this->input->post('cat_text');

			// Get the category
			$url_category                   = 'fastsellitemcategories/.jsons?categorytext='.urlencode($category_text).'&includerelationships=true';
			$call_category                  = $this->scrap_web->webserv_call($url_category, FALSE, 'get', FALSE, FALSE);
			$dt_body['category']            = $call_category;

			// Category breadcrumb
			$this->load->view('fastsells/category_breadcrumbs', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| SEARCH FOR A FASTSELL CATEGORY
	|--------------------------------------------------------------------------
	*/
	function search_for_category()
	{
		// Get the category
		$url_category                   = 'fastsellitemcategories/.jsons?categorytext='.urlencode($_GET['term']).'&includerelationships=true';
		$call_category                  = $this->scrap_web->webserv_call($url_category, FALSE, 'get', FALSE, FALSE);

		if($call_category['error'] == FALSE)
		{
			// Data
			$json_categories        = $call_category['result'];
			$ar_categories          = array();

			foreach($json_categories->fastsell_item_categories as $list_category)
			{
				array_push($ar_categories, $list_category->category);
			}

			echo json_encode($ar_categories);
		}
	}
	
}
/* End of file ajax_handler_fastsells.php */
/* Location: ./scrap_application/engine/controllers/ajax_handler_fastsells.php */