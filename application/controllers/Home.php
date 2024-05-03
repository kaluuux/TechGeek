<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function index() {
        $this->load->model('Question_model');
        $sort = $this->input->get('sort');  // Get the sort parameter from the URL
        
        // Determine the order by based on the sort parameter
        switch ($sort) {
            case 'most_upvotes':
                $order_by = 'upvotes DESC';
                break;
            case 'most_downvotes':
                $order_by = 'downvotes DESC';
                break;
            case 'most_views':
                $order_by = 'view_count DESC';
                break;
            default:
                $order_by = 'created_at DESC';  // Default is sorting by most recent
        }

        // Fetch questions with the specified order
        $data['questions'] = $this->Question_model->get_recent_questions($order_by);
        
        // Check if user is logged in
        $logged_in = $this->session->userdata('logged_in');

        // Pass login status and questions to the view
        $data['logged_in'] = $logged_in;
        $this->load->view('home_view', $data);
    }
}

