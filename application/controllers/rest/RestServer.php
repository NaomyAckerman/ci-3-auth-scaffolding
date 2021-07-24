<?php

defined('BASEPATH') or exit('No direct script access allowed');

class RestServer extends CI_Controller
{

	public function index()
	{
		$this->load->view('rest/v_restserver');
	}
}
