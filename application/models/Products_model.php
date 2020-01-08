<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('app');
	}

	function count_all_records()
	{
		$sql = "SELECT * FROM productos WHERE eliminado_en IS NULL";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	function get_product_by_id($id)
	{
		$sql = "SELECT * FROM productos WHERE id = ? AND eliminado_en IS NULL";
		$query = $this->db->query($sql, $id);
		return $query->row();
	}

	function create_product($product)
	{
		$data = array(
			'nombre' => $product['name'],
			'descripcion' => $product['description'],
			'precio_unitario' => $product['unit_price'],
			'creado_en' => get_timestamp(),
		);
		$this->db->insert('productos', $data);
		return $this->db->affected_rows();
	}

	function update_product($product)
	{
		$data = array(
			'nombre' => $product['name'],
			'descripcion' => $product['description'],
			'precio_unitario' => $product['unit_price'],
			'actualizado_en' => get_timestamp(),
		);
		$this->db->where('id', $product['id']);
		$this->db->update('productos', $data);
		return $this->db->affected_rows();
	}

	function delete_product($id)
	{
		$data = array(
			'eliminado_en' => get_timestamp(),
		);
		$this->db->where('id', $id);
		$this->db->update('productos', $data);
		return $this->db->affected_rows();
	}
}

?>
