<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function index() {
        $this->load->model('Question_model');
        $sort = $this->input->get('sort');
        $search_query = $this->input->get('search_query', TRUE); // Fetch search query, default to TRUE to allow empty strings

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
                $order_by = 'created_at DESC';
        }

        $data['questions'] = $this->Question_model->get_filtered_questions($order_by, $search_query);
        $data['logged_in'] = $this->session->userdata('logged_in');
        $this->load->view('home_view', $data);
    }
}

