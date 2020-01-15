<?php


defined('BASEPATH') OR exit('No direct script access allowed');


class Bs4 extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['page'] = 'bs4';
		$data['title'] = 'Prueba de Bootstrap 4';
		$this->load->view('layouts/blank', $data);
	}
}
