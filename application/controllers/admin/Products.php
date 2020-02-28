<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
            $data['js_files'] = [
                base_url('assets/js/empty.vendor.min.js'),
            ];
            $this->load->view('layouts/dashboard_layout', $data);
        } else {
            $data['page'] = 'product_list';
            $data['title'] = 'Productos';
            $data['js_files'] = [
                base_url('assets/js/products.vendor.min.js'),
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
            base_url('assets/js/product.vendor.min.js'),
            base_url('assets/js/product.min.js')
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
            'required' => 'El campo "%s"  es requerido',
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
            if ($affected_rows > 0) {
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
            return '<strong>' . $data['id'] . '</strong>';
        });
        $datatables->edit('precio_unitario', function ($data) {
            return "$" . number_format($data['precio_unitario'], 2);
        });
        $datatables->add('action', function ($data) {
            $csrf = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
            $data['url'] = base_url('admin/productos/') . $data['id'];
            $edit_button = $this->load->view('partials/edit_button', $data, true);
            $data['url'] = base_url('admin/products/delete_product_validation');
            $data['id'] = $data['id'];
            $data['csrf_name'] = $csrf['name'];
            $data['csrf_hash'] = $csrf['hash'];
            $delete_button = $this->load->view('partials/delete_button', $data, true);
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
            base_url('assets/js/product.vendor.min.js'),
            base_url('assets/js/product.min.js')
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
            'required' => 'El campo "%s"  es requerido',
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
            if ($affected_rows > 0) {
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
