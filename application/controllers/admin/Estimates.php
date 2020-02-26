<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once 'vendor/autoload.php';

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;
use Dompdf\Dompdf;

class Estimates extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Estimates_model', 'estimates');
        $this->load->model('Orders_model', 'orders');
        $this->load->model('Customers_model', 'customers');
        $this->load->model('Products_model', 'products');
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
                base_url('assets/js/estimates.vendor.min.js'),
                base_url('assets/js/estimates.min.js')
            ];
            $this->load->view('layouts/dashboard_layout', $data);
        }
    }

    public function new_estimate()
    {
        $next_estimate_number = $this->estimates->get_next_estimate_number(date("Y"));
        $data['next_estimate_number'] = $next_estimate_number;
        $data['page'] = 'new_estimate';
        $data['title'] = 'Nuevo presupuesto';
        $data['js_files'] = [
            base_url('assets/js/document.vendor.min.js'),
            base_url('assets/js/document.min.js')
        ];
        $this->load->view('layouts/dashboard_layout', $data);
    }

    public function new_estimate_validation()
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
            redirect('admin/presupuestos/nuevo');
        } else {
            $trans = $this->estimates->create_estimate($this->input->post());
            if ($trans) {
                $this->session->set_flashdata('flash_message', [
                    'type' => 'success',
                    'title' => 'El presupuesto se creó con éxito',
                ]);
                redirect('/admin/presupuestos');
            }
        }
    }

    public function edit_estimate($id)
    {
        $estimate = $this->estimates->get_estimate_by_id($id);
        if (!$estimate) {
            show_404();
        }
        $customer = $this->customers->get_customer_by_id($estimate->cliente_id);
        $lines = $this->estimates->get_lines_by_id($id);
        $data['page'] = 'edit_estimate';
        $data['title'] = 'Editar presupuesto ' . $estimate->folio;
        $data['js_files'] = [
            base_url('assets/js/document.vendor.min.js'),
            base_url('assets/js/document.min.js')
        ];
        $data['customer'] = $customer;
        $data['estimate'] = $estimate;
        $data['lines'] = $lines;
        $this->load->view('layouts/dashboard_layout', $data);
    }

    public function edit_estimate_validation()
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
            redirect('admin/presupuestos/' . $this->input->post('id'));
        } else {
            $trans = $this->estimates->update_estimate($this->input->post());
            if ($trans) {
                $this->session->set_flashdata('flash_message', [
                    'type' => 'success',
                    'title' => 'Los datos se actualizaron correctamente',
                ]);
                redirect('/admin/presupuestos');
            }
        }
    }

    public function duplicate_estimate()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            show_404();
        }
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
            $estimate = $this->estimates->get_estimate_by_id($id);
            $next_estimate_number = $this->estimates->get_next_estimate_number(date("Y"));
            $lines = $this->estimates->get_lines_by_id($id);
            $new_items = [];
            foreach ($lines as $line) {
                array_push($new_items, [
                    'name' => $line->nombre,
                    'description' => $line->descripcion,
                    'qty' => $line->cantidad,
                    'unit_price' => $line->precio_unitario,
                    'total' => $line->total,
                    'product_id' => $line->producto_id
                ]);
            }
            $new_estimate = [
                'number' => $next_estimate_number,
                'date' => $estimate->fecha_presupuesto,
                'validity_in_days' => $estimate->validez_en_dias,
                'due_date' => $estimate->fecha_vencimiento,
                'status' => $estimate->status,
                'notes' => $estimate->notas,
                'sub_total' => $estimate->sub_total,
                'discount_type' => $estimate->tipo_descuento,
                'discount' => $estimate->descuento,
                'discount_val' => $estimate->cantidad_descontada,
                'include_tax' => $estimate->incluir_impuesto,
                'tax' => $estimate->impuesto,
                'total' => $estimate->total,
                'customer_id' => $estimate->cliente_id,
                'items' => $new_items,
            ];
            $trans = $this->estimates->create_estimate($new_estimate);
            if ($trans) {
                $this->session->set_flashdata('flash_message', [
                    'type' => 'success',
                    'title' => 'El presupuesto se duplicó con éxito',
                ]);
                $estimate = $this->estimates->get_estimate_by_number($next_estimate_number);

                if ($estimate) {

                    $this->session->set_flashdata('flash_message', [
                        'type' => 'success',
                        'title' => 'El presupuesto se duplicó con éxito',
                    ]);

                    redirect('admin/presupuestos/' . $estimate->id);

                }
            }
        }
    }

    //convert_to_order

    public function convert_to_order()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            show_404();
        }
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
            $estimate = $this->estimates->get_estimate_by_id($id);
            $next_order_number = $this->orders->get_next_order_number(date("Y"));
            $lines = $this->estimates->get_lines_by_id($id);
            $estimate_items = [];
            foreach ($lines as $line) {
                array_push($estimate_items, [
                    'name' => $line->nombre,
                    'description' => $line->descripcion,
                    'qty' => $line->cantidad,
                    'unit_price' => $line->precio_unitario,
                    'total' => $line->total,
                    'product_id' => $line->producto_id
                ]);
            }
            $estimate_data = [
                'number' => $next_order_number,
                'date' => $estimate->fecha_presupuesto,
                'validity_in_days' => $estimate->validez_en_dias,
                'due_date' => $estimate->fecha_vencimiento,
                'status' => 'unpaid',
                'notes' => $estimate->notas,
                'sub_total' => $estimate->sub_total,
                'discount_type' => $estimate->tipo_descuento,
                'discount' => $estimate->descuento,
                'discount_val' => $estimate->cantidad_descontada,
                'include_tax' => $estimate->incluir_impuesto,
                'tax' => $estimate->impuesto,
                'total' => $estimate->total,
                'amount_due' => $estimate->total,
                'customer_id' => $estimate->cliente_id,
                'items' => $estimate_items,
            ];

            $trans = $this->orders->create_order($estimate_data);

            if ($trans) {
                $new_order = $this->orders->get_order_by_number($next_order_number);
                if ($new_order) {
                    $this->session->set_flashdata('flash_message', [
                        'type' => 'success',
                        'title' => 'El presupuesto se convirtió en pedido con éxito',
                    ]);
                    redirect('admin/pedidos/' . $new_order->id);
                }
            }
        }
    }

    public function get_estimates_ajax()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $sql = "SELECT
				p.id,
				p.folio,
				CONCAT(c.nombre, ' ', c.apellidos ) as cliente,
				p.status,
				p.fecha_presupuesto,
				p.total
				FROM presupuestos p
				INNER JOIN clientes c
				ON p.cliente_id = c.id
				WHERE p.eliminado_en IS NULL";
        $datatables->query($sql);
        $datatables->hide('id');
        $datatables->edit('folio', function ($data) {
            return '<strong>' . $data['folio'] . '</strong>';
        });
        $datatables->edit('fecha_presupuesto', function ($data) {
            $date_dmy = date('d/m/Y', strtotime($data['fecha_presupuesto']));
            return $date_dmy;
        });
        $datatables->edit('status', function ($data) {
            $status = $data['status'];
            switch ($status) {
                case 'accepted':
                    $color = 'success';
                    $status_text = 'aceptado';
                    break;
                case 'rejected':
                    $color = 'danger';
                    $status_text = 'rechazado';
                    break;
                default:
                    $color = 'light';
                    $status_text = 'borrador';
            }
            return '<span class="py-2 px-3 badge badge-pill badge-' . $color . '"><span class="font-weight-bold">' . strtoupper($status_text) . '</span></span>';
        });
        $datatables->edit('total', function ($data) {
            return "$" . number_format($data['total'], 2);
        });
        $datatables->add('action', function ($data) {
            $csrf = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
            $data['url'] = base_url('admin/presupuestos/pdf/') . $data['id'];
            $view_button = $this->load->view('partials/view_button', $data, true);
            $data['url'] = base_url('admin/presupuestos/') . $data['id'];
            $edit_button = $this->load->view('partials/edit_button', $data, true);
            $data['url'] = base_url('admin/estimates/delete_estimate');
            $data['id'] = $data['id'];
            $data['csrf_name'] = $csrf['name'];
            $data['csrf_hash'] = $csrf['hash'];
            $delete_button = $this->load->view('partials/delete_button', $data, true);
            $data['id'] = $data['id'];
            $data['csrf_name'] = $csrf['name'];
            $data['csrf_hash'] = $csrf['hash'];
            $more_button = $this->load->view('partials/more_button_1', $data, true);
            return $view_button . $edit_button . $delete_button . $more_button;
        });
        echo $datatables->generate();
    }

    public function delete_estimate()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            show_404();
        }
        if ($this->input->post('id')) {
            $affected_rows = $this->estimates->delete_estimate($this->input->post('id'));
            if ($affected_rows > 0) {
                $this->session->set_flashdata('flash_message', [
                    'type' => 'success',
                    'title' => 'El presupuesto se eliminó con éxito',
                ]);
                $this->session->set_flashdata('old', $this->input->post());
                redirect('admin/presupuestos');
            }
        }
    }

    public function change_status()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            show_404();
        }
        if ($this->input->post('id') && $this->input->post('status')) {
            $affected_rows = $this->estimates->change_status($this->input->post());
            if ($affected_rows >= 0) {
                $status = $this->input->post('status');
                switch ($status) {
                    case 'accepted':
                        $status_text = 'aceptado';
                        break;
                    case 'rejected':
                        $status_text = 'rechazado';
                        break;
                    default:
                        $status_text = 'borrador';
                }
                $this->session->set_flashdata('flash_message', [
                    'type' => 'success',
                    'title' => 'Presupuesto marcado como ' . $status_text,
                ]);
                $this->session->set_flashdata('old', $this->input->post());
                redirect('admin/presupuestos');
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
        $estimate = $this->estimates->get_estimate_by_id($id);
        if (!$estimate) {
            show_404();
        }
        $customer = $this->customers->get_customer_by_id($estimate->cliente_id);
        $lines = $this->estimates->get_lines_by_id($id);
        $estimate_number = $estimate->folio;
        $data['page'] = 'pdf_estimate';
        $data['title'] = 'Presupuesto ' . $estimate_number;
        $data['css_files'] = [
            base_url('assets/css/print.min.css'),
        ];
        $data['customer'] = $customer;
        $data['estimate'] = $estimate;
        $data['lines'] = $lines;
        $html = $this->load->view('layouts/pdf', $data, true);
        $dompdf = new Dompdf();
        $html = preg_replace('/>\s+</', "><", $html);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream('presupuesto-' . strtolower($estimate_number) . '.pdf', array("Attachment" => false));
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


        $estimates = $this->estimates->get_estimates_for_report($month, $year);
        $sum_of_subtotal = $this->estimates->get_sum_of_subtotal($month, $year);
        $sum_of_tax = $this->estimates->get_sum_of_tax($month, $year);
        $sum_of_total = $this->estimates->get_sum_of_total($month, $year);
        $data['page'] = 'pdf_estimate_report';
        $data['title'] = 'Presupuestos de ' . $spanish_month . ' del ' . $year;
        $data['css_files'] = [
            base_url('assets/css/report-to-print.min.css'),
        ];
        $data['estimates'] = $estimates;
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
        $dompdf->stream('reporte_presupuestos_' . strtolower($spanish_month) .  '_' . $year . '.pdf', array("Attachment" => false));
    }

    public function view_estimate($id)
    {
        $product = $this->estimates->get_estimate_by_id($id);
        if (!$product) {
            show_404();
        }
        $data['page'] = 'view_estimate';
        $data['title'] = 'Editar producto #' . $id;
        $this->load->view('layouts/dashboard_layout', $data);
    }
}

?>