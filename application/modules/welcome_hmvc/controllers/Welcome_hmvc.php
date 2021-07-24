<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome_hmvc extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->temp->set('title', 'Welcome to CodeIgniter');
		$this->temp->load('template/index', 'v_welcome', [
			'message' 	=> 'make app work in modules folder and enjoy coding:',
			'header'	=> 'Welcome to CodeIgniter! HMVC',
		]);
	}
}
