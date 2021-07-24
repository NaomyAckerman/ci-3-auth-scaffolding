<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{
	public function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->model('user');
		$this->CI->config->load('auth');
	}

	// Login Method
	public function attempt($credentials)
	{
		$email = $credentials['email'];
		$password = $credentials['password'];
		$user = $this->CI->user->find_where(['email' => $email])->row();
		if ($user) {
			if (password_verify($password, $user->password)) {
				if ($user->status == 1) {
					$this->CI->user->update([
						'user_platform' => $this->CI->agent->platform(),
						'user_browser' 	=> "{$this->CI->agent->browser()} {$this->CI->agent->version()}"
					], $user->id);
					$this->CI->session->set_userdata([
						'id' 		=> $user->id,
						'logged_in' => true
					]);
					return true;
				} else {
					return alert("Your account has not been activated, please check your email", "error");
				}
			} else {
				return alert("Your password is wrong, please check your credentials", "error");
			}
		} else {
			return alert("Your email is not registered, please sign-up", "error");
		}
	}

	// Register Method
	public function prepare($credentials)
	{
		if ($this->CI->config->item('user_active')) {
			$token 	= substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(32))), 0, 32);
			$url 	= base_url("login?token={$token}&email={$credentials['email']}");
			$html 	= "<p>Click this link to verify your account</p><br><a href='{$url}'>Activated now</a>";
			$send 	= $this->_send_email($credentials['email'], 'User Activation', $html);
			if (!$send) {
				return alert('failed to register user data, email is having problems', 'error');
			}
			$credentials['email_verified_token'] = $token;
		} else {
			$credentials['status'] = 1;
		}
		$result = $this->CI->user->save($credentials);
		$default_role = $this->CI->config->item('default_role') ? $this->CI->config->item('default_role') : null;
		$role_data = $this->CI->db->get_where('users_roles', ['role' => $default_role])->row();
		$role_post = $this->CI->input->post('role_id');
		if ($role_post || $role_data) {
			$user_id = $result;
			$role_id = $role_post ?? $role_data->id;
			$this->set_role($user_id, $role_id);
		}
		if ($result) {
			$this->CI->config->item('user_active') ?
				alert('User successfully registered, Check your email for activation', 'success') :
				alert('Successfully registered user', 'success');
			return true;
		}
		return alert('failed to register user data', 'error');
	}

	// Temp send email
	private function _send_email($user_email, $subject, $message)
	{
		$config = [
			'mailtype'  	=> 'html',
			'charset'   	=> 'utf-8',
			'wordwrap' 		=> true,
			'protocol'  	=> 'smtp',
			'smtp_host' 	=> 'smtp.gmail.com',
			'smtp_user' 	=> $this->CI->config->item('email'),
			'smtp_pass'   	=> $this->CI->config->item('email_pass'),
			'smtp_crypto' 	=> 'ssl',
			'smtp_port'   	=> 465,
			'crlf'    		=> "\r\n",
			'newline' 		=> "\r\n"
		];
		$this->CI->email->initialize($config);
		$this->CI->email->from($this->CI->config->item('email'), $this->CI->config->item('app_name'));
		$this->CI->email->to($user_email);
		$this->CI->email->subject($subject);
		$this->CI->email->message($message);
		if ($this->CI->email->send()) {
			return true;
		}
		return false;
	}

	public function verify($email, $token)
	{
		$user = $this->CI->user->find_where(['email' => $email])->row();
		if ($user) {
			if ($user->email_verified_token == $token) {
				$start_date 	= new DateTime($user->created_at);
				$since_start 	= $start_date->diff(new DateTime(date('Y-m-d h:i:s', now())));
				if ($since_start->d < 1) {
					$this->CI->user->update([
						'status' => 1,
						'email_verified_token' => null,
						'email_verified_at' => mdate('%Y-%m-%d %H:%i:%s', now())
					], $user->id);
					return alert('account activated successfully', 'success');
				} else {
					$this->CI->user->delete(['id' => $user->id]);
					return alert('activation token has expired', 'error');
				}
			} else {
				return alert('Invalid activation token', 'error');
			}
		} else {
			return alert('Invalid activation email', 'error');
		}
	}

	// Logout Method
	public function destroy()
	{
		$this->CI->user->update([
			'user_platform' => null,
			'user_browser' => null,
		], $this->CI->session->userdata('id'));
		$this->CI->session->unset_userdata(['id', 'logged_in']);
	}

	// Get User Login
	public function user()
	{
		$user_id = $this->CI->session->userdata('id');
		return ($user_id) ? $this->CI->user->get($user_id)->row() : false;
	}

	// ==================================== Auth method ====================================
	public function check($mode = null, $default_route = null)
	{
		// Check access method must login
		if ($mode == 'auth') {
			// if Not login
			if (!$this->CI->session->userdata('logged_in')) {
				redirect(base_url($default_route ? $default_route : 'login'));
			}
		} else {
			// if has logged in
			if ($this->CI->session->userdata('logged_in')) {
				$default_role_routes = $this->CI->db->get('users_roles')->result();
				$current_role = $this->get_role();
				foreach ($default_role_routes as $routes) {
					if ($current_role->role == $routes->role) {
						redirect(base_url($routes->default_url));
					}
				}
				redirect(base_url($default_route));
			}
		}
	}

	public function logged_in()
	{
		return $this->CI->session->userdata('logged_in') ? true : false;
	}
	// =====================================================================================

	// ==================================== Roles method ====================================
	public function has_role($role)
	{
		$in_group = $this->get_role($role);
		if ($in_group) {
			return true;
		}
		return false;
	}

	public function get_role($role = null)
	{
		$this->CI->db
			->join('users_roles', 'users_roles.id = users_roles_groups.role_id')
			->where('user_id', $this->CI->session->userdata('id'));
		$role ? $this->CI->db->where('users_roles.role', $role) : null;
		return $this->CI->db->get('users_roles_groups')->row();
	}

	public function set_role($user_id, $role_id)
	{
		$this->CI->db->insert('users_roles_groups', [
			'user_id' => $user_id,
			'role_id' => $role_id,
		]);
	}
	// =====================================================================================
}
