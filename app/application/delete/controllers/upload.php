<?php

class Upload extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}

	function index()
	{
		$this->load->view('upload_forms', array('error' => ' ' ));
	}

	function do_upload()
	{
		$config['upload_path'] = './upload/cell/';
		$config['allowed_types'] = 'gif|jpg|png|tif';
		$config['max_size']	= '1000';
		$config['max_width']  = '2024';
		$config['max_height']  = '1768';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());

			$this->load->view('upload_forms', $error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());

			$this->load->view('upload_success', $data);
		}
	}
}
?>