<!-- Author : Toh Chen Long -->

<?php
    session_start();
    if(!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) 
        || !isset($_SESSION['username'])){
        header("location:../Login/login.php");
        exit();
    }
    else{
        if($_SESSION['logged_in'] === "admin"){
            header("location:../../Main Pages/dashboard.php");
            exit();
        }
    }
    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forumify</title>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <link rel="stylesheet" type="text/css" href="../../General%20files/main.css">
</head>
<style type="text/css">
    .bottomPane{
        flex-direction: column;
    }
    .searchContainer{
        width:100%;
        display:flex;
        align-items:center;
        justify-content:center;
    }

    /*Search User CSS*/
    .profileCardContainer{
        width: 100%;
        height: auto;
        display:flex;
        flex-wrap: wrap;
        overflow-y: scroll;
        padding:5px 10px;
    }
    .profileCard{
        background-color: white;
        border-radius: 0.25rem;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background-color:transparent;
        margin:10px 10px;
    }
    .card_image{
        margin:0;
        padding:1rem 0px;
        background-color: rgb(230, 230, 230);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .btn {
        color: #ffffff;
        padding: 0.8rem;
        font-size: 14px;
        text-transform: uppercase;
        border-radius: 4px;
        font-weight: 400;
        display: block;
        width: 100%;
        cursor: pointer;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: transparent;
    }

    .btn:hover {
        background-color: rgba(255, 255, 255, 0.12);
    }
    @media (min-width: 40rem) {
        .profileCard {
            width: 50%;
        }
    }

    @media (min-width: 56rem) {
        .profileCard {
            width: 31.5%;
        }
    }
    .card_content {
        padding: 1rem;
        background: linear-gradient(to bottom left, #EF8D9C 40%, #FFC39E 100%);
    }

    .card_title {
        color: #ffffff;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: capitalize;
        margin: 0px;
    }

    .card_text {
        color: #ffffff;
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: 1.25rem;    
        font-weight: 400;
    }

    /*Search Post CSS*/
    .postPane{
        width: 100%;
        display: flex;
        align-items: center;
        flex-direction: column;
        overflow-y: scroll;
    }
    .cardContainer{
        width: 60%;
    }
}
</style>
<body>
    <div class="topnav">
        <a class="logo" href=""><img class="logoImage" src="../../General files/logo.png"></a>
        <div class="topnav-content">
            <a class="links active" href="search.php"><i class="fas fa-search"></i></a>
            <a class="links" href="../../Main Pages/forum.php">Forum</a>
            <?php
            	echo "<a class=\"links\" href=\"../../SendBird/web/chat.html?userid={$_SESSION['user_id']}&nickname={$_SESSION['username']}\">ChatRoom</a>";
            ?>
            <a class="links" href="../Schedule/schedule_page.php">Schedule</a>
            <div class="linkDiv">
                <a href=""><img class="profilePicture navPicture" src="../../General%20files/sample.jpg"></a>
                <div class="dropdown-content">
                    <a href="../../Iteration 3/Friend List/friendList.php">Friends</a>
                    <a href="../../Iteration 2/Manage Profile/userProfile.php">Account</a>
                    <a href="../Logout/logout.php">Logout</a>
                </div>
            </div>  
        </div>
    </div>
    <div class="bottomPane">
        <div class="searchContainer">
            <form action="search.php" method="post">
                <select name="key">
                    <option value="user">By User</option>
                    <option value="post">By Post</option>
                </select>
                <input type="text" placeholder="Search.." name="search">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <?php
            if(isset($_POST['key']) && isset($_POST['search']) && $_SERVER["REQUEST_METHOD"] == "POST"){
                $search = mysqli_real_escape_string($conn, $_POST['search']);
                
                if($_POST['key'] == "user")
                    $sql = "SELECT * FROM user WHERE username LIKE '%{$search}%';";
                else if($_POST['key'] == "post")
                    $sql = "SELECT * FROM post WHERE title LIKE '%{$search}%';";
                
                $result = mysqli_query($conn, $sql);
                if($_POST['key'] == "user"){
                    echo "<form class='profileCardContainer' action='addFriend.php' method='post'>";
                    while($row = mysqli_fetch_assoc($result)){
                        if($row['id'] == $_SESSION['user_id'])
                            continue;
                        echo    "<div class='profileCard'>
                                    <div class='card_image'>
                                        <img class='profilePicture' src='../../General files/sample.jpg'>
                                    </div>
                                    <div class='card_content'>
                                        <h2 class='card_title'>".$row['username']."</h2>
                                        <p class='card_text'>".$row['description']."</p>
                                        <button name='friend_id' type='submit' value='".$row['id']."' class='btn card_btn'>Add Friend</button>
                                    </div>
                                </div>
                                ";        
                    }
                    echo "</form>";
                }
                else if($_POST['key'] == "post"){
                    echo "<div class=\"postPane\">";
                    while($row = mysqli_fetch_assoc($result)){
                        echo    "<div class=\"cardContainer\">
                                    <div class=\"card\">
                                        <p>".$row['title']."</p>
                                        <div class=\"content\">".$row['content']."</div>
                                        <p>Reply</p>
                                    </div>
                                </div>";
                    }
                    echo "</div>";
                }
            }        
        ?>
    </div>
</body>
</html>