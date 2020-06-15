<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
            base_url('assets/js/login.vendor.min.js'),
            base_url('assets/js/login.min.js')
        ];
        $this->load->view('layouts/full_height_layout', $data);
    }

    public function login_validation()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            show_404();
        }
        $error_messages = [
            'required' => 'Este campo  es requerido',
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
                    'role' => $user->rol,
                    'avatar' => $user->avatar
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

    public function verify_password_ajax()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            show_404();
        }
        $user = $this->users->get_user($this->input->post('username'));
        $pass = (isset($user->clave)) ? $user->clave : '';
        $res = [
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ];
        if (!$user) {
            $res['password_is_valid'] = true;
        } elseif ($user && password_verify($this->input->post('password'), $pass)) {
            $res['password_is_valid'] = true;
        } else {
            $res['password_is_valid'] = false;
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
