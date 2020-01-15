<?php


defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;


class Products extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Products_model', 'products');
		$this->load->helper('app');
		$this->load->library(['form_validation']);
		$logged_in_user = $this->session->userdata('logged_in_user');
		if (!$logged_in_user) {
			redirect('auth/login');
		}
	}

	public function index()
	{
		if ($this->products->count_all_records() <= 0) {
			$data['page'] = 'no_products';
			$data['title'] = 'No hay productos';
			$this->load->view('layouts/dashboard_layout', $data);
		} else {
			$data['page'] = 'product_list';
			$data['title'] = 'Productos';
			$data['js_files'] = [
				base_url('assets/js/list.vendor.min.js'),
				base_url('assets/js/products.min.js')
			];
			$this->load->view('layouts/dashboard_layout', $data);
		}


	}

	public function new_product()
	{
		$data['page'] = 'new_product';
		$data['title'] = 'Nuevo producto';
		$data['js_files'] = [
			base_url('assets/js/new-edit.vendor.min.js'),
			base_url('assets/js/new-product.min.js')
		];
		$this->load->view('layouts/dashboard_layout', $data);
	}

	public function new_product_validation()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}

		if ($this->input->post('unit_price')) {
			$_POST['unit_price'] = remove_commas($_POST['unit_price']);
		}

		$common_error_messages = [
			'required' => 'El campo "%s" es obligatorio',
			'max_length' => 'El tamaño máximo del campo "%s" es de 255 caracteres ',
			'numeric' => 'El campo "%s" debe contener solo números',
			'greater_than' => 'El campo "%s" debe contener un número mayor que 0',
			'less_than' => 'El campo "%s" debe contener un número menor que 1,000,000'
		];

		$this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('description', 'Descripción', 'trim|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('unit_price', 'Precio unitario', 'trim|required|numeric|greater_than[0]|less_than[1000000]', $common_error_messages);

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('errors', $this->form_validation->error_array());
			$this->session->set_flashdata('old', $this->input->post());
			redirect('admin/productos/nuevo');
		} else {
			$affected_rows = $this->products->create_product($this->input->post());
			if (count($affected_rows) > 0) {
				$this->session->set_flashdata('flash_message', [
					'type' => 'success',
					'title' => 'El producto se creó con éxito',
				]);
				redirect('admin/productos');
			}
		}
	}

	public function delete_product_validation()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}
		if ($this->input->post('id')) {
			$affected_rows = $this->products->delete_product($this->input->post('id'));
			if ($affected_rows > 0) {
				$this->session->set_flashdata('flash_message', [
					'type' => 'success',
					'title' => 'El producto se eliminó con éxito',
				]);
				$this->session->set_flashdata('old', $this->input->post());
				redirect('admin/productos');
			}
		}
	}

	public function get_products_ajax()
	{
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT id, nombre, descripcion, precio_unitario FROM productos WHERE eliminado_en IS NULL");


		$datatables->edit('id', function ($data) {
			return '<span class="px-3 badge badge-pill badge-light"><span class="font-weight-bold h6">#' . $data['id'] . '</span></span>';
		});


		$datatables->edit('precio_unitario', function ($data) {
			return "$ " . number_format($data['precio_unitario'], 2);
		});

		$datatables->add('action', function ($data) {
			$csrf = array(
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			);

			$delete_button = '<form class="d-inline" method="POST" action="' . base_url('admin/products/delete_product_validation') . '">';
			$delete_button .= '<input type="hidden" name="id" value="' . $data['id'] . '" />';
			$delete_button .= '<input type="hidden" name="' . $csrf['name'] . '" value="' . $csrf['hash'] . '" />';
			$delete_button .= '<button class="btn btn-danger delete_btn"><i class="fas fa-times"></i></button>';
			$delete_button .= '</form>';

			$edit_button = '<a ';
			$edit_button .= 'href="' . base_url('admin/productos/' . $data['id']) . '"';
			$edit_button .= 'class="btn btn-primary mr-2">';
			$edit_button .= '<i class="fas fa-pencil-alt"></i>';
			$edit_button .= '</a>';

			return $edit_button . $delete_button;
		});
		echo $datatables->generate();
	}

	public function edit_product($id)
	{
		$product = $this->products->get_product_by_id($id);
		if (!$product) {
			show_404();
		}
		$data['page'] = 'edit_product';
		$data['title'] = 'Editar producto #' . $id;
		$data['js_files'] = [
			base_url('assets/js/new-edit.vendor.min.js'),
			base_url('assets/js/edit-product.min.js')
		];
		$data['product'] = $product;
		$this->load->view('layouts/dashboard_layout', $data);
	}

	public function edit_product_validation()
	{

		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}

		if ($this->input->post('unit_price')) {
			$_POST['unit_price'] = remove_commas($_POST['unit_price']);
		}

		$common_error_messages = [
			'required' => 'El campo "%s" es obligatorio',
			'max_length' => 'El tamaño máximo del campo "%s" es de 255 caracteres ',
			'numeric' => 'El campo "%s" debe contener solo números',
			'greater_than' => 'El campo "%s" debe contener un número mayor que 0',
			'less_than' => 'El campo "%s" debe contener un número menor que 1,000,000'
		];

		$this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('description', 'Descripción', 'trim|max_length[255]', $common_error_messages);
		$this->form_validation->set_rules('unit_price', 'Precio unitario', 'trim|required|numeric|greater_than[0]|less_than[1000000]', $common_error_messages);


		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('errors', $this->form_validation->error_array());
			$this->session->set_flashdata('old', $this->input->post());
			redirect('admin/productos/' . $this->input->post('id'));

		} else {
			$affected_rows = $this->products->update_product($this->input->post());
			if (count($affected_rows) > 0) {
				$this->session->set_flashdata('flash_message', [
					'type' => 'success',
					'title' => 'Los datos se actualizaron correctamente',
				]);
				redirect('admin/productos');
			}
		}
	}

}

?>
