<?php
    include('../../connection.php');
    include('../../sessioncheck.php');

    if (isset($_SESSION['id'])) {
        $admin_id = $_SESSION['id']; // Assuming admin's ID is stored in the session
    } else {
        die("Admin not logged in.");
    }

    // Check if the logged-in user is an admin
    $query = "SELECT is_admin, fname, lname FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['is_admin'] != 1) {
            die("Access denied. You are not authorized to view this page.");
        }

        // Assign fname and lname for displaying on the dashboard
        $fname = $user['fname'];
        $lname = $user['lname'];
    } else {
        die("User not found.");
    }
    $stmt->close();
    
    // Get bookings for each week of January
    $currentYear = date('Y'); // Get the current year
    $sql_bookings_per_week = "
        SELECT WEEK(booking_date, 1) AS week, COUNT(*) AS bookings
        FROM bookings
        WHERE YEAR(booking_date) = ? AND MONTH(booking_date) = 1
        GROUP BY WEEK(booking_date, 1)
        ORDER BY week";
    $stmt = $conn->prepare($sql_bookings_per_week);
    $stmt->bind_param("i", $currentYear);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare data for graph
    $weeks = [];
    $bookings_per_week = [];
    while ($row = $result->fetch_assoc()) {
        $weeks[] = "Week " . $row['week'];  // Format week number
        $bookings_per_week[] = $row['bookings'];
    }


    // Get total regular users (where is_admin = 0)
    $sql = "SELECT COUNT(*) AS total_users FROM users WHERE is_admin = 0";
    $userResult = $conn->query($sql);
    $totalUsers = 0;
    if ($userResult && $userResult->num_rows > 0) {
        $row = $userResult->fetch_assoc();
        $totalUsers = $row['total_users'];
    }

    // Get total branches (based on the map table in your code)
    $sql_branches = "SELECT COUNT(*) AS total_branches FROM branches";
    $result_branches = $conn->query($sql_branches);
    $branch_count = 0;
    if ($result_branches->num_rows > 0) {
        $row = $result_branches->fetch_assoc();
        $branch_count = $row['total_branches'];
    }

    // Get the total number of mechanics
    $sql_mechanics = "SELECT COUNT(*) AS total_mechanics FROM mechanics";
    $result_mechanics = $conn->query($sql_mechanics);
    $totalMechanics = 0;
    if ($result_mechanics && $result_mechanics->num_rows > 0) {
        $row = $result_mechanics->fetch_assoc();
        $totalMechanics = $row['total_mechanics'];
    }

    // Count total services (individual services in each table)
    $total_services = 0;
    $tables = ['brakes_table', 'brake_service', 'package_list', 'service_list', 'ac_service'];
    foreach ($tables as $table) {
        $sql = "SELECT COUNT(*) AS count FROM $table";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total_services += $row['count'];
        }
    }

    // Query to get the count of "Very Satisfactory" ratings (5 stars)
    $sql_very_satisfactory = "SELECT COUNT(*) AS very_satisfactory FROM ratings WHERE stars = 5";
    $result_very_satisfactory = $conn->query($sql_very_satisfactory);
    $very_satisfactory = 0;
    if ($result_very_satisfactory && $result_very_satisfactory->num_rows > 0) {
        $row = $result_very_satisfactory->fetch_assoc();
        $very_satisfactory = $row['very_satisfactory'];
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>RAPIDE ADMIN </title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="assets/js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../../images\rapide_logo.png" type="image/x-icon">

    <style>
    

    /* Adjust dropdown spacing */
    .dropdown-menu {
        margin-top: 10px;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .card .card-body {
        padding: 20px;
    }

    .card .icon-lg {
        font-size: 36px;
        margin-bottom: 10px;
    }

    .bg-light-primary {
        background-color: #e3f2fd;
    }

    .bg-light-warning {
        background-color: #fff8e1;
    }

    .bg-light-success {
        background-color: #e8f5e9;
    }

    .bg-light-info {
        background-color: #e0f7fa;
    }

    .text-center {
        text-align: center;
    }

    .text-primary {
        color: #2196f3;
    }

    .text-warning {
        color: #ff9800;
    }

    .text-success {
        color: #4caf50;
    }

    .text-info {
        color: #00bcd4;
    }
    </style>
</head>

<body class="with-welcome-text">
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <div class="me-3">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                        data-bs-toggle="minimize">
                        <span class="icon-menu"></span>
                    </button>
                </div>
                <div>
                    <a class="navbar-brand brand-logo" href="../../admin1\dist\Admin-Homepage.php">
                        <h2>Rapide</h2>
                    </a>
                    <a class="navbar-brand brand-logo-mini" href="../../admin1\dist\Admin-Homepage.php">
                        <h3>R</h3>
                    </a>
                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-top">
                <ul class="navbar-nav">
                    <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                        <h1 class="welcome-text"><?php echo $fname; ?></h1> <!-- First name in H1 -->
                        <h3 class="welcome-sub-text"><?php echo $lname; ?></h3> <!-- Last name in H3 -->
                    </li>
                </ul>

            </div>
        </nav>
        <!-- Side bar -->

        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="Admin-Homepage.php">
                            <i class="mdi mdi-view-dashboard-outline menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Menu</li>
                    <li class="nav-item">
                        <a class="nav-link" href="Calendar.php">
                            <i class="mdi mdi-calendar-check menu-icon"></i>
                            <span class="menu-title">Calendar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../booking/history.php">
                            <i class="mdi mdi-calendar-check menu-icon"></i>
                            <span class="menu-title">Booking</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages.php">
                            <i class="mdi mdi-file-multiple menu-icon"></i>
                            <span class="menu-title">Pages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="service.php">
                            <i class="mdi mdi-tools menu-icon"></i>
                            <span class="menu-title">Services</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="Users.php">
                            <i class="mdi mdi-account-multiple menu-icon"></i>
                            <span class="menu-title">Users</span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="message_inbox.php">
                            <i class="mdi mdi-message-text-outline menu-icon"></i>
                            <span class="menu-title">Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Reports.php">
                            <i class="mdi mdi-file-chart menu-icon"></i>
                            <span class="menu-title">Reports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../login/login.php">
                            <i class="mdi mdi-logout user"></i> <!-- Changed the icon to mdi-logout -->
                            <span class="menu-title">Sign Out</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <!-- Row 1: Quick Stats -->
                    <div class="row">
                        <!-- Total Users -->
                        <div class="col-md-3 col-sm-6 stretch-card grid-margin">
                            <div class="card bg-light-primary text-center">
                                <div class="card-body">
                                    <a href="../table/users.php">
                                    <i class="mdi mdi-account-multiple icon-lg text-primary"></i>
                                    <h3 class="rate-percentage"><?php echo $totalUsers; ?></h3>
                                    <p class="statistics-title">Total Users</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Total Branches -->
                        <div class="col-md-3 col-sm-6 stretch-card grid-margin">
                            <div class="card bg-light-warning text-center">
                                <div class="card-body">
                                    <a href="../table/branches.php">
                                    <i class="mdi mdi-storefront icon-lg text-warning"></i>
                                    <h3 class="rate-percentage"><?php echo $branch_count; ?></h3>
                                    <p class="statistics-title">Total Branches</p>
                                        </a>
                                </div>
                            </div>
                        </div>

                        <!-- Total Categories -->
                        <div class="col-md-3 col-sm-6 stretch-card grid-margin">
                            <div class="card bg-light-success text-center">
                                <div class="card-body">
                                    <a href="../table/mechanics.php">
                                        <i class="mdi mdi-cogs icon-lg text-info"></i>
                                        <h3 class="rate-percentage"><?php echo $totalMechanics; ?></h3>
                                        <p class="statistics-title">Total Mechanics</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Total Services -->
                        <div class="col-md-3 col-sm-6 stretch-card grid-margin">
                            <div class="card bg-light-info text-center">
                                <div class="card-body">
                                <a href="service.php">
                                    <i class="mdi mdi-tag-multiple icon-lg text-success"></i>
                                    <h3 class="rate-percentage"><?php echo $total_services; ?></h3>
                                    <p class="statistics-title">Total Services</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Graphs and Metrics -->
                    <div class="row">
                        <!-- Total Revenue -->
                        <div class="col-md-6 stretch-card grid-margin">
                            <div class="card">
                                <div class="card-body">
                                <a href="../booking/history.php">
                                    <h4 class="card-title">Bookings</h4>
                                    <canvas id="weeklyBookingsChart" height="150"></canvas>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Customer Satisfaction -->
                        <div class="col-md-6 stretch-card grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Customer Satisfaction</h4>
                                    <p>Very Satisfactory Ratings: <strong><?php echo $very_satisfactory; ?></strong></p>
                                    <a href="../table/ratings.php" class="btn btn-primary btn-sm">View Ratings Breakdown</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const ctx = document.getElementById('weeklyBookingsChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($weeks); ?>,  // Week labels (Week 1, Week 2, etc.)
                            datasets: [{
                                label: 'Bookings',
                                data: <?php echo json_encode($bookings_per_week); ?>, // Number of bookings per week
                                backgroundColor: '#4caf50',
                                borderColor: '#4caf50',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return tooltipItem.label + ': ' + tooltipItem.raw + ' bookings';
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>

            <!-- plugins:js -->
            <script src="assets/vendors/js/vendor.bundle.base.js"></script>
            <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
            <!-- endinject -->
            <!-- Plugin js for this page -->
            <script src="assets/vendors/chart.js/chart.umd.js"></script>
            <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
            <!-- End plugin js for this page -->
            <!-- inject:js -->
            <script src="assets/js/off-canvas.js"></script>
            <script src="assets/js/template.js"></script>
            <script src="assets/js/settings.js"></script>
            <script src="assets/js/hoverable-collapse.js"></script>
            <script src="assets/js/todolist.js"></script>
            <!-- endinject -->
            <!-- Custom js for this page-->
            <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
            <script src="assets/js/dashboard.js"></script>
            <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
            <!-- End custom js for this page-->
</body>

</html>