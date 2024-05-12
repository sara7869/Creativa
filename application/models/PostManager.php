<?php

class PostManager extends CI_Model
{

    /**
     * PostManager constructor.
     * Loaded the DB connection module to do DB functions.
     */
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * @param $postContent
     * @param $userId
     *
     * Creating a post using both the content and user ID.
     */
    public function createPost($postData, $userId)
    {
        $postData['userId'] = $userId;
        $postData['dateTime'] = date("Y-m-d H:i:s");

        $postData['title'] = $postData['title'];
        // $postData['postContent'] = $postData['content'];
        $postData['image'] = $postData['image'];
        $postData['category'] = $postData['category'];
        $postData['tags'] = $postData['tags'];
        $postData['status'] = $postData['status']; 
        echo json_encode($postData);

        $this->db->insert('post', $postData);
        // echo json_encode($postData);


        return $this->db->insert_id();
    }

    /**
     * @param $userId
     * @return mixed
     *
     * retrieving selected user's post.
     */
    public function retrievePublishedPosts($userId)
    {
        $this->db->select('postId, title, postContent, image, userId, dateTime');
        $this->db->from('post');
        $this->db->where('userId', $userId);
        $this->db->where('status', 'Published');
        $this->db->order_by('dateTime', 'desc');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->custom_result_object('Post');
        }
    }

    public function retrieveDraftPosts($userId)
    {
        $this->db->select('postId, title, postContent, image, userId, dateTime');
        $this->db->from('post');
        $this->db->where('userId', $userId);
        $this->db->where('status', 'Draft'); // Filter only draft posts
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
    public function editSelectedPost($postId)
    {
        $this->db->where('postId', $postId);
        $result = $this->db->get('post');

        if ($result->num_rows() > 0) {
            return $result->custom_result_object('Post');
        }
    }


    /**
     * @param $postId
     * @param $postContent
     *
     * Method to edit/update post.
     */
    public function updateSelectedPost($postId, $postContent)
    {
        $this->db->where('postId', $postId);
        $result = $this->db->get('post');

        if ($result->num_rows() > 0) {
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
    public function deleteSelectedPost($postId)
    {
        $this->db->where('postId', $postId);
        $this->db->delete('post');
    }

    public function viewSelectedPost($postId)
    {
        return $this->getPostContent($postId);
    }

    public function getPostContent($postId)
    {
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
    public function getTimelinePosts($userId)
    {
        $userPosts = array();
        $otherPosts = array();
        
        // Query for user's posts
        $this->db->select('post.postId, post.postContent, user.userId, user.avatarUrl, user.profileName, user.username, post.dateTime, post.like_count, post.image, post.title,
            SUM(CASE WHEN reactions.reaction_type = "happy" THEN 1 ELSE 0 END) AS happy_count,
            SUM(CASE WHEN reactions.reaction_type = "surprised" THEN 1 ELSE 0 END) AS surprised_count,
            SUM(CASE WHEN reactions.reaction_type = "sad" THEN 1 ELSE 0 END) AS sad_count,
            SUM(CASE WHEN reactions.reaction_type = "angry" THEN 1 ELSE 0 END) AS angry_count,
            SUM(CASE WHEN reactions.reaction_type = "laughing" THEN 1 ELSE 0 END) AS laughing_count,
            SUM(CASE WHEN reactions.reaction_type = "fire" THEN 1 ELSE 0 END) AS fire_count');
        $this->db->from('post');
        $this->db->join('user', 'user.userId = post.userId');
        $this->db->join('reactions', 'reactions.post_id = post.postId', 'left');
        $this->db->where('user.userId', $userId);
        $this->db->where('post.status', 'Published'); 
        $this->db->order_by('post.dateTime', 'desc');
        $result = $this->db->get();

        echo json_encode($result->result());
    
        if ($result->num_rows() > 0) {
            $userPosts = $result->result();
        }
    
        // Query for posts from followed users
        $this->db->select('post.postId, post.postContent, user.userId, user.avatarUrl, user.profileName, user.username, post.dateTime, post.like_count, post.image, post.title,
            SUM(CASE WHEN reactions.reaction_type = "happy" THEN 1 ELSE 0 END) AS happy_count,
            SUM(CASE WHEN reactions.reaction_type = "surprised" THEN 1 ELSE 0 END) AS surprised_count,
            SUM(CASE WHEN reactions.reaction_type = "sad" THEN 1 ELSE 0 END) AS sad_count,
            SUM(CASE WHEN reactions.reaction_type = "angry" THEN 1 ELSE 0 END) AS angry_count,
            SUM(CASE WHEN reactions.reaction_type = "laughing" THEN 1 ELSE 0 END) AS laughing_count,
            SUM(CASE WHEN reactions.reaction_type = "fire" THEN 1 ELSE 0 END) AS fire_count');        
        $this->db->from('post');
        $this->db->join('connection', 'post.userId = connection.followingUserId');
        $this->db->join('user', 'connection.followingUserId = user.userId');
        $this->db->join('reactions', 'reactions.post_id = post.postId', 'left');
        $this->db->where("connection.currentUserId", $userId);
        $this->db->where('post.status', 'Published'); // Filter for published posts
        $this->db->order_by('post.dateTime', 'desc');
        $timelineResult = $this->db->get();
    
        if ($timelineResult->num_rows() > 0) {
            $otherPosts = $timelineResult->result();
        }
    
        // Merge and sort posts
        if (count($userPosts) != 0 and count($otherPosts) != 0) {
            $allPosts = array_merge($userPosts, $otherPosts);
    
            usort($allPosts, function ($a, $b) {
                return strtotime($b->dateTime) - strtotime($a->dateTime);
            });
    
            return $allPosts;
        } elseif (count($userPosts) != 0 and count($otherPosts) == 0) {
            return $userPosts;
        } elseif (count($userPosts) == 0 and count($otherPosts) != 0) {
            return $otherPosts;
        } else {
            return null;
        }
    }

    /**
     * @param $postId
     * @return string
     *
     * Validation to prevent unauthorized post deletion.
     */
    public function getPostOwnerId($postId)
    {
        $this->db->select('userId');
        $this->db->where('postId', $postId);
        $ownerResult = $this->db->get('post');

        if ($ownerResult->num_rows() > 0) {
            return $ownerResult->row(0)->userId;
        } else {
            return 'Error';
        }
    }

    public function likePost($postId, $userId)
    {
        if (!is_numeric($postId) || !is_numeric($userId)) {
            // Handle invalid input, e.g., log an error or throw an exception
            return false;
        }
        // Check if the user has already liked the post
        $isLiked = $this->checkIfUserLikedPost($postId, $userId);
        echo $isLiked;
        if ($isLiked) {
            // User has already liked the post, so unlike it
            $result = $this->unlikePost($postId, $userId);
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

    private function checkIfUserLikedPost($postId, $userId)
    {
        $this->db->where('post_id', $postId);
        $this->db->where('user_id', $userId);
        $query = $this->db->get('likes');
        if ($query->num_rows() > 0) {
            return true; // User has liked the post
        } else {
            return false; // User has not liked the post
        }
    }

    private function unlikePost($postId, $userId)
    {
        $this->db->where('postId', $postId);
        $this->db->where('userId', $userId);
        $this->db->delete('likes');

        $this->db->where('postId', $postId);
        // $this->db->set('like_count', 'like_count - 1', FALSE);
        $result = $this->db->update('post', array('like_count' => 'like_count - 1'));
        $this->db->update('post');
        return $result;
    }

    private function likePostCount($postId)
    {
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

    private function addUserToLikes($postId, $userId)
    {
        $data = array(
            'post_id' => $postId,
            'user_id' => $userId
        );
        $this->db->insert('likes', $data);
        return true;
    }

    public function searchPosts($query)
    {
        $this->db->select('postId, title, postContent, category, tags, status, draft_status');
        $this->db->like('title', $query);
        $this->db->or_like('postContent', $query);
        $this->db->or_like('category', $query);
        $this->db->or_like('tags', $query);
        $this->db->or_like('status', $query);
        $this->db->or_like('draft_status', $query);
        $query = $this->db->get('post');
        return $query->result_array();
    }

    public function getCommentsForPost($postId)
    {
        $this->db->select('*');
        $this->db->from('comments');
        $this->db->where('post_id', $postId);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLikesForPost($postId)
    {
        $this->db->select('*');
        $this->db->from('likes');
        $this->db->where('post_id', $postId);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function reactToPost($postId, $userId, $reactionType)
    {
        $existingReaction = $this->db->get_where('reactions', array('post_id' => $postId, 'user_id' => $userId))->row();

        if ($existingReaction) {
            $this->db->where('post_id', $postId);
            $this->db->where('user_id', $userId);
            $this->db->update('reactions', array('reaction_type' => $reactionType));
        } else {
            $this->db->insert('reactions', array('post_id' => $postId, 'user_id' => $userId, 'reaction_type' => $reactionType));
        }

        $this->updateReactionCounts($postId);
    }

    private function updateReactionCounts($postId)
    {
        // Query to get reaction counts for the post
        $this->db->select('reaction_type, COUNT(*) as count');
        $this->db->from('reactions');
        $this->db->where('post_id', $postId);
        $this->db->group_by('reaction_type');
        $counts = $this->db->get()->result_array();

        // Update reaction counts in the post table
        $updateData = array();
        foreach ($counts as $count) {
            $updateData[$count['reaction_type'] . '_count'] = $count['count'];
        }

        $this->db->where('post_id', $postId);
        $this->db->update('post', $updateData);
    }
}
