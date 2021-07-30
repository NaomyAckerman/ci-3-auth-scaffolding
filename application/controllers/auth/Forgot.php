<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Forgot extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->check();
        $this->load->model('user');
    }

    public function index()
    {
        if (!$this->form_validation->run('forgot')) {
            $this->temp->set('title', 'Forgot Password');
            $this->temp->load('template/auth', 'auth/v_forgot');
        } else {
            $this->auth->sendResetLink($this->input->post());
            redirect('forgot');
        }
    }
}

/* End of file Forgot.php */
