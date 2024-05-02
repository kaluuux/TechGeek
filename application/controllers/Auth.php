<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends CI_Controller {
    public function signup() {
        $this->load->view('signup');
    }

    public function register() {
        $data = [
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password'))
        ];
        $this->load->model('User_model');
        $this->User_model->create_user($data);
        redirect('login');
    }

    public function login() {
        $this->load->view('login');
    }

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');  // Check this line carefully
    }

    public function login_process() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
    
        $user = $this->User_model->authenticate($email, $password); // This should now return the user object or false
        if ($user) {
            $this->session->set_userdata('logged_in', true);
            $this->session->set_userdata('user_id', $user->id); // Store user ID in session
            echo json_encode(['success' => true, 'redirect' => base_url('home')]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
        }
    }
    

    public function logout() {
        $this->session->unset_userdata('logged_in');
        $this->session->sess_destroy();
        redirect('login');
    }
}
