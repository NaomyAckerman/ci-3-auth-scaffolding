<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->check();
	}

	public function index()
	{
		if (!$this->form_validation->run('register')) {
			$this->temp->set('title', 'Register');
			$this->temp->load('template/auth', 'auth/v_register');
		} else {
			$this->_registered($this->input->post());
		}
	}

	private function _registered($credentials)
	{
		if ($this->auth->prepare([
			'name' 		=> $credentials['name'],
			'email' 	=> $credentials['email'],
			'password' 	=> password_hash($credentials['password'], PASSWORD_BCRYPT),
		])) {
			redirect(base_url('login'));
		} else {
			redirect(base_url('register'));
		}
	}
}
