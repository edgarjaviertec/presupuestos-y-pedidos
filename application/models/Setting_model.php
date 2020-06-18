<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('app');
	}


	function get_setting($option)
	{
		$sql = "SELECT * FROM configuracion WHERE opcion = ? AND eliminado_en IS NULL";
		$query = $this->db->query($sql, $option);
		return trim($query->row()->valor);
	}


	function set_setting($option, $value)
	{
		$data = array(
			'opcion' => $option,
			'valor' => $value,
			'actualizado_en' => get_timestamp(),
		);
		$this->db->where('opcion', $option);
		$this->db->update('configuracion', $data);
		return $this->db->affected_rows();
	}

}

?>
