<?php

/**
 * Created by PhpStorm.
 * User: Suwadith
 * Date: 11/18/2019
 * Time: 11:11 AM
 */

class Post extends CI_Model
{

    public $postId;
    public $postContent;
    public $dateTime;
    public $userId;
    public $title;
    public $content;
    public $image;
    public $user_id;

    /**
     * @param $postContent
     * @param $dateTime
     * @param $userId
     *
     * Method to create a post.
     */
    public function createPost($postContent, $dateTime, $userId)
    {
        $this->title = $postContent['title'];
        $this->content = $postContent['content'];
        $this->image = $postContent['image'];
        $this->user_id = $postContent['user_id'];
        $this->dateTime = date("Y-m-d H:i:s");
    }


    /**
     * @return mixed
     *
     * Method to display images and urls in proper formats using regex
     */
    public function getPostContent($postId)
    {
        $query = $this->db->get_where('post', array('PostId' => $postId));
        return $query->row_array();
    }


    public function getRawPostContent()
    {
        return $this->postContent;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    public function updatePostData($postContent)
    {
        $this->postContent = $postContent;
    }
}
