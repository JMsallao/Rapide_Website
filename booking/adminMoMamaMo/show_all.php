<?php
    include('../../connection.php');
    include('../../sessioncheck.php');

    // Ensure the admin is logged in
    if (!isset($_SESSION['id'])) {
        die("Admin not logged in.");
    }

    $admin_id = $_SESSION['id'];

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
    
   
    // Fetch all emergency requests (no longer joining with branches table)
    $emergency_query = "
        SELECT e.emergency_ID, e.emergency_type, e.car_type, e.contact, e.location, e.created_at
        FROM emergencies e
        ORDER BY e.created_at DESC";
    $emergency_result = $conn->query($emergency_query);

    // Step 1: Get the admin's branch_id from the branches table using the logged-in admin's ID
    $query = "SELECT id FROM branches WHERE admin_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id); // Using the admin_id to get the branch_id
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $branch = $result->fetch_assoc();
        $admin_branch_id = $branch['id'];
    } else {
        die("Admin branch not found.");
    }
    $stmt->close();

    // Step 2: Fetch bookings associated with the admin's branch
    $booking_query = "
        SELECT bo.booking_id, bo.booking_date, bo.status, bo.total_price, bo.service_type,
               u.username AS user_name, b.branch_name
        FROM bookings bo
        LEFT JOIN users u ON bo.user_id = u.id
        LEFT JOIN branches b ON bo.branch_id = b.id
        WHERE bo.branch_id = ?  -- Filtering bookings by the admin's branch
        ORDER BY bo.booking_date DESC";
    $stmt = $conn->prepare($booking_query);
    $stmt->bind_param("i", $admin_branch_id);  // Using the branch_id to filter bookings
    $stmt->execute();
    $booking_result = $stmt->get_result();
    $stmt->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>RAPIDE ADMIN </title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../admin1/dist/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../../admin1/dist/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../admin1/dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../admin1/dist/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../admin1/dist/assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../../admin1/dist/assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../../admin1/dist/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../admin1/dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../../admin1/dist/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="../../admin1/dist/assets/js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../../admin1/dist/assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../images\LogoRapide.png" type="image/x-icon">


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

        /* Enhance the table design */
        .table {
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #f8f9fa;
            color: #343a40;
            font-weight: bold;
            border: none;
        }

        .table td {
            border: none;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        /* Improve the button styles */
        .btn-primary {
            background-color: #4caf50;
            border-color: #4caf50;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 5px;
        }

        /* Make the table responsive */
        .table-responsive {
            overflow-x: auto;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        /* Custom tab styling */
        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 5px 5px 0 0;
            color:rgb(69, 69, 69);
            font-weight: bold;
            padding: 10px 20px;
            transition: all 0.3s ease-in-out;
        }

        .nav-tabs .nav-link.active {
            color: black;
            background-color:rgb(255, 221, 0); /* Primary color for active tab */
            border: none;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .nav-tabs .nav-link:hover {
            color:rgb(9, 9, 9);
            background-color: #f1f1f1;
            text-decoration: none;
        }

        /* Adjust the tab content spacing */
        .tab-content {
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 20px;
            background-color: #ffffff;
            margin-top: -1px; /* Removes gap between tab and content */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
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
                        <a class="nav-link" href="../../admin1/dist/Admin-Homepage.php">
                            <i class="mdi mdi-view-dashboard-outline menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Menu</li>
                    <li class="nav-item">
                        <a class="nav-link" href="show_all.php">
                            <i class="mdi mdi-calendar-check menu-icon"></i>
                            <span class="menu-title">Booking</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../admin1/dist/pages.php">
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
                </ul>
            </nav>
            <!-- partial -->




            <div class="container mt-5">
    <h1 class="text-center mb-4">Notifications</h1>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="notificationTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergency"
                type="button" role="tab" aria-controls="emergency" aria-selected="true">Emergency Requests</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="booking-tab" data-bs-toggle="tab" data-bs-target="#booking" type="button"
                role="tab" aria-controls="booking" aria-selected="false">Bookings</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-4" id="notificationTabsContent">
        <!-- Emergency Requests -->
        <!-- Emergency Requests -->
        <div class="tab-pane fade show active" id="emergency" role="tabpanel" aria-labelledby="emergency-tab">
            <h4>Emergency Requests</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Emergency ID</th>
                            <th>Type</th>
                            <th>Car Type</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Actions</th> <!-- Actions column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $emergency_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['emergency_ID']; ?></td>
                            <td><?php echo htmlspecialchars($row['emergency_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['car_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo date('F j, Y, g:i A', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="emergency_details.php?emergency_ID=<?php echo $row['emergency_ID']; ?>"
                                    class="btn btn-primary btn-sm">View Details</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Bookings -->
        <div class="tab-pane fade" id="booking" role="tabpanel" aria-labelledby="booking-tab">
            <h4>Bookings</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Total Price</th>
                            <th>Service</th>
                            <th>Branch</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $booking_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['booking_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                            <td><?php echo ucfirst($row['status']); ?></td>
                            <td>₱<?php echo number_format($row['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['service_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['branch_name'] ?: 'N/A'); ?></td>
                            <td><?php echo date('F j, Y, g:i A', strtotime($row['booking_date'])); ?></td>
                            <td>
                                <a href="bukingdets.php?booking_id=<?php echo $row['booking_id']; ?>"
                                    class="btn btn-primary btn-sm">View Details</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
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