<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once 'vendor/autoload.php';

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;
use Dompdf\Dompdf;

class Orders extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Setting_model', 'settings');
		$this->load->model('Orders_model', 'orders');
		$this->load->model('Customers_model', 'customers');
		$this->load->model('Products_model', 'products');
		$this->load->model('Payments_model', 'payments');
		$this->load->helper('app');
		$this->load->library(['form_validation']);
		$this->load->helper('path');
		$logged_in_user = $this->session->userdata('logged_in_user');
		if (!$logged_in_user) {
			redirect('auth/login');
		}
	}

	public function index()
	{
		if ($this->orders->count_all_records() <= 0) {
			$data['page'] = 'no_orders';
			$data['title'] = 'No hay pedidos';
			$data['js_files'] = [
				base_url('assets/js/empty.vendor.min.js'),
			];
			$this->load->view('layouts/dashboard_layout', $data);
		} else {
			$data['page'] = 'order_list';
			$data['title'] = 'Pedidos';
			$data['js_files'] = [
				base_url('assets/js/orders.vendor.min.js'),
				base_url('assets/js/orders.min.js'),
			];
			$this->load->view('layouts/dashboard_layout', $data);
		}
	}

	public function new_order()
	{

		if ($this->input->get('reuse')) {
			$order = $this->orders->get_order_by_id($this->input->get('reuse'));



			if (!empty($order)) {
				$data['order'] = $order;
				$data['customer'] = $this->customers->get_customer_by_id($order->cliente_id);
				$data['lines'] = $this->orders->get_lines_by_id(
					$this->input->get('reuse')
				);
				$data['total_paid'] = $this->payments->get_total_paid([
					'id' => $order->id,
					'customer_id' => $order->cliente_id
				]);

			}
		}


		$company_logo = $this->settings->get_setting('logo_empresa');
		$business_name = $this->settings->get_setting('razon_social');
		$company_name = $this->settings->get_setting('nombre_empresa');
		$company_address = $this->settings->get_setting('domicilio_fiscal');


		$data['company_settings'] = [
			'company_logo' => $company_logo,
			'business_name' => $business_name,
			'company_name' => $company_name,
			'company_address' => $company_address
		];





		$next_order_number = $this->orders->get_next_order_number(date("Y"));
		$data['next_order_number'] = $next_order_number;
		$data['page'] = 'new_order';
		$data['title'] = 'Nuevo pedido';
		$data['js_files'] = [
			base_url('assets/js/document.vendor.min.js'),
			base_url('assets/js/document.min.js'),
			base_url('assets/js/payments.min.js')
		];
		$this->load->view('layouts/dashboard_layout', $data);
	}

	public function new_order_validation()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}
		$error_messages = [
			'required' => 'El campo "%s" es requerido',
			'max_length' => 'El tamaño máximo del campo "%s" es de %s caracteres',
			'numeric' => 'El campo "%s" debe contener solo números',
			'greater_than' => 'El campo "%s" debe contener un número mayor que %s',
			'less_than' => 'El campo "%s" debe contener un número menor que %s',
			'in_list' => 'El campo "%s" debe ser alguno de los siguientes valores: %s',
			'integer' => 'El campo "%s" debe ser entero'
		];
		$this->form_validation->set_rules('number', 'Folio', 'trim|required|max_length[255]', $error_messages);
		$this->form_validation->set_rules('date', 'Fecha de emisión ', 'trim|required|max_length[255]', $error_messages);
		$this->form_validation->set_rules('validity_in_days', 'Fecha de vencimiento', 'trim|required|integer|greater_than[-1]', $error_messages);
		$this->form_validation->set_rules('due_date', 'Fecha de vencimiento', 'trim|required|max_length[255]', $error_messages);
		$this->form_validation->set_rules('status', 'Estado', 'trim|required', $error_messages);
		$this->form_validation->set_rules('customer_id', 'ID del cliente', 'trim|required|integer', $error_messages);
		$this->form_validation->set_rules('sub_total', 'Subtotal', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
		$this->form_validation->set_rules('discount_type', 'Tipo de descuento', 'trim|in_list[fixed,percentage]', $error_messages);
		$this->form_validation->set_rules('discount', 'Descuento', 'trim|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
		$this->form_validation->set_rules('discount_val', 'Cantidad a descontar', 'trim|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
		$this->form_validation->set_rules('include_tax', 'Con IVA', 'trim|required|integer|in_list[1,0]', $error_messages);
		$this->form_validation->set_rules('tax', 'IVA', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
		$this->form_validation->set_rules('total', 'Total', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
		$this->form_validation->set_rules('notes', 'Notas', 'trim|max_length[255]', $error_messages);
		$items = $this->input->post('items');
		if (!empty($items)) {
			foreach ($items as $key => $val) {
				$this->form_validation->set_rules('items[' . $key . '][product_id]', 'ID del producto', 'trim|integer|greater_than[-1]', $error_messages);
				$this->form_validation->set_rules('items[' . $key . '][qty]', 'Cantidad', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
				$this->form_validation->set_rules('items[' . $key . '][name]', 'Nombre', 'trim|required|max_length[255]', $error_messages);
				$this->form_validation->set_rules('items[' . $key . '][description]', 'descripción', 'trim|max_length[255]', $error_messages);
				$this->form_validation->set_rules('items[' . $key . '][unit_price]', 'Precio unitario', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
				$this->form_validation->set_rules('items[' . $key . '][total]', 'Precio unitario', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
			}
		}
		$payments = $this->input->post('payments');
		if (!empty($payments)) {
			foreach ($payments as $key => $val) {
				$this->form_validation->set_rules('payments[' . $key . '][amount]', 'Cantidad', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
				$this->form_validation->set_rules('payments[' . $key . '][date]', 'Fecha', 'trim|required|max_length[255]', $error_messages);
				$this->form_validation->set_rules('payments[' . $key . '][type]', 'Tipo', 'trim|required|max_length[255]', $error_messages);
				$this->form_validation->set_rules('payments[' . $key . '][notes]', 'Notas', 'trim|max_length[255]', $error_messages);
			}
		}
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('errors', $this->form_validation->error_array());
			$this->session->set_flashdata('old', $this->input->post());
			redirect('admin/pedidos/nuevo');
		} else {
			$trans = $this->orders->create_order($this->input->post());
			if ($trans) {
				$this->session->set_flashdata('flash_message', [
					'type' => 'success',
					'title' => 'El pedido se creó con éxito',
				]);
				redirect('/admin/pedidos');
			}
		}
	}

	public function edit_order($id)
	{


		$order = $this->orders->get_order_by_id($id);
		if (!$order) {
			show_404();
		}
		$customer = $this->customers->get_customer_by_id($order->cliente_id);
		$lines = $this->orders->get_lines_by_id($id);
		$data = [
			'id' => $order->id,
			'customer_id' => $order->cliente_id
		];


		$company_logo = $this->settings->get_setting('logo_empresa');
		$business_name = $this->settings->get_setting('razon_social');
		$company_name = $this->settings->get_setting('nombre_empresa');
		$company_address = $this->settings->get_setting('domicilio_fiscal');


		$data['company_settings'] = [
			'company_logo' => $company_logo,
			'business_name' => $business_name,
			'company_name' => $company_name,
			'company_address' => $company_address
		];


		$payments_made = $this->payments->get_all_payments_made($data);
		$total_paid = $this->payments->get_total_paid($data);
		$data['page'] = 'edit_order';
		$data['title'] = 'Editar pedido ' . $order->folio;
		$data['js_files'] = [
			base_url('assets/js/document.vendor.min.js'),
			base_url('assets/js/document.min.js'),
			base_url('assets/js/payments.min.js')
		];
		$data['payments_made'] = $payments_made;
		$data['total_paid'] = $total_paid;
		$data['customer'] = $customer;
		$data['order'] = $order;
		$data['lines'] = $lines;
		$this->load->view('layouts/dashboard_layout', $data);
	}

	public function edit_order_validation()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}
		$error_messages = [
			'required' => 'El campo "%s" es requerido',
			'max_length' => 'El tamaño máximo del campo "%s" es de %s caracteres',
			'numeric' => 'El campo "%s" debe contener solo números',
			'greater_than' => 'El campo "%s" debe contener un número mayor que %s',
			'less_than' => 'El campo "%s" debe contener un número menor que %s',
			'in_list' => 'El campo "%s" debe ser alguno de los siguientes valores: %s',
			'integer' => 'El campo "%s" debe ser entero'
		];
		$this->form_validation->set_rules('number', 'Folio', 'trim|required|max_length[255]', $error_messages);
		$this->form_validation->set_rules('date', 'Fecha de emisión ', 'trim|required|max_length[255]', $error_messages);
		$this->form_validation->set_rules('validity_in_days', 'Fecha de vencimiento', 'trim|required|integer|greater_than[-1]', $error_messages);
		$this->form_validation->set_rules('due_date', 'Fecha de vencimiento', 'trim|required|max_length[255]', $error_messages);
		$this->form_validation->set_rules('status', 'Estado', 'trim|required', $error_messages);
		$this->form_validation->set_rules('customer_id', 'ID del cliente', 'trim|required|integer', $error_messages);
		$this->form_validation->set_rules('sub_total', 'Subtotal', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
		$this->form_validation->set_rules('discount_type', 'Tipo de descuento', 'trim|in_list[fixed,percentage]', $error_messages);
		$this->form_validation->set_rules('discount', 'Descuento', 'trim|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
		$this->form_validation->set_rules('discount_val', 'Cantidad a descontar', 'trim|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
		$this->form_validation->set_rules('include_tax', 'Con IVA', 'trim|required|integer|in_list[1,0]', $error_messages);
		$this->form_validation->set_rules('tax', 'IVA', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
		$this->form_validation->set_rules('amount_due', 'Saldo', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
		$this->form_validation->set_rules('total', 'Total', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
		$this->form_validation->set_rules('notes', 'Notas', 'trim|max_length[255]', $error_messages);
		$items = $this->input->post('items');
		if (!empty($items)) {
			foreach ($items as $key => $val) {
				$this->form_validation->set_rules('items[' . $key . '][product_id]', 'ID del producto', 'trim|integer|greater_than[-1]', $error_messages);
				$this->form_validation->set_rules('items[' . $key . '][qty]', 'Cantidad', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
				$this->form_validation->set_rules('items[' . $key . '][name]', 'Nombre', 'trim|required|max_length[255]', $error_messages);
				$this->form_validation->set_rules('items[' . $key . '][description]', 'descripción', 'trim|max_length[255]', $error_messages);
				$this->form_validation->set_rules('items[' . $key . '][unit_price]', 'Precio unitario', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
				$this->form_validation->set_rules('items[' . $key . '][total]', 'Precio unitario', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
			}
		}
		$payments = $this->input->post('payments');
		if (!empty($payments)) {
			foreach ($payments as $key => $val) {
				$this->form_validation->set_rules('payments[' . $key . '][amount]', 'Cantidad', 'trim|required|numeric|greater_than[-1]|less_than[1000000]', $error_messages);
				$this->form_validation->set_rules('payments[' . $key . '][date]', 'Fecha', 'trim|required|max_length[255]', $error_messages);
				$this->form_validation->set_rules('payments[' . $key . '][type]', 'Tipo', 'trim|required|max_length[255]', $error_messages);
				$this->form_validation->set_rules('payments[' . $key . '][notes]', 'Notas', 'trim|max_length[255]', $error_messages);
			}
		}
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('errors', $this->form_validation->error_array());
			$this->session->set_flashdata('old', $this->input->post());
			redirect('admin/pedidos/' . $this->input->post('id'));
		} else {
			$trans = $this->orders->update_order($this->input->post());
			if ($trans) {
				$this->session->set_flashdata('flash_message', [
					'type' => 'success',
					'title' => 'Los datos se actualizaron correctamente',
				]);
				redirect('/admin/pedidos');
			}
		}
	}

	public function get_orders_ajax()
	{
		$datatables = new Datatables(new CodeigniterAdapter);
		$sql = '
	
	SELECT 
		  p.id, 
		  p.folio, 
		  c.nombre_razon_social as cliente, 
		  c.rfc, 
		  c.correo_electronico as email, 
		  CASE WHEN MONTH(p.fecha_pedido) = 1 THEN CONCAT(
			p.fecha_pedido, 
			"/", 
			"Enero"
		  ) WHEN MONTH(p.fecha_pedido) = 2 THEN CONCAT(
			p.fecha_pedido, 
			"/", 
			"Febrero"
		  ) WHEN MONTH(p.fecha_pedido) = 3 THEN CONCAT(
			p.fecha_pedido, 
			"/", 
			"Marzo"
		  ) WHEN MONTH(p.fecha_pedido) = 4 THEN CONCAT(
			p.fecha_pedido, 
			"/", 
			"Abril"
		  ) WHEN MONTH(p.fecha_pedido) = 5 THEN CONCAT(
			p.fecha_pedido, 
			"/", 
			"Mayo"
		  ) WHEN MONTH(p.fecha_pedido) = 6 THEN CONCAT(
			p.fecha_pedido, 
			"/", 
			"Junio"
		  ) WHEN MONTH(p.fecha_pedido) = 7 THEN CONCAT(
			p.fecha_pedido, 
			"/", 
			"Julio"
		  ) WHEN MONTH(p.fecha_pedido) = 8 THEN CONCAT(
			p.fecha_pedido, 
			"/", 
			"Agosto"
		  ) WHEN MONTH(p.fecha_pedido) = 9 THEN CONCAT(
			p.fecha_pedido, 
			"/", 
			"Septiembre"
		  ) WHEN MONTH(p.fecha_pedido) = 10 THEN CONCAT(
			p.fecha_pedido, 
			"/", 
			"Octubre"
		  ) WHEN MONTH(p.fecha_pedido) = 11 THEN CONCAT(
			p.fecha_pedido, 
			"/", 
			"Noviembre"
		  ) ELSE CONCAT(
			p.fecha_pedido, 
			"/", 
			"Diciembre"
		  ) END AS fecha, 
		  p.total, 
		  p.saldo, 
		  CASE WHEN p.status = "paid" 
		  AND p.eliminado_en IS NULL THEN "Pagado" WHEN p.status = "partially_paid" 
		  AND p.eliminado_en IS NULL THEN "Parcialmente pagado" WHEN p.status = "unpaid" 
		  AND p.eliminado_en IS NULL THEN "No pagado" ELSE "cancelado" END AS estado, 
		  CASE WHEN p.status = "paid" 
		  AND p.eliminado_en IS NULL THEN "success" WHEN p.status = "partially_paid" 
		  AND p.eliminado_en IS NULL THEN "info" WHEN p.status = "unpaid" 
		  AND p.eliminado_en IS NULL THEN "warning" ELSE "danger" END AS color_fondo 
		FROM 
		  pedidos p 
		  INNER JOIN clientes c ON p.cliente_id = c.id 
		ORDER BY 
		  p.folio DESC
	
	
		';
		$datatables->query($sql);
		$datatables->hide('id');
		$datatables->hide('email');
		$datatables->hide('color_fondo');
		$datatables->edit('folio', function ($data) {
			return '<strong>' . $data['folio'] . '</strong>';
		});

		$datatables->edit('fecha', function ($data) {
			$full_date = explode("/", $data['fecha']);
			$day = date('d', strtotime($full_date[0]));
			$month = $full_date[1];
			$year = date('Y', strtotime($full_date[0]));
			$date_dmy = $day . '/' . $month . '/' . $year;
			return $date_dmy;
		});

		$datatables->edit('estado', function ($data) {
			$status = $data['estado'];
			$bg_color = $data['color_fondo'];
			return '<span class="py-2 px-3 badge badge-pill badge-' . $bg_color . '"><span class="font-weight-bold">' . strtoupper($status) . '</span></span>';
		});
		$datatables->edit('total', function ($data) {
			return "$" . number_format($data['total'], 2);
		});
		$datatables->edit('saldo', function ($data) {
			return "$" . number_format($data['saldo'], 2);
		});
//		$datatables->add('action', function ($data) {
//			$csrf = array(
//				'name' => $this->security->get_csrf_token_name(),
//				'hash' => $this->security->get_csrf_hash()
//			);
//			$status = $data['estado'];
//			$data['url'] = base_url('admin/pedidos/pdf/') . $data['id'];
//			$view_button = $this->load->view('partials/view_button', $data, true);
//			$mail_is_enabled = $this->settings->get_setting('mail_is_enabled');
//			if ((integer)$mail_is_enabled === 1 && $data['email']) {
//				$data['id'] =  $data['id'];
//				$data['company_name'] = $this->settings->get_setting('nombre_empresa');
//				$data['document_id'] = $data['folio'];
//				$data['email'] = $data['email'];
//				$email_button = $this->load->view('partials/email_button', $data, true);
//			} else {
//				$email_button = '';
//			}
//			$data['url'] = base_url('admin/pedidos/') . $data['id'];
//			$data['status'] = $status;
//			$edit_button = $this->load->view('partials/edit_button', $data, true);
//			$data['url'] = base_url('admin/orders/delete_order');
//			$data['id'] = $data['id'];
//			$data['csrf_name'] = $csrf['name'];
//			$data['csrf_hash'] = $csrf['hash'];
//			$data['status'] = $status;
//			$delete_button = $this->load->view('partials/cancel_button', $data, true);
//			$data['id'] = $data['id'];
//			$data['csrf_name'] = $csrf['name'];
//			$data['csrf_hash'] = $csrf['hash'];
//			return $view_button . $email_button . $edit_button . $delete_button;
//		});


		$datatables->add('action', function ($data) {
			$csrf = [
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			];

			$mail_is_enabled = $this->settings->get_setting('mail_is_enabled');

			$data['id'] = $data['id'];
			$data['status'] = $data['estado'];
			$data['csrf_name'] = $csrf['name'];
			$data['csrf_hash'] = $csrf['hash'];

			$data['url'] = base_url('admin/pedidos/pdf/') . $data['id'];

			$email_button = '';
			$view_button = $this->load->view('partials/view_button', $data, true);
			$edit_button = '';
			$delete_button = '';
			$reuse_button = '';

			if ((integer)$mail_is_enabled === 1 && !empty($data['email'])) {
				$data['company_name'] = $this->settings->get_setting('nombre_empresa');
				$data['document_id'] = $data['folio'];
				$data['email'] = $data['email'];
				$email_button = $this->load->view('partials/email_button', $data, true);
			}

			if ($data['estado'] !== 'cancelado') {
				$data['url'] = base_url('admin/pedidos/') . $data['id'];
				$edit_button = $this->load->view('partials/edit_button', $data, true);

				$data['url'] = base_url('admin/orders/delete_order');
				$delete_button = $this->load->view('partials/cancel_button', $data, true);

				$data['url'] = base_url('admin/orders/new_order');
				$reuse_button = $this->load->view('partials/reuse_button', $data, true);


			}

			return $view_button . $email_button . $reuse_button . $edit_button . $delete_button;

		});


		echo $datatables->generate();
	}

	public function delete_order()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			show_404();
		}
		if ($this->input->post('id')) {
			$affected_rows = $this->orders->delete_order($this->input->post('id'));
			if ($affected_rows > 0) {
				$this->session->set_flashdata('flash_message', [
					'type' => 'success',
					'title' => 'El pedido se canceló con éxito',
				]);
				$this->session->set_flashdata('old', $this->input->post());
				redirect('admin/pedidos');
			}
		}
	}

	public function get_products_ajax()
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
			$data = $this->products->get_products_for_typeahead($search);
			$this->output
				->set_header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK')
				->set_content_type('application/json')
				->set_output(json_encode($data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
		}
	}

	public function get_customers_ajax()
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
			$data = $this->customers->get_customers_for_select2($start, $length, $search);
			$this->output
				->set_header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK')
				->set_content_type('application/json')
				->set_output(json_encode($data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
		}
	}

	public function get_pdf($id)
	{

		$company_logo = $this->settings->get_setting('logo_empresa');
		$business_name = $this->settings->get_setting('razon_social');
		$company_name = $this->settings->get_setting('nombre_empresa');
		$company_address = $this->settings->get_setting('domicilio_fiscal');


		$data['company_settings'] = [
			'company_logo' => $company_logo,
			'business_name' => $business_name,
			'company_name' => $company_name,
			'company_address' => $company_address
		];


		$order = $this->orders->get_any_order_by_id($id);
		if (!$order) {
			show_404();
		}
		$customer = $this->customers->get_customer_by_id($order->cliente_id);
		$lines = $this->orders->get_lines_by_id($id);
		$order_number = $order->folio;
		$data['page'] = 'pdf_order';
		$data['title'] = 'Pedido ' . $order_number;
		$data['css_files'] = [
			base_url('assets/css/print.min.css'),
		];
		$data['customer'] = $customer;
		$data['order'] = $order;
		$data['lines'] = $lines;
		$html = $this->load->view('layouts/pdf_layout', $data, true);
		$dompdf = new Dompdf();
		$html = preg_replace('/>\s+</', "><", $html);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('letter', 'portrait');
		$dompdf->render();
		$dompdf->stream('pedido-' . strtolower($order_number) . '.pdf', array("Attachment" => false));
	}

	public function generate_pdf_report()
	{
		$spanish_months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		$report_type = $this->input->get('report_type');
		$month = $this->input->get('month');
		$year = $this->input->get('year');
		if (!$report_type || !$month || !$year || intval($month) > 12) {
			show_404();
		}
		if (!is_numeric($month) && $month !== 'all') {
			show_404();
		}
		$spanish_month = ($month == 'all') ? 'anual' : $spanish_months[intval($month) - 1];
		$title = ($month == 'all') ? 'Pedidos del ' . $year : 'Pedidos de ' . $spanish_month . ' del ' . $year;
		if ($month == 'all') {
			if ($report_type == 'orders') {
				$orders = $this->orders->get_annual_report($year);
				$data['page'] = 'pdf_order_report';
			} else {
				$orders = $this->orders->get_annual_report_by_customers($year);
				$data['page'] = 'pdf_order_report_2';
			}
			$sum_of_total = $this->orders->get_annual_sum($year);
		} else {
			if ($report_type == 'orders') {
				$orders = $this->orders->get_monthly_report($month, $year);
				$data['page'] = 'pdf_order_report';
			} else {
				$orders = $this->orders->get_monthly_report_by_customers($month, $year);
				$data['page'] = 'pdf_order_report_2';
			}
			$sum_of_total = $this->orders->get_monthly_sum($month, $year);
		}
		$data['title'] = $title;
		$data['css_files'] = [
			base_url('assets/css/report-to-print.min.css'),
		];
		$data['orders'] = $orders;
		$data['month'] = $spanish_month;
		$data['year'] = $year;
		$data['sum_of_total'] = $sum_of_total;
		$html = $this->load->view('layouts/pdf_layout', $data, true);
		$dompdf = new Dompdf();
		$html = preg_replace('/>\s+</', "><", $html);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('letter', 'portrait');
		$dompdf->render();
		$dompdf->stream('reporte_presupuestos_' . strtolower($spanish_month) . '_' . $year . '.pdf', array("Attachment" => false));
	}
}

?>
