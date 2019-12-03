<!-- Author : Ting Kee Chung -->

<?php
    session_start();
    if(!isset($_SESSION['logged_in'])){
        header('Location:../../Iteration 1/Login/login.php');
        exit();
    }
    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/OnlineFriends.php';
  
    $app = new OnlineFriends();
    $online_friends = $app->getOnlineFriends($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forumify</title>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <link rel="stylesheet" type="text/css" href="../../General files/main.css">
    <style>
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
    .cardContainer{
        width: 60%;
    }
    </style>
</head>
<body>
    <div class="topnav">
        <a class="logo" href="../Login/login.php"><img class="logoImage" src="../../General files/logo.png"></a>
        <div class="topnav-content">
            <a href="../../Iteration 1/Search/search.php"><i class="fas fa-search"></i></a>
            <a class="links active" href="../../Main Pages/forum.php">Forum</a>
            <?php
            	echo "<a class=\"links\" href=\"../../SendBird/web/chat.html?userid={$_SESSION['user_id']}&nickname={$_SESSION['username']}\">ChatRoom</a>";
            ?>
            <a class="links" href="../../Iteration 1/Schedule/schedule_page.php">Schedule</a>
            <div class="linkDiv">
                <a href=""><img class="profilePicture navPicture" src="../../General files/sample.jpg"></a>
                <div class="dropdown-content">
                    <a href="../../Iteration 3/Friend List/friendList.php">Friends</a>
                    <a href="../../Iteration 2/User Profile/userProfile.php">Account</a>
                    <a href="../../Iteration 1/Logout/logout.php">Logout</a>
                </div>
            </div>  
        </div>
    </div>
    <div class="bottomPane">
        <div class="leftPane">
            <!-- <div>
                <div class="card">
                    <p>Username</p>
                    <div class="content">
                        Lorem ipsum
                    </div>
                    <a href="">Reply</a>
                </div>
            </div> -->
            <div>
                Friends<div style="text-align: right; margin: 0 160px 0 0">
                <!--<button onclick="location.href='friendRequest.php'">Friend Requests</button>-->
                </div>
                    <?php
                        $sql = 'SELECT * FROM friend WHERE id_1 = "'.$_SESSION['user_id'].'" OR id_2 = "'.$_SESSION['user_id'].'";';
                        $result = mysqli_query($conn, $sql);
                        if($result->num_rows == 0){
                            echo "<p class='errorMessage'>You don't have any friends now</p>";
                        }
                        $friends = array();
                        //$count = 0;
                        if ($result->num_rows != 0) {
                            while($row = $result->fetch_assoc()) {
                                if($row["id_1"]==$_SESSION['user_id']){
                                    $friends[] = $row["id_2"];
                                    //$count ++;
                                }
                                else if($row["id_2"]==$_SESSION['user_id']){
                                    $friends[] = $row["id_1"];
                                    //$count ++;
                                }
                            }
                        }
                        echo "<form class='profileCardContainer' action='deleteFriend_proc.php' method='post'>";

                        $size = sizeof($friends);
                        $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?;");
                        for ($x = 0; $x < $size; $x++) {
                            $stmt->bind_param("s", $friends[$x]);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result){
                                while($row = $result->fetch_assoc()) {
                                    echo    "<div class='profileCard'>
                                                <div class='card_image'>
                                                    <img class='profilePicture' src='../../General files/sample.jpg'>
                                                </div>
                                                <div class='card_content'>
                                                    <h2 class='card_title'>".$row['username']."</h2>
                                                    <p class='card_text'>".$row['description']."</p>
                                                    <button onclick='return checkDelete()' name='delete_friend' type='submit' value='".$row['id']."' class='btn card_btn'>Delete Friend</button>
                                                </div>
                                            </div>
                                            ";
                                }
                            }
                        }
                        echo "</form>";
                    ?>
            </div>
        </div>
        <div class="rightPane">
            <div class="schedulePane">
                <p class="text">Up Coming Schedule</p>
            </div>
            <div class="friendPane">
                <p class="text">Online Friends</p>
                <div class="friendsDiv"></div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
    function checkDelete(){
        var result = confirm("Are you sure?");
        if(result){
            return true;
        }
        return false;
    }

    $(function() {
        function updateOnlineFriends() {
          $.ajax({
            url: 'ajax/update_stats.php',
            dataType: 'html',
            success: function(data, status) {
              if (data.length > 0) {
                $('.friendsDiv').html(data);
              } else {
                $('.friendsDiv').html('No friends online.');
              }
            }
          });
        }
        
        // Update friends list every 3 seconds.
        setInterval(updateOnlineFriends, 5000);
    });
</script>
</html>