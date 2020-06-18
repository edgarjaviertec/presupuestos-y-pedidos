<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once 'vendor/autoload.php';
class Settings extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Setting_model', 'settings');
		$this->load->helper('app');
		$this->load->library(['form_validation']);
		$logged_in_user = $this->session->userdata('logged_in_user');
		if (!$logged_in_user) {
			redirect('auth/login');
		} else if ($logged_in_user['role'] === 'user') {
			redirect('admin/clientes');
		}
	}

	public function edit_settings()
	{
		$company_logo = $this->settings->get_setting('logo_empresa');
		$business_name = $this->settings->get_setting('razon_social');
		$company_name = $this->settings->get_setting('nombre_empresa');
		$company_address = $this->settings->get_setting('domicilio_fiscal');
		$mail_is_enabled = $this->settings->get_setting('mail_is_enabled');
		$mail_host = $this->settings->get_setting('mail_host');
		$mail_username = $this->settings->get_setting('mail_username');
		$mail_password = $this->settings->get_setting('mail_password');
		$mail_port = $this->settings->get_setting('mail_port');
		$mail_smtp_secure = $this->settings->get_setting('mail_smtp_secure');
		$mail_from = $this->settings->get_setting('mail_from');
		$mail_from_name = $this->settings->get_setting('mail_from_name');
		$data['company_settings'] = [
			'company_logo' => $company_logo,
			'business_name' => $business_name,
			'company_name' => $company_name,
			'company_address' => $company_address
		];
		$data['mail_settings'] = [
			'mail_is_enabled' => $mail_is_enabled,
			'mail_host' => $mail_host,
			'mail_username' => $mail_username,
			'mail_password' => $mail_password,
			'mail_port' => $mail_port,
			'mail_smtp_secure' => $mail_smtp_secure,
			'mail_from' => $mail_from,
			'mail_from_name' => $mail_from_name,
		];
		$data['page'] = 'edit_settings';
		$data['title'] = 'Configuración general';
		$data['js_files'] = [
			base_url('assets/js/settings.vendor.min.js'),
			base_url('assets/js/settings.min.js')
		];
		$this->load->view('layouts/dashboard_layout', $data);
	}

	public function edit_settings_validation()
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
			'new_username_is_unique' => 'El nombre de usuario ya existe',
			'new_email_is_unique' => 'Este correo electrónicoo ya existe',
		];
		$this->form_validation->set_rules('business_name', 'Razón social', 'trim|required|max_length[255]', $error_messages);
		$this->form_validation->set_rules('company_name', 'Nombre de la empresa', 'trim|required|max_length[255]', $error_messages);
		$this->form_validation->set_rules('company_address', 'Domicilio fiscal', 'trim|required|max_length[255]', $error_messages);
		if ((integer)$this->input->post('mail_is_enabled') === 1) {
			$this->form_validation->set_rules('mail_host', 'Host de correo', 'trim|required|max_length[255]', $error_messages);
			$this->form_validation->set_rules('mail_username', 'Nombre de usuario', 'trim|required|max_length[255]', $error_messages);
			$this->form_validation->set_rules('mail_password', 'Contraseña', 'trim|required|max_length[255]', $error_messages);
			$this->form_validation->set_rules('mail_smtp_secure', 'Cifrado', 'trim|required|max_length[255]', $error_messages);
			$this->form_validation->set_rules('mail_port', 'Puerto', 'trim|required|max_length[255]', $error_messages);
			$this->form_validation->set_rules('mail_from', 'E-mail del remitente', 'trim|required|max_length[255]', $error_messages);
			$this->form_validation->set_rules('mail_from_name', 'Nombre del remitente', 'trim|required|max_length[255]', $error_messages);
		}
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('errors', $this->form_validation->error_array());
			$this->session->set_flashdata('old', $this->input->post());
			redirect('admin/configuracion/' . $this->input->post('id'));
		} else {
			$image_filename = NULL;
			$current_logo = !empty($this->settings->get_setting('logo_empresa')) ? $this->settings->get_setting('logo_empresa') : NULL;
			$null_logo = !empty($this->input->post('null_logo')) ? $this->input->post('null_logo') : NULL;
			$new_logo = !empty($this->input->post('logo')) ? $this->input->post('logo') : NULL;
			if (
				$current_logo && !$new_logo && !$null_logo
			) {
				$image_filename = $current_logo;
			} else {
				if ($new_logo) {
					$this->load->helper('path');
					$data = $this->input->post('logo');
					if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
						$data = substr($data, strpos($data, ',') + 1);
						$type = strtolower($type[1]);
						if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
							throw new \Exception('invalid image type');
						}
						$data = base64_decode($data);
						if ($data === false) {
							throw new \Exception('base64_decode failed');
						}
					} else {
						throw new \Exception('did not match data URI with image data');
					}
					$hash = md5(uniqid(rand(), true));
					$image_filename = "{$hash}.{$type}";
					$path = set_realpath('uploads/');
					file_put_contents("{$path}{$image_filename}", $data);
				}
			}
			$company_logo = $image_filename;
			$business_name = $this->input->post('business_name');
			$company_name = $this->input->post('company_name');
			$company_address = $this->input->post('company_address');
			$this->settings->set_setting('logo_empresa', !empty($company_logo) ? $company_logo : '');
			$this->settings->set_setting('razon_social', !empty($business_name) ? $business_name : '');
			$this->settings->set_setting('nombre_empresa', !empty($company_name) ? $company_name : '');
			$this->settings->set_setting('domicilio_fiscal', !empty($company_address) ? $company_address : '');
			if ((integer)$this->input->post('mail_is_enabled') === 1) {
				$mail_host = $this->input->post('mail_host');
				$mail_username = $this->input->post('mail_username');
				$mail_password = $this->input->post('mail_password');
				$mail_smtp_secure = $this->input->post('mail_smtp_secure');
				$mail_port = $this->input->post('mail_port');
				$mail_from = $this->input->post('mail_from');
				$mail_from_name = $this->input->post('mail_from_name');
				$this->settings->set_setting('mail_is_enabled', 1);
				$this->settings->set_setting('mail_host', !empty($mail_host) ? $mail_host : '');
				$this->settings->set_setting('mail_username', !empty($mail_username) ? $mail_username : '');
				$this->settings->set_setting('mail_password', !empty($mail_password) ? $mail_password : '');
				$this->settings->set_setting('mail_smtp_secure', !empty($mail_smtp_secure) ? $mail_smtp_secure : '');
				$this->settings->set_setting('mail_port', !empty($mail_port) ? $mail_port : '');
				$this->settings->set_setting('mail_from', !empty($mail_from) ? $mail_from : '');
				$this->settings->set_setting('mail_from_name', !empty($mail_from_name) ? $mail_from_name : '');
			} else {
				$this->settings->set_setting('mail_is_enabled', 0);
			}
			$this->session->set_flashdata('flash_message', [
				'type' => 'success',
				'title' => 'La configuración se actualizó correctamente',
			]);
			redirect('admin/clientes');
		}
	}
}
?>
