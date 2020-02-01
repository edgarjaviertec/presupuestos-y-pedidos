<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'vendor/autoload.php';

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Users extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Users_model', 'users');
		$this->load->helper('app');
		$this->load->library(['form_validation']);
		$logged_in_user = $this->session->userdata('logged_in_user');
		if (!$logged_in_user) {
			redirect('auth/login');
		} else if ($logged_in_user['role'] === 'user') {
			redirect('admin/clientes');
		}
	}

	public function index()
	{
		if ($this->users->count_all_records() <= 0) {
			$data['page'] = 'no_users';
			$data['title'] = 'No hay usuarios';
			$data['js_files'] = [
				base_url('assets/js/empty.vendor.min.js'),
			];
			$this->load->view('layouts/dashboard_layout', $data);
		} else {
			$data['page'] = 'user_list';
			$data['title'] = 'Usuarios';
			$data['js_files'] = [
				base_url('assets/js/list.vendor.min.js'),
				base_url('assets/js/users.min.js')
			];
			$this->load->view('layouts/dashboard_layout', $data);
		}
	}

	public function new_user()
	{
		$data['page'] = 'new_user';
		$data['title'] = 'Nuevo usuario';
		$data['js_files'] = [
			base_url('assets/js/new-edit.vendor.min.js')
		];
		$this->load->view('layouts/dashboard_layout', $data);
	}

	public function new_user_validation()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}

		$error_messages = [
			'required' => 'El campo "%s"  es requerido',
			'alpha_dash' => 'El campo "%s" solo puede contener caracteres alfanuméricos, guiones bajos y guiones medios',
			'alpha_numeric' => 'El campo "%s" solo puede contener caracteres alfanuméricos',
			'max_length' => 'El tamaño máximo del campo "%s" es de 255 caracteres ',
			'valid_email' => 'El correo electrónico es inválido',
			'username_is_unique' => 'Este nombre de usuario ya existe',
			'email_is_unique' => 'Este correo electrónicoo ya existe',
		];

		$this->form_validation->set_rules('username', 'Nombre de usuario', 'trim|required|alpha_dash|max_length[255]|username_is_unique', $error_messages);
		$this->form_validation->set_rules('email', 'Correo electrónico', 'trim|required|max_length[255]|valid_email|email_is_unique', $error_messages);
		$this->form_validation->set_rules('role', 'Rol', 'trim|required|alpha_numeric|max_length[255]', $error_messages);
		$this->form_validation->set_rules('password', 'Contraseña', 'trim|required|alpha_numeric|max_length[255]', $error_messages);

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('errors', $this->form_validation->error_array());
			$this->session->set_flashdata('old', $this->input->post());
			redirect('admin/usuarios/nuevo');
		} else {
			$affected_rows = $this->users->create_user($this->input->post());
			if (count($affected_rows) > 0) {
				$this->session->set_flashdata('flash_message', [
					'type' => 'success',
					'title' => 'El usuario se creó con éxito',
				]);
				redirect('admin/usuarios');
			}
		}
	}

	public function delete_user_validation()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}
		if ($this->input->post('id')) {
			$deleted_records = $this->users->delete_user($this->input->post('id'));
			if ($deleted_records > 0) {
				$this->session->set_flashdata('flash_message', [
					'type' => 'success',
					'title' => 'El usuario se eliminó con éxito',
				]);
				$this->session->set_flashdata('old', $this->input->post());
				redirect('admin/usuarios');
			}
		}
	}

	public function change_password($id)
	{
		$user = $this->users->get_user_by_id($id);
		if (!$user) {
			show_404();
		}
		$data['page'] = 'change_password';
		$data['title'] = 'Cambiar contraseña del usuario #' . $id;
		$data['js_files'] = [
			base_url('assets/js/new-edit.vendor.min.js')
		];
		$data['user'] = $user;
		$this->load->view('layouts/dashboard_layout', $data);
	}

	public function change_password_validation()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}
		$error_messages = [
			'required' => 'El campo "%s"  es requerido',
			'alpha_numeric' => 'El campo "%s" solo puede contener caracteres alfanumérico',
			'max_length' => 'El tamaño máximo del campo "%s" es de 255 caracteres ',
			'matches' => 'Las contraseña no coincide',
		];

		$this->form_validation->set_rules('password', 'Contraseña', 'trim|required|alpha_numeric|max_length[255]', $error_messages);
		$this->form_validation->set_rules('confirm_password', 'Repetir contraseña', 'trim|required|matches[password]', $error_messages);

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('errors', $this->form_validation->error_array());
			$this->session->set_flashdata('old', $this->input->post());
			redirect('admin/usuarios/cambiar_contrasena/' . $this->input->post('id'));
		} else {
			$affected_rows = $this->users->update_password($this->input->post());
			if (count($affected_rows) > 0) {
				$this->session->set_flashdata('flash_message', [
					'type' => 'success',
					'title' => 'La contraseña se cambió con éxito',
				]);
				redirect('admin/usuarios');
			}
		}
	}

	public function edit_user($id)
	{
		$user = $this->users->get_user_by_id($id);
		if (!$user) {
			show_404();
		}
		$data['page'] = 'edit_user';
		$data['title'] = 'Editar usuario #' . $id;
		$data['js_files'] = [
			base_url('assets/js/new-edit.vendor.min.js')
		];
		$data['user'] = $user;
		$this->load->view('layouts/dashboard_layout', $data);
	}

	public function edit_user_validation()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}
		$error_messages = [
			'required' => 'El campo "%s"  es requerido',
			'alpha_dash' => 'El campo "%s" solo puede contener caracteres alfanuméricos, guiones bajos y guiones medios',
			'alpha_numeric' => 'El campo "%s" solo puede contener caracteres alfanuméricos',
			'max_length' => 'El tamaño máximo del campo "%s" es de 255 caracteres ',
			'valid_email' => 'El correo electrónico es inválido',
			'new_username_is_unique' => 'Este nombre de usuario ya existe',
			'new_email_is_unique' => 'Este correo electrónicoo ya existe',
		];

		$this->form_validation->set_rules('username', 'Nombre de usuario', 'trim|required|alpha_dash|max_length[255]|new_username_is_unique', $error_messages);
		$this->form_validation->set_rules('email', 'Correo electrónico', 'trim|required|max_length[255]|valid_email|new_email_is_unique', $error_messages);
		$this->form_validation->set_rules('role', 'Rol', 'trim|required|alpha_numeric|max_length[255]', $error_messages);

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('errors', $this->form_validation->error_array());
			$this->session->set_flashdata('old', $this->input->post());
			redirect('admin/usuarios/' . $this->input->post('id'));
		} else {
			$logged_in_user = $this->session->userdata('logged_in_user');
			$affected_rows = $this->users->update_user($this->input->post());
			if (count($affected_rows) > 0) {
				// Si el usuario que se quiere editar es el mismo que esta conectado entonces actualizamos los datos de la sesión actual
				if (isset($logged_in_user['id']) && $logged_in_user['id'] === $this->input->post('id')) {
					$this->session->set_userdata('logged_in_user', [
						'id' => $this->input->post('id'),
						'username' => $this->input->post('username'),
						'email' => $this->input->post('email'),
						'role' => $this->input->post('role')
					]);
				}
				$this->session->set_flashdata('flash_message', [
					'type' => 'success',
					'title' => 'Los datos se actualizaron correctamente',
				]);
				redirect('admin/usuarios');
			}
		}
	}

	public function get_users_ajax()
	{
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query('SELECT id, nombre_usuario, correo_electronico, rol FROM usuarios WHERE eliminado_en IS NULL');

		$datatables->edit('id', function ($data) {
			return '<span class="px-3 badge badge-pill badge-light"><span class="font-weight-bold h6">#' . $data['id'] . '</span></span>';
		});

		$datatables->add('action', function ($data) {
			$csrf = array(
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			);
			$logged_in_user = $this->session->userdata('logged_in_user');
			$disabled_attr = $logged_in_user['id'] === $data['id'] ? 'disabled' : '';
			$delete_button = '<form class="d-inline" method="POST" action="' . base_url('admin/users/delete_user_validation') . '">';
			$delete_button .= '<input type="hidden" name="id" value="' . $data['id'] . '" />';
			$delete_button .= '<input type="hidden" name="' . $csrf['name'] . '" value="' . $csrf['hash'] . '" />';
			$delete_button .= '<button class="btn btn-danger delete_btn" ' . $disabled_attr . '><i class="fas fa-times"></i></button>';
			$delete_button .= '</form>';
			$edit_button = '<a ';
			$edit_button .= 'href="' . base_url('admin/usuarios/' . $data['id']) . '"';
			$edit_button .= 'class="btn btn-primary mr-2">';
			$edit_button .= '<i class="fas fa-pencil-alt"></i>';
			$edit_button .= '</a>';
			$change_pass_button = '<a ';
			$change_pass_button .= 'href="' . base_url('admin/usuarios/cambiar_contrasena/' . $data['id']) . '"';
			$change_pass_button .= 'class="btn btn-primary mr-2">';
			$change_pass_button .= '<i class="fas fa-key"></i>';
			$change_pass_button .= '</a>';
			return $change_pass_button . $edit_button . $delete_button;
		});
		echo $datatables->generate();
	}
}

?>
