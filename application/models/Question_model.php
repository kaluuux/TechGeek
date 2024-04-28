<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Question_model extends CI_Model {
    
    public function get_recent_questions() {
        $this->db->order_by('created_at', 'desc');
        $query = $this->db->get('questions');
        return $query->result();
    }

    public function add_question($data) {
        // Insert the question into the database
        $this->db->insert('questions', $data);
    }

    public function get_question_details($question_id) {
        $this->db->where('id', $question_id);
        $query = $this->db->get('questions');
        return $query->row();
    }

    public function upvote_question($question_id) {
        $this->db->set('upvotes', 'upvotes+1', FALSE);
        $this->db->where('id', $question_id);
        $this->db->update('questions');
    }
    
    public function downvote_question($question_id) {
        $this->db->set('downvotes', 'downvotes+1', FALSE);
        $this->db->where('id', $question_id);
        $this->db->update('questions');
    }
    
    public function get_upvotes($question_id) {
        $this->db->select('upvotes');
        $this->db->where('id', $question_id);
        $query = $this->db->get('questions');
        $row = $query->row();
        return $row->upvotes;
    }
    
    public function get_downvotes($question_id) {
        $this->db->select('downvotes');
        $this->db->where('id', $question_id);
        $query = $this->db->get('questions');
        $row = $query->row();
        return $row->downvotes;
    }
    
}

