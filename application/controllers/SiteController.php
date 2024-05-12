<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SiteController extends CI_Controller
{

    /**
     * SiteController constructor.
     * Loads both the UserManager & PostManager Models to deal with various tasks.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserManager');
        $this->load->model('PostManager');
        $this->load->model('Comment');
        $this->form_validation->set_error_delimiters('<div class="errorMessage">', '</div><br>');
    }

    public function index()
    {
        $this->load->view('header');
        $this->load->view('navigation_bar');
        $this->load->view('user_login');
        $this->load->view('footer');
    }


    /**
     * Loads the profile edit/update page to gather additional data such as Profile name, email address, Avatar image URL, Genres that the user likes.
     */
    public function profile()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $userId = $this->session->userdata('userId');
        $this->load->view('header');
        $this->load->view('navigation_bar');
        $profileResult = $this->UserManager->getProfileData($userId);
        $this->load->view('user_profile', array(
            'profileData' => $profileResult[0],
            'genreData' => $profileResult[1]
        ));
        $this->load->view('footer');
    }

    /**
     * Loads the home page of a logged in user.
     */
    public function homepage()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $this->load->view('header');
        $this->load->view('navigation_bar');
        $this->displayProfileData();
        // echo "Welcome";
        $this->load->view('footer');
    }

    /**
     * Loads the timeline page where the user will be able to see both his posts and the posts of the users' he's following.
     */
    public function timelinePage()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $userId = $this->session->userdata('userId');
        $this->load->view('header');
        $this->load->view('navigation_bar');
        $timelinePostsResult = $this->PostManager->getTimelinePosts($userId);
        $this->load->view('user_timeline', array('timelinePosts' => $timelinePostsResult));
        $this->load->view('footer');
    }

    /**
     * Loads the search page where the user will be able to find other users under a selected genre.
     */
    public function searchPage()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $this->load->view('header');
        $this->load->view('navigation_bar');
        $this->displaySearch();
        $this->load->view('footer');
    }

    /**
     * Loads a selected user's public home page.
     */
    public function viewUserProfile()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $this->load->view('header');
        $this->load->view('navigation_bar');
        $this->loadUserProfile();
        $this->load->view('footer');
    }

    /**
     * Load connections page (Shows followers/following users & friends)
     */
    public function connections()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $userId = $this->session->userdata('userId');
        $this->load->view('header');
        $this->load->view('navigation_bar');
        $followingResult = $this->UserManager->getFollowing($userId);
        $followerResult = $this->UserManager->getFollowers($userId);
        $friendsResult = $this->UserManager->getFriends($userId);
        $this->load->view('user_connections', array(
            'followingData' => $followingResult,
            'followerData' => $followerResult,
            'friendsData' => $friendsResult
        ));
        $this->load->view('footer');
    }


    public function contactsList()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $this->load->view('header');
        $this->load->view('navigation_bar');
        $this->load->view('user_contacts');
        $this->load->view('footer');
    }

    /**
     * Validations for handling editing/updating profile section
     */
    public function createProfile()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $this->form_validation->set_rules(
            'profileName',
            'Profile Name',
            'trim|required|min_length[8]|max_length[32]|is_unique[user.username]',
            array(
                'min_length' => 'Username length has to be between 8 & 32 characters.',
                'max_length' => 'Username length has to be between 8 & 32 characters.',
                'is_unique' => 'Username is already in use.'
            )
        );
        $this->form_validation->set_rules(
            'avatarUrl',
            'Avatar URL',
            'trim|valid_url|max_length[1024]',
            array(
                'max_length' => 'Avatar URL character length is restricted to 1024.',
                'matches' => 'Passwords do not match.'
            )
        );
        $this->form_validation->set_rules('genres', 'Genre Selection', 'required');
        $this->form_validation->set_rules(
            'emailAddress',
            'Email Address',
            'trim|required|valid_email|max_length[64]',
            array(
                'valid_email' => 'Email address is not valid.',
                'max_length' => 'Maximum character length of the Email address is only 64.'
            )
        );

        if ($this->form_validation->run() == FALSE) {
            $this->profile();
        } else {
            $userId = $this->session->userdata('userId');
            $formProfileName = $this->input->post('profileName');
            $formAvatarUrl = $this->input->post('avatarUrl');
            $formGenres = $this->input->post('genres');
            $formEmail = $this->input->post('emailAddress');
            $createProfileResult = $this->UserManager->createProfile($userId, $formProfileName, $formAvatarUrl, $formGenres, $formEmail);
            redirect('/SiteController/homepage');
        }
    }

    /**
     * Delete logged in user's profile
     */
    public function deleteProfile()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $userId = $this->session->userdata('userId');
        $deleteProfileResult = $this->UserManager->deleteProfileData($userId);
        redirect('/UserController/logoutUser');
    }

    /**
     * Display logged in user's home page.
     */
    public function displayProfileData()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $userId = $this->session->userdata('userId');
        $profileResult = $this->UserManager->getProfileData($userId);
        $publishedPosts = $this->PostManager->retrievePublishedPosts($userId);
        $draftPosts = $this->PostManager->retrieveDraftPosts($userId);
        $this->load->view('user_homepage', array(
            'publishedPosts' => $publishedPosts,
            'draftPosts' => $draftPosts,
            'profileData' => $profileResult[0],
            'genreData' => $profileResult[1]
        ));
    }

    /**
     * Create post within user's home page
     */
    public function createHomePost()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }

        $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
        $this->form_validation->set_rules('postContent', 'Content', 'required|max_length[1000]');
        $this->form_validation->set_rules('image', 'Image URL', 'valid_url|max_length[255]');
        $this->form_validation->set_rules('category', 'Category', 'required');
        $this->form_validation->set_rules('tags', 'Tags', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[Draft,Published]');

        if ($this->form_validation->run() == FALSE) {
            $this->homepage();
        } else {
            $postData = array(
                'title' => $this->input->post('title'),
                'postContent' => $this->input->post('postContent'),
                'image' => $this->input->post('image'),
                'category' => $this->input->post('category'),
                'tags' => $this->input->post('tags'),
                'status' => $this->input->post('status')
            );
            $userId = $this->session->userdata('userId');
            $createPostResult = $this->PostManager->createPost($postData, $userId);
            echo json_encode($postData); 
            redirect('/SiteController/homepage');
        }
    }

    /**
     * Create post from timeline page.
     */
    public function createTimelinePost()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }

        $postData = array(
            'title' => $this->input->post('title'),
            'postContent' => $this->input->post('postContent'),
            'image' => $this->input->post('image'),
            'category' => $this->input->post('category'),
            'tags' => $this->input->post('tags'),
            'status' => $this->input->post('status')
        );
        $userId = $this->session->userdata('userId');
        $createPostResult = $this->PostManager->createPost($postData, $userId);
        redirect('/SiteController/timelinePage');
    }

    public function viewPost($postId)
    {
        $post = $this->Post->getPostContent($postId);
        $viewPostResult = $this->PostManager->viewSelectedPost($postId);
        $comments = $this->PostManager->getCommentsForPost($postId);
        $likes = $this->PostManager->getLikesForPost($postId);

        $authorData = $this->UserManager->getProfileData($post['userId']);
        $data = array(
            'postId' => $postId,
            'post' => $post,
            'posts' => $viewPostResult,
            'comments' => $comments,
            'likes' => $likes,
            'authorData' => $authorData
        );

        $this->load->view('header');
        $this->load->view('navigation_bar');
        $this->load->view('single_post', $data);
        $this->load->view('footer');
    }

    /**
     * Edit logged in user's selected post using post ID.
     */
    public function editPost()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $postId = $this->uri->segment(3);
        $editPostResult = $this->PostManager->editSelectedPost($postId);
        if ($this->PostManager->getPostOwnerId($postId) === 'Error' or $this->session->userdata('userId') != $editPostResult[0]->getUserId()) {
            redirect('/SiteController/homepage');
        }
        $this->load->view('header');
        $this->load->view('navigation_bar');
        $this->load->view('edit_post', array('posts' => $editPostResult));
        $this->load->view('footer');
    }

    /**
     * validation for updating edited post
     */
    public function updatePost()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $this->form_validation->set_rules(
            'postContent',
            'Post Content',
            'required|max_length[1000]',
            array('max_length' => 'Maximum character length of a post is 1000.')
        );

        if ($this->form_validation->run() == FALSE) {
            $this->editPost();
        } else {
            $postContent = $this->input->post('postContent');
            $postId = $this->uri->segment(3);
            $updatePostResult = $this->PostManager->updateSelectedPost($postId, $postContent);
            redirect('/SiteController/homepage');
        }
    }

    /**
     * Delete logged in user's selected post.
     */
    public function deletePost()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $postId = $this->uri->segment(3);
        if ($this->session->userdata('userId') != $this->PostManager->getPostOwnerId($postId)) {
            redirect('/UserController/login');
        }
        $deletePostResult = $this->PostManager->deleteSelectedPost($postId);
        redirect('/SiteController/homepage');
    }

    public function likePost($postId)
    {
        echo "sf";
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $postId = $this->input->post('postId');
        $userId = $this->session->userdata('userId');
        echo $userId;
        $this->PostManager->likePost($postId, $userId);
        // redirect('/SiteController/timelinePage');
    }

    public function displayLikeButton($postId)
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $userId = $this->session->userdata('userId');
        $isLiked = $this->PostManager->checkIfUserLikedPost($postId, $userId);
        $this->load->view('like_button', array('postId' => $postId, 'isLiked' => $isLiked));
    }

    public function add_comment()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }

        $comment = $this->input->post('comment');
        $postId = $this->input->post('postId');

        $userId = $this->session->userdata('userId');
        $data = array(
            'post_id' => $postId,
            'user_id' => $userId,
            'comment' => $comment
        );
        // echo json_encode($data);

        $result = $this->Comment->add_comment($data);
        // echo json_encode($result);
        redirect('SiteController/viewPost/' . $postId);
    }

    /**
     * Search user's using their favorite genres from the search page
     */
    public function search()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $query = $this->input->get('query');
        $posts = $this->PostManager->searchPosts($query);
        $users = $this->UserManager->searchUsers($query);
        $data = array(
            'posts' => $posts,
            'users' => $users
        );
        $this->load->view('header');
        $this->load->view('navigation_bar');
        $this->load->view('user_search', $data); // Display the search results on the same page
        $this->load->view('footer');
    }

    public function displaySearch()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $emptyResult = '';
        $userId = $this->session->userdata('userId');

        $data = array(
            'posts' => [],
            'users' => []
        );
        $this->load->view('user_search', $data);
    }


    /**
     * load selected user's profile wit han option to follow/unfollow
     */
    public function loadUserProfile()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $currentUserId = $userId = $this->session->userdata('userId');
        $userId = $this->uri->segment(3);
        if ($this->UserManager->checkIfUserExists($userId) === 'Error') {
            redirect('/SiteController/homepage');
        }
        $profileResult = $this->UserManager->getProfileData($userId);
        $postResult = $this->PostManager->retrievePosts($userId);
        $ifFollowingResult = $this->UserManager->findIfFollowing($currentUserId, $userId);
        $this->load->view('user_profile_page', array(
            'posts' => $postResult,
            'profileData' => $profileResult[0],
            'genreData' => $profileResult[1],
            'isFollowing' => $ifFollowingResult
        ));
    }


    /**
     * follow selected user using his user ID.
     */
    public function followUser()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $userId = $this->session->userdata('userId');
        $actionType = $this->uri->segment(2);
        $foundUserId = $this->uri->segment(3);
        $actionResult = $this->UserManager->userActions($userId, $actionType, $foundUserId);
        redirect('/SiteController/homepage');
    }

    /**
     * unfollow selected user using user ID.
     */
    public function unfollowUser()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('/UserController/login');
        }
        $userId = $this->session->userdata('userId');
        $actionType = $this->uri->segment(2);
        $foundUserId = $this->uri->segment(3);
        $actionResult = $this->UserManager->userActions($userId, $actionType, $foundUserId);
        redirect('/SiteController/homepage');
    }

    public function reactToPost()
{
    if (!$this->session->userdata('user_logged_in')) {
        redirect('/UserController/login');
    }
    $postId = $this->input->post('postId');
    $userId = $this->session->userdata('userId');
    $reactionType = $this->input->post('reaction');

    $this->PostManager->reactToPost($postId, $userId, $reactionType);

    redirect('/SiteController/timelinePage');
}

public function reactToPostHomePage()
{
    if (!$this->session->userdata('user_logged_in')) {
        redirect('/UserController/login');
    }
    $postId = $this->input->post('postId');
    $userId = $this->session->userdata('userId');
    $reactionType = $this->input->post('reaction');

    $this->PostManager->reactToPost($postId, $userId, $reactionType);

    redirect('/SiteController/homePage');
}

}
