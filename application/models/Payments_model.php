<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payments_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('app');
    }

    function get_all_payments_made($order)
    {
        $sql = "SELECT *
		FROM pagos
		WHERE pedido_id = ?
		AND cliente_id = ?";
        $query = $this->db->query($sql, [$order['id'], $order['customer_id']]);
        return $query->result();
    }

    function get_total_paid($order)
    {
        $sql = "SELECT 
                CASE WHEN SUM(monto) IS NULL
                THEN 0
                ELSE SUM(monto)
                END AS total_pagado
                FROM pagos
                WHERE pedido_id = ?
                AND cliente_id = ?";
        $query = $this->db->query($sql, [$order['id'], $order['customer_id']]);
        $res = $query->row();
        $total_paid = $res->total_pagado;
        return $total_paid;
    }
}

?>
