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

    public function increment_view_count($question_id) {
        $this->db->set('view_count', 'view_count+1', FALSE);
        $this->db->where('id', $question_id);
        $this->db->update('questions');
    }
    
    public function get_question_details($question_id) {
        // First, get the question details along with the username from the users table
        $this->db->select('questions.*, users.username, questions.view_count');
        $this->db->from('questions');
        $this->db->join('users', 'users.id = questions.user_id');
        $this->db->where('questions.id', $question_id);
        $question = $this->db->get()->row();
    
        // Check if the question exists before trying to increment the view count
        if ($question) {
            $this->increment_view_count($question_id);
        }
    
        return $question;
    }
    
    // public function get_question_details($question_id) {
    //     $this->db->select('questions.*, users.username');
    //     $this->db->from('questions');
    //     $this->db->join('users', 'users.id = questions.user_id');
    //     $this->db->where('questions.id', $question_id);
    //     $query = $this->db->get();
    //     return $query->row();
    // }
    

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

    public function cast_vote($question_id, $user_id, $vote_type) {
        // Check existing vote
        $this->db->where('question_id', $question_id);
        $this->db->where('user_id', $user_id);
        $existing_vote = $this->db->get('votes')->row();

        // Start transaction
        $this->db->trans_start();

        if ($existing_vote) {
            // Update existing vote if different
            if ($existing_vote->vote_type !== $vote_type) {
                $this->db->where('id', $existing_vote->id);
                $this->db->update('votes', ['vote_type' => $vote_type]);
            }
        } else {
            // Insert new vote
            $this->db->insert('votes', [
                'question_id' => $question_id,
                'user_id' => $user_id,
                'vote_type' => $vote_type
            ]);
        }

        // Update question vote counts
        $this->update_vote_counts($question_id);

        // Complete transaction
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    private function update_vote_counts($question_id) {
        // Count upvotes
        $this->db->where('question_id', $question_id);
        $this->db->where('vote_type', 'up');
        $upvotes = $this->db->count_all_results('votes');

        // Count downvotes
        $this->db->where('question_id', $question_id);
        $this->db->where('vote_type', 'down');
        $downvotes = $this->db->count_all_results('votes');

        // Update questions table
        $this->db->where('id', $question_id);
        $this->db->update('questions', ['upvotes' => $upvotes, 'downvotes' => $downvotes]);
    }

    
}

