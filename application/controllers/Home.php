<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function index() {
        $this->load->model('Question_model');
        $search_query = $this->input->get('search_query', TRUE);
        
        $sort = $this->input->get('sort', TRUE);
    
    if (empty($sort)) {
        $sort = 'recent';
    }

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
            $sort = 'recent';
            break;

        }
        if (!empty($search_query)) {
            $data['questions'] = $this->Question_model->get_filtered_questions($order_by, $search_query);
        } else {
            $data['questions'] = $this->Question_model->get_recent_questions_with_comments($order_by);
        }
        $data['logged_in'] = $this->session->userdata('logged_in');
        $data['sort'] = $sort;
        $user_id = $this->session->userdata('user_id');
        $data['user_votes'] = $this->Question_model->get_user_votes($user_id);
        $this->load->view('home_view', $data);
    }
}

