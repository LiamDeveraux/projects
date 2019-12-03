<?php
    session_start();
    if(!isset($_SESSION['logged_in'])){
        header('Location:../Iteration 1/Login/login.php');
        exit();
    }else{
        if($_SESSION['logged_in'] === "user"){
			header("Location:../../Main Pages/forum.php");
			exit();
		}
    }
    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';

    $user_count = $post_count = $reply_count = $active_user_count = 0;

    $user_count = mysqli_query($conn, "SELECT * FROM user")->num_rows;
    $post_count = mysqli_query($conn, "SELECT * FROM post")->num_rows;
    $reply_count = mysqli_query($conn, "SELECT * FROM reply")->num_rows;

    $active_user_count = mysqli_query($conn, "SELECT * FROM user WHERE status = 1")->num_rows;
    $inactive_user_count = $user_count - $active_user_count;
    $registeredYear = mysqli_query($conn, "SELECT DISTINCT YEAR(register_date) FROM user GROUP BY register_date ORDER BY register_date ASC");
    // SELECT * FROM user WHERE YEAR(register_date) IN("2018","2019")
    $postedMonth = mysqli_query($conn, "SELECT DISTINCT YEAR(date_posted) FROM post GROUP BY date_posted ORDER BY date_posted ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forumify</title>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../General files/main.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<style>
    h1{
        font-family: 'Poppins';
    }
</style>
<body>
    <div class="topnav">
        <a class="logo" href=""><img class="logoImage" src="../General files/logo.png"></a>
        <div class="topnav-content">
            <a class="links active" href="">Dashboard</a>
            <a class="links" href="../Iteration 2/Manage Forum/postList.php">Forum</a>
            <a class="links" href="../Iteration 2/Manage Database/userList.php">Users</a>
            <a class="links" href="../Iteration 1/Logout/logout.php">Logout</a>
        </div>
    </div>

    <div class="bottomPane" style="flex-direction:column;">
        <div style="overflow:auto">
            <h1 style="text-align: center;">Overview</h1>
            
            <div class="statistic_container">
                <div class="statistic_card_container">
                    <div class="statistic_card">
                        <?php echo "<h1>{$user_count}</h1>"; ?>
                        <h2>Registered User</h2>
                        <i class="far fa-user"></i>
                    </div>
                    <div class="statistic_card">
                        <?php echo "<h1>{$post_count}</h1>"; ?>
                        <h2>Posts</h2>
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="statistic_card">
                            <?php echo "<h1>{$reply_count}</h1>"; ?>
                        <h2>Replies</h2>
                        <i class="far fa-comment-dots"></i>
                    </div>
                </div>
            </div>

            <h1 style="text-align: center;">Statistic</h1>

            <div class="statistic_container">
                <div class="graph_card_container">
                    <div class="graph_card">
                        <div id="graph_1"></div>                  
                    </div>
                    <div class="graph_card">
                        <div id="graph_2"></div>                  
                    </div>
                    <div class="graph_card">
                        <div id="graph_3"></div>                  
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
    // Load google charts
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    // Draw the chart and set the chart values
    function drawChart() {
        //Graph 1 (Pie Chart)
        var data = google.visualization.arrayToDataTable([
        ['Active User', 'Offline User'],
        ['Active', <?php echo $active_user_count; ?>],
        ['Offline', <?php echo $inactive_user_count; ?>]
        ]);

        // Optional; add a title and set the width and height of the chart
        var options = {'title':'User status', 'width':550, 'height':400};

        // Display the chart inside the <div> element with id="piechart"
        var chart = new google.visualization.PieChart(document.getElementById('graph_1'));
        chart.draw(data, options);
        
        //Graph 2 (Bar Chart)
        data = google.visualization.arrayToDataTable([
            ['Year', 'Number of User', { role: 'style' }],
            <?php
                $rows = [];
                while($row = mysqli_fetch_assoc($registeredYear))
                {
                    $rows[] = $row['YEAR(register_date)'];
                }
                
                foreach($rows as $value){
                    $temp = mysqli_query($conn, "SELECT * FROM user WHERE YEAR(register_date) = '{$value}'");
                    $val = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
                    echo "['{$value}', {$temp->num_rows} , '{$val}'],";
                }
            ?>
            // ['2018', 10, '#b87333'],            // RGB value
            // ['2019', 20, 'silver'],            // English color name
            // ['March', 30, 'gold'],
            // ['April', 40, 'color: #e5e4e2' ], // CSS-style declaration
        ]);
        
        options = {'title':'User Registered', 'width':550, 'height':400, 'bar': {groupWidth: "50%"},
        'legend': { position: "none" }};

        chart = new google.visualization.BarChart(document.getElementById('graph_2'));
        chart.draw(data, options);
        
        //Graph 3 (Line Graph)
        data = google.visualization.arrayToDataTable([
            ['Year', 'Post'],
            <?php
                $rows = [];
                while($row = mysqli_fetch_assoc($postedMonth))
                {
                    $rows[] = $row['YEAR(date_posted)'];
                }

                foreach($rows as $value){
                    $temp = mysqli_query($conn, "SELECT * FROM post WHERE YEAR(date_posted) = '{$value}'");
                    echo "['{$value}', {$temp->num_rows}],";
                }
            ?>
            // ['January',  3],
            // ['Febuary',  10],
            // ['March',  20],
            // ['April',  40],
            // ['May',  10],
            // ['June',  25],
            // ['July',  2],
            // ['August',  60],
            // ['September',  50],
            // ['October',  101],
            // ['November',  40],
            // ['December',  70]
        ]);

        options = {
          title: 'Number of Posts',
          curveType: 'function',
          legend: { position: 'bottom' },
          'width':550, 'height':400
        };

        chart = new google.visualization.LineChart(document.getElementById('graph_3'));
        chart.draw(data, options);
    }
</script>
</html>