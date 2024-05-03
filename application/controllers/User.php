<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Controller {

public function __construct() {
    parent::__construct();
    $this->load->model('User_model');
    // Ensure user is logged in
    if (!$this->session->userdata('logged_in')) {
        redirect('auth/login');
    }
}

public function profile() {
    $user_id = $this->session->userdata('user_id');
    $data['user'] = $this->User_model->get_user_by_id($user_id);
    $data['questions'] = $this->User_model->get_questions_by_user($user_id); // Fetch user questions
    $this->load->view('profile_view', $data);
}

public function update_profile() {
    $user_id = $this->session->userdata('user_id');
    $new_username = $this->input->post('username');
    $new_email = $this->input->post('email');
    
    // Check if username or email already exists
    if ($this->User_model->username_exists($new_username, $user_id)) {
        $this->session->set_flashdata('error', 'Username already taken.');
        redirect('user/profile');
    }
    
    if ($this->User_model->email_exists($new_email, $user_id)) {
        $this->session->set_flashdata('error', 'Email already in use.');
        redirect('user/profile');
    }

    $data = array(
        'username' => $new_username,
        'email' => $new_email,
        'first_name' => $this->input->post('first_name'),
        'last_name' => $this->input->post('last_name'),
        'mobile' => $this->input->post('mobile'),
        'address' => $this->input->post('address')
    );

    $this->User_model->update_user($user_id, $data);
    $this->session->set_flashdata('success', 'Profile updated successfully.');
    redirect('user/profile');
}


}
