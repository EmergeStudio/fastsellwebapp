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


	/*
	|--------------------------------------------------------------------------
	| ADD DEFINITION
	|--------------------------------------------------------------------------
	*/
	function add_definition()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$def_name                           = $this->input->post('def_name');
			$def_fields                         = $this->input->post('def_fields');
			$user_id                            = $this->session->userdata('sv_user_id');

			// Get sample jason
			$url_sample                         = 'catalogitemdefinitions/sample.json';
			$call_sample                        = $this->scrap_web->webserv_call($url_sample);

			// Validate
			if($call_sample['error'] == FALSE)
			{
				// Get show host id
				$show_host_id			        = $this->scrap_web->get_show_host_id();

				// Sample
				$json_sample			        = $call_sample['result'];

				// Change the data
				$json_sample->name              = $def_name;
				$json_sample->show_host_organization->id        = $show_host_id;
				$json_sample->user->id          = $user_id;

				// Explode
				$ex_def_fields                  = explode('][', $this->scrap_string->remove_flc($def_fields));
				$ar_fields                      = array();

				// Default fields
				$ar_field_info                  = array('field_name' => 'Product Name', 'field_type' => array('id' => 2));
				array_push($ar_fields, $ar_field_info);

				$ar_field_info                  = array('field_name' => 'Description', 'field_type' => array('id' => 2));
				array_push($ar_fields, $ar_field_info);

				// Added fields
				if(!empty($def_fields))
				{
					foreach($ex_def_fields as $field_item)
					{
						$ar_field_info              = array('field_name' => $field_item, 'field_type' => array('id' => 2));
						array_push($ar_fields, $ar_field_info);
					}
				}
				$json_sample->catalog_item_definition_fields    = $ar_fields;

				// Recode
				$new_json				        = json_encode($json_sample);
				//echo $new_json;

				// Submit the insert
				$new_item_definition            = $this->scrap_web->webserv_call('catalogitemdefinitions/.json', $new_json, 'put', FALSE, FALSE);

				// Validate the result
				if($new_item_definition['error'] == FALSE)
				{
					echo 'wassuccessfullycreated';
				}
				else
				{
					// Return the error message
					$json				        = $new_item_definition['result'];
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
	| GET ITEM DEFINITIONS
	|--------------------------------------------------------------------------
	*/
	function get_definitions()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id               = $this->scrap_web->get_show_host_id();

			// Get the definitions
			$url_definitions            = 'catalogitemdefinitions/.jsons?showhostid='.$show_host_id;
			$call_definitions           = $this->scrap_web->webserv_call($url_definitions, FALSE, 'get', FALSE, FALSE);
			$dt_body['definitions']     = $call_definitions;

			// Load the content view
			$this->load->view('products/definitions_list', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| DELETE DEFINITION
	|--------------------------------------------------------------------------
	*/
	function delete_definition()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$definition_id                      = $this->input->post('definition_id');

			// Call to delete
			$url_delete                         = 'catalogitemdefinitions/.json?id='.$definition_id;
			$call_delete                        = $this->scrap_web->webserv_call($url_delete, FALSE, 'delete');

			// Validate
			if($call_delete['error'] == FALSE)
			{
				echo 'wassuccessfullydeleted';
			}
			else
			{
				// Return the error message
				$json				        = $call_delete['result'];
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
	| DELETE DEFINITION FIELD
	|--------------------------------------------------------------------------
	*/
	function delete_definition_field()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$definition_id                      = $this->input->post('definition_id');
			$field_id                           = $this->input->post('field_id');

			// Get the definition
			$url_definition                     = 'catalogitemdefinitions/.json?id='.$definition_id;
			$call_definition                    = $this->scrap_web->webserv_call($url_definition, FALSE, 'get', FALSE, FALSE);
			$json_definition                    = $call_definition['result'];

			// Rebuild the fields
			$ar_defintion_fields                = array();
			foreach($json_definition->catalog_item_definition_fields as $field)
			{
				if($field->id  != $field_id)
				{
					array_push($ar_defintion_fields, $field);
				}
			}
			$json_definition->catalog_item_definition_fields    = $ar_defintion_fields;

			$update_json                        = json_encode($json_definition);
			//echo $update_json;

			// Call to update
			$url_update                         = 'catalogitemdefinitions/.json';
			$call_update                        = $this->scrap_web->webserv_call($url_update, $update_json, 'post');

			// Validate
			if($call_update['error'] == FALSE)
			{
				echo 'wassuccessfullyupdated';
			}
			else
			{
				// Return the error message
				$json				        = $call_update['result'];
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
	| ADD PRODUCT POPUP
	|--------------------------------------------------------------------------
	*/
	function add_product_popup()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();

			// Get the definitions
			$url_definitions                = 'catalogitemdefinitions/.jsons?showhostid='.$show_host_id;
			$call_definitions               = $this->scrap_web->webserv_call($url_definitions, FALSE, 'get', FALSE, FALSE);
			$dt_body['definitions']         = $call_definitions;

			if($call_definitions['error'] == FALSE)
			{
				// Definition id
				$json_def_id                    = $call_definitions['result'];

				// Get the definition
				$url_crt_definition             = 'catalogitemdefinitions/.json?id='.$json_def_id->catalog_item_definitions[0]->id;
				$call_crt_definition            = $this->scrap_web->webserv_call($url_crt_definition, FALSE, 'get', FALSE, FALSE);
				$dt_body['crt_definition']      = $call_crt_definition;
			}

			// Get the view
			$this->load->view('products/ajax/add_product_popup', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD PRODUCT POPUP 2
	|--------------------------------------------------------------------------
	*/
	function add_product_popup_2()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();

			// Get the definitions
			$url_definitions                = 'catalogitemdefinitions/.jsons?showhostid='.$show_host_id;
			$call_definitions               = $this->scrap_web->webserv_call($url_definitions, FALSE, 'get', FALSE, FALSE);
			$dt_body['definitions']         = $call_definitions;

			if($call_definitions['error'] == FALSE)
			{
				// Definition id
				$json_def_id                    = $call_definitions['result'];

				// Get the definition
				$url_crt_definition             = 'catalogitemdefinitions/.json?id='.$json_def_id->catalog_item_definitions[0]->id;
				$call_crt_definition            = $this->scrap_web->webserv_call($url_crt_definition, FALSE, 'get', FALSE, FALSE);
				$dt_body['crt_definition']      = $call_crt_definition;
			}

			// Get the view
			$this->load->view('products/ajax/add_product_popup_2', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| UPLOAD PRODUCT IMAGE
	|--------------------------------------------------------------------------
	*/
	function add_product_image()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);

		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$product_id                = $this->input->post('hdProductId');
			//echo $product_id;

			if(isset($_FILES['uploadedFileProductImage']) && !empty($_FILES['uploadedFileProductImage']))
			{
				$document_file			= str_replace(' ', '%20', $_FILES['uploadedFileProductImage']);
			}
			else
			{
				$document_file			= FALSE;
			}

			// Create the banner folder
			$url_sample_path                = 'serverlocalfiles/sample.json';
			$call_sample_path               = $this->scrap_web->webserv_call($url_sample_path);
			$json_sample_path               = $call_sample_path['result'];

			// Edit DOM
			$json_sample_path->path         = 'scrap_products/'.$product_id.'/image';
			$json_new_folder                = json_encode($json_sample_path);

			// Create directory
			$new_directory                  = $this->scrap_web->webserv_call('serverlocalfiles/folder.json', $json_new_folder, 'put');

			// Upload the file
			$url_file_upload                = 'serverlocalfiles/.json?path=scrap_products%2F'.$product_id.'%2Fimage%2F'.$document_file['name'];
			$call_file_upload               = $this->scrap_web->webserv_call($url_file_upload, array('uploadedFile'	=> '@'.$document_file['tmp_name']), 'post', 'multipart_form', FALSE);

			if($call_file_upload['error'] == FALSE)
			{
				echo 'wassuccessfullyuploaded';
			}
			else
			{
				// Return the error message
				$json				        = $call_file_upload['result'];
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
	| UPLOAD TEMPORARY PRODUCT IMAGE
	|--------------------------------------------------------------------------
	*/
	function add_product_image_temp()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);

		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$random_folder                  = $this->scrap_string->random_string_letter();

			// Create the banner folder
			$url_sample_path                = 'serverlocalfiles/sample.json';
			$call_sample_path               = $this->scrap_web->webserv_call($url_sample_path);
			$json_sample_path               = $call_sample_path['result'];

			// Edit DOM
			$json_sample_path->path         = 'scrap_temp/temp_'.$random_folder.'_temp';
			$json_new_folder                = json_encode($json_sample_path);

			// Create directory
			$new_directory                  = $this->scrap_web->webserv_call('serverlocalfiles/folder.json', $json_new_folder, 'put');

			if(isset($_FILES['uploadedFileProductImage']) && !empty($_FILES['uploadedFileProductImage']))
			{
				$document_file			    = str_replace(' ', '%20', $_FILES['uploadedFileProductImage']);

				// Upload the file
				$url_file_upload            = 'serverlocalfiles/.json?path=scrap_temp/temp_'.$random_folder.'_temp/'.$document_file['name'];
				$call_file_upload           = $this->scrap_web->webserv_call($url_file_upload, array('uploadedFile'	=> '@'.$document_file['tmp_name']), 'post', 'multipart_form', FALSE);

				echo $this->config->item('scrap_web_address').'serverlocalfiles/file?path=scrap_temp/temp_'.$random_folder.'_temp/'.$document_file['name'];
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
	| UPLOAD PRODUCT IMAGE
	|--------------------------------------------------------------------------
	*/
	function add_product_image_2()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);

		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$product_id                = $this->input->post('hdProductId2');
			//echo $product_id;

			if(isset($_FILES['uploadedFileProductImage2']) && !empty($_FILES['uploadedFileProductImage2']))
			{
				$document_file			= str_replace(' ', '%20', $_FILES['uploadedFileProductImage2']);

				$url_product_image      = 'serverlocalfiles/.jsons?path=scrap_products%2F'.$product_id.'%2Fimage';
				$call_product_image     = $this->scrap_web->webserv_call($url_product_image, FALSE, 'get', FALSE, FALSE);

				if($call_product_image['error'] == FALSE)
				{
					$json_fastsell_image    = $call_product_image['result'];

					if($json_fastsell_image->is_empty == TRUE)
					{
						// Create the banner folder
						$url_sample_path                = 'serverlocalfiles/sample.json';
						$call_sample_path               = $this->scrap_web->webserv_call($url_sample_path);
						$json_sample_path               = $call_sample_path['result'];

						// Edit DOM
						$json_sample_path->path         = 'scrap_products/'.$product_id.'/image';
						$json_new_folder                = json_encode($json_sample_path);

						// Create directory
						$new_directory                  = $this->scrap_web->webserv_call('serverlocalfiles/folder.json', $json_new_folder, 'put');

						// Upload the file
						$url_file_upload                = 'serverlocalfiles/.json?path=scrap_products%2F'.$product_id.'%2Fimage%2F'.$document_file['name'];
						$call_file_upload               = $this->scrap_web->webserv_call($url_file_upload, array('uploadedFile'	=> '@'.$document_file['tmp_name']), 'post', 'multipart_form', FALSE);
					}
					else
					{
						foreach($json_fastsell_image->server_local_files as $file_info)
						{
							$filename                   = str_replace(' ', '%20', $file_info->name);
							$url_delete                 = 'serverlocalfiles/.json?path=scrap_products%2F'.$product_id.'%2Fimage%2F'.$filename;
							$call_delete                = $this->scrap_web->webserv_call($url_delete, FALSE, 'delete');
						}

						// Upload the file
						$url_file_upload                = 'serverlocalfiles/.json?path=scrap_products%2F'.$product_id.'%2Fimage%2F'.$document_file['name'];
						$call_file_upload               = $this->scrap_web->webserv_call($url_file_upload, array('uploadedFile'	=> '@'.$document_file['tmp_name']), 'post', 'multipart_form', FALSE);
					}

					echo $this->config->item('scrap_web_address').'serverlocalfiles/file?path=scrap_products%2F'.$product_id.'%2Fimage%2F'.$document_file['name'];
					//echo base_url().'scrap_products/'.$product_id.'/image/'.$_FILES['uploadedFileProductImage2']['name'];
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
	| GET PRODUCT FIELDS
	|--------------------------------------------------------------------------
	*/
	function get_product_fields()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$definition_id                  = $this->input->post('definition_id');

			$url_crt_definition             = 'catalogitemdefinitions/.json?id='.$definition_id;
			$call_crt_definition            = $this->scrap_web->webserv_call($url_crt_definition, FALSE, 'get', FALSE, FALSE);
			$dt_body['crt_definition']      = $call_crt_definition;

			// Get the view
			$this->load->view('products/ajax/product_fields', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| GET PRODUCT FIELDS
	|--------------------------------------------------------------------------
	*/
	function get_product_fields_2()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$definition_id                  = $this->input->post('definition_id');

			$url_crt_definition             = 'catalogitemdefinitions/.json?id='.$definition_id;
			$call_crt_definition            = $this->scrap_web->webserv_call($url_crt_definition, FALSE, 'get', FALSE, FALSE);
			$dt_body['crt_definition']      = $call_crt_definition;

			// Get the view
			$this->load->view('products/ajax/product_fields_2', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD PRODUCT
	|--------------------------------------------------------------------------
	*/
	function add_product()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id               = $this->scrap_web->get_show_host_id();
			$user_id                    = $this->session->userdata('sv_user_id');
			$product_number             = $this->input->post('product_number');
			$product_definition         = $this->input->post('product_definition');
			$product_fields_required    = $this->input->post('product_fields_required');
			$product_fields_extra       = $this->input->post('product_fields_extra');

			// JSON sample
			$url_sample                 = 'catalogitems/sample.json';
			$call_sample                = $this->scrap_web->webserv_call($url_sample);

			if($call_sample['error'] == FALSE)
			{
				// Get the result
				$json_sample            = $call_sample['result'];

				// Edit the data
				$json_sample->user->id                      = $user_id;
				$json_sample->show_host_organization->id    = $show_host_id;
				$json_sample->item_number                   = $product_number;
				$json_sample->catalog_item_definition->id   = $product_definition;
				$json_sample->is_fastsell_based             = TRUE;

				$ex_item_fields_required                    = explode('][', $this->scrap_string->remove_flc($product_fields_required));
				$ex_item_fields_extra                       = explode('][', $this->scrap_string->remove_flc($product_fields_extra));
				$ar_field_values                            = array();
				foreach($ex_item_fields_required as $item_field_values)
				{
					$ex_item_field_values   = explode(':', $item_field_values);
					$field_id               = $ex_item_field_values[1];
					$field_value            = $ex_item_field_values[0];

					$url_item_sample        = 'catalogitemfieldvalues/sample.json';
					$call_item_sample       = $this->scrap_web->webserv_call($url_item_sample);
					$json_item_sample       = $call_item_sample['result'];

					$json_item_sample->value                                = $field_value;
					$json_item_sample->show_host_organization->id           = $show_host_id;
					$json_item_sample->catalog_item_definition->id          = $product_definition;
					$json_item_sample->catalog_item_definition_field->id    = $field_id;

					array_push($ar_field_values, $json_item_sample);
				}
				if(!empty($product_fields_extra))
				{
					foreach($ex_item_fields_extra as $item_field_values)
					{
						$ex_item_field_values   = explode(':', $item_field_values);
						$field_id               = $ex_item_field_values[1];
						$field_value            = $ex_item_field_values[0];

						$url_item_sample        = 'catalogitemfieldvalues/sample.json';
						$call_item_sample       = $this->scrap_web->webserv_call($url_item_sample);
						$json_item_sample       = $call_item_sample['result'];

						$json_item_sample->value                                = $field_value;
						$json_item_sample->show_host_organization->id           = $show_host_id;
						$json_item_sample->catalog_item_definition->id          = $product_definition;
						$json_item_sample->catalog_item_definition_field->id    = $field_id;

						array_push($ar_field_values, $json_item_sample);
					}
				}
				$json_sample->catalog_item_field_values     = $ar_field_values;

				// Recode
				$new_json				        = json_encode($json_sample);
				//echo $new_json;

				// Submit the insert
				$new_item                       = $this->scrap_web->webserv_call('catalogitems/.json', $new_json, 'put', FALSE, FALSE);

				// Validate the result
				if($new_item['error'] == FALSE)
				{
					$json_new_item              = $new_item['result'];
					echo 'wassuccessfullycreated::'.$json_new_item->id;
				}
				else
				{
					// Return the error message
					$json				        = $new_item['result'];
					echo $json->error_description;
				}
			}
			else
			{
				// Return the error message
				$json				        = $call_sample['result'];
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
	| ADD PRODUCT AND LINK
	|--------------------------------------------------------------------------
	*/
	function add_product_and_link()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id               = $this->scrap_web->get_show_host_id();
			$user_id                    = $this->session->userdata('sv_user_id');
			$product_number             = $this->input->post('product_number');
			$product_stock              = $this->input->post('product_stock');
			$product_price              = $this->input->post('product_price');
			$product_definition         = $this->input->post('product_definition');
			$product_fields_required    = $this->input->post('product_fields_required');
			$product_fields_extra       = $this->input->post('product_fields_extra');

			// JSON sample
			$url_sample                 = 'catalogitems/sample.json';
			$call_sample                = $this->scrap_web->webserv_call($url_sample);

			if($call_sample['error'] == FALSE)
			{
				// Get the result
				$json_sample            = $call_sample['result'];

				// Edit the data
				$json_sample->user->id                      = $user_id;
				$json_sample->show_host_organization->id    = $show_host_id;
				$json_sample->item_number                   = $product_number;
				$json_sample->catalog_item_definition->id   = $product_definition;
				$json_sample->is_fastsell_based             = TRUE;

				$ex_item_fields_required                    = explode('][', $this->scrap_string->remove_flc($product_fields_required));
				$ex_item_fields_extra                       = explode('][', $this->scrap_string->remove_flc($product_fields_extra));
				$ar_field_values                            = array();
				foreach($ex_item_fields_required as $item_field_values)
				{
					$ex_item_field_values   = explode(':', $item_field_values);
					$field_id               = $ex_item_field_values[1];
					$field_value            = $ex_item_field_values[0];

					$url_item_sample        = 'catalogitemfieldvalues/sample.json';
					$call_item_sample       = $this->scrap_web->webserv_call($url_item_sample);
					$json_item_sample       = $call_item_sample['result'];

					$json_item_sample->value                                = $field_value;
					$json_item_sample->show_host_organization->id           = $show_host_id;
					$json_item_sample->catalog_item_definition->id          = $product_definition;
					$json_item_sample->catalog_item_definition_field->id    = $field_id;

					array_push($ar_field_values, $json_item_sample);
				}
				if(!empty($product_fields_extra))
				{
					foreach($ex_item_fields_extra as $item_field_values)
					{
						$ex_item_field_values   = explode(':', $item_field_values);
						$field_id               = $ex_item_field_values[1];
						$field_value            = $ex_item_field_values[0];

						$url_item_sample        = 'catalogitemfieldvalues/sample.json';
						$call_item_sample       = $this->scrap_web->webserv_call($url_item_sample);
						$json_item_sample       = $call_item_sample['result'];

						$json_item_sample->value                                = $field_value;
						$json_item_sample->show_host_organization->id           = $show_host_id;
						$json_item_sample->catalog_item_definition->id          = $product_definition;
						$json_item_sample->catalog_item_definition_field->id    = $field_id;

						array_push($ar_field_values, $json_item_sample);
					}
				}
				$json_sample->catalog_item_field_values     = $ar_field_values;

				// Recode
				$new_json				        = json_encode($json_sample);

				// Submit the insert
				$new_item                       = $this->scrap_web->webserv_call('catalogitems/.json', $new_json, 'put', FALSE, FALSE);

				// Validate the result
				if($new_item['error'] == FALSE)
				{
					// New item
					$json_new_item                  = $new_item['result'];

					// Link the product
					$product_id                     = $json_new_item->id;
					$event_id                       = $this->session->userdata('sv_show_set');

					// Get fastsell definition
					$url_fs_definition              = 'fastsellitemdefinitions/.jsons?catalogitemid='.$product_id.'&fastselleventid='.$event_id;
					$call_fs_definition             = $this->scrap_web->webserv_call($url_fs_definition, FALSE, 'get', FALSE, FALSE);

					if($call_fs_definition['error'] == FALSE)
					{
						// Get definition
						$fs_definition                  = $call_fs_definition['result']->fastsell_item_definitions[0]->id;

						// Sample
						$url_sample                     = 'fastsellitems/sample.json';
						$call_sample                    = $this->scrap_web->webserv_call($url_sample, FALSE, 'get', FALSE, FALSE, TRUE);

						if($call_sample['error'] == FALSE)
						{
							$json_sample                                    = $call_sample['result'];

							// Edit JSON
							$json_sample['price']                           = $product_price;
							$json_sample['user']['id']                      = $user_id;
							$json_sample['stock_count']                     = $product_stock;
							$json_sample['catalog_item']['id']              = $product_id;
							$json_sample['fastsell_event']['id']            = $event_id;
							$json_sample['fastsell_item_field_values']      = null;
							$json_sample['fastsell_item_definition']['id']  = $fs_definition;

							// Recode
							$json_create_product		                    = json_encode($json_sample);

							// Create product
							$new_fastsell_product   = $this->scrap_web->webserv_call('fastsellitems/.json', $json_create_product, 'put', FALSE, FALSE);
						}
					}

					echo 'wassuccessfullycreated::'.$json_new_item->id;
				}
				else
				{
					// Return the error message
					$json				        = $new_item['result'];
					echo $json->error_description;
				}
			}
			else
			{
				// Return the error message
				$json				        = $call_sample['result'];
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
	| GET PRODUCTS
	|--------------------------------------------------------------------------
	*/
	function get_products()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();

			// Get the products
			$offset                         = 0;
			$limit                          = 20;
			$search_text                    = '';

			$dt_body['search_text']         = $search_text;
			$dt_body['offset']              = $offset;
			$dt_body['limit']               = $limit;

			// Make the call
			$url_products                   = 'catalogitems/.jsons?showhostid='.$show_host_id.'&searchtext='.$search_text.'&limit='.$limit.'&offset='.$offset;
			$call_products                  = $this->scrap_web->webserv_call($url_products, FALSE, 'get', FALSE, FALSE);
			$dt_body['products']            = $call_products;

			// Get the definitions
			$url_definitions                = 'catalogitemdefinitions/.jsons?showhostid='.$show_host_id;
			$call_definitions               = $this->scrap_web->webserv_call($url_definitions, FALSE, 'get', FALSE, FALSE);
			$dt_body['definitions']         = $call_definitions;

			// Load the view
			$this->load->view('products/products_list', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| CHECK PRODUCTS UPLOAD
	|--------------------------------------------------------------------------
	*/
	function check_products_upload()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();
			$def_id                         = $this->input->post('dropItemDefinitions');
			//echo $def_id;

			if(isset($_FILES['uploadedFile']) && !empty($_FILES['uploadedFile']))
			{
				$document_file			= str_replace(' ', '%20', $_FILES['uploadedFile']);
			}
			else
			{
				$document_file			= FALSE;
			}

			// Submit the file
			$url_file_convert           = 'masterdata/multipartmasterdata.xls';
			$call_file_convert          = $this->scrap_web->webserv_call($url_file_convert, array('uploadedFile'	=> '@'.$document_file['tmp_name']), 'post', 'multipart_form');
			$json_file_convert          = $call_file_convert['result'];
			$encode_file_convert        = json_encode($json_file_convert);
			//echo $encode_file_convert;

			// Submit the JSON
			$url_file_check             = 'catalogitems/masterdata.json?showhostid='.$show_host_id.'&validateinnercontentonly=true&catalogitemdefid='.$def_id;
			$call_file_check            = $this->scrap_web->webserv_call($url_file_check, $encode_file_convert, 'put', FALSE, FALSE);
			$dt_body['row_check']       = $call_file_check;
			//echo json_encode($call_file_check['result']);

			// Load the view
			$this->load->view('products/check_rows_products', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| UPLOAD PRODUCTS
	|--------------------------------------------------------------------------
	*/
	function upload_products()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();
			$headers                        = $this->scrap_string->remove_flc($this->input->post('headers'));
			$rows                           = $this->scrap_string->remove_flc($this->input->post('rows'));
			$definition_id                  = $this->input->post('definition_id');
			$ex_headers                     = explode('][', $headers);
			$ex_rows                        = explode('][', $rows);

			// Top level JSON
			$ar_json                        = array('id' => null, 'message' => null, 'worksheets' => '', 'error_code' => null, 'error_description' => null, 'type_name' => 'workbook');

			// Worksheet JSON
			$ar_worksheets                  = array();
			$ar_worksheet                   = array('id' => null, 'message' => null, 'headers' => '', 'rows' => '', 'error_code' => null, 'error_description' => null, 'type_name' => 'sheet', 'sheet_name' => 'Customer Create Master Data');

			// Header JSON
			$ar_header                      = array();
			foreach($ex_headers as $header)
			{
				array_push($ar_header, $header);
			}

			// Rows array
			$ar_rows                        = array();
			foreach($ex_rows as $row_item)
			{
				// Some variables
				$columns                    = $this->scrap_string->remove_flc($row_item);
				$ex_columns                 = explode('}{', $columns);
				$ar_row                     = array('id' => null, 'message' => null, 'columns' => '', 'error_code' => -1, 'error_description' => null, 'type_name' => 'row');
				$ar_columns                 = array();

				// Set the columns
				foreach($ex_columns as $column_item)
				{
					$ar_column              = array('id' => null, 'message' => null, 'value' => $column_item, 'error_code' => null, 'error_description' => null, 'type_name' => 'column');
					array_push($ar_columns, $ar_column);
				}

				// Push the columns and rows into the arrays
				$ar_row['columns']          = $ar_columns;
				array_push($ar_rows, $ar_row);
			}

			// Generate the JSON
			$ar_worksheet['rows']           = $ar_rows;
			$ar_worksheet['headers']        = $ar_header;
			array_push($ar_worksheets, $ar_worksheet);
			$ar_json['worksheets']          = $ar_worksheets;
			$json_items                     = json_encode($ar_json);
			//echo $json_items;

			// Submit the JSON
			$url_insert_items               = 'catalogitems/masterdata.json?showhostid='.$show_host_id.'&validateinnercontentonly=false&catalogitemdefid='.$definition_id;
			$call_insert_items              = $this->scrap_web->webserv_call($url_insert_items, $json_items, 'put', FALSE, FALSE);
			$dt_body['row_check']           = $call_insert_items;


			// Load the view
			$this->load->view('show_host/error_rows_products', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD PRODUCTS POPUP
	|--------------------------------------------------------------------------
	*/
	function add_products_popup()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();

			// Get the products
			$url_products                   = 'catalogitems/.jsons?showhostid='.$show_host_id;
			$call_products                  = $this->scrap_web->webserv_call($url_products, FALSE, 'get', FALSE, FALSE);
			$dt_body['products']            = $call_products;

			// Get the view
			$this->load->view('products/ajax/add_products_popup', $dt_body);
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
			$limit                          = 20;
			$offset                         = 0;
			$dt_body['offset']              = $offset;
			$dt_body['limit']               = $limit;

			// Get fastsell products
			$url_fs_products                = 'fastsellitems/.jsons?fastselleventid='.$event_id.'&includevalues=true&includecatalogvalues=true&offset='.$offset.'&limit='.$limit;
			$call_fs_products               = $this->scrap_web->webserv_call($url_fs_products, FALSE, 'get', FALSE, FALSE);
			$dt_body['products']            = $call_fs_products;

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
	| REFRESH ADDED PRODUCT LIST
	|--------------------------------------------------------------------------
	*/
	function get_added_products_2()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$event_id                       = $this->input->post('event_id');
			$limit                          = 5;
			$offset                         = 0;

			// Get fastsell products
			$url_fs_products                = 'fastsellitems/.jsons?fastselleventid='.$event_id.'&includevalues=true&includecatalogvalues=true&offset='.$offset.'&limit='.$limit;
			$call_fs_products               = $this->scrap_web->webserv_call($url_fs_products, FALSE, 'get', FALSE, FALSE);
			$dt_body['products']            = $call_fs_products;

			$dt_body['limit']               = $limit;
			$dt_body['offset']              = $offset;

			// Load the view
			$this->load->view('products/fastsell_products_list_small', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD CUSTOMERS VIA MASTER DATA FILE POPUP
	|--------------------------------------------------------------------------
	*/
	function add_master_data_file_popup()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$fastsell_id                    = $this->session->userdata('sv_show_set');

			// Get the definitions
			$url_definitions                = 'fastsellitemdefinitions/.jsons?fastselleventid='.$fastsell_id;
			$call_definitions               = $this->scrap_web->webserv_call($url_definitions, FALSE, 'get', FALSE, FALSE);
			$dt_body['item_defs']           = $call_definitions;

			// Get the view
			$this->load->view('products/ajax/add_products_via_master_data_popup', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD PRODUCTS VIA MASTER DATA FILE POPUP
	|--------------------------------------------------------------------------
	*/
	function add_master_data_file_popup_2()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$fastsell_id                    = $this->session->userdata('sv_show_set');

			// Get the definitions
			$url_definitions                = 'fastsellitemdefinitions/.jsons?fastselleventid='.$fastsell_id;
			$call_definitions               = $this->scrap_web->webserv_call($url_definitions, FALSE, 'get', FALSE, FALSE);
			$dt_body['item_defs']           = $call_definitions;

			// Get the view
			$this->load->view('products/ajax/add_products_via_master_data_popup_2', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}



	/*
	|--------------------------------------------------------------------------
	| ADD PRODUCTS VIA MASTER DATA FILE POPUP
	|--------------------------------------------------------------------------
	*/
	function add_master_data_file_popup_3()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$fastsell_id                    = $this->session->userdata('sv_show_set');

			// Get the definitions
			$url_definitions                = 'fastsellitemdefinitions/.jsons?fastselleventid='.$fastsell_id;
			$call_definitions               = $this->scrap_web->webserv_call($url_definitions, FALSE, 'get', FALSE, FALSE);
			$dt_body['item_defs']           = $call_definitions;

			// Get the view
			$this->load->view('products/ajax/add_products_via_master_data_popup_2', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| LINK / CREATE PRODUCTS VIA MASTER DATA FILE
	|--------------------------------------------------------------------------
	*/
	function products_master_data_upload()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);

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

			// Link / Create products
			$url_link                   = 'fastsellitems/masterdata.json?showhostid='.$show_host_id.'&fastselleventid='.$fastsell_id.'&fastsellitemdefid='.$product_definition.'&validateinnercontentonly=false';
			$call_link                  = $this->scrap_web->webserv_call($url_link, $encode_file_convert, 'put', FALSE, FALSE);

			// Validate the result
			if($call_link['error'] == FALSE)
			{
				echo 'wassuccessfullyuploaded';
			}
			else
			{
				// Return the error message
				$json				        = $call_link['result'];
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
	| UPDATE PRODUCT FIELDS
	|--------------------------------------------------------------------------
	*/
	function update_product_fields()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id               = $this->scrap_web->get_show_host_id();
			$product_id                 = $this->input->post('product_id');
			$product_number             = $this->input->post('product_number');
			$product_fields             = $this->input->post('product_fields');
			$ar_fields                  = array();

			// Get the product
			$url_product                = 'catalogitems/.json?id='.$product_id;
			$call_product               = $this->scrap_web->webserv_call($url_product, FALSE, 'get', FALSE, FALSE);
			$json_product               = $call_product['result'];
			$definition_id              = $json_product->catalog_item_definition->id;

			// Explode fields
			$ex_fields                  = explode('][', $this->scrap_string->remove_flc($product_fields));
			foreach($ex_fields as $field_info)
			{
				$ex_field_info                      = explode('::', $field_info);
				$def_field_id                       = $ex_field_info[0];
				$def_value                          = $ex_field_info[1];

				$ar_catalog_item_fv                 = array();

				if($def_value != '')
				{
					$ar_catalog_item_fv['value']                            = $def_value;
					$ar_catalog_item_fv['show_host_organization']           = array('id' => $show_host_id);
					$ar_catalog_item_fv['catalog_item_definition']          = array('id' => $definition_id);
					$ar_catalog_item_fv['catalog_item_definition_field']    = array('id' => $def_field_id);

					array_push($ar_fields, $ar_catalog_item_fv);
				}
			}

			$json_product->catalog_item_field_values    = $ar_fields;

			$json_product->is_fastsell_based            = TRUE;
			$json_product->item_number                  = $product_number;

			// Encode again
			$json_update                = json_encode($json_product);

			// Update
			$call_product               = $this->scrap_web->webserv_call('catalogitems/.json', $json_update, 'post');

			// Validate the result
			if($call_product['error'] == FALSE)
			{
				echo 'wassuccessfullyupdated';
			}
			else
			{
				// Return the error message
				$json				        = $call_product['result'];
				echo $json->error_description;
			}
		}
		else
		{
			echo 9876;
		}
	}
}

/* End of file ajax_handler_products.php */
/* Location: scrap_engine/controllers/ajax_handler_products.php */