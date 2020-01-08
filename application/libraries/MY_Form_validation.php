<?php

class MY_Form_validation extends CI_Form_validation
{
	protected $CI;

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->model('Users_model', 'users');
	}

	function valid_username($str)
	{
		$user_by_username = $this->CI->users->get_user_by_username($str);
		$user_by_email = $this->CI->users->get_user_by_email($str);
		if ($user_by_username === null && $user_by_email === null) {
			return false;
		} else {
			return true;
		}
	}

	function new_username_is_unique()
	{
		$user = $this->CI->users->get_user_by_id($this->CI->input->post('id'));
		$username = $user->nombre_usuario;
		if ($this->CI->input->post('username') !== $username) {
			if ($this->CI->users->check_if_username_is_unique($this->CI->input->post('username')) > 0) {
				return false;
			} else {
				return true;
			}
		}
	}

	function new_email_is_unique()
	{
		$user = $this->CI->users->get_user_by_id($this->CI->input->post('id'));
		$email = $user->correo_electronico;
		if ($this->CI->input->post('email') !== $email) {
			if ($this->CI->users->check_if_email_is_unique($this->CI->input->post('email')) > 0) {
				return false;
			} else {
				return true;
			}
		}
	}

	function valid_phone($str)
	{
		$pattern = '/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/';
		if (preg_match($pattern, $str)) {
			return true;
		} else {
			return false;
		}
	}

	function valid_postal_code($str)
	{
		$pattern = '/^[0-9]{5}$/';
		if (preg_match($pattern, $str)) {
			return true;
		} else {
			return false;
		}
	}

	function valid_rfc($str)
	{
		$pattern = '/^[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?$/';
		if (preg_match($pattern, $str)) {
			return true;
		} else {
			return false;
		}
	}

	function username_is_unique()
	{
		if ($this->CI->users->check_if_username_is_unique($this->CI->input->post('username')) > 0) {
			return false;
		} else {
			return true;
		}
	}

	function email_is_unique()
	{
		if ($this->CI->users->check_if_email_is_unique($this->CI->input->post('email')) > 0) {
			return false;
		} else {
			return true;
		}
	}
}
