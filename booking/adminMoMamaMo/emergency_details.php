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
    
    // Fetch recent "pending" booking notifications
    $query = "SELECT b.booking_id, b.created_at, u.username 
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            WHERE b.status = 'pending'
            ORDER BY b.created_at DESC
            LIMIT 5"; // Limiting to 5 recent pending bookings
    $result = mysqli_query($conn, $query);
    $notif_count = mysqli_num_rows($result); // Count the number of pending bookings

    // Fetch all emergency requests (no branch information now)
    $emergency_query = "
        SELECT e.emergency_ID, e.emergency_type, e.car_type, e.contact, e.location, e.created_at
        FROM emergencies e
        ORDER BY e.created_at DESC";
    $emergency_result = $conn->query($emergency_query);

    // Fetch all bookings
    $booking_query = "
        SELECT bo.booking_id, bo.booking_date, bo.status, bo.total_price, bo.service_type,
            u.username AS user_name
        FROM bookings bo
        LEFT JOIN users u ON bo.user_id = u.id
        ORDER BY bo.created_at DESC";
    $booking_result = $conn->query($booking_query);
?>

<?php
    // Include connection and session-check files
    include('../../connection.php');
    include('../../header.php');

    // Ensure the admin is logged in
    if (!isset($_SESSION['id'])) {
        die("Admin not logged in.");
    }

    $admin_id = $_SESSION['id'];

    // Check if the logged-in user is an admin
    $query = "SELECT is_admin FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user['is_admin'] != 1) {
        die("Access denied. You are not authorized to view this page.");
    }
    $stmt->close();

    // Handle delete request
    if (isset($_POST['delete'])) {
        $emergency_id = $_POST['emergency_ID'];

        // Delete emergency record
        $delete_query = "DELETE FROM emergencies WHERE emergency_ID = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $emergency_id);

        if ($delete_stmt->execute()) {
            // Redirect back to emergency list after successful deletion
            header("Location: show_all.php?delete_success=true");
            exit;
        } else {
            echo "<script>alert('Failed to delete the emergency. Please try again.');</script>";
        }
    }

    // Validate and get emergency ID from the URL
    if (!isset($_GET['emergency_ID']) || !is_numeric($_GET['emergency_ID'])) {
        die("Invalid Emergency ID.");
    }

    $emergency_id = $_GET['emergency_ID'];

    // Fetch emergency details (removed branch_id and branch_name)
    $emergency_query = "
        SELECT e.emergency_ID, e.emergency_type, e.car_type, e.contact, e.location, e.userLat, e.userLng, e.created_at, e.withinRadius,
            u.username AS requested_by
        FROM emergencies e
        LEFT JOIN users u ON e.user_id = u.id
        WHERE e.emergency_ID = ?";
    $stmt = $conn->prepare($emergency_query);
    $stmt->bind_param("i", $emergency_id);
    $stmt->execute();
    $emergency_result = $stmt->get_result();

    // Check if the emergency exists
    if ($emergency_result->num_rows === 0) {
        die("Emergency not found.");
    }

    $emergency = $emergency_result->fetch_assoc();
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
    

        body {
                background-color: #f8f9fc; /* Light gray for professional background */
                font-family: 'Arial', sans-serif;
            }

            .container {
                max-width: 1200px;
                margin-top: 50px;
            }

            .card {
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
                background: #ffffff;
            }

            .card-header {
                background-color: #3a3f51; /* Professional dark gray */
                color: #ffffff;
                font-size: 20px;
                font-weight: bold;
                text-align: center;
                padding: 15px;
                border-bottom: 1px solid #ddd;
                border-top-left-radius: 8px;
                border-top-right-radius: 8px;
            }

            .card-body {
                padding: 20px;
            }

            .table {
                margin-bottom: 0;
                border: 1px solid #ddd;
                border-radius: 5px;
            }

            .table th {
                background-color: #f4f6f8;
                color: #495057;
                font-weight: bold;
                text-align: left;
                padding: 10px 15px;
            }

            .table td {
                padding: 10px 15px;
                color: #212529;
            }

            .action-buttons {
                margin-top: 20px;
                text-align: center;
    
            }

            .btn-secondary {
                background-color: #6c757d;
                border: none;
                color: white;
                padding: 10px 20px;
                font-size: 16px;
                border-radius: 4px;
                transition: 0.3s;
                
            }

            .btn-secondary:hover {
                background-color: #5a6268;
            }

            .btn-danger {
                background-color: #dc3545;
                border: none;
                color: white;
                padding: 10px 20px;
                font-size: 16px;
                border-radius: 4px;
                transition: 0.3s;
            }

            .btn-danger:hover {
                background-color: #bd2130;
            }

            .footer-note {
                font-size: 14px;
                text-align: center;
                color: #6c757d;
                margin-top: 20px;
            }

            @media (max-width: 768px) {
                .btn {
                    width: 100%;
                    margin-bottom: 10px;
                }

                .table th, .table td {
                    font-size: 14px;
                    padding: 8px 10px;
                }
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
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-none d-lg-block">
                        <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
                            <span class="input-group-addon input-group-prepend border-right">
                                <span class="icon-calendar input-group-text calendar-icon"></span>
                            </span>
                            <input type="text" class="form-control">
                        </div>
                    </li>
                    <!-- <li class="nav-item">
                        <form class="search-form" action="#">
                            <i class="icon-search"></i>
                            <input type="search" class="form-control" placeholder="Search Here" title="Search here">
                        </form>
                    </li> -->


                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-bell"></i>
                            <!-- Counter - Alerts -->
                            <span
                                class="badge badge-danger badge-counter"><?= $notif_count > 0 ? $notif_count : ''; ?></span>
                        </a>
                        <!-- Dropdown - Alerts -->
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">Alerts Center</h6>

                            <?php if ($notif_count > 0): 
            while ($row = mysqli_fetch_assoc($result)): ?>
                            <a class="dropdown-item d-flex align-items-center"
                                href="bukingdets.php?booking_id=<?= $row['booking_id']; ?>">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-calendar-check text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">
                                        <?= date('F j, Y', strtotime($row['created_at'])); ?>
                                    </div>
                                    <span class="font-weight-bold"><?= htmlspecialchars($row['username']); ?>
                                        has a pending booking request.</span>
                                </div>
                            </a>
                            <?php endwhile;
        else: ?>
                            <a class="dropdown-item text-center small text-gray-500" href="#">No new alerts</a>
                            <?php endif; ?>

                            <a class="dropdown-item text-center small text-gray-500" href="all_alerts.php">Show All
                                Alerts</a>
                        </div>
                    </li>


                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="icon-mail icon-lg"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0"
                            aria-labelledby="countDropdown">
                            <h6 class="dropdown-header">
                                Message Center
                            </h6>

                            <?php
            // Query to fetch unique users who have sent messages to the admin, excluding admin's own messages
            $query = "SELECT u.id, u.username, MAX(m.created_at) AS last_message_time
                      FROM users u
                      JOIN message m ON u.id = m.sender
                      WHERE m.recipient = ? AND u.id != ?
                      GROUP BY u.id, u.username
                      ORDER BY last_message_time DESC
                      LIMIT 5"; // Limit to 5 recent users

            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $admin_id, $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Display each unique user who has sent messages to the admin
                while ($row = $result->fetch_assoc()) {
                    ?>
                            <a class="dropdown-item d-flex align-items-center"
                                href="../message_kineme/admin_kinems/eto_again.php?user_id=<?php echo $row['id']; ?>">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="img/default_profile.svg" alt="...">
                                    <div class="status-indicator bg-success"></div>
                                </div>
                                <div class="font-weight-bold">
                                    <div class="text-truncate">Chat with
                                        <?php echo htmlspecialchars($row['username']); ?>
                                    </div>
                                    <div class="small text-gray-500">
                                        Last message:
                                        <?php echo date('H:i A', strtotime($row['last_message_time'])); ?>
                                    </div>
                                </div>
                            </a>
                            <?php
                }
            } else {
                // If there are no chat users
                echo '<a class="dropdown-item d-flex align-items-center" href="#">';
                echo '<div class="font-weight-bold text-center">No chats found</div>';
                echo '</a>';
            }

            $stmt->close();
        ?>

                            <a class="dropdown-item text-center small text-gray-500"
                                href="../../message_kineme/user_ansya/chat_kineme.php">GO TO MESSAGES</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="img-xs rounded-circle" src="assets/images/faces/face8.jpg" alt="Profile image">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                            <a class="dropdown-item"><i
                                    class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile
                                <span class="badge badge-pill badge-danger">1</span></a>

                            <a class="dropdown-item " href="../../login/login.php"><i
                                    class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-bs-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
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



<!-- Emergency Details HTML (No branch info) -->

<div class="container my-5 fade-in">
    <h1 class="text-center mb-4">Emergency Details</h1>
    <div class="card">
        <div class="card-header">
            Emergency ID: <?php echo $emergency['emergency_ID']; ?>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>Emergency Type</th>
                    <td><?php echo htmlspecialchars($emergency['emergency_type']); ?></td>
                </tr>
                <tr>
                    <th>Car Type</th>
                    <td><?php echo htmlspecialchars($emergency['car_type']); ?></td>
                </tr>
                <tr>
                    <th>Contact</th>
                    <td><?php echo htmlspecialchars($emergency['contact']); ?></td>
                </tr>
                <tr>
                    <th>Location</th>
                    <td><?php echo htmlspecialchars($emergency['location']); ?></td>
                </tr>
                <tr>
                    <th>Latitude</th>
                    <td><?php echo htmlspecialchars($emergency['userLat']); ?></td>
                </tr>
                <tr>
                    <th>Longitude</th>
                    <td><?php echo htmlspecialchars($emergency['userLng']); ?></td>
                </tr>
                <tr>
                    <th>Within Radius</th>
                    <td><?php echo $emergency['withinRadius'] ? 'Yes' : 'No'; ?></td>
                </tr>
                <tr>
                    <th>Requested By</th>
                    <td><?php echo htmlspecialchars($emergency['requested_by']); ?></td>
                </tr>
                <tr>
                    <th>Date Requested</th>
                    <td><?php echo date('F j, Y, g:i A', strtotime($emergency['created_at'])); ?></td>
                </tr>
            </table>
        </div>
        <div class="card-footer text-end">
            <a href="show_all.php" class="btn btn-secondary me-2">Back to List</a>
            <form method="POST" class="d-inline">
                <input type="hidden" name="emergency_ID" value="<?php echo $emergency['emergency_ID']; ?>">
                <button type="submit" name="delete" class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to delete this emergency?');">
                    Delete Emergency
                </button>
            </form>
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

