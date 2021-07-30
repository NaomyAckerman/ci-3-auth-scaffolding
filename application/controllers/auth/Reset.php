<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reset extends CI_Controller
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
        if ($email && $token) {
            // cek valid email and token (return bolean)
            if ($this->auth->emailVerify($email, $token, true)) {
                $this->_changePassword();
            } else {
                redirect(base_url('login'));
            }
        } else {
            show_error('You are not authorized to view this page!', 401, 'Unauthorized');
        }
    }
    private function _changePassword()
    {
        if (!$this->form_validation->run('reset')) {
            $this->temp->set('title', 'Reset Password');
            $this->temp->load('template/auth', 'auth/v_reset');
        } else {
            $this->user->update([
                'password'      => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'reset_token'   => null,
                'reset_at'      => mdate('%Y-%m-%d %H:%i:%s', now()),
            ], $this->session->flashdata('user_id'));
            alert("Successfully changed password", 'success');
            redirect(base_url('login'));
        }
    }
}

/* End of file Reset.php */
