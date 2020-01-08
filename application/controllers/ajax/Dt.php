<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'vendor/autoload.php';


use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;


class Dt extends CI_Controller
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
}

?>
