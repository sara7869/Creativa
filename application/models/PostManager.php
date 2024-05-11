<?php
/**
 * Created by PhpStorm.
 * User: Suwadith
 * Date: 11/18/2019
 * Time: 11:11 AM
 */

//include_once('Post.php');

class PostManager extends CI_Model {

    /**
     * PostManager constructor.
     * Loaded the DB connection module to do DB functions.
     */
    public function __construct() {
        $this->load->database();
    }

    /**
     * @param $postContent
     * @param $userId
     *
     * Creating a post using both the content and user ID.
     */
    public function createPost($postData, $userId) {
        $postData['userId'] = $userId;
        $postData['dateTime'] = date("Y-m-d H:i:s");
        $this->db->insert('post', $postData);
        return $this->db->insert_id();
    }

    /**
     * @param $userId
     * @return mixed
     *
     * retrieving selected user's post.
     */
    public function retrievePosts($userId) {
        $this->db->select('postId, title, postContent, image, userId, dateTime');
        $this->db->from('post');
        $this->db->where('userId', $userId);
        $this->db->order_by('dateTime', 'desc');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->custom_result_object('Post');
        }
    }

    /**
     * @param $postId
     * @return mixed
     *
     * Method to edit selected post.
     */
    // public function viewSelectedPost($postId) {
    //     $this->db->where('postId', $postId);
    //     $result = $this->db->get('post');

    //          if($result->num_rows() > 0) {
    //              return $result->custom_result_object('Post');
    //          }
    // }


    /**
     * @param $postId
     * @return mixed
     *
     * Method to edit selected post.
     */
    public function editSelectedPost($postId) {
        $this->db->where('postId', $postId);
        $result = $this->db->get('post');

             if($result->num_rows() > 0) {
                 return $result->custom_result_object('Post');
             }
    }


    /**
     * @param $postId
     * @param $postContent
     *
     * Method to edit/update post.
     */
    public function updateSelectedPost($postId, $postContent) {
        $this->db->where('postId', $postId);
        $result = $this->db->get('post');

        if($result->num_rows() > 0) {
            $postObjArray = $result->custom_result_object('Post');
            $postObj = $postObjArray[0];
            $postObj->updatePostData($postContent);
            $this->db->where('postId', $postId);
            $this->db->update('post', $postObj);
        }
    }


    /**
     * @param $postId
     *
     * Method to delete selected post using post ID.
     */
    public function deleteSelectedPost($postId) {
        $this->db->where('postId', $postId);
        $this->db->delete('post');
    }

    public function viewSelectedPost($postId) {
        return $this->getPostContent($postId);
    }

    public function getPostContent($postId) {
        $query = $this->db->get_where('post', array('postId' => $postId));
        return $query->row_array();
    }


    /**
     * @param $userId
     * @return array|null
     *
     * Method to populate timeline posts by combining user's own posts and follower's post and then merging them on to an array
     * and then reordering them in descending time order.
     */
    public function getTimelinePosts($userId) {

        $userPosts = array();
        $otherPosts = array();
        $this->db->select('post.postId, post.postContent, user.userId, user.avatarUrl, user.profileName, user.username, post.dateTime, post.like_count, post.image, post.title');
        $this->db->from('post');
        $this->db->join('user', 'user.userId = post.userId');
        $this->db->where('user.userId', $userId);
        $this->db->order_by('post.dateTime', 'desc');
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $userPosts = $result->result();
        }


        $this->db->select('post.postId, post.postContent, user.userId, user.avatarUrl, user.profileName, user.username, post.dateTime, post.like_count, post.image, post.title');
        $this->db->from('post');
        $this->db->join('connection', 'post.userId = connection.followingUserId');
        $this->db->join('user', 'connection.followingUserId = user.userId');
        $this->db->where("connection.currentUserId = $userId");
        $this->db->order_by('post.dateTime', 'desc');
        $timelineResult = $this->db->get();

        if($timelineResult->num_rows() > 0){
            $otherPosts = $timelineResult->result();
        }

        if(count($userPosts)!=0 AND count($otherPosts)!=0) {
            $allPosts = array_merge($userPosts, $otherPosts);

            usort($allPosts, function($a, $b) {
                return strtotime($b->dateTime) - strtotime($a->dateTime);
            });

            return $allPosts;
        }elseif(count($userPosts)!=0 AND count($otherPosts)==0){
            return $userPosts;
        }elseif(count($userPosts)==0 AND count($otherPosts)!=0){
            return $otherPosts;
        }else {
            return null;
        }


    }

    /**
     * @param $postId
     * @return string
     *
     * Validation to prevent unauthorized post deletion.
     */
    public function getPostOwnerId($postId) {
        $this->db->select('userId');
        $this->db->where('postId', $postId);
        $ownerResult = $this->db->get('post');

        if ($ownerResult->num_rows() > 0) {
            return $ownerResult->row(0)->userId;
        } else {
            return 'Error';
        }

    }

    public function likePost($postId, $userId) {
        if (!is_numeric($postId) ||!is_numeric($userId)) {
            // Handle invalid input, e.g., log an error or throw an exception
            return false;
        }
        // Check if the user has already liked the post
        $isLiked = $this->checkIfUserLikedPost($postId, $userId);
        if ($isLiked) {
            // User has already liked the post, so unlike it
            $result =$this->unlikePost($postId, $userId);
            if (!$result) {
                // Log or handle the error
                return false;
            }
        } else {
            // User has not liked the post, so like it
            $result = $this->likePostCount($postId);
            if (!$result) {
                // Log or handle the error
                return false;
            }
            $result = $this->addUserToLikes($postId, $userId);
            echo "User added to likes.";
            if (!$result) {
                echo "Error adding user to likes.";
                // Log or handle the error
                return false;
            }
        }
    
        return true;
    }

    private function checkIfUserLikedPost($postId, $userId) {
        $this->db->where('post_id', $postId);
        $this->db->where('user_id', $userId);
        $query = $this->db->get('likes');
        if ($query->num_rows() > 0) {
            return true; // User has liked the post
        } else {
            return false; // User has not liked the post
        }
    }

    private function unlikePost($postId, $userId) {
        $this->db->where('postId', $postId);
        $this->db->where('userId', $userId);
        $this->db->delete('likes');
    
        $this->db->where('postId', $postId);
        // $this->db->set('like_count', 'like_count - 1', FALSE);
        $result = $this->db->update('post', array('like_count' => 'like_count - 1'));
        $this->db->update('post');
        return $result;
    }

    private function likePostCount($postId) {
        try {
            $this->db->where('postId', $postId);
            // $result = $this->db->update('post', array('like_count' => 'like_count + 1'));
            $this->db->set('like_count', 'like_count + 1', FALSE);
            $this->db->update('post');
            return true;
        } catch (Exception $e) {
            // Log the error message
            error_log($e->getMessage());
            return false;
        }
    }

    private function addUserToLikes($postId, $userId) {
        $data = array(
            'post_id' => $postId,
            'user_id' => $userId
        );
        $this->db->insert('likes', $data);
        return true;
    }

    // private function getCommentsForPost($postId) {
    //     try {
    //         $this->db->where('post_id', $postId);
    //         $query = $this->db->get('comments');
    //         return $query->result();

    //     } catch (Exception $e) {
    //         // Log the error message
    //         error_log($e->getMessage());
    //         return false;
    //     }
    // }

    // public function getLikesForPost($postId) {
    //     try {
    //         $this->db->where('post_id', $postId);
    //         $query = $this->db->get('likes');
    //         return $query->result();

    //     } catch (Exception $e) {
    //         // Log the error message
    //         error_log($e->getMessage());
    //         return false;
    //     }
    // }

    public function getCommentsForPost($postId) {
        $this->db->select('*');
        $this->db->from('comments');
        $this->db->where('post_id', $postId);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLikesForPost($postId) {
        $this->db->select('*');
        $this->db->from('likes');
        $this->db->where('post_id', $postId);
        $query = $this->db->get();
        return $query->result_array();
    }

}