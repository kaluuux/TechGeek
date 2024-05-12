<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Question_model');
        $this->output->set_content_type('application/json');

        // Allow from any
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $this->output->set_header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            $this->output->set_header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            $this->output->set_header('Access-Control-Allow-Headers: Content-Type, Authorization');
            $this->output->set_header('Access-Control-Allow-Credentials: true');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0);
        }
    }    

    public function questions() {
        $questions = $this->Question_model->get_recent_questions();
        $this->output->set_output(json_encode($questions));
    }

    public function post_question() {
        $title = $this->input->post('title', TRUE);
        $description = $this->input->post('description', TRUE);
        $user_id = $this->session->userdata('user_id');

        if (empty($title) || empty($description)) {
            $this->output->set_output(json_encode(['error' => 'Missing title or description']));
            return;
        }

        $data = array(
            'title' => $title,
            'description' => $description,
            'user_id' => $user_id
        );

        $result = $this->Question_model->add_question($data);
        if ($result) {
            $this->output->set_output(json_encode(['success' => 'Question added successfully']));
        } else {
            $this->output->set_output(json_encode(['error' => 'Failed to add question']));
        }
    }
}
