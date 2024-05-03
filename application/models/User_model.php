<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_Model {
    public function create_user($data) {
        $this->db->insert('users', $data);
        // return $this->db->insert('users', $data);
    }

    public function update_user($user_id, $data) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    public function check_user($email, $password) {
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        return $this->db->get('users')->row();
    }

    public function authenticate($email, $password) {
        $this->db->where('email', $email);
        $this->db->where('password', md5($password)); // Consider using better hashing like password_hash() in the future
        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            return $query->row();  // Return the entire user object
        }
        return false;
    }

    public function get_user_by_id($user_id) {
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        return $query->row();
    }
    public function get_questions_by_user($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('questions');
        return $query->result();
    }

    public function username_exists($username, $exclude_user_id) {
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->where('id !=', $exclude_user_id);
        return $this->db->count_all_results() > 0;
    }
    
    public function email_exists($email, $exclude_user_id) {
        $this->db->from('users');
        $this->db->where('email', $email);
        $this->db->where('id !=', $exclude_user_id);
        return $this->db->count_all_results() > 0;
    }
    
    
}
