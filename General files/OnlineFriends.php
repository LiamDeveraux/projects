<?php
  
  class OnlineFriends {
    
    private $db;
    
    public function __construct() {
      require("connection.php");
      $this->db = $conn;
    }
      
    /**
     * Update the last login in checkin time
     */
    public function updateLastCheckin($userId) {
      $query = 'UPDATE `user` SET `last_active` = NOW()' .
          'WHERE `id` = ' . (int)$userId;
      $result = $this->db->query($query);
    }
    
    /**
     * Check if the login is correct
     * @param String - User email id
     * @param String - Password for the user email
     */
    public function checkLogin($username, $password) {
      $cUsername = $this->db->real_escape_string($username);
      
      $query = "SELECT * FROM `user` 
            WHERE `username` = '{$cUsername}'";
      $result = $this->db->query($query);
      
      if ($result) {
        $row = $result->fetch_assoc();
        // Check if the credentials are correct.
        if (!empty($row) && $row['password'] === $password) {
          // Update the user login status
          $this->db->query('UPDATE `user` SET `status` = 1 WHERE `id` = ' .
              $row['id']);
          return $row;
        }
      }
      
      return false;
    }
    
    /**
     * Get the list of user's friends
     * @param type $userId
     */
    public function getUserFriends($userId) {
      $query = 'SELECT * FROM `friend` WHERE (`id_1` = ' . (int)$userId
          . ' OR `id_2` = ' . (int)$userId . ')';
      $result = $this->db->query($query);
      
      $friends = array();
      if ($result) {
        while($row = $result->fetch_assoc()) {
          $friends[] = $row;
        }
        
        return $friends;
      }
    }
    
    /**
     * Get's list of online friends for the current user.
     * @param int $userId
     */
    public function getOnlineFriends($userId) {
      $friends = $this->getUserFriends($userId);
      
      $friend_ids = '(';
      
      if (!empty($friends)) {
        foreach ($friends as $friend) {
          if ($friend['id_1'] !== $userId) {
            $friend_ids .= $friend['id_1'] . ',';
          }
          if ($friend['id_2'] !== $userId) {
            $friend_ids .= $friend['id_2'] . ',';
          }
        }
      } else {
        return array();
      }
      
      // Gather the list of friends id's.
      $friend_ids = substr($friend_ids, 0, (strlen($friend_ids) - 1)) . ')';
      
      $query = 'SELECT * FROM `user` ' .
          'WHERE TIME_TO_SEC(TIMEDIFF(NOW(), `user`.`last_active`)) <= 180 ' .
          'AND `user`.`status` = 1 ' .
          "AND `user`.`id` IN {$friend_ids}";
      $result = $this->db->query($query);
      
      if ($result) {
        $onlineFriends = array();
        while($row = $result->fetch_assoc()) {
          $onlineFriends[] = $row;
        }
        
        return $onlineFriends;
      }
      
      return array();
    }
    
    /**
     * Logout the user
     * @param type $userId
     */
    public function logout($userId) {
      $query = 'UPDATE `user` SET `user`.`status` = 0 ' . 
                'WHERE `user`.`id` = ' . (int)$userId;
      $this->db->query($query);
    }
  
  }