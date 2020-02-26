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
				base_url('assets/js/users.vendor.min.js'),
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
			base_url('assets/js/user.vendor.min.js'),
			base_url('assets/js/user.min.js')
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
			'min_length' => 'El tamaño mínimo del campo "%s" es de %s caracteres',
			'max_length' => 'El tamaño máximo del campo "%s" es de %s caracteres ',
			'valid_email' => 'El correo electrónico es inválido',
			'username_is_unique' => 'Este nombre de usuario ya existe',
			'email_is_unique' => 'Este correo electrónico ya existe',
		];
		$this->form_validation->set_rules('username', 'Nombre de usuario', 'trim|required|alpha_dash|max_length[255]|username_is_unique', $error_messages);
		$this->form_validation->set_rules('email', 'Correo electrónico', 'trim|required|max_length[255]|valid_email|email_is_unique', $error_messages);
		$this->form_validation->set_rules('role', 'Rol', 'trim|required|max_length[255]', $error_messages);
		$this->form_validation->set_rules('password', 'Contraseña', 'trim|required|min_length[8]|max_length[255]', $error_messages);
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
			base_url('assets/js/user.vendor.min.js'),
			base_url('assets/js/change-password.min.js')
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
			'max_length' => 'El tamaño máximo del campo "%s" es de 255 caracteres ',
			'matches' => 'Ingrese la misma contraseña, para la verificación',
		];
		$this->form_validation->set_rules('password', 'Contraseña', 'trim|required|max_length[255]', $error_messages);
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
			base_url('assets/js/user.vendor.min.js'),
			base_url('assets/js/user.min.js')
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
			'min_length' => 'El tamaño mínimo del campo "%s" es de %s caracteres',
			'max_length' => 'El tamaño máximo del campo "%s" es de %s caracteres ',
			'valid_email' => 'El correo electrónico es inválido',
			'new_username_is_unique' => 'Este nombre de usuario ya existe',
			'new_email_is_unique' => 'Este correo electrónicoo ya existe',
		];
		$this->form_validation->set_rules('username', 'Nombre de usuario', 'trim|required|alpha_dash|max_length[255]|new_username_is_unique', $error_messages);
		$this->form_validation->set_rules('email', 'Correo electrónico', 'trim|required|max_length[255]|valid_email|new_email_is_unique', $error_messages);
		$this->form_validation->set_rules('role', 'Rol', 'trim|required|max_length[255]', $error_messages);
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
			return '<strong>' . $data['id'] . '</strong>';
		});
		$datatables->add('action', function ($data) {
			$csrf = array(
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			);
			$logged_in_user = $this->session->userdata('logged_in_user');
			$data['url'] = base_url('admin/usuarios/') . $data['id'];
			$edit_button = $this->load->view('partials/edit_button', $data, true);
			$data['url'] = base_url('admin/users/delete_user_validation');
			$data['id'] = $data['id'];
			$data['csrf_name'] = $csrf['name'];
			$data['csrf_hash'] = $csrf['hash'];
			$data['is_disabled'] = $logged_in_user['id'] === $data['id'] ? TRUE : FALSE;;
			$delete_button = $this->load->view('partials/delete_button', $data, true);
			$data['url'] = base_url('admin/usuarios/cambiar_contrasena/') . $data['id'];
			$change_pass_button = $this->load->view('partials/change_pass_button', $data, true);
			return $change_pass_button . $edit_button . $delete_button;
		});
		echo $datatables->generate();
	}
}

?>
