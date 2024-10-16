<?php 
session_start();
require_once "../connection.php";

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1){
    echo "<script>alert('Access denied!'); window.location.href = '../home/login-form.php';</script>";
    exit();
}



$sql = "SELECT * FROM booking WHERE status = 'approved' AND date > CURDATE() ORDER BY date ASC, STR_TO_DATE(time, '%h:%i %p') ASC";
$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}

$sql_account = "SELECT COUNT(*) AS id FROM account WHERE is_admin != 1";
$result_account = $conn->query($sql_account);
$count = $result_account->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../img/logo.png" />
    <link rel="stylesheet" href="../css/admin/db-notif.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/admin/db-home.css">
    <link rel="stylesheet" href="../css/admin/a-chat.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <?php
        $sql = "SELECT city, COUNT(*) as service_count FROM booking WHERE status = 'done' GROUP BY city ORDER BY city ASC";
        $result = $conn->query($sql);

        $chartData = [['Places', 'Service']];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $chartData[] = [$row["city"], (int)$row["service_count"]];
            }
        } 
        $jsonData = json_encode($chartData);
        ?>

    <script type="text/javascript">
    google.charts.load('current', {
        'packages': ['bar']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo $jsonData; ?>);

        var options = {
            backgroundColor: '#f8f8f8',
            chart: {
                title: 'Service Accomplish in Cavite',
                subtitle: 'Cities',
            },
            bars: 'vertical'
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
    window.addEventListener('resize', drawChart);
    </script>



<body>
    <div class="wrapper">
        <aside id="sidebar" class="js-sidebar">
            <!-- Content For Sidebar -->
            <div class="h-100">
                <div class="sidebar-logo"><img src="../img/logo-header.png" width="70px" height="auto" alt="Icon">
                    <a href="dashboard.html" class="logo">A.C Tech</a>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="db-home.php" class="sidebar-link">
                            <i class="fa-solid fa-chart-simple pe-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-calendar pe-2"></i>
                            Booking Appointments
                        </a>
                        <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="db-calendar.php" class="sidebar-link">Calendar</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link collapsed" data-bs-target="#bookings"
                                    data-bs-toggle="collapse" aria-expanded="false"><i
                                        class="fa-solid fa-bookmark pe-2"></i>
                                    List of Bookings
                                </a>
                                <ul id="bookings" class="sidebar-dropdown list-unstyled collapse"
                                    data-bs-parent="#pages">
                                    <li class="sidebar-item">
                                        <a href="db-appointment-list.php" class="sidebar-link">Pendings</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="db-ap-approved.php" class="sidebar-link">Approved</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="db-ap-rejected.php" class="sidebar-link">Rejected</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="db-ap-cancelled.php" class="sidebar-link">Cancelled</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="db-users-manage.php" class="sidebar-link">
                            <i class="fa-solid fa-user pe-2"></i>
                            Users
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="db-reports.php" class="sidebar-link">
                            <i class="fa-solid fa-chart-pie pe-2"></i>
                            Reports
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
                <button class="btn" id="sidebar-toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <li>
                            <i class="fa-regular fa-bell notification-icon" onclick="togglePopup()"></i>
                            <span class="notification-dot"></span>
                            <div class="notification-popup" id="notificationPopup">
                                <div class="notif-header">Notifications</div>
                                <p>No notifications available.</p>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <i id="avatar" class="fa-regular fa-user"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="db-settings.php" class="dropdown-item">Setting</a>
                                <a href="a-logout.php" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-6 d-flex">
                            <div class="card flex-fill border-0 illustration">
                                <div class="card-body p-0 d-flex flex-fill">
                                    <div class="row g-0 w-100">
                                        <div class="col-8">
                                            <div class="p-3 m-2">
                                                <h4>Welcome Back, Admin!</h4>
                                                <p class="mb-0"> A.C Tech Air-conditoning Service</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3 d-flex">
                            <div class="card flex-fill border-0 illustration">
                                <div class="card-body d-flex flex-fill">
                                    <div class="row g-0 w-100">
                                        <div class="col-10">
                                            <div class="p-3 m-1">
                                                <div class="row-8">
                                                    <span class="material-icons-outlined">
                                                        group
                                                    </span>
                                                    <span class="count">
                                                        <?php echo $count['account_id'] ?></span>
                                                    <div class="client-info">
                                                        <h5 class="mb-2">Clients</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12 col-md-3 d-flex">
                            <div class="card flex-fill border-0 illustration">
                                <div class="card-body d-flex flex-fill">
                                    <div class="row g-0 w-100">
                                        <div class="col-10">
                                            <div class="p-3 m-1">
                                                <div class="row-8">
                                                    <span class="material-icons-outlined">
                                                        swap_vert
                                                    </span>
                                                    <?php
                                                            // Assuming $conn is your database connection
                                                            $sql = "SELECT AVG(rate) AS averageRating FROM rating";
                                                            $result = $conn->query($sql);
                                                            $row = $result->fetch_assoc();
                                                            $averageRating = $row['averageRating'];
                                                            $ratingPercentage = $averageRating * 20;
                                                            ?>
                                                    <span
                                                        class="count"><?php echo number_format($ratingPercentage, 2); ?>%</span>
                                                    <div class="client-info">
                                                        <h5 class="mb-2">Ratings</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Table Element -->
                    <div class="card p-2 border-0">
                        <div class="card-header">
                            <h5 class="card-title">

                            </h5>
                            <h6 class="card-subtitle text-muted">
                                Upcoming <b>Bookings</b>
                            </h6>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Book ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Service</th>
                                        <th scope="col">City</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Time</th>
                                    </tr>
                                </thead>
                                <?php
                            $sql = "SELECT * FROM booking WHERE status = 'approved' AND date > CURDATE() ORDER BY date ASC, STR_TO_DATE(time, '%h:%i %p') ASC";
                            $result = $conn->query($sql);
                            
                            if (!$result) {
                                die("Error executing query: " . $conn->error);
                            }
                            ?>

                                <tbody>

                                    <?php while($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['booking_id'];?></td>
                                        <td><?php echo $row['name'];?></td>
                                        <td><?php echo $row['service'];?></td>
                                        <td><?php echo $row['city'];?></td>
                                        <td><?php echo $row['date'];?></td>
                                        <td><?php echo $row['time'];?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                        $conn->close();
                    ?>

                    <div class="col-12 col-md-6 d-flex">
                        <div class="card my-card border-0">
                            <div class="card-body p-3 d-flex flex-fill">
                                <div id="barchart_material" style="width: 900px; height: 500px;"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
            <a href="#" class="theme-toggle">
                <i class="fa-regular fa-moon"></i>
                <i class="fa-regular fa-sun"></i>
            </a>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a href="#" class="text-muted">
                                    <strong>A.C TECH</strong>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/switch-toggle.js"></script>
    <script src="db-notif.js"></script>
</body>

</html>