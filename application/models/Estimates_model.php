<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estimates_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('app');
	}


	function get_products_for_typeahead($search)
	{
		$search = '%' . $search . '%';
		$sql = "SELECT * FROM productos
				WHERE eliminado_en IS NULL
				AND nombre LIKE ?
				ORDER BY nombre
				LIMIT 10";
		$query = $this->db->query($sql, $search);
		return $query->result();
	}


	function get_customers_for_select2($start, $length, $search)
	{
		$start = intval($start);
		$length = intval($length);
		$search = '%' . $search . '%';
		$sql = "SELECT * FROM clientes WHERE eliminado_en IS NULL";
		$query = $this->db->query($sql);
		$records_total = $query->num_rows();
		$sql = "SELECT * FROM clientes
				WHERE eliminado_en IS NULL
				AND nombre LIKE ? 
				OR apellidos LIKE ?";
		$query = $this->db->query($sql, [$search, $search]);
		$records_filtered = $query->num_rows();
		$sql = "SELECT * FROM clientes
				WHERE eliminado_en IS NULL
				AND nombre LIKE ? 
				OR apellidos LIKE ?
				LIMIT ?, ?";
		$query = $this->db->query($sql, [$search, $search, $start, $length]);
		$data = $query->result();
		foreach ($data as $row) {
			$row->text = $row->nombre . ' ' . $row->apellidos;
		}
		$res = (object)[
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_filtered,
			'data' => $data
		];
		return $res;
	}

	function count_all_records()
	{
		$sql = "SELECT * FROM presupuestos WHERE eliminado_en IS NULL";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	function get_product_by_id($id)
	{
		$sql = "SELECT * FROM productos WHERE id = ? AND eliminado_en IS NULL";
		$query = $this->db->query($sql, $id);
		return $query->row();
	}

	function get_next_estimate_number($year)
	{
		$sql = "SELECT
				CASE WHEN MAX(CAST(SUBSTRING(folio,5,6) AS UNSIGNED)) IS NULL
				THEN 1
				ELSE 1 + MAX(CAST(SUBSTRING(folio,5,6) AS UNSIGNED)) 
				END AS folio_siguiente
				FROM presupuestos 
				WHERE YEAR(creado_en) = ?";
		$query = $this->db->query($sql, $year);
		$res = $query->row();
		$next_number = $res->folio_siguiente;
		$year = date("y");
		return 'E' . $year . '-' . sprintf('%06d', intval($next_number));
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
