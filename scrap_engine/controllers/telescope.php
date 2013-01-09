<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TELESCOPE Controller
 *
 * This controller handles all AJAX requests for telescope.
 *
 * This controller does not run through the AJAX handler
 * and is designed to revieve the image value, convert the
 * images into a usable format and return the images back
 * to the viewer
 * 
 * @author	Chris Humboldt (http://www.chrismodem.com)
 * @link		http://www.scrappyworx.com/
 */
 
class Telescope extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOAD LIBRARY -------------------------------
		$this->load->library('image_lib');
	}


	/*
	|--------------------------------------------------------------------------
	| INDEX Function
	|--------------------------------------------------------------------------
	|
	| Direct navigation through the url is not allowed.
	|
	*/
	function index()
	{
		redirect('admin/login');
	}


	/*
	|--------------------------------------------------------------------------
	| LOAD VIEWER
	|--------------------------------------------------------------------------
	|
	| Load the Telescope Viewer view
	|
	| Get an image from request.  If the image is in TIF format convert it to 
	| JPG for browser viewing.  This is done to prevent the need for plugins 
	| and to keep up with HTML and CSS compliance.
	|
	*/
	function load_viewer()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{	
			// Delete current files
			$this->delete_temp_files('yup_delete_it');
			
			// Some variables
			$dt_body['height']				= $this->input->post('height');
			$dt_body['width']				= $this->input->post('width');
			$doc_id							= $this->input->post('doc_id');
			$attach_id						= $this->input->post('attach_id');
			$attach_filename				= $this->input->post('attach_filename');
			$attach_ext						= $this->scrap_string->get_extension($attach_filename);
			$user_dir						= $this->scrap_web->get_user_dir(FALSE, 'local');
			$user_dir_web					= $this->scrap_web->get_user_dir();
			$img_loop						= 0;
			$img_src_result					= '';
			$java_app_root					= $this->config->item('java_root');
			$info_open						= TRUE;
			
			// Get the document
			$attach_url						= 'attachments/download?id='.$attach_id;
			$attach_return					= $this->scrap_web->byte_call($attach_url);
			
			// Check extension
			if(($attach_ext == 'tif') || ($attach_ext == 'tiff'))
			{
				// Must be converted
				$dest_dir					= $user_dir.'/to_be_converted/';
				file_put_contents($dest_dir.$attach_filename, $attach_return['result']);
				
				// Convert the image
				$tiff_src					= $dest_dir;
				$png_dest					= $user_dir.'/telescope_view';
				$cmd	= "java -jar ".$java_app_root."/tiffto.jar -s ".$tiff_src.$attach_filename." -t png -d ".$png_dest;
				@exec($cmd);
			}
			else
			{
				// Dump in the the telescope directory
				$dest_dir					= $user_dir.'/telescope_view/';
				file_put_contents($dest_dir.$attach_filename, $attach_return['result']);
			}
			
			// Get all the images in the view directory
			$telescope_images		= get_filenames($user_dir.'/telescope_view/');
			asort($telescope_images);
				
			// Display the desired page
			foreach($telescope_images as $telescope_image)
			{
				// Image loop for class variable
				$img_loop++;
				
				if($img_loop == 1)
				{
					$class  				= 'imgDisplay scrapImg';
				}
				else
				{
					$class					= 'displayNone scrapImg';
				}
				
				// Image source
				$img_source					= $user_dir_web.'/telescope_view/'.$telescope_image;
					
				$img_src_result .= '<img class="'.$class.'" src="'.$img_source.'" onselectstart="return false;" ondragstart="return false;" />';
			}
			
 			// Set the variables
			$dt_body['images']				= $img_src_result;
			$dt_body['cnt_images']			= $img_loop;
			$dt_body['info_open']			= $info_open;	
			
			// Get the document
			$url_doc						= 'documents/xml?id='.$doc_id;
			$document						= $this->scrap_web->webserv_call($url_doc);
			
			// XML
			$doc_xml				= new SimpleXMLElement($document['result']);

			// Get the document type
			$url_doc_type			= 'documenttypes/xml?id='.$doc_xml->document_type['id'];
			$doc_type				= $this->scrap_web->webserv_call($url_doc_type);
			$doc_type_xml			= new SimpleXMLElement($doc_type['result']);

			// Set the view variables
			$dt_body['doc_xml']		= $doc_xml;
			$dt_body['doc_type']	= $doc_type_xml;
			
			// Load the view
			$this->load->view('telescope/telescope_view', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| DOWNLOAD VIEWER FILES
	|--------------------------------------------------------------------------
	*/
	function download_viewer_files()
	{
		// Login check
		$this->scrap_wall->login_check('check');
		
		// Some loads
		$this->load->library('zip');
		
		// Some variable
		$user_dir						= $this->scrap_web->get_user_dir(FALSE, 'local');
		$viewer_dir						= $user_dir.'/telescope_view/';

		// Zip the viewer directory
		$this->zip->read_dir($viewer_dir, FALSE);
		
		// Download zip
		$this->zip->download('document_download.zip');
	}


	/*
	|--------------------------------------------------------------------------
	| DOWNLOAD DOCUMENT
	|--------------------------------------------------------------------------
	*/
	function download_document()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{	
			// Delete current files
			$this->delete_temp_files('yup_delete_it');
			
			// Some variables
			$dt_body['height']				= $this->input->post('height');
			$dt_body['width']				= $this->input->post('width');
			$attach_id						= $this->input->post('attach_id');
			$attach_filename				= $this->input->post('attach_filename');
			$attach_ext						= $this->scrap_string->get_extension($attach_filename);
			$user_dir						= $this->scrap_web->get_user_dir(FALSE, 'local');
			$user_dir_web					= $this->scrap_web->get_user_dir();
			
			// Get the document
			$attach_url						= 'attachments/download?id='.$attach_id;
			$attach_return					= $this->scrap_web->byte_call($attach_url);
			
			// Dump in the the download directory
			$dest_dir					= $user_dir.'/download/';
			file_put_contents($dest_dir.$attach_filename, $attach_return['result']);
			
			// Return
			echo $user_dir_web.'/download/'.$attach_filename;
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| DELETE TEMP IMAGES Function
	|--------------------------------------------------------------------------
	|
	| Delete all teh temporary files stored in the image_view folder for the
	| users specific account.
	|
	*/
	function delete_temp_files($do_delete)
	{
		if(($this->input->post('check') == 'yupdeleteitbuddy') || ($do_delete == 'yup_delete_it'))
		{
			// Some variables
			$user_dir					= $this->scrap_web->get_user_dir(FALSE, 'local');
			
			// Delete all the files in the viewing folder ----- I dont like peanuts !
			delete_files($user_dir.'/telescope_view', TRUE);
			delete_files($user_dir.'/to_be_converted', TRUE);
		}
	}
}
?>