<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;

class Email extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Setting_model', 'settings');
		$this->load->model('Estimates_model', 'estimates');
		$this->load->model('Orders_model', 'orders');
		$this->load->model('Customers_model', 'customers');
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

	function send_estimate($id)
	{
		$estimate = $this->estimates->get_any_estimate_by_id($id);
		if (!$estimate) {
			show_404();
		}
		$to = '';
		$subject = '';
		$message = '';
		$errors = [];
		$company_logo = $this->settings->get_setting('logo_empresa');
		$business_name = $this->settings->get_setting('razon_social');
		$company_name = $this->settings->get_setting('nombre_empresa');
		$company_address = $this->settings->get_setting('domicilio_fiscal');
		$mail_host = $this->settings->get_setting('mail_host');
		$mail_username = $this->settings->get_setting('mail_username');
		$mail_password = $this->settings->get_setting('mail_password');
		$mail_port = $this->settings->get_setting('mail_port');
		$mail_smtp_secure = $this->settings->get_setting('mail_smtp_secure');
		$mail_from = $this->settings->get_setting('mail_from');
		$mail_from_name = $this->settings->get_setting('mail_from_name');
		$customer = $this->customers->get_customer_by_id($estimate->cliente_id);
		$lines = $this->estimates->get_lines_by_id($id);
		$estimate_number = $estimate->folio;

		if (!empty($this->input->get('to'))) {
			$to = $this->input->get('to');
		} else {
			$errors['to'] = "El parámetro 'to' es requerido";
		}
		if (!empty($this->input->get('subject'))) {
			$subject = $this->input->get('subject');
		} else {
			$errors['subject'] = "El parámetro 'subject' es requerido";
		}
		if (!empty($this->input->get('message'))) {
			$message = $this->input->get('message');
		} else {
			$errors['message'] = "El parámetro 'message' es requerido";
		}
		if (count($errors) > 0) {
			$res['status '] = 'fail';
			$res['data'] = [
				"errors" => $errors
			];
			$this->output
				->set_header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request')
				->set_content_type('application/json')
				->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
		} else {
			// Inicio generacion del HTMl
			$data['company_settings'] = [
				'company_logo' => $company_logo,
				'business_name' => $business_name,
				'company_name' => $company_name,
				'company_address' => $company_address
			];
			$data['page'] = 'pdf_estimate';
			$data['title'] = 'Presupuesto ' . $estimate_number;
			$data['css_files'] = base_url('assets/css/print.min.css');
			$data['customer'] = $customer;
			$data['estimate'] = $estimate;
			$data['lines'] = $lines;
			$html = $this->load->view('layouts/pdf_layout', $data, true);
			// Fin generacion del HTMl
			// inicio generacion del PDF
			$dompdf = new Dompdf();
			$dompdf->loadHtml($html);
			$dompdf->setPaper('letter', 'portrait');
			$dompdf->render();
			$pdf = $dompdf->output();
			// Fin generacion del PDF
			// inicio envio email
			$this->load->library('phpmailer_lib');
			$mail = $this->phpmailer_lib->load();
			$mail->isSMTP();
			$mail->Host = $mail_host;
			$mail->SMTPAuth = true;
			$mail->Username = $mail_username;
			$mail->Password = $mail_password;
			$mail->SMTPSecure = $mail_smtp_secure;
			$mail->Port = $mail_port;
			$mail->setFrom($mail_from, $mail_from_name);
			$mail->addAddress($to);
			$mail->Subject = base64_decode($subject);
			$mail->isHTML(true);
			$mailContent = base64_decode($message);
			$mail->Body = $mailContent;
			$pdf_filename = 'presupuesto-' . strtolower($estimate_number) . '.pdf';
			$mail->AddStringAttachment($pdf, $pdf_filename, 'base64', 'application/pdf');
			// Fin envio email
			if (!$mail->send()) {
				$res['status '] = 'fail';
				$res['data'] = [
					"php_mailer" => $mail->ErrorInfo,
				];
				$this->output
					->set_header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error')
					->set_content_type('application/json')
					->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
			} else {
				$res['status '] = 'success';
				$res['data'] = [
					"to" => $to,
					"subject" => $subject,
					"message" => $message,
				];
				$this->output
					->set_header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK')
					->set_content_type('application/json')
					->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
			}
		}
	}

	function send_order($id)
	{
		$order = $this->orders->get_any_order_by_id($id);
		if (!$order) {
			show_404();
		}
		$to = '';
		$subject = '';
		$message = '';
		$errors = [];
		$company_logo = $this->settings->get_setting('logo_empresa');
		$business_name = $this->settings->get_setting('razon_social');
		$company_name = $this->settings->get_setting('nombre_empresa');
		$company_address = $this->settings->get_setting('domicilio_fiscal');
		$mail_host = $this->settings->get_setting('mail_host');
		$mail_username = $this->settings->get_setting('mail_username');
		$mail_password = $this->settings->get_setting('mail_password');
		$mail_port = $this->settings->get_setting('mail_port');
		$mail_smtp_secure = $this->settings->get_setting('mail_smtp_secure');
		$mail_from = $this->settings->get_setting('mail_from');
		$mail_from_name = $this->settings->get_setting('mail_from_name');
		$customer = $this->customers->get_customer_by_id($order->cliente_id);
		$lines = $this->orders->get_lines_by_id($id);
		$order_number = $order->folio;
		if (!empty($this->input->get('to'))) {
			$to = $this->input->get('to');
		} else {
			$errors['to'] = "El parámetro 'to' es requerido";
		}
		if (!empty($this->input->get('subject'))) {
			$subject = $this->input->get('subject');
		} else {
			$errors['subject'] = "El parámetro 'subject' es requerido";
		}
		if (!empty($this->input->get('message'))) {
			$message = $this->input->get('message');
		} else {
			$errors['message'] = "El parámetro 'message' es requerido";
		}
		if (count($errors) > 0) {
			$res['status '] = 'fail';
			$res['data'] = [
				"errors" => $errors
			];
			$this->output
				->set_header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request')
				->set_content_type('application/json')
				->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
		} else {
			// Inicio generacion del HTMl
			$data['company_settings'] = [
				'company_logo' => $company_logo,
				'business_name' => $business_name,
				'company_name' => $company_name,
				'company_address' => $company_address
			];
			$data['page'] = 'pdf_order';
			$data['title'] = 'Pedido ' . $order_number;
			$data['css_files'] = base_url('assets/css/print.min.css');
			$data['customer'] = $customer;
			$data['order'] = $order;
			$data['lines'] = $lines;
			$html = $this->load->view('layouts/pdf_layout', $data, true);
			// Fin generacion del HTMl
			// inicio generacion del PDF
			$dompdf = new Dompdf();
			$dompdf->loadHtml($html);
			$dompdf->setPaper('letter', 'portrait');
			$dompdf->render();
			$pdf = $dompdf->output();
			// Fin generacion del PDF
			// inicio envio email
			$this->load->library('phpmailer_lib');
			$mail = $this->phpmailer_lib->load();
			$mail->isSMTP();
			$mail->Host = $mail_host;
			$mail->SMTPAuth = true;
			$mail->Username = $mail_username;
			$mail->Password = $mail_password;
			$mail->SMTPSecure = $mail_smtp_secure;
			$mail->Port = $mail_port;
			$mail->setFrom($mail_from, $mail_from_name);
			$mail->addAddress($to);
			$mail->Subject = base64_decode($subject);
			$mail->isHTML(true);
			$mailContent = base64_decode($message);
			$mail->Body = $mailContent;
			$pdf_filename = 'pedido-' . strtolower($order_number) . '.pdf';
			$mail->AddStringAttachment($pdf, $pdf_filename, 'base64', 'application/pdf');
			// Fin envio email
			if (!$mail->send()) {
				$res['status '] = 'fail';
				$res['data'] = [
					"php_mailer" => $mail->ErrorInfo,
				];
				$this->output
					->set_header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error')
					->set_content_type('application/json')
					->set_output(json_encode($res, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
			} else {
				$res['status '] = 'success';
				$res['data'] = [
					"to" => $to,
					"subject" => $subject,
					"message" => $message,
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
