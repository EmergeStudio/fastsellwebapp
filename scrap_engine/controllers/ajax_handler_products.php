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

				$ar_field_info                  = array('field_name' => 'MSRP', 'field_type' => array('id' => 2));
				array_push($ar_fields, $ar_field_info);

				$ar_field_info                  = array('field_name' => 'Pack & Size', 'field_type' => array('id' => 2));
				array_push($ar_fields, $ar_field_info);

				$ar_field_info                  = array('field_name' => 'Expiry Date', 'field_type' => array('id' => 2));
				array_push($ar_fields, $ar_field_info);

				$ar_field_info                  = array('field_name' => 'Shelf Date', 'field_type' => array('id' => 2));
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
			$product_fields             = $this->input->post('product_fields');

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

				$ex_item_fields             = explode('][', $this->scrap_string->remove_flc($product_fields));
				$ar_field_values            = array();
				foreach($ex_item_fields as $item_field_values)
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
				$json_sample->catalog_item_field_values     = $ar_field_values;

				// Recode
				$new_json				        = json_encode($json_sample);
				//echo $new_json;

				// Submit the insert
				$new_item                       = $this->scrap_web->webserv_call('catalogitems/.json', $new_json, 'put', FALSE, FALSE);

				// Validate the result
				if($new_item['error'] == FALSE)
				{
					echo 'wassuccessfullycreated';
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
			$url_products                   = 'catalogitems/.jsons?showhostid='.$show_host_id;
			$call_products                  = $this->scrap_web->webserv_call($url_products, FALSE, 'get', FALSE, FALSE);
			$dt_body['products']            = $call_products;

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
}

/* End of file ajax_handler_products.php */
/* Location: scrap_engine/controllers/ajax_handler_products.php */