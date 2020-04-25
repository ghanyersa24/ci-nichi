<?php
defined('BASEPATH') or exit('No direct script access allowed');
class UPLOAD_FILE
{
	public static function img($post_name, $location = null, $file_name = null)
	{
		$CI = &get_instance();
		if (!is_null($location))
			$config['upload_path'] = "./uploads/$location";
		else {
			$location = 'untitles';
			$config['upload_path'] = "./uploads/untitles";
		}
		if (!is_null($file_name))
			$config['file_name'] = date('Y-m-d H i') . ' ' . $file_name;
		else
			$config['encrypt_name'] = true;

		$config['allowed_types'] = 'gif|jpg|png|JPG|jpeg';

		$CI->load->library('upload', $config);
		$CI->upload->initialize($config);

		$upload_status = $CI->upload->do_upload($post_name);
		$upload_message = strip_tags($CI->upload->display_errors());
		$upload_location = base_url() . "uploads/$location/" . $CI->upload->data("file_name");
		if ($upload_status)
			return $upload_location;
		else
			error($upload_message);
	}

	// ------------------------------------------------------------------------
}

/* End of file upload_file.php */
/* Location: ./application/libraries/upload_file.php */
