<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->auth->check();
	}

	public function index()
	{
		if (!$this->form_validation->run('login')) {
			$this->temp->set('title', 'Login');
			$this->temp->load('template/auth', 'auth/v_login');
		} else {
			$this->_authenticate($this->input->post());
		}
	}

	private function _authenticate($credentials)
	{
		if ($this->auth->attempt($credentials)) {
			redirect(base_url('dashboard'));
		} else {
			redirect(base_url('login'));
		}
	}
}
