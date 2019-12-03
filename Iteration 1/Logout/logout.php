<!-- Author : Toh Chen Long -->

<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
    if($_SESSION['logged_in'] === 'user'){
        mysqli_query($conn, "UPDATE user SET status = 0 WHERE id = '".$_SESSION['user_id']."';");
    }
    session_unset();
    session_destroy();
    header('location:../Login/login.php');
    exit();
?>
