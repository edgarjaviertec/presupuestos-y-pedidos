<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Estimates_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('app');
    }

    function count_all_records()
    {
        $sql = "SELECT * FROM presupuestos WHERE eliminado_en IS NULL";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    function get_estimate_by_id($id)
    {
        $sql = "SELECT * FROM presupuestos WHERE id = ? AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $id);
        return $query->row();
    }

    function get_monthly_sum($month, $year)
    {
        $sql = "SELECT 
                CASE WHEN SUM(p.total) IS NULL
                THEN 0
                ELSE SUM(p.total) 
                END AS sum_of_total
                FROM presupuestos p
                INNER JOIN clientes c
                ON p.cliente_id = c.id
                WHERE MONTH(p.fecha_presupuesto) = ?
                AND YEAR(p.fecha_presupuesto) = ?
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
                FROM presupuestos p
                INNER JOIN clientes c
                ON p.cliente_id = c.id
                WHERE YEAR(p.fecha_presupuesto) = ?
                AND p.eliminado_en IS NULL";
        $query = $this->db->query($sql, $year);
        $res = $query->row();
        return $res->sum_of_total;
    }

    function get_monthly_report($month, $year)
    {
        $sql = "SELECT 
                p.folio,
                p.fecha_presupuesto,
                c.nombre_razon_social,
                c.rfc,
                c.telefono,
                p.sub_total,
                p.cantidad_descontada,
                p.impuesto,
                p.total
                FROM presupuestos p
                INNER JOIN clientes c
                ON p.cliente_id = c.id
                WHERE MONTH(p.fecha_presupuesto) = ?
                AND YEAR(p.fecha_presupuesto) = ?
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
                COUNT(*) as presupuestos, 
                SUM(total) as total
                FROM presupuestos p
                INNER JOIN clientes c
                ON p.cliente_id = c.id
                WHERE MONTH(p.fecha_presupuesto) = ?
                AND YEAR(p.fecha_presupuesto) = ?
                AND p.eliminado_en IS NULL
                GROUP BY c.id, c. telefono, c.rfc, c.nombre_razon_social";
        $query = $this->db->query($sql, [$month, $year]);
        return $query->result();
    }

    function get_annual_report($year)
    {
        $sql = "SELECT 
                p.folio,
                p.fecha_presupuesto,
                c.nombre_razon_social,
                c.rfc,
                c.telefono,
                p.sub_total,
                p.cantidad_descontada,
                p.impuesto,
                p.total
                FROM presupuestos p
                INNER JOIN clientes c
                ON p.cliente_id = c.id
                WHERE YEAR(p.fecha_presupuesto) = ?
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
        COUNT(*) as presupuestos, 
        SUM(total) as total
        FROM presupuestos p
        INNER JOIN clientes c
        ON p.cliente_id = c.id
        WHERE YEAR(p.fecha_presupuesto) = ?
        AND p.eliminado_en IS NULL
        GROUP BY c.id, c. telefono, c.rfc, c.nombre_razon_social";
        $query = $this->db->query($sql, $year);
        return $query->result();
    }

    function get_estimate_by_number($number)
    {
        $sql = "SELECT * FROM presupuestos WHERE folio = ? AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $number);
        return $query->row();
    }

    function get_lines_by_id($id)
    {
        $sql = "SELECT * FROM presupuestos_productos WHERE presupuesto_id = ?";
        $query = $this->db->query($sql, $id);
        return $query->result();
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

    function update_estimate($estimate)
    {
        $data = array(
            'folio' => $estimate['number'],
            'fecha_presupuesto' => $estimate['date'],
            'validez_en_dias' => $estimate['validity_in_days'],
            'fecha_vencimiento' => $estimate['due_date'],
            'status' => $estimate['status'],
            'notas' => $estimate['notes'],
            'sub_total' => $estimate['sub_total'],
            'tipo_descuento' => $estimate['discount_type'],
            'descuento' => $estimate['discount'],
            'cantidad_descontada' => $estimate['discount_val'],
            'incluir_impuesto' => $estimate['include_tax'],
            'impuesto' => $estimate['tax'],
            'total' => $estimate['total'],
            'cliente_id' => $estimate['customer_id'],
            'creado_en' => get_timestamp(),
        );
        $items = $estimate['items'];
        $this->db->trans_start();
        $this->db->where('id', $estimate['id']);
        $this->db->update('presupuestos', $data);
        $this->db->where('presupuesto_id', $estimate['id']);
        $this->db->delete('presupuestos_productos');
        if (!empty($items)) {
            foreach ($items as $item) {
                $itemData = array(
                    'nombre' => $item['name'],
                    'descripcion' => $item['description'],
                    'cantidad' => $item['qty'],
                    'precio_unitario' => $item['unit_price'],
                    'total' => $item['total'],
                    'presupuesto_id' => $estimate['id'],
                    'producto_id' => (!empty($item['product_id'])) ? $item['product_id'] : NULL,
                );
                $this->db->insert('presupuestos_productos', $itemData);
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function create_estimate($estimate)
    {
        $data = array(
            'folio' => $estimate['number'],
            'fecha_presupuesto' => $estimate['date'],
            'validez_en_dias' => $estimate['validity_in_days'],
            'fecha_vencimiento' => $estimate['due_date'],
            'status' => $estimate['status'],
            'notas' => $estimate['notes'],
            'sub_total' => $estimate['sub_total'],
            'tipo_descuento' => $estimate['discount_type'],
            'descuento' => $estimate['discount'],
            'cantidad_descontada' => $estimate['discount_val'],
            'incluir_impuesto' => $estimate['include_tax'],
            'impuesto' => $estimate['tax'],
            'total' => $estimate['total'],
            'cliente_id' => $estimate['customer_id'],
            'creado_en' => get_timestamp(),
        );
        $items = $estimate['items'];
        $this->db->trans_start();
        $this->db->insert('presupuestos', $data);
        $insert_id = $this->db->insert_id();
        if (!empty($items)) {
            foreach ($items as $item) {
                $itemData = array(
                    'nombre' => $item['name'],
                    'descripcion' => $item['description'],
                    'cantidad' => $item['qty'],
                    'precio_unitario' => $item['unit_price'],
                    'total' => $item['total'],
                    'presupuesto_id' => $insert_id,
                    'producto_id' => (!empty($item['product_id'])) ? $item['product_id'] : NULL,
                );
                $this->db->insert('presupuestos_productos', $itemData);
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function delete_estimate($id)
    {
        $data = array(
            'eliminado_en' => get_timestamp(),
        );
        $this->db->where('id', $id);
        $this->db->update('presupuestos', $data);
        return $this->db->affected_rows();
    }

    function change_status($estimate)
    {
        $data = array(
            'status' => $estimate['status'],
        );
        $this->db->where('id', $estimate['id']);
        $this->db->update('presupuestos', $data);
        return $this->db->affected_rows();
    }
}

?>
