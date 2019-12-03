<?php
  session_start();
  
  $online_friends = array();
  
  // Check if the user is logged in.
  if (isset($_SESSION['user_id']) && isset($_COOKIE['user_id']) &&
      $_SESSION['user_id'] === $_COOKIE['user_id'] && 
      isset($_SESSION['user_id']) && $_SESSION['is_loggedin'] === true) {
    
    include_once __DIR__ . '/../includes/OnlineFriends.php';
  
    $app = new OnlineFriends();
    // Get the list of online friends for the current user.
    $online_friends = $app->getOnlineFriends($_SESSION['user_id']);
    $html = '';
    if (!empty($online_friends)) {
      foreach ($online_friends as $friend) {
        <p><span class="badge">•  </span>Donald J Trump</p>
        $html .= ' <p><span class="badge">•  </span>'.$friend['name'].'</p>';
      }
    }
    // Update the user's last active time.
    $app->updateLastCheckin($_SESSION['user_id']); 
  }
  
  // Send response to browser.
  if ($html !== '') echo $html;
?>