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
    

    // public function details($question_id) {
    //     $user_id = $this->session->userdata('user_id');
    //     // $data['question'] = $this->Question_model->get_question_details($question_id);
    //     $data['question'] = $this->Question_model->get_question_details($question_id, $user_id);
    //     $data['comments'] = $this->Question_model->get_comments_by_question($question_id);
    //     $data['logged_in'] = $this->session->userdata('logged_in');
    //     $this->load->view('question_details', $data);
    // }

    public function details($question_id) {
        $user_id = $this->session->userdata('user_id');
        $question = $this->Question_model->get_question_details($question_id, $user_id);
        $comments = $this->Question_model->get_comments_by_question($question_id);
        $comment_count = $this->Question_model->get_comment_count_by_question($question_id); // Retrieve the count of comments
        $logged_in = $this->session->userdata('logged_in');
        
        $this->load->view('question_details', [
            'question' => $question,
            'comments' => $comments,
            'comment_count' => $comment_count,
            'logged_in' => $logged_in
        ]);
    }
    

    // public function details($question_id) {
    //     $user_id = $this->session->userdata('user_id'); // Assuming you store user ID in session
    //     $data['question'] = $this->Question_model->get_question_details_with_user_votes($question_id, $user_id);
    //     $data['logged_in'] = $this->session->userdata('logged_in');
    //     $this->load->view('question_details', $data);
    // }
    
    

    // public function upvote($question_id) {
    //     try {
    //         if ($this->session->userdata('logged_in')) {
    //             $user_id = $this->session->userdata('user_id');
    //             $result = $this->Question_model->cast_vote($question_id, $user_id, 'up');
    //             if ($result) {
    //                 $question = $this->Question_model->get_question_details($question_id);
    //                 echo json_encode(['upvotes' => $question->upvotes, 'downvotes' => $question->downvotes]);
    //             } else {
    //                 echo json_encode(['error' => 'Could not update vote.']);
    //             }
    //         } else {
    //             echo json_encode(['error' => 'User not logged in.']);
    //         }
    //     } catch (Exception $e) {
    //         log_message('error', $e->getMessage());
    //         echo json_encode(['error' => 'Server error occurred.']);  // More specific error can be added for debugging
    //     }
    // }
    // public function upvote($question_id) {
    //     if ($this->session->userdata('logged_in')) {
    //         $user_id = $this->session->userdata('user_id');
    //         $result = $this->Question_model->cast_vote($question_id, $user_id, 'up');
    //         if ($result) {
    //             $question = $this->Question_model->get_question_details($question_id, $user_id);  // Ensure both parameters are passed
    //             echo json_encode(['upvotes' => $question->upvotes, 'downvotes' => $question->downvotes]);
    //         } else {
    //             echo json_encode(['error' => 'Could not update vote.']);
    //         }
    //     } else {
    //         echo json_encode(['error' => 'User not logged in.']);
    //     }
    // }
    public function upvote($question_id) {
        if ($this->session->userdata('logged_in')) {
            $user_id = $this->session->userdata('user_id');
            $result = $this->Question_model->cast_vote($question_id, $user_id, 'up');
            if ($result['status']) {
                $question = $this->Question_model->get_question_details($question_id, $user_id);
                echo json_encode([
                    'upvotes' => $question->upvotes,
                    'downvotes' => $question->downvotes,
                    'currentVote' => $result['currentVote']
                ]);
            } else {
                echo json_encode(['error' => 'Could not update vote.']);
            }
        } else {
            echo json_encode(['error' => 'User not logged in.']);
        }
    }
    
    
    
    // public function downvote($question_id) {
    //     if ($this->session->userdata('logged_in')) {
    //         $user_id = $this->session->userdata('user_id');
    //         $result = $this->Question_model->cast_vote($question_id, $user_id, 'down');
    //         if ($result) {
    //             $question = $this->Question_model->get_question_details($question_id, $user_id); // Ensure both parameters are passed
    //             echo json_encode(['upvotes' => $question->upvotes, 'downvotes' => $question->downvotes]);
    //         } else {
    //             echo json_encode(['error' => 'Could not update vote.']);
    //         }
    //     } else {
    //         redirect('login');  // Redirects to login if the user is not logged in
    //     }
    // }
    
    public function downvote($question_id) {
        if ($this->session->userdata('logged_in')) {
            $user_id = $this->session->userdata('user_id');
            $result = $this->Question_model->cast_vote($question_id, $user_id, 'down');
            if ($result['status']) {
                $question = $this->Question_model->get_question_details($question_id, $user_id);
                echo json_encode([
                    'upvotes' => $question->upvotes,
                    'downvotes' => $question->downvotes,
                    'currentVote' => $result['currentVote']
                ]);
            } else {
                echo json_encode(['error' => 'Could not update vote.']);
            }
        } else {
            echo json_encode(['error' => 'User not logged in.']);
        }
    }
    

    public function post_comment() {
        try {
            $question_id = $this->input->post('question_id');
            $comment = $this->input->post('comment');
            $user_id = $this->session->userdata('user_id');
    
            if (!$user_id) {
                throw new Exception('User not logged in');
            }
    
            if (empty($comment)) {
                throw new Exception('Comment cannot be empty');
            }
    
            $data = [
                'question_id' => $question_id,
                'user_id' => $user_id,
                'comment' => $comment
            ];
    
            $this->load->model('Question_model');
            $inserted = $this->Question_model->add_comment($data);
            if (!$inserted) {
                throw new Exception('Failed to insert the comment');
            }
    
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            log_message('error', 'Error posting comment: ' . $e->getMessage());
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function delete($question_id) {
        $user_id = $this->session->userdata('user_id'); // Ensure the user is logged in
        if (!$user_id) {
            echo json_encode(['success' => false]);
            return;
        }
    
        $this->load->model('Question_model');
        $result = $this->Question_model->delete_question($question_id, $user_id);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
    
    // public function delete($question_id) {
    //     echo json_encode(['debug' => 'Method hit, question_id: ' . $question_id]);
    //     die();
    // }
    
    // public function delete_comment($comment_id) {
    //     $user_id = $this->session->userdata('user_id');
    //     if (!$user_id) {
    //         echo json_encode(['success' => false, 'message' => 'User not logged in']);
    //         return;
    //     }
    
    //     $this->load->model('Question_model');
    //     $result = $this->Question_model->delete_comment($comment_id, $user_id);
    //     if ($result) {
    //         echo json_encode(['success' => true]);
    //     } else {
    //         echo json_encode(['success' => false, 'message' => 'Failed to delete comment or permission denied']);
    //     }
    // }

    public function delete_comment($comment_id) {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            return;
        }
    
        // Retrieve the question_id associated with the comment before deletion, if needed
        $question_id = $this->Question_model->get_question_id_by_comment($comment_id);
    
        if ($this->Question_model->delete_comment($comment_id, $user_id)) {
            $new_comment_count = $this->Question_model->get_comment_count_by_question($question_id);
            echo json_encode(['success' => true, 'new_comment_count' => $new_comment_count, 'question_id' => $question_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete comment']);
        }
    }
    
    // public function details($question_id) {
    //     $user_id = $this->session->userdata('user_id');
    //     $data['question'] = $this->Question_model->get_question_details($question_id, $user_id);
    //     $data['comments'] = $this->Question_model->get_comments_by_question($question_id);
    //     $data['comment_count'] = $this->Question_model->get_comment_count_by_question($question_id); // Retrieve the count of comments
    //     $data['logged_in'] = $this->session->userdata('logged_in');
        
    //     $this->load->view('question_details', $data);
    // }
    
    
}