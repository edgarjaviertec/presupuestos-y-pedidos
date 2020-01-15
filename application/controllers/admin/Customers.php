<?php


defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;


class Customers extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Customers_model', 'customers');
		$this->load->helper('app');
		$this->load->library(['form_validation']);
		$logged_in_user = $this->session->userdata('logged_in_user');
		if (!$logged_in_user) {
			redirect('auth/login');
		}
	}

	public function index()
	{
		if ($this->customers->count_all_records() <= 0) {
			$data['page'] = 'no_customers';
			$data['title'] = 'No hay clientes';
			$this->load->view('layouts/dashboard_layout', $data);
		} else {
			$data['page'] = 'customer_list';
			$data['title'] = 'Clientes';
			$data['js_files'] = [
				base_url('assets/js/list.vendor.min.js'),
				base_url('assets/js/customers.min.js')
			];
			$this->load->view('layouts/dashboard_layout', $data);
		}


	}

	public function new_customer()
	{
		$data['page'] = 'new_customer';
		$data['title'] = 'Nuevo cliente';
		$data['js_files'] = [
			base_url('assets/js/new-edit.vendor.min.js'),
			base_url('assets/js/new-customer.min.js')
		];
		$this->load->view('layouts/dashboard_layout', $data);
	}

	public function new_customer_validation()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}
		$common_error_messages = [
			'required' => 'El campo "%s" es obligatorio',
			'max_length' => 'El tamaño máximo del campo "%s" es de 255 caracteres ',
			'valid_email' => 'El correo electrónico es inválido',
			'valid_phone' => 'El número de teléfono es inválido',
			'valid_rfc' => 'El RFC es inválido',
			'valid_postal_code' => 'El código postal es inválido',
		];

		$this->form_validation->set_rules('rfc', 'RFC', 'trim|max_length[255]|valid_rfc', $common_error_messages);
		$this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('last_name', 'Apellidos', 'trim|required|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('company', 'Empresa', 'trim|required|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('email', 'Correo electrónico', 'trim|max_length[255]|valid_email', $common_error_messages);
		$this->form_validation->set_rules('phone', 'Teléfono', 'trim|max_length[255]|valid_phone', $common_error_messages);
		$this->form_validation->set_rules('mobile_phone', 'Teléfono celular', 'trim|max_length[255]|valid_phone', $common_error_messages);
		$this->form_validation->set_rules('address', 'Dirección', 'trim|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('city', 'Ciudad', 'trim|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('state', 'Estado', 'trim|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('country', 'País', 'trim|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('postal_code', 'Código postal', 'trim|max_length[255]|valid_postal_code', $common_error_messages);
		$this->form_validation->set_rules('notes', 'Notas', 'trim|max_length[255]', $common_error_messages);

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('errors', $this->form_validation->error_array());
			$this->session->set_flashdata('old', $this->input->post());
			redirect('admin/clientes/nuevo');
		} else {
			$affected_rows = $this->customers->create_customer($this->input->post());
			if (count($affected_rows) > 0) {
				$this->session->set_flashdata('flash_message', [
					'type' => 'success',
					'title' => 'El cliente se creó con éxito',
				]);
				redirect('admin/clientes');
			}
		}
	}

	public function delete_customer_validation()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}
		if ($this->input->post('id')) {
			$deleted_records = $this->customers->delete_customer($this->input->post('id'));
			if ($deleted_records > 0) {
				$this->session->set_flashdata('flash_message', [
					'type' => 'success',
					'title' => 'El cliente se eliminó con éxito',
				]);
				$this->session->set_flashdata('old', $this->input->post());
				redirect('admin/clientes');
			}
		}
	}

	public function get_customers_ajax()
	{
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT id, CONCAT(nombre, ' ', apellidos) as nombre_completo, empresa, rfc FROM clientes WHERE eliminado_en IS NULL");


		$datatables->edit('id', function ($data) {
			return '<span class="px-3 badge badge-pill badge-light"><span class="font-weight-bold h6">#' . $data['id'] . '</span></span>';
		});

		$datatables->add('action', function ($data) {
			$csrf = array(
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			);

			$delete_button = '<form class="d-inline" method="POST" action="' . base_url('admin/customers/delete_customer_validation') . '">';
			$delete_button .= '<input type="hidden" name="id" value="' . $data['id'] . '" />';
			$delete_button .= '<input type="hidden" name="' . $csrf['name'] . '" value="' . $csrf['hash'] . '" />';
			$delete_button .= '<button class="btn btn-danger delete_btn"><i class="fas fa-times"></i></button>';
			$delete_button .= '</form>';

			$edit_button = '<a ';
			$edit_button .= 'href="' . base_url('admin/clientes/' . $data['id']) . '"';
			$edit_button .= 'class="btn btn-primary mr-2">';
			$edit_button .= '<i class="fas fa-pencil-alt"></i>';
			$edit_button .= '</a>';

			return $edit_button . $delete_button;
		});
		echo $datatables->generate();
	}

	public function edit_customer($id)
	{
		$customer = $this->customers->get_customer_by_id($id);
		if (!$customer) {
			show_404();
		}
		$data['page'] = 'edit_customer';
		$data['title'] = 'Editar cliente #' . $id;
		$data['js_files'] = [
			base_url('assets/js/new-edit.vendor.min.js'),
			base_url('assets/js/edit-customer.min.js')
		];
		//$data['css_files'] = base_url('assets/vendor/select2/select2.min.css');
		$data['customer'] = $customer;
		$this->load->view('layouts/dashboard_layout', $data);
	}

	public function edit_customer_validation()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}
		$common_error_messages = [
			'required' => 'El campo "%s" es obligatorio',
			'max_length' => 'El tamaño máximo del campo "%s" es de 255 caracteres ',
			'valid_email' => 'El correo electrónico es inválido',
			'valid_phone' => 'El número de teléfono es inválido',
			'valid_rfc' => 'El RFC es inválido',
			'valid_postal_code' => 'El código postal es inválido',
		];

		$this->form_validation->set_rules('rfc', 'RFC', 'trim|max_length[255]|valid_rfc', $common_error_messages);
		$this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('last_name', 'Apellidos', 'trim|required|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('company', 'Empresa', 'trim|required|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('email', 'Correo electrónico', 'trim|max_length[255]|valid_email', $common_error_messages);
		$this->form_validation->set_rules('phone', 'Teléfono', 'trim|max_length[255]|valid_phone', $common_error_messages);
		$this->form_validation->set_rules('mobile_phone', 'Teléfono celular', 'trim|max_length[255]|valid_phone', $common_error_messages);
		$this->form_validation->set_rules('address', 'Dirección', 'trim|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('city', 'Ciudad', 'trim|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('state', 'Estado', 'trim|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('country', 'País', 'trim|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('postal_code', 'Código postal', 'trim|max_length[255]|valid_postal_code', $common_error_messages);
		$this->form_validation->set_rules('notes', 'Notas', 'trim|max_length[255]', $common_error_messages);

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('errors', $this->form_validation->error_array());
			$this->session->set_flashdata('old', $this->input->post());
			redirect('admin/clientes/' . $this->input->post('id'));
		} else {
			$affected_rows = $this->customers->update_customer($this->input->post());

			if (count($affected_rows) > 0) {
				$this->session->set_flashdata('flash_message', [
					'type' => 'success',
					'title' => 'Los datos se actualizaron correctamente',
				]);
				redirect('admin/clientes');
			}
		}
	}

}

?>
