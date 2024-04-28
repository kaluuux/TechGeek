<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function index() {
        // $this->load->view('home_view');  // This will load the view file 'home_view.php'
        $this->load->model('Question_model');
        $data['questions'] = $this->Question_model->get_recent_questions();
                // Check if user is logged in
                $logged_in = $this->session->userdata('logged_in');

                // Pass login status to the view
                $data['logged_in'] = $logged_in;
        
                // Load home view with recent questions and login status
                $this->load->view('home_view', $data);
        // $this->load->view('home_view', $data);
    }
}
