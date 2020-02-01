<?php


defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;


class Estimates extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Estimates_model', 'estimates');
		$this->load->helper('app');
		$this->load->library(['form_validation']);
//		$logged_in_user = $this->session->userdata('logged_in_user');
//		if (!$logged_in_user) {
//			redirect('auth/login');
//		}
	}

	public function index()
	{
		if ($this->estimates->count_all_records() <= 0) {
			$data['page'] = 'no_estimates';
			$data['title'] = 'No hay presupuestos';
			$data['js_files'] = [
				base_url('assets/js/empty.vendor.min.js'),
			];
			$this->load->view('layouts/dashboard_layout', $data);
		} else {
			$data['page'] = 'estimate_list';
			$data['title'] = 'Presupuestos';
			$data['js_files'] = [
				base_url('assets/js/list.vendor.min.js'),
				base_url('assets/js/estimates.min.js')
			];
			$this->load->view('layouts/dashboard_layout', $data);
		}


	}

	public function new_estimate()
	{
		$next_estimate_number = $this->estimates->get_next_estimate_number(date("Y"));
//		var_dump($next_estimate_number);
		$data['next_estimate_number'] = $next_estimate_number;
		$data['page'] = 'new_estimate';
		$data['title'] = 'Nuevo presupuesto';
		$data['js_files'] = [
			base_url('assets/js/new_edit_estimate.vendor.min.js'),
			base_url('assets/js/new_estimate.min.js')
		];

		$this->load->view('layouts/dashboard_layout', $data);
	}


	function get_products_ajax()
	{
		$errors = [];
		if (is_null($this->input->get('search'))) {
			$errors['search'] = 'El parámetro search es requerido';
		}
		if (count($errors) > 0) {
			$this->output
				->set_header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request')
				->set_content_type('application/json')
				->set_output(json_encode($errors, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
		} else {
			$search = $this->input->get('search');
			$data = $this->estimates->get_products_for_typeahead($search);
			$this->output
				->set_header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK')
				->set_content_type('application/json')
				->set_output(json_encode($data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
		}

	}


	function get_customers_ajax()
	{
		$errors = [];
		if (is_null($this->input->get('start')) || trim($this->input->get('start')) == '') {
			$errors['start'] = 'El parámetro start es requerido';
		} elseif (!preg_match("/^[0-9]*$/", $this->input->get('start'))) {
			$errors['start'] = 'El parámetro start debe ser un numero entero';
		}
		if (is_null($this->input->get('length')) || trim($this->input->get('length')) == '') {
			$errors['length'] = 'El parámetro length es requerido';
		} elseif (!preg_match("/^[0-9]*$/", $this->input->get('length'))) {
			$errors['length'] = 'El parámetro length debe ser un numero entero';
		}

		if (count($errors) > 0) {
			$this->output
				->set_header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request')
				->set_content_type('application/json')
				->set_output(json_encode($errors, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
		} else {
			$start = $this->input->get('start');
			$length = $this->input->get('length');
			$search = $this->input->get('search');
			$data = $this->estimates->get_customers_for_select2($start, $length, $search);
			$this->output
				->set_header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK')
				->set_content_type('application/json')
				->set_output(json_encode($data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
		}
	}


	public function new_estimate_validation()
	{
		echo "<pre>";
		echo json_encode($_GET, JSON_PRETTY_PRINT);
		echo "</pre>";
	}


//	public function new_estimate_validation()
//	{
//		if ($this->input->server('REQUEST_METHOD') != 'POST') {
//			show_404();
//		}
//
//		if ($this->input->post('unit_price')) {
//			$_POST['unit_price'] = remove_commas($_POST['unit_price']);
//		}
//
//		$common_error_messages = [
//			'required' => 'El campo "%s"  es requerido',
//			'max_length' => 'El tamaño máximo del campo "%s" es de 255 caracteres ',
//			'numeric' => 'El campo "%s" debe contener solo números',
//			'greater_than' => 'El campo "%s" debe contener un número mayor que 0',
//			'less_than' => 'El campo "%s" debe contener un número menor que 1,000,000'
//		];
//
//		$this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[255]', $common_error_messages);
//		$this->form_validation->set_rules('description', 'Descripción', 'trim|max_length[255]', $common_error_messages);
//		$this->form_validation->set_rules('unit_price', 'Precio unitario', 'trim|required|numeric|greater_than[0]|less_than[1000000]', $common_error_messages);
//
//		if ($this->form_validation->run() == FALSE) {
//			$this->session->set_flashdata('errors', $this->form_validation->error_array());
//			$this->session->set_flashdata('old', $this->input->post());
//			redirect('admin/productos/nuevo');
//		} else {
//			$affected_rows = $this->products->create_product($this->input->post());
//			if (count($affected_rows) > 0) {
//				$this->session->set_flashdata('flash_message', [
//					'type' => 'success',
//					'title' => 'El producto se creó con éxito',
//				]);
//				redirect('admin/productos');
//			}
//		}
//	}
//
//	public function get_estimates_ajax()
//	{
//		$datatables = new Datatables(new CodeigniterAdapter);
//		$datatables->query("SELECT id, nombre, descripcion, precio_unitario FROM productos WHERE eliminado_en IS NULL");
//
//
//		$datatables->edit('id', function ($data) {
//			return '<span class="px-3 badge badge-pill badge-light"><span class="font-weight-bold h6">#' . $data['id'] . '</span></span>';
//		});
//
//
//		$datatables->edit('precio_unitario', function ($data) {
//			return "$ " . number_format($data['precio_unitario'], 2);
//		});
//
//		$datatables->add('action', function ($data) {
//			$csrf = array(
//				'name' => $this->security->get_csrf_token_name(),
//				'hash' => $this->security->get_csrf_hash()
//			);
//
//			$delete_button = '<form class="d-inline" method="POST" action="' . base_url('admin/products/delete_product_validation') . '">';
//			$delete_button .= '<input type="hidden" name="id" value="' . $data['id'] . '" />';
//			$delete_button .= '<input type="hidden" name="' . $csrf['name'] . '" value="' . $csrf['hash'] . '" />';
//			$delete_button .= '<button class="btn btn-danger delete_btn"><i class="fas fa-times"></i></button>';
//			$delete_button .= '</form>';
//
//			$edit_button = '<a ';
//			$edit_button .= 'href="' . base_url('admin/productos/' . $data['id']) . '"';
//			$edit_button .= 'class="btn btn-primary mr-2">';
//			$edit_button .= '<i class="fas fa-pencil-alt"></i>';
//			$edit_button .= '</a>';
//
//			return $edit_button . $delete_button;
//		});
//		echo $datatables->generate();
//	}
//


}

?>
