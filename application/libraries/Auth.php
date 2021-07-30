<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{
	public function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->config->load('auth');
		$this->CI->load->model('user');
	}

	//* Login Method
	public function attempt($credentials)
	{
		$default_login = $this->CI->config->item('default_login');
		$user_credential = $credentials[$default_login];
		$password_credential = $credentials['password'];
		$user = $this->CI->user->find_where([$default_login => $user_credential])->row();
		if ($user) {
			if (password_verify($password_credential, $user->password)) {
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
			return alert("Your {$default_login} is not registered, please sign-up", "error");
		}
	}

	//* Register Method
	public function prepare($credentials)
	{
		if ($this->CI->config->item('user_active')) {
			$token 	= substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(32))), 0, 32);
			$url 	= base_url("auth/verify?token={$token}&email={$credentials['email']}");
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

	//* Forgot Method
	public function sendResetLink($credentials)
	{
		$email = $credentials['email'];
		$user = $this->CI->user->find_where(['email' => $email])->row();
		if ($user) {
			$token 	= substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(32))), 0, 32);
			$url 	= base_url("auth/reset?token={$token}&email={$email}");
			$html 	= "<p>Click this link to reset your old password </p><br><a href='{$url}'>Reset now</a>";
			$send 	= $this->_send_email($credentials['email'], 'Forgot Password', $html);
			$credentials['reset_token'] = $token;
			if (!$send) {
				alert('Failed to forget password, there is a problem with the server', 'warning');
				return false;
			}
			$this->CI->user->update($credentials, $user->id);
			alert('Successfully forgot password, please check your email to reset password', 'success');
			return true;
		}
		alert('Your email is not registered, please sign-up', 'error');
		return false;
	}

	//* Temp send email
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
	//* Email verify
	public function emailVerify($email, $token, $reset = false)
	{
		$data = [];
		$title = $reset ? 'reset' : 'activation';
		$user = $this->CI->user->find_where(['email' => $email])->row();
		if ($user) {
			$date = $reset ? $user->updated_at : $user->created_at;
			$dbtoken = $reset ? $user->reset_token : $user->email_verified_token;
			if ($dbtoken == $token) {
				$start_date 	= new DateTime($date);
				$since_start 	= $start_date->diff(new DateTime(date('Y-m-d h:i:s', now())));
				// cek valid date
				if ($since_start->d < 1) {
					if ($reset) {
						$this->CI->session->set_flashdata('user_id', $user->id);
					} else {
						$data['status'] 				= 1;
						$data['email_verified_token']	= null;
						$data['email_verified_at']		= mdate('%Y-%m-%d %H:%i:%s', now());
						alert("Account {$title} successfully", 'success');
						$this->CI->user->update($data, $user->id);
					}
					return true;
				} else {
					$reset ?
						$this->CI->user->update(['reset_token' => null], $user->id) :
						$this->CI->user->delete(['id' => $user->id]);
					alert(ucfirst("{$title} token has expired"), 'error');
					return false;
				}
			} else {
				alert("Invalid {$title} token", 'error');
				return false;
			}
		} else {
			alert("Invalid {$title} email", 'error');
			return false;
		}
	}

	// *Logout Method
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
