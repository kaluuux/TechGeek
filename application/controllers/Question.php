<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Question extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // Load the necessary model
        $this->load->model('Question_model');
    }
    
    public function add() {
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $user_id = $this->session->userdata('user_id'); // Get user ID from session
    
        $data = array(
            'title' => $title,
            'description' => $description,
            'user_id' => $user_id // Include the user ID
        );
        $this->Question_model->add_question($data);
        redirect('home');
    }
    

    public function details($question_id) {
        $data['question'] = $this->Question_model->get_question_details($question_id);
        $this->load->view('question_details', $data);
    }

    public function upvote($question_id) {
        try {
            if ($this->session->userdata('logged_in')) {
                $user_id = $this->session->userdata('user_id');
                $result = $this->Question_model->cast_vote($question_id, $user_id, 'up');
                if ($result) {
                    $question = $this->Question_model->get_question_details($question_id);
                    echo json_encode(['upvotes' => $question->upvotes, 'downvotes' => $question->downvotes]);
                } else {
                    echo json_encode(['error' => 'Could not update vote.']);
                }
            } else {
                echo json_encode(['error' => 'User not logged in.']);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            echo json_encode(['error' => 'Server error occurred.']);  // More specific error can be added for debugging
        }
    }
    
    
    public function downvote($question_id) {
        if ($this->session->userdata('logged_in')) {
            $user_id = $this->session->userdata('user_id');
            $result = $this->Question_model->cast_vote($question_id, $user_id, 'down');
            if ($result) {
                $question = $this->Question_model->get_question_details($question_id);
                echo json_encode(['upvotes' => $question->upvotes, 'downvotes' => $question->downvotes]);
            } else {
                echo json_encode(['error' => 'Could not update vote.']);
            }
        } else {
            redirect('login');
        }
    }
    
}