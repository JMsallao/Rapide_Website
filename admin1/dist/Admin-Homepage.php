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
    

    // Fetch recent "pending" booking notifications
    $query = "SELECT b.booking_id, b.created_at, u.username 
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            WHERE b.status = 'pending'
            ORDER BY b.created_at DESC
            LIMIT 5"; // Limiting to 5 recent pending bookings
    $result = mysqli_query($conn, $query);
    $notif_count = mysqli_num_rows($result); // Count the number of pending bookings

    // Fetch ratings data
    $ratings_query = "SELECT 
                    COUNT(CASE WHEN stars = 5 THEN 1 END) AS very_satisfactory,
                    COUNT(CASE WHEN stars = 4 THEN 1 END) AS satisfactory,
                    COUNT(CASE WHEN stars = 3 THEN 1 END) AS neutral,
                    COUNT(CASE WHEN stars = 2 THEN 1 END) AS unsatisfactory,
                    COUNT(CASE WHEN stars = 1 THEN 1 END) AS poor
                  FROM ratings";
    $ratings_result = $conn->query($ratings_query);
    $ratings = $ratings_result->fetch_assoc();

    // Ratings breakdown
    $very_satisfactory = $ratings['very_satisfactory'] ?? 0;
    $satisfactory = $ratings['satisfactory'] ?? 0;
    $neutral = $ratings['neutral'] ?? 0;
    $unsatisfactory = $ratings['unsatisfactory'] ?? 0;
    $poor = $ratings['poor'] ?? 0;

    // Total ratings
    $total_ratings = $very_satisfactory + $satisfactory + $neutral + $unsatisfactory + $poor;

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
    .badge-counter {
        position: absolute;
        top: 8px;
        right: 8px;
        transform: translate(50%, -50%);
        font-size: 12px;
        background-color: #ff3d3d;
        /* Vibrant red */
        color: white;
        padding: 4px 8px;
        border-radius: 50%;
        /* Perfect circle */
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        font-weight: bold;
    }

    /* Adjust bell icon positioning */
    .fa-bell {
        position: relative;
    }

    /* Adjust dropdown spacing */
    .dropdown-menu {
        margin-top: 10px;
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
                        <a class="nav-link" href="../../admin1\dist\Admin-Homepage.php">
                            <i class="mdi mdi-view-dashboard-outline menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Menu</li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../booking/adminMoMamaMo/show_all.php">
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
                        <a class="nav-link" href="../../admin1/dist/service.php">
                            <i class="mdi mdi-tools menu-icon"></i>
                            <span class="menu-title">Services</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../admin1/dist/Users.php">
                            <i class="mdi mdi-account-multiple menu-icon"></i>
                            <span class="menu-title">Users</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../admin1/dist/message_inbox.php">
                            <i class="mdi mdi-message-text-outline menu-icon"></i>
                            <span class="menu-title">Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../booking/adminMoMamaMo/bukingdets.php">
                            <i class="mdi mdi-file-chart menu-icon"></i>
                            <span class="menu-title">Reports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../login/logout.php">
                            <i class="mdi mdi-logout user"></i> <!-- Changed the icon to mdi-logout -->
                            <span class="menu-title">Sign Out</span>
                        </a>
                    </li>
                </ul>
            </nav>




            <!-- partial -->




            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="home-tab">

                                <div class="tab-content tab-content-basic">
                                    <div class="tab-pane fade show active" id="overview" role="tabpanel"
                                        aria-labelledby="overview">
                                        <div class="row">
                                        <div class="col-sm-12">
                                        <div class="statistics-details d-flex align-items-center justify-content-between">
                                            <!-- Very Satisfactory -->
                                            <div>
                                                <p class="statistics-title">Very Satisfactory</p>
                                                <h3 class="rate-percentage"><?= $very_satisfactory; ?> ratings</h3>
                                                <p class="text-success d-flex">
                                                    <i class="mdi mdi-menu-up"></i>
                                                    <span><?= $total_ratings > 0 ? round(($very_satisfactory / $total_ratings) * 100, 2) : 0; ?>%</span>
                                                </p>
                                            </div>

                                            <!-- Satisfactory -->
                                            <div>
                                                <p class="statistics-title">Satisfactory</p>
                                                <h3 class="rate-percentage"><?= $satisfactory; ?> ratings</h3>
                                                <p class="text-success d-flex">
                                                    <i class="mdi mdi-menu-up"></i>
                                                    <span><?= $total_ratings > 0 ? round(($satisfactory / $total_ratings) * 100, 2) : 0; ?>%</span>
                                                </p>
                                            </div>

                                            <!-- Neutral -->
                                            <div>
                                                <p class="statistics-title">Neutral</p>
                                                <h3 class="rate-percentage"><?= $neutral; ?> ratings</h3>
                                                <p class="text-warning d-flex">
                                                    <i class="mdi mdi-menu-up"></i>
                                                    <span><?= $total_ratings > 0 ? round(($neutral / $total_ratings) * 100, 2) : 0; ?>%</span>
                                                </p>
                                            </div>

                                            <!-- Unsatisfactory -->
                                            <div>
                                                <p class="statistics-title">Unsatisfactory</p>
                                                <h3 class="rate-percentage"><?= $unsatisfactory; ?> ratings</h3>
                                                <p class="text-danger d-flex">
                                                    <i class="mdi mdi-menu-down"></i>
                                                    <span><?= $total_ratings > 0 ? round(($unsatisfactory / $total_ratings) * 100, 2) : 0; ?>%</span>
                                                </p>
                                            </div>

                                            <!-- Poor -->
                                            <div>
                                                <p class="statistics-title">Poor</p>
                                                <h3 class="rate-percentage"><?= $poor; ?> ratings</h3>
                                                <p class="text-danger d-flex">
                                                    <i class="mdi mdi-menu-down"></i>
                                                    <span><?= $total_ratings > 0 ? round(($poor / $total_ratings) * 100, 2) : 0; ?>%</span>
                                                </p>
                                            </div>
                                        </div>


                                        </div>
                                        <div class="row">
                                            <div class="col-lg-8 d-flex flex-column">
                                                <div class="row flex-grow">
                                                    <div class="col-12 col-lg-4 col-lg-12 grid-margin stretch-card">
                                                        <div class="card card-rounded">
                                                            <div class="card-body">
                                                                <div
                                                                    class="d-sm-flex justify-content-between align-items-start">
                                                                    <div>
                                                                        <h4 class="card-title card-title-dash">
                                                                            Performance Line Chart</h4>
                                                                        <h5 class="card-subtitle card-subtitle-dash">
                                                                            Lorem Ipsum is simply dummy text of the
                                                                            printing</h5>
                                                                    </div>
                                                                    <div id="performanceLine-legend"></div>
                                                                </div>
                                                                <div class="chartjs-wrapper mt-4">
                                                                    <canvas id="performanceLine" width="400" height="200"></canvas>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 d-flex flex-column">
                                                <div class="row flex-grow">
                                                    <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                                                        <div class="card bg-primary card-rounded">
                                                            <div class="card-body pb-0">
                                                                <h4 class="card-title card-title-dash text-white mb-4">
                                                                    Status Summary</h4>
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <p class="status-summary-ight-white mb-1">Closed
                                                                            Value</p>
                                                                        <h2 class="text-info">357</h2>
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        <div class="status-summary-chart-wrapper pb-4">
                                                                            <canvas id="status-summary"></canvas>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                                                        <div class="card card-rounded">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center mb-2 mb-sm-0">
                                                                            <div class="circle-progress-width">
                                                                                <div id="totalVisitors"
                                                                                    class="progressbar-js-circle pr-2">
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <p class="text-small mb-2">Total
                                                                                    Visitors</p>
                                                                                <h4 class="mb-0 fw-bold">26.80%</h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center">
                                                                            <div class="circle-progress-width">
                                                                                <div id="visitperday"
                                                                                    class="progressbar-js-circle pr-2">
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <p class="text-small mb-2">Visits per
                                                                                    day</p>
                                                                                <h4 class="mb-0 fw-bold">9065</h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <script>
    const ratingsData = {
      very_satisfactory: <?= $very_satisfactory; ?>,
      satisfactory: <?= $satisfactory; ?>,
      neutral: <?= $neutral; ?>,
      unsatisfactory: <?= $unsatisfactory; ?>,
      poor: <?= $poor; ?>
    };
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