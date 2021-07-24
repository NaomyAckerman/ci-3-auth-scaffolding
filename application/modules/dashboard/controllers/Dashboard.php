<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		// check auth must login
		$this->auth->check('auth');
	}

	public function index()
	{
		$this->temp->set('title', 'Dashboard');
		$this->temp->load('template/index', 'v_dashboard');
	}
}
