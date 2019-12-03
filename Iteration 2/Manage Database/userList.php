<?php
    session_start();
    if(!isset($_SESSION['logged_in'])){
        header('Location:../../Iteration 1/Login/login.php');
        exit();
    }else{
        if($_SESSION['logged_in'] === "user"){
			header("Location:../../Main Pages/forum.php");
			exit();
		}
    }
    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
?>
<!DOCTYPE html>
<html>
<!-- http://jsfiddle.net/h7gzwo01/ -->
<head>
    <title>Forumify</title>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous">
    </script>
    <link rel="stylesheet" type="text/css" href="../../General files/main.css">
</head>
<body>
    <div class="procMessage">
        <?php
            if(isset($_SESSION['proc_message'])){
                echo '<div style="background-color: #dff0d8; color:#3c763d; opacity:0.7;">'.$_SESSION["proc_message"].'</div>';
                unset($_SESSION['proc_message']);
            }
            else if(isset($_SESSION['proc_error'])){
                echo '<div style="background-color: #f2dede; color:#a94442; opacity:0.7;">'.$_SESSION["proc_error"].'</div>';
                unset($_SESSION['proc_error']);
            }

        ?>
    </div>
    <div class="topnav">
        <a class="logo" href=""><img class="logoImage" src="../../General files/logo.png"></a>
        <div class="topnav-content">
            <a class="links " href="../../Main Pages/dashboard.php">Dashboard</a>
            <a class="links" href="../Manage Forum/postList.php">Forum</a>
            <a class="links active" href="">Users</a>
            <a class="links" href="../../Iteration 1/Logout/logout.php">Logout</a>
        </div>
    </div>
    <div class="bottomPane" style="position:relative;">
        <div class="userListContainer" style="position:absolute;">
            <h1>User List :</h1>
            <div class="table_container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Last Active</th>
                        <th>Posts</th>
                        <th>Replies</th>
                        <th>Status</th>
                        <th colspan="3">Action</th>
                    </tr>
                    <?php
                        $result = mysqli_query($conn, "SELECT * FROM user");
                        while($row = mysqli_fetch_assoc($result)){
                            if($row['ban_status'] == 1){
                                $ban = "banned";
                                $string = '<a data-id="'.$row['id'].'" class="removeRestrictBtn" title="Remove Restriction"><i class="fas fa-lock-open"></i></a>';
                            }else{
                                $ban = "unbanned";
                                $string = '<a data-id="'.$row['id'].'" class="restrictBtn" title="Restrict Access"><i class="fas fa-lock"></i></a>';
                            }
                            echo '<tr><td>' . $row['id'].'</td>
                            <td>'.$row['username'].'</td>
                            <td>'.$row['email'].'</td>
                            <td>'.$row['password'].'</td>
                            <td>'.$row['last_active'].'</td>
                            <td>'.$row['post_count'].'</td>
                            <td>'.$row['reply_count'].'</td>
                            <td class="'.$ban.'"><div>'.$ban.'</div></td>
                            <td>'.$string.'</td>
                            <td><a href="profile.php?user_id='.$row['id'].'"class="viewBtn" title="View Profile"><i class="far fa-address-card"></i></a></td>
                            <td><a data-id="'.$row['id'].'" class="deleteBtn" title="Delete"><i class="fas fa-trash-alt"></i></a></td></tr>';
                        }

                    ?>
                </table>
            </div>
        </div>
    </div>
    
    <!-- The Modal -->
    <div id="myModal" class="modal">
        
        <!-- Modal content 1 -->
        <div id="delete_user_modal" class="modal-content">
            <div class="modal-header">
                <span class="close cross">&times;</span>
                <h2 style="margin:0;">Deletion Confirmation</h2>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
            </div>
            <div class="modal-footer">
                <form action="deleteUser.php" method="post">                                    
                    <input id="delete_user_form" type="hidden" name="user_id" value="id">
                    <button class="" type="submit">Delete</button>
                    <button class="close" type="reset">Cancel</button>
                </form>
            </div>
        </div>

        <!-- Modal content 2 -->
        <div id="view_profile_modal" class="modal-content">
            <!-- <div class="bottomPane">
                <div class="profileLeftPane">
                    <img class="profilePicture" src="sample.jpg">
                    <div><p>Username : Ah Chung Soohigh</p></div>
                </div>	
                <div class="profileRightPane">
                    <h1>Username : XXX</h1>
                    <h1>Password : *********</h1>
                    <h1>Email : XXX@XXX.com</h1>
                    <h1>Description : ASD</h1>
                    <h1>Number of posts posted : XX</h1>
                    <h1>Number of posts replied to : XX</h1>
                    <div class="highlight">
                        <a class="button" href="#">Button</a>
                        <a class="button" style="background-color:red" href="#">Button</a>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- Modal content 3 -->
        <div id="ban_user_modal" class="modal-content">
            <div class="modal-header">
                <span class="close cross">&times;</span>
                <h2 style="margin:0;">Restriction Confirmation</h2>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to ban this user?</p>
            </div>
            <div class="modal-footer">
                <form action="restrictUser.php" method="post">                                    
                    <input id="restrict_user_form" type="hidden" name="user_id" value="id">
                    <button class="" type="submit">Ban</button>
                    <button class="close" type="reset">Cancel</button>
                </form>
            </div>
        </div>

        <div id="unban_user_modal" class="modal-content">
            <div class="modal-header">
                <span class="close cross">&times;</span>
                <h2 style="margin:0;">Remove Restriction Confirmation</h2>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to lift the ban status of this user?</p>
            </div>
            <div class="modal-footer">
                <form action="removeRestrict.php" method="post">                                    
                    <input id="remove_restrict_user_form" type="hidden" name="user_id" value="id">
                    <button class="" type="submit">Unban</button>
                    <button class="close" type="reset">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    // Get the modal
    var modal = document.getElementById("myModal");
    var delete_user = document.getElementById("delete_user_modal");
    var view_profile = document.getElementById("view_profile_modal");
    var ban_user = document.getElementById("ban_user_modal");
    var unban_user = document.getElementById("unban_user_modal");

    // Get the button that opens the modal
    var btn_1 = document.querySelectorAll(".deleteBtn");
    var btn_2 = document.querySelectorAll(".restrictBtn");
    var btn_3 = document.querySelectorAll(".removeRestrictBtn");

    // Get the <span> element that closes the modal
    var close = document.querySelectorAll(".close");

    // When the user clicks the button, open the modal
    btn_1.forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            delete_user.style.display = "block";
            modal.style.display = "flex";
        });
    });

    btn_2.forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            ban_user.style.display = "block";
            modal.style.display = "flex";
        });
    });

    btn_3.forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            unban_user.style.display = "block";
            modal.style.display = "flex";
        });
    });

    close.forEach(function(c){
        c.addEventListener('click', function(e) {
            e.preventDefault();
            delete_user.style.display = "none";
            view_profile.style.display = "none";
            ban_user.style.display = "none";
            unban_user.style.display = "none";
            modal.style.display = "none";
        });
    });

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            delete_user.style.display = "none";
            view_profile.style.display = "none";
            ban_user.style.display = "none";
            unban_user.style.display = "none";
            modal.style.display = "none";
        }
    };

    //profile modal
    $(function(){
        // changed id to class
        $('.viewBtn').click(function (){
            $.get($(this).attr('href'), function(data) {
                view_profile.style.display = "block";
                modal.style.display="flex";
                $('#myModal').find('#view_profile_modal').html(data)
                var close = document.querySelectorAll(".close");
                close.forEach(function(c){
                    c.addEventListener('click', function(e) {
                        e.preventDefault();
                        document.getElementById("myModal").style.display = "none";
                        delete_user.style.display = "none";
                        view_profile.style.display = "none";
                        ban_user.style.display = "none";
                        modal.style.display = "none";
                    });
                });
            });
            return false;
        });
    }); 

    //Add value to input on action
    $('.deleteBtn').click(function(){
        var id = $(this).data("id");
        $('#delete_user_form').attr('value', id);
    });
    $('.restrictBtn').click(function(){
        var id = $(this).data("id"); 
        $('#restrict_user_form').attr('value', id);
    });
    $('.removeRestrictBtn').click(function(){
        var id = $(this).data("id"); 
        $('#remove_restrict_user_form').attr('value', id);
    });
    
</script>
</html>