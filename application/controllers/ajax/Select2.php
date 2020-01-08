<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'vendor/autoload.php';


use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;


class Select2 extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query('SELECT actor_id, first_name,last_name FROM actor');
		echo $datatables->generate();
	}

//	function index()
//	{
//		$this->load->model('select2_model', 'Model');
//		$table = $this->input->get('table');
//		$fields = $this->input->get('columns');
//		$limit = $this->input->get('limit');
//		if (!empty( $this->input->get('page') )) {
//			$page = $this->input->get('page');
//		} else {
//			$page = 1;
//		}
//		$offset = $limit * ($page - 1);
//		$search = $this->input->get('q');
//		$records = $this->Model->get_filtered_records($table, $fields, $search, $offset, $limit);
//		$totalFiltered = $this->Model->count_filtered_records($table, $fields, $search);
//		$json_data = array(
//			"total_count" => intval($totalFiltered),
//			"items" => $records
//		);
//		header('Content-Type: application/json');
//		echo json_encode($json_data);
//	}
}

?>
