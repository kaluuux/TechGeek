<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Auth extends CI_Controller
{
    public function signup()
    {
        $this->load->view('signup');
    }

    public function register()
    {
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $this->load->model('User_model');

        if ($this->User_model->username_exists($username)) {
            $this->session->set_flashdata('error', 'Username already exists.');
            redirect('signup');
        }

        if ($this->User_model->email_exists($email)) {
            $this->session->set_flashdata('error', 'Email already in use.');
            redirect('signup');
        }

        $data = [
            'username' => $username,
            'email' => $email,
            'password' => md5($password)
        ];
        if ($this->User_model->create_user($data)) {
            redirect('login');
        } else {
            log_message('error', 'Failed to insert user data: ' . print_r($data, TRUE));
            $this->session->set_flashdata('error', 'Unable to register. Please try again.');
            redirect('signup');
        }
    }

    public function login()
    {
        $this->load->view('login');
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function login_process()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->User_model->authenticate($email, $password);
        if ($user) {
            $this->session->set_userdata('logged_in', true);
            $this->session->set_userdata('user_id', $user->id);
            echo json_encode(['success' => true, 'redirect' => base_url('home')]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('logged_in');
        $this->session->sess_destroy();
        redirect('login');
    }
}
