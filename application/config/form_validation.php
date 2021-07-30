<?php

$config = [
	'error_prefix' => '<span class="font-medium text-xs text-red-400">',
	'error_suffix' => '</span>',
	'login' => [
		[
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email'
		],
		[
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'trim|required|alpha_numeric'
		]
	],
	'register' => [
		[
			'field' => 'name',
			'label' => 'Username',
			'rules' => 'trim|required|is_unique[users.name]'
		],
		[
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email|is_unique[users.email]'
		],
		[
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'trim|required|alpha_numeric|min_length[8]'
		],
		[
			'field' => 'password_conf',
			'label' => 'Password confirmation',
			'rules' => 'trim|required|matches[password]'
		]
	],
	'forgot' => [
		[
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email',
		]
	],
	'reset' => [
		[
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'trim|required|alpha_numeric|min_length[8]'
		],
		[
			'field' => 'password_conf',
			'label' => 'Password confirmation',
			'rules' => 'trim|required|matches[password]'
		]
	]
];
