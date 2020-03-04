<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customers_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('app');
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
				WHERE nombre_razon_social LIKE ? 
				AND eliminado_en IS NULL";
        $query = $this->db->query($sql, [$search]);
        $records_filtered = $query->num_rows();
        $sql = "SELECT * FROM clientes
				WHERE nombre_razon_social LIKE ?
				AND eliminado_en IS NULL
				LIMIT ?, ?";
        $query = $this->db->query($sql, [$search, $start, $length]);
        $data = $query->result();
        foreach ($data as $row) {
            $row->text = $row->nombre_razon_social;
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
            'nombre_razon_social' => $customer['name'],
            'correo_electronico' => $customer['email'],
            'telefono' => $customer['phone'],
            'telefono_celular' => $customer['mobile_phone'],
            'domicilio' => $customer['address'],
            'ciudad' => $customer['city'],
            'estado' => $customer['state'],
            'pais' => $customer['country'],
            'codigo_postal' => $customer['postal_code'],
            'creado_en' => get_timestamp(),
        );
        $this->db->insert('clientes', $data);
        return $this->db->affected_rows();
    }

    function update_customer($customer)
    {
        $data = array(
            'rfc' => $customer['rfc'],
            'nombre_razon_social' => $customer['name'],
            'correo_electronico' => $customer['email'],
            'telefono' => $customer['phone'],
            'telefono_celular' => $customer['mobile_phone'],
            'domicilio' => $customer['address'],
            'ciudad' => $customer['city'],
            'estado' => $customer['state'],
            'pais' => $customer['country'],
            'codigo_postal' => $customer['postal_code'],
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
