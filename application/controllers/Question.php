<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Question extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // Load the necessary model
        $this->load->model('Question_model');
    }

    public function add() {
        // Get form data
        $title = $this->input->post('title');
        $description = $this->input->post('description');

        // Insert the question into the database
        $data = array(
            'title' => $title,
            'description' => $description
        );
        $this->Question_model->add_question($data);

        // Redirect back to the home page after adding the question
        redirect('home');
    }

    public function details($question_id) {
        $data['question'] = $this->Question_model->get_question_details($question_id);
        $this->load->view('question_details', $data);
    }

    public function upvote($question_id) {
        // Check if user is logged in
        if ($this->session->userdata('logged_in')) {
            $this->Question_model->upvote_question($question_id);
            // Get updated question data
            $question = $this->Question_model->get_question_details($question_id);
            // Return updated counts as JSON
            echo json_encode(array('upvotes' => $question->upvotes, 'downvotes' => $question->downvotes));
        } else {
            // Redirect to login page if not logged in
            redirect('login');
        }
    }
    
    public function downvote($question_id) {
        // Check if user is logged in
        if ($this->session->userdata('logged_in')) {
            $this->Question_model->downvote_question($question_id);
            // Get updated question data
            $question = $this->Question_model->get_question_details($question_id);
            // Return updated counts as JSON
            echo json_encode(array('upvotes' => $question->upvotes, 'downvotes' => $question->downvotes));
        } else {
            // Redirect to login page if not logged in
            redirect('login');
        }
    }
}