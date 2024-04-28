<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_Model {
    public function create_user($data) {
        $this->db->insert('users', $data);
    }

    public function check_user($email, $password) {
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        return $this->db->get('users')->row();
    }

    public function authenticate($email, $password) {
        // Code to check database for email/password
        $this->db->where('email', $email);
        $this->db->where('password', md5($password)); // Consider using better hashing like password_hash()
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
