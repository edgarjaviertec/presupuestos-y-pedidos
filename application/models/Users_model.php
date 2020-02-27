<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('app');
    }

    function count_all_records()
    {
        $sql = "SELECT * FROM usuarios WHERE eliminado_en IS NULL";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    function check_if_email_is_unique($email)
    {
        $sql = "SELECT * FROM usuarios WHERE correo_electronico = ? AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $email);
        return $query->num_rows();
    }

    function check_if_username_is_unique($username)
    {
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ? AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $username);
        return $query->num_rows();
    }

    function get_user_by_id($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = ? AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $id);
        return $query->row();
    }

    function get_user_by_username($usuario)
    {
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ? AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $usuario);
        return $query->row();
    }

    function get_user_by_email($email)
    {
        $sql = "SELECT * FROM usuarios WHERE correo_electronico = ? AND eliminado_en IS NULL";
        $query = $this->db->query($sql, $email);
        return $query->row();
    }

    function get_user($id_username_email)
    {
        $user_by_id = $this->get_user_by_id($id_username_email);
        $user_by_username = $this->get_user_by_username($id_username_email);
        $user_by_email = $this->get_user_by_email($id_username_email);
        if ($user_by_id !== null) {
            return $user_by_id;
        } else if ($user_by_username !== null) {
            return $user_by_username;
        } else if ($user_by_email !== null) {
            return $user_by_email;
        } else {
            return null;
        }
    }

    function create_user($user)
    {
        $data = array(
            'nombre_usuario' => $user['username'],
            'correo_electronico' => $user['email'],
            'clave' => password_hash($user['password'], PASSWORD_DEFAULT),
            'rol' => $user['role'],
            'creado_en' => get_timestamp()
        );
        $this->db->insert('usuarios', $data);
        return $this->db->affected_rows();
    }

    function update_password($user)
    {
        $data = array(
            'clave' => password_hash($user['password'], PASSWORD_DEFAULT),
            'actualizado_en' => get_timestamp(),
        );
        $this->db->where('id', $user['id']);
        $this->db->update('usuarios', $data);
        return $this->db->affected_rows();
    }

    function update_user($user)
    {
        $data = array(
            'nombre_usuario' => $user['username'],
            'correo_electronico' => $user['email'],
            'rol' => $user['role'],
            'actualizado_en' => get_timestamp(),
        );
        $this->db->where('id', $user['id']);
        $this->db->update('usuarios', $data);
        return $this->db->affected_rows();
    }

    function delete_user($id)
    {
        $data = array(
            'eliminado_en' => get_timestamp(),
        );
        $this->db->where('id', $id);
        $this->db->update('usuarios', $data);
        return $this->db->affected_rows();
    }
}

?>
