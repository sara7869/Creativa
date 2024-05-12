<?php

class Connection extends CI_Model {


    public $currentUserId;
    public $followingUserId;


    /**
     * @param $currentUserId
     * @param $followingUserId
     *
     * Method to insert following/follower data to the connection object.
     */
    public function setUserIds($currentUserId, $followingUserId) {
        $this->currentUserId = $currentUserId;
        $this->followingUserId = $followingUserId;
    }

}