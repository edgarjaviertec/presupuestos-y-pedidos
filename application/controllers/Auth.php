<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller

{
	public function __construct()
	{
		parent::__construct();
		$this->load->library(['form_validation']);
		$this->load->model('Users_model', 'users');
	}

	function index()
	{
		redirect('auth/login');
	}

	public function login()
	{
		if ($this->session->userdata('logged_in_user')) {
			redirect('admin/clientes');
		}
		$data['page'] = 'login';
		$data['title'] = 'Iniciar sesión';
		$data['js_files'] = [
			base_url('assets/js/login.vendor.min.js')
		];
		$this->load->view('layouts/full_height_layout', $data);
	}

	public function login_validation()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}
		$error_messages = [
			'required' => 'Este campo es obligatorio',
			'max_length' => 'El tamaño máximo del campo "%s" es de 255 caracteres ',
			'valid_username' => 'Nombre de usuario o correo electrónico inválido'
		];
		$this->form_validation->set_rules('username', 'Nombre de usuario o correo electrónico', 'trim|required|max_length[255]|valid_username', $error_messages);
		$this->form_validation->set_rules('password', 'Contraseña', 'trim|required|max_length[255]', $error_messages);
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('errors', $this->form_validation->error_array());
			$this->session->set_flashdata('old', $this->input->post());
			redirect('auth/login');
		} else {
			$user = $this->users->get_user($this->input->post('username'));
			if (password_verify($this->input->post('password'), $user->clave)) {
				$this->session->set_userdata('logged_in_user', [
					'id' => $user->id,
					'username' => $user->nombre_usuario,
					'email' => $user->correo_electronico,
					'role' => $user->rol
				]);
				redirect('admin/clientes');
			} else {
				$this->session->set_flashdata('errors', [
					'password' => 'La contraseña es incorrecta'
				]);
				$this->session->set_flashdata('old', $this->input->post());
				redirect('login');
			}
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}
