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
                base_url('assets/js/orders.min.js')
            ];
            $this->load->view('layouts/dashboard_layout', $data);
        }
    }

    public function new_order()
    {
        $next_order_number = $this->orders->get_next_order_number(date("Y"));
        $data['next_order_number'] = $next_order_number;
        $data['page'] = 'new_order';
        $data['title'] = 'Nuevo pedido';
        $data['js_files'] = [
            base_url('assets/js/document.vendor.min.js'),
            base_url('assets/js/document.min.js')
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
        $this->form_validation->set_rules('notes', 'Notas', 'trim|max_length[255]', $error_messages);
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

       $payments_made = $this->payments->get_all_payments_made($data);
//
//        echo "<pre>";
//        var_dump($payments_made);
//        echo "</pre>";
//        die();




        $data['page'] = 'edit_order';
        $data['title'] = 'Editar pedido ' . $order->folio;
        $data['js_files'] = [
            base_url('assets/js/document.vendor.min.js'),
            base_url('assets/js/document.min.js')
        ];
        $data['payments_made'] = $payments_made;
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
        $this->form_validation->set_rules('notes', 'Notas', 'trim|max_length[255]', $error_messages);
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
        $sql = "SELECT
				p.id,
				p.folio,
				CONCAT(c.nombre, ' ', c.apellidos ) as cliente,
				p.status,
				p.fecha_pedido,
				p.total,
				p.saldo
				FROM pedidos p
				INNER JOIN clientes c
				ON p.cliente_id = c.id
				WHERE p.eliminado_en IS NULL";
        $datatables->query($sql);
        $datatables->hide('id');
        $datatables->edit('folio', function ($data) {
            return '<strong>' . $data['folio'] . '</strong>';
        });
        $datatables->edit('fecha_pedido', function ($data) {
            $date_dmy = date('d/m/Y', strtotime($data['fecha_pedido']));
            return $date_dmy;
        });
        $datatables->edit('status', function ($data) {
            $status = $data['status'];
            switch ($status) {
                case 'paid':
                    $color = 'success';
                    $status_text = 'Pagado';
                    break;
                case 'partially_paid':
                    $color = 'warning';
                    $status_text = 'Parcialmente pagado';
                    break;
                default:
                    $color = 'danger';
                    $status_text = 'No pagado';
            }
            return '<span class="py-2 px-3 badge badge-pill badge-' . $color . '"><span class="font-weight-bold">' . strtoupper($status_text) . '</span></span>';
        });
        $datatables->edit('total', function ($data) {
            return "$" . number_format($data['total'], 2);
        });

        $datatables->edit('saldo', function ($data) {
            return "$" . number_format($data['saldo'], 2);
        });
        $datatables->add('action', function ($data) {
            $csrf = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
            $data['url'] = base_url('admin/pedidos/pdf/') . $data['id'];
            $view_button = $this->load->view('partials/view_button', $data, true);
            $data['url'] = base_url('admin/pedidos/') . $data['id'];
            $edit_button = $this->load->view('partials/edit_button', $data, true);
            $data['url'] = base_url('admin/orders/delete_order');
            $data['id'] = $data['id'];
            $data['csrf_name'] = $csrf['name'];
            $data['csrf_hash'] = $csrf['hash'];
            $delete_button = $this->load->view('partials/delete_button', $data, true);
            $data['id'] = $data['id'];
            $data['csrf_name'] = $csrf['name'];
            $data['csrf_hash'] = $csrf['hash'];
            $more_button = $this->load->view('partials/more_button_1', $data, true);
            return $view_button . $edit_button . $delete_button ;
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
                    'title' => 'El pedido se eliminó con éxito',
                ]);
                $this->session->set_flashdata('old', $this->input->post());
                redirect('admin/pedidos');
            }
        }
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
            $data = $this->products->get_products_for_typeahead($search);
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
            $data = $this->customers->get_customers_for_select2($start, $length, $search);
            $this->output
                ->set_header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK')
                ->set_content_type('application/json')
                ->set_output(json_encode($data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
        }
    }


    function get_pdf($id)
    {
        $order = $this->orders->get_order_by_id($id);
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
        $html = $this->load->view('layouts/pdf', $data, true);
        $dompdf = new Dompdf();
        $html = preg_replace('/>\s+</', "><", $html);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream('pedido-' . strtolower($order_number) . '.pdf', array("Attachment" => false));
    }


    function generate_pdf_report()
    {
        $spanish_months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $month = $this->input->get('month');
        $year = $this->input->get('year');
        if  ( !$month || !$year  || intval($month) > 12 ) {
            show_404();
        }
        $spanish_month =  $spanish_months[intval($month) - 1];
        $orders = $this->orders->get_orders_for_report($month, $year);
        $sum_of_subtotal = $this->orders->get_sum_of_subtotal($month, $year);
        $sum_of_tax = $this->orders->get_sum_of_tax($month, $year);
        $sum_of_total = $this->orders->get_sum_of_total($month, $year);
        $data['page'] = 'pdf_order_report';
        $data['title'] = 'Pedidos de ' . $spanish_month . ' del ' . $year;
        $data['css_files'] = [
            base_url('assets/css/report-to-print.min.css'),
        ];
        $data['orders'] = $orders;
        $data['month'] = $spanish_month;
        $data['year'] = $year;
        $data['sum_of_subtotal'] = $sum_of_subtotal;
        $data['sum_of_tax'] = $sum_of_tax;
        $data['sum_of_total'] = $sum_of_total;
        $html = $this->load->view('layouts/pdf', $data, true);
        $dompdf = new Dompdf();
        $html = preg_replace('/>\s+</', "><", $html);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream('reporte_pedidos_' . strtolower($spanish_month) .  '_' . $year . '.pdf', array("Attachment" => false));
    }



//    public function get_total_paid_ajax()
//    {
//        $order_id = $this->input->get('order_id');
//        $customer_id = $this->input->get('customer_id');
//        if ($order_id && $customer_id) {
//            $total_paid = $this->payments->get_total_paid($order_id, $customer_id);
//            $res = [
//                'total_paid' => $total_paid
//            ];
//            $this->output
//                ->set_header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK')
//                ->set_content_type('application/json')
//                ->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
//        }
//    }
//
//    public function get_amount_due_ajax()
//    {
//        $id = $this->input->get('id');
//        if ($id) {
//            $amount_due = $this->orders->get_amount_due_by_id($id);
//            $res = [
//                'amount_due' => $amount_due
//            ];
//            $this->output
//                ->set_header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK')
//                ->set_content_type('application/json')
//                ->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
//
//        }
//    }

    public function new_payment_ajax()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            show_404();
        }

        if ($this->input->post('unit_price')) {
            $_POST['unit_price'] = remove_commas($_POST['unit_price']);
        }

        $common_error_messages = [
            'required' => 'El campo "%s" es requerido',
            'max_length' => 'El tamaño máximo del campo "%s" es de %s caracteres',
            'numeric' => 'El campo "%s" debe contener solo números',
            'greater_than' => 'El campo "%s" debe contener un número mayor que %s',
            'less_than' => 'El campo "%s" debe contener un número menor que %s',
            'integer' => 'El campo "%s" debe ser entero'
        ];

        $this->form_validation->set_rules('customer_id', 'ID del cliente', 'trim|required|integer', $common_error_messages);
        $this->form_validation->set_rules('order_id', 'ID del pedido', 'trim|required|integer', $common_error_messages);
        $this->form_validation->set_rules('amount', 'Cantidad', 'trim|required|numeric|greater_than[0]|less_than[1000000]', $common_error_messages);
        $this->form_validation->set_rules('payment_method', 'Método de pago', 'trim|max_length[255]', $common_error_messages);
        $this->form_validation->set_rules('date', 'Fecha', 'trim|max_length[255]', $common_error_messages);
        $this->form_validation->set_rules('notes', 'Notas', 'trim|max_length[255]', $common_error_messages);

        if ($this->form_validation->run() == FALSE) {
            $res = [
                'message' => "error",
                'errors' => $this->form_validation->error_array(),
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash()
            ];
            $this->output
                ->set_header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request')
                ->set_content_type('application/json')
                ->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
        } else {
            $trans = $this->payments->create_payment($this->input->post());
            if ($trans) {
                $order = [
                    'id' => $this->input->post('order_id'),
                    'customer_id' => $this->input->post('customer_id')
                ];
                $total_paid = $this->payments->get_total_paid($order);
                $payments_made = $this->payments->get_all_payments_made($order);
                $res = [
                    'message' => "ok",
                    'total_paid' => $total_paid,
                    'payments_made' => $payments_made,
                    'csrfName' => $this->security->get_csrf_token_name(),
                    'csrfHash' => $this->security->get_csrf_hash()
                ];
                $this->output
                    ->set_header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK')
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
            }
        }
    }

    public function delete_payment_ajax()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            show_404();
        }

        if ($this->input->post('unit_price')) {
            $_POST['unit_price'] = remove_commas($_POST['unit_price']);
        }

        $common_error_messages = [
            'required' => 'El campo "%s" es requerido',
            'integer' => 'El campo "%s" debe ser entero'
        ];

        $this->form_validation->set_rules('id', 'ID del cliente', 'trim|required|integer', $common_error_messages);
        $this->form_validation->set_rules('order_id', 'ID del cliente', 'trim|required|integer', $common_error_messages);

        if ($this->form_validation->run() == FALSE) {
            $res = [
                'message' => "error",
                'errors' => $this->form_validation->error_array(),
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash()
            ];
            $this->output
                ->set_header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request')
                ->set_content_type('application/json')
                ->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
        } else {
            $trans = $this->payments->delete_payment($this->input->post());
            if ($trans) {
                $order = [
                    'id' => $this->input->post('order_id'),
                    'customer_id' => $this->input->post('customer_id')
                ];
                $total_paid = $this->payments->get_total_paid($order);
                $payments_made = $this->payments->get_all_payments_made($order);
                $res = [
                    'message' => "ok",
                    'total_paid' => $total_paid,
                    'payments_made' => $payments_made,
                    'csrfName' => $this->security->get_csrf_token_name(),
                    'csrfHash' => $this->security->get_csrf_hash()
                ];
                $this->output
                    ->set_header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK')
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
            }
        }
    }







    public function update_amount_due_ajax()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            show_404();
        }
        if ($this->input->post('unit_price')) {
            $_POST['unit_price'] = remove_commas($_POST['unit_price']);
        }
        $common_error_messages = [
            'required' => 'El campo "%s" es requerido',
            'numeric' => 'El campo "%s" debe contener solo números',
            'greater_than' => 'El campo "%s" debe contener un número mayor que %s',
            'less_than' => 'El campo "%s" debe contener un número menor que %s',
            'integer' => 'El campo "%s" debe ser entero'
        ];
        $this->form_validation->set_rules('id', 'ID del pedido', 'trim|required|integer', $common_error_messages);
        $this->form_validation->set_rules('amount_due', 'Saldo', 'trim|required|numeric|greater_than[0]|less_than[1000000]', $common_error_messages);
        if ($this->form_validation->run() == FALSE) {
            $res = [
                'message' => "error",
                'errors' => $this->form_validation->error_array(),
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash()
            ];
            $this->output
                ->set_header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request')
                ->set_content_type('application/json')
                ->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
        } else {
            $affected_rows = $this->orders->update_amount_due($this->input->post());
            if ($affected_rows > 0) {
                $res = [
                    'message' => "ok",
                    'csrfName' => $this->security->get_csrf_token_name(),
                    'csrfHash' => $this->security->get_csrf_hash()
                ];
                $this->output
                    ->set_header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK')
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
            }
        }
    }

}

?>
