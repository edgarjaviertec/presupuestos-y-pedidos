<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Customers_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('app');
	}

	function count_all_records()
	{
		$sql = "SELECT * FROM clientes WHERE eliminado_en IS NULL";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	function get_customer_by_id($id)
	{
		$sql = "SELECT * FROM clientes WHERE id = ? AND eliminado_en IS NULL";
		$query = $this->db->query($sql, $id);
		return $query->row();
	}

	function create_customer($customer)
	{
		$data = array(
			'rfc' => $customer['rfc'],
			'nombre' => $customer['name'],
			'apellidos' => $customer['last_name'],
			'empresa' => $customer['company'],
			'correo_electronico' => $customer['email'],
			'telefono' => $customer['phone'],
			'telefono_celular' => $customer['mobile_phone'],
			'domicilio' => $customer['address'],
			'ciudad' => $customer['city'],
			'estado' => $customer['state'],
			'pais' => $customer['country'],
			'codigo_postal' => $customer['postal_code'],
			'notas' => $customer['notes'],
			'creado_en' => get_timestamp(),
		);
		$this->db->insert('clientes', $data);
		return $this->db->affected_rows();
	}

	function update_customer($customer)
	{
		$data = array(
			'rfc' => $customer['rfc'],
			'nombre' => $customer['name'],
			'apellidos' => $customer['last_name'],
			'empresa' => $customer['company'],
			'correo_electronico' => $customer['email'],
			'telefono' => $customer['phone'],
			'telefono_celular' => $customer['mobile_phone'],
			'domicilio' => $customer['address'],
			'ciudad' => $customer['city'],
			'estado' => $customer['state'],
			'pais' => $customer['country'],
			'codigo_postal' => $customer['postal_code'],
			'notas' => $customer['notes'],
			'actualizado_en' => get_timestamp(),
		);
		$this->db->where('id', $customer['id']);
		$this->db->update('clientes', $data);
		return $this->db->affected_rows();
	}

	function delete_customer($id)
	{
		$data = array(
			'eliminado_en' => get_timestamp(),
		);
		$this->db->where('id', $id);
		$this->db->update('clientes', $data);
		return $this->db->affected_rows();
	}
}
?>
