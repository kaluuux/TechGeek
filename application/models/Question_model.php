<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Question_model extends CI_Model
{

    public function get_recent_questions($order_by = 'created_at DESC')
    {
        $this->db->order_by($order_by);
        $query = $this->db->get('questions');
        return $query->result();
    }

    public function add_question($data)
    {
        $this->db->insert('questions', $data);
        log_message('debug', 'Last Query: ' . $this->db->last_query());
        log_message('debug', 'Session user_id: ' . $this->session->userdata('user_id'));
        return $this->db->affected_rows() > 0;
    }

    public function increment_view_count($question_id)
    {
        $this->db->set('view_count', 'view_count+1', FALSE);
        $this->db->where('id', $question_id);
        $this->db->update('questions');
    }

    public function get_question_details($question_id, $user_id)
    {
        $this->db->select('questions.*, users.username, questions.view_count, COUNT(comments.id) as comment_count, 
                           votes.vote_type as user_vote');
        $this->db->from('questions');
        $this->db->join('users', 'users.id = questions.user_id');
        $this->db->join('comments', 'comments.question_id = questions.id', 'left');
        $this->db->join('votes', 'votes.question_id = questions.id AND votes.user_id = ' . $user_id, 'left');
        $this->db->where('questions.id', $question_id);
        $question = $this->db->get()->row();

        // Check if the question exists
        if ($question) {
            $this->increment_view_count($question_id);
        }
        return $question;
    }

    public function upvote_question($question_id)
    {
        $this->db->set('upvotes', 'upvotes+1', FALSE);
        $this->db->where('id', $question_id);
        $this->db->update('questions');
    }

    public function downvote_question($question_id)
    {
        $this->db->set('downvotes', 'downvotes+1', FALSE);
        $this->db->where('id', $question_id);
        $this->db->update('questions');
    }

    public function get_upvotes($question_id)
    {
        $this->db->select('upvotes');
        $this->db->where('id', $question_id);
        $query = $this->db->get('questions');
        $row = $query->row();
        return $row->upvotes;
    }

    public function get_downvotes($question_id)
    {
        $this->db->select('downvotes');
        $this->db->where('id', $question_id);
        $query = $this->db->get('questions');
        $row = $query->row();
        return $row->downvotes;
    }

    public function cast_vote($question_id, $user_id, $vote_type)
    {
        $this->db->where('question_id', $question_id);
        $this->db->where('user_id', $user_id);
        $existing_vote = $this->db->get('votes')->row();

        $this->db->trans_start();
        $currentVote = null;

        if ($existing_vote) {
            if ($existing_vote->vote_type === $vote_type) {
                $this->db->where('id', $existing_vote->id);
                $this->db->delete('votes');
                $currentVote = 'none';
            } else {
                $this->db->where('id', $existing_vote->id);
                $this->db->update('votes', ['vote_type' => $vote_type]);
                $currentVote = $vote_type;
            }
        } else {
            $this->db->insert('votes', [
                'question_id' => $question_id,
                'user_id' => $user_id,
                'vote_type' => $vote_type
            ]);
            $currentVote = $vote_type;
        }

        $this->update_vote_counts($question_id);

        $this->db->trans_complete();

        $status = $this->db->trans_status();
        return ['status' => $status, 'currentVote' => $currentVote];
    }


    private function update_vote_counts($question_id)
    {
        $this->db->where('question_id', $question_id);
        $this->db->where('vote_type', 'up');
        $upvotes = $this->db->count_all_results('votes');
        $this->db->where('question_id', $question_id);
        $this->db->where('vote_type', 'down');
        $downvotes = $this->db->count_all_results('votes');
        $this->db->where('id', $question_id);
        $this->db->update('questions', ['upvotes' => $upvotes, 'downvotes' => $downvotes]);
    }

    public function search_questions($search_query)
    {
        if (!empty($search_query)) {
            $this->db->like('title', $search_query);
            $this->db->or_like('username', $search_query);
        }
        $this->db->join('users', 'users.id = questions.user_id');
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('questions')->result();
    }

    public function get_filtered_questions($order_by = 'created_at DESC', $search_query = '')
    {
        $this->db->select('questions.*, COALESCE(users.username, \'Anonymous\') as username, GROUP_CONCAT(DISTINCT CONCAT(COALESCE(users.username, \'Anonymous\'), ": ", comments.comment) SEPARATOR "|||") as comments, COUNT(DISTINCT comments.id) AS comment_count');
        $this->db->from('questions');
        $this->db->join('comments', 'comments.question_id = questions.id', 'left');
        $this->db->join('users', 'users.id = questions.user_id', 'left');

        if (!empty($search_query)) {
            $this->db->group_start();
            $this->db->like('questions.title', $search_query);
            $this->db->or_like('users.username', $search_query);
            $this->db->group_end();
        }

        $this->db->group_by('questions.id');
        $this->db->order_by($order_by);
        return $this->db->get()->result();
    }


    public function get_comments_by_question($question_id)
    {
        $this->db->select('comments.*, users.username');
        $this->db->from('comments');
        $this->db->join('users', 'users.id = comments.user_id');
        $this->db->where('comments.question_id', $question_id);
        $this->db->order_by('comments.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function add_comment($data)
    {
        $result = $this->db->insert('comments', $data);
        if (!$result) {
            log_message('error', 'Database error: ' . $this->db->error()['message']);
            return false;
        }
        return true;
    }

    public function get_recent_questions_with_comments($order_by = 'created_at DESC')
    {
        $this->db->select('questions.*, COALESCE(users.username, \'Anonymous\') as username, GROUP_CONCAT(DISTINCT CONCAT(COALESCE(comment_users.username, \'Anonymous\'), ": ", comments.comment) SEPARATOR "|||") as comments, COUNT(DISTINCT comments.id) AS comment_count, questions.created_at');
        $this->db->from('questions');
        $this->db->join('users', 'users.id = questions.user_id', 'left');
        $this->db->join('comments', 'comments.question_id = questions.id', 'left');
        $this->db->join('users as comment_users', 'comment_users.id = comments.user_id', 'left');
        $this->db->group_by('questions.id');
        $this->db->order_by($order_by);
        return $this->db->get()->result();
    }

    public function get_user_votes($user_id)
    {
        $this->db->select('question_id, vote_type');
        $this->db->from('votes');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        $votes = [];
        foreach ($query->result() as $row) {
            $votes[$row->question_id] = $row->vote_type;
        }
        return $votes;
    }

    public function delete_question($question_id, $user_id)
    {
        $this->db->where('id', $question_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('questions');

        if ($query->num_rows() != 1) {
            log_message('error', 'No question found, or it does not belong to the user');
            return false; // no question found
        }
        $this->db->where('id', $question_id);
        $this->db->where('user_id', $user_id);
        if ($this->db->delete('questions')) {
            log_message('info', 'Successfully deleted question with ID: ' . $question_id);
            return ($this->db->affected_rows() > 0);
        } else {
            $error = $this->db->error();
            log_message('error', 'Delete failed for question ID ' . $question_id . ': ' . $error['message']);
            return false;
        }
    }

    public function delete_comment($comment_id, $user_id)
    {
        $this->db->where('id', $comment_id);
        $this->db->where('user_id', $user_id);
        if ($this->db->delete('comments')) {
            return ($this->db->affected_rows() > 0);
        }
        return false;
    }

    public function get_comment_count_by_question($question_id)
    {
        $this->db->where('question_id', $question_id);
        $this->db->from('comments');
        return $this->db->count_all_results();
    }

    public function get_question_id_by_comment($comment_id)
    {
        $this->db->select('question_id');
        $this->db->from('comments');
        $this->db->where('id', $comment_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->question_id;
        }
        return null;
    }

    public function upvote_comment($comment_id)
    {
        $this->db->set('upvotes', 'upvotes+1', FALSE);
        $this->db->where('id', $comment_id);
        $this->db->update('comments');
    }

    public function downvote_comment($comment_id)
    {
        $this->db->set('downvotes', 'downvotes+1', FALSE);
        $this->db->where('id', $comment_id);
        $this->db->update('comments');
    }

    public function get_comment_by_id($comment_id)
    {
        $this->db->select('*');
        $this->db->from('comments');
        $this->db->where('id', $comment_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return NULL;
        }
    }

    public function cast_comment_vote($comment_id, $user_id, $vote_type)
    {
        $this->db->where('comment_id', $comment_id);
        $this->db->where('user_id', $user_id);
        $existing_vote = $this->db->get('comment_votes')->row();

        $this->db->trans_start();

        if ($existing_vote) {
            if ($existing_vote->vote_type === $vote_type) {
                $this->db->where('id', $existing_vote->id);
                $this->db->delete('comment_votes');
            } else {
                $this->db->where('id', $existing_vote->id);
                $this->db->update('comment_votes', ['vote_type' => $vote_type]);
            }
        } else {
            // If no existing vote, insert a new vote
            $this->db->insert('comment_votes', [
                'comment_id' => $comment_id,
                'user_id' => $user_id,
                'vote_type' => $vote_type
            ]);
        }

        // Update comment vote counts
        $this->update_comment_vote_counts($comment_id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return $this->get_comment_vote_count($comment_id);
        }
    }

    private function update_comment_vote_counts($comment_id)
    {
        // Calculate upvotes and downvotes
        $upvotes = $this->db->where(['comment_id' => $comment_id, 'vote_type' => 'up'])->from('comment_votes')->count_all_results();
        $downvotes = $this->db->where(['comment_id' => $comment_id, 'vote_type' => 'down'])->from('comment_votes')->count_all_results();

        // Update the comment record
        $this->db->where('id', $comment_id)->update('comments', ['upvotes' => $upvotes, 'downvotes' => $downvotes]);
    }

    public function get_comment_vote_count($comment_id)
    {
        $this->db->select('upvotes, downvotes');
        $this->db->from('comments');
        $this->db->where('id', $comment_id);
        return $this->db->get()->row();
    }
}
