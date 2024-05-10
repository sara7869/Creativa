<?php
class Comment extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_comments_by_post_id($postId)
    {
        echo $postId;
        $query = $this->db->get_where('comments', array('post_id' => $postId));
        echo $query->num_rows();
        return $query->result();
    }

    // public function add_comment($data)
    // {
    //     $this->db->insert('comments', $data);
    //     // echo "Comment added";

    // }

    public function add_comment($data)
    {
        // Check if the necessary keys are present in $data
        // if (!isset($data['post_id']) || !isset($data['user_id']) || !isset($data['comment'])) {
        //     // Handle the error, e.g., log it, throw an exception, or return an error message
        //     log_message('error', 'Missing required fields in add_comment');
        //     return false;
        // }

        // // Insert the comment into the database
        $this->db->insert('comments', $data);

        // // Optionally, return the ID of the inserted comment
        // return $this->db->insert_id();
        return true;
    }

}
