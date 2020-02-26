<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payments_model extends CI_Model
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
				WHERE nombre LIKE ? AND eliminado_en IS NULL
				ORDER BY nombre
				LIMIT 10";
        $query = $this->db->query($sql, $search);
        return $query->result();
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

    function create_payment($payment)
    {
        $this->db->trans_start();
        $data = array(
            'fecha_pago' => $payment['date'],
            'tipo' => $payment['payment_method'],
            'notas' => $payment['notes'],
            'monto' => $payment['amount'],
            'pedido_id' => $payment['order_id'],
            'cliente_id' => $payment['customer_id'],
            'creado_en' => get_timestamp(),
        );

        $this->db->insert('pagos', $data);


        // obtenemos el saldo actual
        $sql = "SELECT saldo
        FROM pedidos
        WHERE id=?
        AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $payment['order_id']);
        $res = $query->row();
        $amount_due = $res->saldo;


        // Actualizamos el saldo actual
        $new_amount_due = $amount_due - $payment['amount'];
        $new_amount_due = number_format($new_amount_due, 2, '.', "");
        $new_amount_due = floatval($new_amount_due);
        $data = [
            'saldo' => $new_amount_due
        ];

        if ($new_amount_due <= 0) {
            $data['status'] = 'paid';
        } else {
            $data['status'] = 'partially_paid';
        }

        $this->db->where('id', $payment['order_id']);
        $this->db->update('pedidos', $data);

        $this->db->trans_complete();
        return $this->db->trans_status();


    }


    function delete_payment($payment)
    {
        $this->db->trans_start();

        $data = array(
            'eliminado_en' => get_timestamp(),
        );

        // obtenemos el saldo actual
        $sql = "SELECT monto FROM pagos WHERE id = ? AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $payment['id']);
        $res = $query->row();
        $payment_amount = $res->monto;

        // obtenemos el total actual del pedido
        $sql = "SELECT total FROM pedidos WHERE id=? AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $payment['order_id']);
        $res = $query->row();
        $order_total = $res->total;

        // obtenemos el saldo actual del pedido
        $sql = "SELECT saldo FROM pedidos WHERE id=? AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $payment['order_id']);
        $res = $query->row();
        $curremt_amount_due = $res->saldo;

        //eliminamos el pago

        $this->db->where('id', $payment['id']);
        $this->db->update('pagos', $data);

        // Actualizamos el saldo actual
        $new_amount_due = $curremt_amount_due + $payment_amount;
        $new_amount_due = number_format($new_amount_due, 2, '.', "");
        $new_amount_due = floatval($new_amount_due);

        $data = [
            'saldo' => $new_amount_due
        ];





        if  ($new_amount_due == $order_total){
            $data['status'] = 'unpaid';
        }else{
            $data['status'] = 'partially_paid';
        }

//        echo '<pre>';
//        echo "<strong>new_amount_due</strong> " . $new_amount_due . '<br />';
//        echo "<strong>order_total</strong> " . $order_total . '<br />';
//        echo "<strong>data['status']</strong> " . $data['status'] . '<br />';
//        echo '</pre>';
//        die();



        $this->db->where('id', $payment['order_id']);
        $this->db->update('pedidos', $data);

        $this->db->trans_complete();
        return $this->db->trans_status();


    }

    function get_all_payments_made($order)
    {
        $sql = "SELECT *
		FROM pagos
		WHERE pedido_id = ?
		AND cliente_id = ?
		AND eliminado_en IS NULL";
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
                AND cliente_id = ? 
                AND eliminado_en IS NULL";
        $query = $this->db->query($sql, [$order['id'], $order['customer_id']]);
        $res = $query->row();
        $total_paid = $res->total_pagado;
        return $total_paid;
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
