<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Temp
{
	protected $template_data = [];
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	public function set($name, $value)
	{
		$this->template_data[$name] = $value;
	}

	public function load($template = '', $view = '', $data = [], $return = false)
	{
		$this->set('contents', $this->CI->load->view($view, $data, true));
		return $this->CI->load->view($template, $this->template_data, $return);
	}
}
