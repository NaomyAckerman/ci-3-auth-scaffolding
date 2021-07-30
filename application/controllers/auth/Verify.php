<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Verify extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->check();
    }
    public function index()
    {
        $token = $this->input->get('token');
        $email = $this->input->get('email');
        if ($token && $email) {
            $this->auth->emailVerify($email, $token);
            $this->temp->load('template/auth', 'auth/v_verify', [
                'title' => 'User Activation'
            ]);
        } else {
            show_error('You are not authorized to view this page!', 401, 'Unauthorized');
        }
    }
}

/* End of file Verify.php */
