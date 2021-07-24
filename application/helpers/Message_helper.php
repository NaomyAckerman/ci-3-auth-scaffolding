<?php

function alert($message, $type, $action = true)
{
	$ci = &get_instance();
	$data = [
		'type'  	=> $type,
		'message'	=> $message,
	];
	if ($action) {
		$data['flash'] = 'flash';
	}
	return $ci->session->set_flashdata($data);
}
