<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('app');
    }

    function count_all_records()
    {
        $sql = "SELECT * FROM pedidos WHERE eliminado_en IS NULL";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }



    function get_any_order_by_id($id)
    {
        $sql = "SELECT * FROM pedidos WHERE id = ?";
        $query = $this->db->query($sql, $id);
        return $query->row();
    }

    function get_order_by_id($id)
    {
        $sql = "SELECT * FROM pedidos WHERE id = ? AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $id);
        return $query->row();
    }




    function get_order_by_number($number)
    {
        $sql = "SELECT * FROM pedidos WHERE folio = ? AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $number);
        return $query->row();
    }







    function get_monthly_sum($month, $year)
    {
        $sql = "SELECT 
                CASE WHEN SUM(p.total) IS NULL
                THEN 0
                ELSE SUM(p.total) 
                END AS sum_of_total
                FROM pedidos p
                INNER JOIN clientes c
                ON p.cliente_id = c.id
                WHERE MONTH(p.fecha_pedido) = ?
                AND YEAR(p.fecha_pedido) = ?
                AND p.eliminado_en IS NULL";
        $query = $this->db->query($sql, [$month, $year]);
        $res = $query->row();
        return $res->sum_of_total;
    }

    function get_annual_sum($year)
    {
        $sql = "SELECT 
                CASE WHEN SUM(p.total) IS NULL
                THEN 0
                ELSE SUM(p.total) 
                END AS sum_of_total
                FROM pedidos p
                INNER JOIN clientes c
                ON p.cliente_id = c.id
                WHERE YEAR(p.fecha_pedido) = ?
                AND p.eliminado_en IS NULL";
        $query = $this->db->query($sql, $year);
        $res = $query->row();
        return $res->sum_of_total;
    }

    function get_monthly_report($month, $year)
    {
        $sql = "SELECT 
                p.folio,
                p.fecha_pedido,
                c.nombre_razon_social,
                c.rfc,
                c.telefono,
                p.sub_total,
                p.cantidad_descontada,
                p.impuesto,
                p.status,
                p.total
                FROM pedidos p
                INNER JOIN clientes c
                ON p.cliente_id = c.id
                WHERE MONTH(p.fecha_pedido) = ?
                AND YEAR(p.fecha_pedido) = ?
                AND p.eliminado_en IS NULL";
        $query = $this->db->query($sql, [$month, $year]);
        return $query->result();
    }

    function get_monthly_report_by_customers($month, $year)
    {
        $sql = "SELECT
                c.id,
                c.nombre_razon_social,
                c.telefono,
                c.rfc,
                COUNT(*) as pedidos, 
                SUM(total) as total
                FROM pedidos p
                INNER JOIN clientes c
                ON p.cliente_id = c.id
                WHERE MONTH(p.fecha_pedido) = ?
                AND YEAR(p.fecha_pedido) = ?
                AND p.eliminado_en IS NULL
                GROUP BY c.id, c. telefono, c.rfc, c.nombre_razon_social";
        $query = $this->db->query($sql, [$month, $year]);
        return $query->result();
    }

    function get_annual_report($year)
    {
        $sql = "SELECT 
                p.folio,
                p.fecha_pedido,
                c.nombre_razon_social,
                c.rfc,
                c.telefono,
                p.sub_total,
                p.cantidad_descontada,
                p.impuesto,
                         p.status,
                p.total
                FROM pedidos p
                INNER JOIN clientes c
                ON p.cliente_id = c.id
                WHERE YEAR(p.fecha_pedido) = ?
                AND p.eliminado_en IS NULL";
        $query = $this->db->query($sql, $year);
        return $query->result();
    }

    function get_annual_report_by_customers($year)
    {
        $sql = "SELECT
        c.id,
        c.nombre_razon_social,
        c.telefono,
        c.rfc,
        COUNT(*) as pedidos, 
        SUM(total) as total
        FROM pedidos p
        INNER JOIN clientes c
        ON p.cliente_id = c.id
        WHERE YEAR(p.fecha_pedido) = ?
        AND p.eliminado_en IS NULL
        GROUP BY c.id, c. telefono, c.rfc, c.nombre_razon_social";
        $query = $this->db->query($sql, $year);
        return $query->result();
    }






//    function get_sum_of_subtotal($month, $year)
//    {
//        $sql = "SELECT
//                CASE WHEN SUM(p.sub_total) IS NULL
//                THEN 0
//                ELSE SUM(p.sub_total)
//                END AS sum_of_subtotal
//                FROM pedidos p
//                INNER JOIN clientes c
//                ON p.cliente_id = c.id
//                WHERE MONTH(p.fecha_pedido) = ?
//                AND YEAR(p.fecha_pedido) = ?
//                AND p.eliminado_en IS NULL";
//        $query = $this->db->query($sql, [$month, $year]);
//        $res = $query->row();
//        return $res->sum_of_subtotal;
//    }
//
//    function get_sum_of_discount($month, $year)
//    {
//        $sql = "SELECT
//                CASE WHEN SUM(p.cantidad_descontada) IS NULL
//                THEN 0
//                ELSE SUM(p.cantidad_descontada)
//                END AS sum_of_discount
//                FROM pedidos p
//                INNER JOIN clientes c
//                ON p.cliente_id = c.id
//                WHERE MONTH(p.fecha_pedido) = ?
//                AND YEAR(p.fecha_pedido) = ?
//                AND p.eliminado_en IS NULL";
//        $query = $this->db->query($sql, [$month, $year]);
//        $res = $query->row();
//        return $res->sum_of_discount;
//    }
//
//    function get_sum_of_tax($month, $year)
//    {
//        $sql = "SELECT
//                CASE WHEN SUM(p.impuesto) IS NULL
//                THEN 0
//                ELSE SUM(p.impuesto)
//                END AS sum_of_tax
//                FROM pedidos p
//                INNER JOIN clientes c
//                ON p.cliente_id = c.id
//                WHERE MONTH(p.fecha_pedido) = ?
//                AND YEAR(p.fecha_pedido) = ?
//                AND p.eliminado_en IS NULL";
//        $query = $this->db->query($sql, [$month, $year]);
//        $res = $query->row();
//        return $res->sum_of_tax;
//    }
//
//    function get_sum_of_total($month, $year)
//    {
//        $sql = "SELECT
//                CASE WHEN SUM(p.total) IS NULL
//                THEN 0
//                ELSE SUM(p.total)
//                END AS sum_of_total
//                FROM pedidos p
//                INNER JOIN clientes c
//                ON p.cliente_id = c.id
//                WHERE MONTH(p.fecha_pedido) = ?
//                AND YEAR(p.fecha_pedido) = ?
//                AND p.eliminado_en IS NULL";
//        $query = $this->db->query($sql, [$month, $year]);
//        $res = $query->row();
//        return $res->sum_of_total;
//    }
//
//    function get_sum_of_amount_due($month, $year)
//    {
//        $sql = "SELECT
//                CASE WHEN SUM(p.saldo) IS NULL
//                THEN 0
//                ELSE SUM(p.saldo)
//                END AS sum_of_amount_due
//                FROM pedidos p
//                INNER JOIN clientes c
//                ON p.cliente_id = c.id
//                WHERE MONTH(p.fecha_pedido) = ?
//                AND YEAR(p.fecha_pedido) = ?
//                AND p.eliminado_en IS NULL";
//        $query = $this->db->query($sql, [$month, $year]);
//        $res = $query->row();
//        return $res->sum_of_amount_due;
//    }
//
//    function get_orders_for_report($month, $year)
//    {
//        $sql = "SELECT
//                p.folio,
//                p.fecha_pedido,
//                c.nombre_razon_social,
//                c.rfc,
//                         c.telefono,
//                p.sub_total,
//                p.cantidad_descontada,
//                p.impuesto,
//                p.total,
//                p.saldo
//                FROM pedidos p
//                INNER JOIN clientes c
//                ON p.cliente_id = c.id
//                WHERE MONTH(p.fecha_pedido) = ?
//                AND YEAR(p.fecha_pedido) = ?
//                AND p.eliminado_en IS NULL";
//        $query = $this->db->query($sql, [$month, $year]);
//        return $query->result();
//    }





    function get_lines_by_id($id)
    {
        $sql = "SELECT * FROM pedidos_productos WHERE pedido_id = ?";
        $query = $this->db->query($sql, $id);
        return $query->result();
    }

    function get_next_order_number($year)
    {
        $sql = "SELECT
				CASE WHEN MAX(CAST(SUBSTRING(folio,5,6) AS UNSIGNED)) IS NULL
				THEN 1
				ELSE 1 + MAX(CAST(SUBSTRING(folio,5,6) AS UNSIGNED)) 
				END AS folio_siguiente
				FROM pedidos 
				WHERE YEAR(creado_en) = ?";
        $query = $this->db->query($sql, $year);
        $res = $query->row();
        $next_number = $res->folio_siguiente;
        $year = date("y");
        return 'P' . $year . '-' . sprintf('%06d', intval($next_number));
    }

    function get_amount_due_by_id($id)
    {
        $sql = "SELECT saldo
        FROM pedidos
        WHERE id=?
        AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $id);
        $res = $query->row();
        $total_paid = $res->saldo;
        return $total_paid;
    }

    function update_order($order)
    {
        $data = array(
            'folio' => $order['number'],
            'fecha_pedido' => $order['date'],
            'validez_en_dias' => $order['validity_in_days'],
            'fecha_vencimiento' => $order['due_date'],
            'status' => $order['status'],
            'notas' => $order['notes'],
            'sub_total' => $order['sub_total'],
            'tipo_descuento' => $order['discount_type'],
            'descuento' => $order['discount'],
            'cantidad_descontada' => $order['discount_val'],
            'incluir_impuesto' => $order['include_tax'],
            'impuesto' => $order['tax'],
            'total' => $order['total'],
            'saldo' => $order['amount_due'],
            'cliente_id' => $order['customer_id'],
            'creado_en' => get_timestamp(),
        );
        $items = $order['items'];
        $payments = (!empty($order['payments'])) ? $order['payments'] : [];
        $this->db->trans_start();
        // Se eliminan todos los pagos del pedido
        $this->db->where('pedido_id', $order['id']);
        $this->db->delete('pagos');
        // Se insertan todos los pagos del pedido
        if (!empty($payments)) {
            foreach ($payments as $payment) {
                $paymentData = array(
                    'monto' => $payment['amount'],
                    'tipo' => $payment['type'],
                    'fecha_pago' => $payment['date'],
                    'notas' => (!empty($payment['notes'])) ? $payment['notes'] : NULL,
                    'pedido_id' => $order['id'],
                    'cliente_id' => $order['customer_id']
                );
                $this->db->insert('pagos', $paymentData);
            }
        }
        if ($order['amount_due'] == $order['total']) {
            $data['status'] = 'unpaid';
        } elseif ($order['amount_due'] > 0) {
            $data['status'] = 'partially_paid';
        } else {
            $data['status'] = 'paid';
        }
        // Se actualiza los datos del pedido
        $this->db->where('id', $order['id']);
        $this->db->update('pedidos', $data);
        // Se eliminan todos los productos del pedido
        $this->db->where('pedido_id', $order['id']);
        $this->db->delete('pedidos_productos');
        // Se insertan todos los productos del pedido
        if (!empty($items)) {
            foreach ($items as $item) {
                $itemData = array(
                    'nombre' => $item['name'],
                    'descripcion' => $item['description'],
                    'cantidad' => $item['qty'],
                    'precio_unitario' => $item['unit_price'],
                    'total' => $item['total'],
                    'pedido_id' => $order['id'],
                    'producto_id' => (!empty($item['product_id'])) ? $item['product_id'] : NULL,
                );
                $this->db->insert('pedidos_productos', $itemData);
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function create_order($order)
    {
        $data = array(
            'folio' => $order['number'],
            'fecha_pedido' => $order['date'],
            'validez_en_dias' => $order['validity_in_days'],
            'fecha_vencimiento' => $order['due_date'],
            'notas' => $order['notes'],
            'sub_total' => $order['sub_total'],
            'tipo_descuento' => $order['discount_type'],
            'descuento' => $order['discount'],
            'cantidad_descontada' => $order['discount_val'],
            'incluir_impuesto' => $order['include_tax'],
            'impuesto' => $order['tax'],
            'total' => $order['total'],
            'saldo' => $order['amount_due'],
            'cliente_id' => $order['customer_id'],
            'creado_en' => get_timestamp(),
        );
        $items = $order['items'];
        $payments = (!empty($order['payments'])) ? $order['payments'] : [];
        if ($order['amount_due'] == $order['total']) {
            $data['status'] = 'unpaid';
        } elseif ($order['amount_due'] > 0) {
            $data['status'] = 'partially_paid';
        } else {
            $data['status'] = 'paid';
        }
        $this->db->trans_start();
        $this->db->insert('pedidos', $data);
        $insert_id = $this->db->insert_id();
        // Se insertan todos los productos del pedido
        if (!empty($items)) {
            foreach ($items as $item) {
                $itemData = array(
                    'nombre' => $item['name'],
                    'descripcion' => $item['description'],
                    'cantidad' => $item['qty'],
                    'precio_unitario' => $item['unit_price'],
                    'total' => $item['total'],
                    'pedido_id' => $insert_id,
                    'producto_id' => (!empty($item['product_id'])) ? $item['product_id'] : NULL,
                );
                $this->db->insert('pedidos_productos', $itemData);
            }
        }
        // se insertan todos los pagos
        if (!empty($payments)) {
            foreach ($payments as $payment) {
                $paymentData = array(
                    'monto' => $payment['amount'],
                    'tipo' => $payment['type'],
                    'fecha_pago' => $payment['date'],
                    'notas' => (!empty($payment['notes'])) ? $payment['notes'] : NULL,
                    'pedido_id' => $insert_id,
                    'cliente_id' => $order['customer_id']
                );
                $this->db->insert('pagos', $paymentData);
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function delete_order($id)
    {
        $data = [
            'eliminado_en' => get_timestamp()
        ];
        $this->db->where('id', $id);
        $this->db->update('pedidos', $data);
        return $this->db->affected_rows();
    }

    function change_status($order)
    {
        $data = [
            'status' => $order['status']
        ];
        $this->db->where('id', $order['id']);
        $this->db->update('pedidos', $data);
        return $this->db->affected_rows();
    }

    function update_amount_due($order)
    {
        $data = [
            'saldo' => $order['amount_due']
        ];
        $this->db->where('id', $order['id']);
        $this->db->update('pedidos', $data);
        return $this->db->affected_rows();
    }
}

?>
