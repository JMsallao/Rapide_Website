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


    $booking_id = (int)$_GET['booking_id'];

    // Fetch booking and user details
    $booking_query = "SELECT b.*, u.fname, u.lname, u.email, u.phone, cl.city_name, bl.brgy_name 
                    FROM bookings b
                    JOIN users u ON b.user_id = u.id
                    LEFT JOIN city_list cl ON u.city = cl.id
                    LEFT JOIN brgy_list bl ON u.brgy = bl.id
                    WHERE b.booking_id = ?";
    $stmt = $conn->prepare($booking_query);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $booking_result = $stmt->get_result();
    $booking = $booking_result->fetch_assoc();

    // Fetch services linked to the booking from the cart table
    $services_query = "SELECT c.service_name, c.price, c.quantity 
    FROM cart c
    WHERE c.booking_id = ?";
    $stmt_services = $conn->prepare($services_query);
    $stmt_services->bind_param("i", $booking_id);
    $stmt_services->execute();
    $services_result = $stmt_services->get_result();


    // Fetch checklist details
    $checklist_query = "SELECT * FROM checklist WHERE booking_id = ?";
    $stmt_checklist = $conn->prepare($checklist_query);
    $stmt_checklist->bind_param("i", $booking_id);
    $stmt_checklist->execute();
    $checklist_result = $stmt_checklist->get_result();
    $checklist = $checklist_result->fetch_assoc();

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['new_status']) && $booking['status'] === 'pending') {
            // Update booking status to confirmed or rejected
            $new_status = $_POST['new_status'];
            if (in_array($new_status, ['confirmed', 'rejected'])) {
                $update_status_query = "UPDATE bookings SET status = ? WHERE booking_id = ?";
                $stmt_update_status = $conn->prepare($update_status_query);
                $stmt_update_status->bind_param("si", $new_status, $booking_id);
                $stmt_update_status->execute();

                // If confirmed, create a checklist if it doesn't already exist
                if ($new_status === 'confirmed') {
                    // Check if a checklist already exists for this booking_id
                    $checklist_exists_query = "SELECT COUNT(*) AS count FROM checklist WHERE booking_id = ?";
                    $stmt_checklist_exists = $conn->prepare($checklist_exists_query);
                    $stmt_checklist_exists->bind_param("i", $booking_id);
                    $stmt_checklist_exists->execute();
                    $result_checklist_exists = $stmt_checklist_exists->get_result();
                    $checklist_exists = $result_checklist_exists->fetch_assoc();

                    // Only insert a checklist if it doesn't already exist
                    if ($checklist_exists['count'] == 0) {
                        $init_query = "INSERT INTO checklist (booking_id, arrival, assessment, service, finalization, completed_at) VALUES (?, 0, 0, 0, 0, NULL)";
                        $stmt_init = $conn->prepare($init_query);
                        $stmt_init->bind_param("i", $booking_id);
                        $stmt_init->execute();
                    }
                }

                header("Location: booking_details.php?booking_id=$booking_id&status_updated=1");
                exit();
            }
        }

        if (isset($_POST['update_checklist']) && $booking['status'] === 'confirmed' && $checklist) {
            // Fetch updated checklist values
            $arrival = isset($_POST['arrival']) ? 1 : 0;
            $assessment = isset($_POST['assessment']) ? 1 : 0;
            $service = isset($_POST['service']) ? 1 : 0;
            $finalization = isset($_POST['finalization']) ? 1 : 0;

            // Update checklist
            $update_query = "UPDATE checklist SET arrival = ?, assessment = ?, service = ?, finalization = ? WHERE booking_id = ?";
            $stmt_update = $conn->prepare($update_query);
            $stmt_update->bind_param("iiiii", $arrival, $assessment, $service, $finalization, $booking_id);
            $stmt_update->execute();

            // Mark booking as completed if all checklist items are done
            if ($arrival && $assessment && $service && $finalization) {
                $status_query = "UPDATE bookings SET status = 'completed' WHERE booking_id = ?";
                $stmt_status = $conn->prepare($status_query);
                $stmt_status->bind_param("i", $booking_id);
                $stmt_status->execute();

                // Update checklist completed_at timestamp
                $completed_at_query = "UPDATE checklist SET completed_at = NOW() WHERE booking_id = ?";
                $stmt_completed = $conn->prepare($completed_at_query);
                $stmt_completed->bind_param("i", $booking_id);
                $stmt_completed->execute();
            }

            header("Location: booking_details.php?booking_id=$booking_id&checklist_updated=1");
            exit();
        }
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
    <link rel="stylesheet" href="../dist/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../dist/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../dist/assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../dist/assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../dist/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../dist/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
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

    .table th,
    .table td {
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
        color: rgb(69, 69, 69);
        font-weight: bold;
        padding: 10px 20px;
        transition: all 0.3s ease-in-out;
    }

    .nav-tabs .nav-link.active {
        color: black;
        background-color: rgb(255, 221, 0);
        /* Primary color for active tab */
        border: none;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .nav-tabs .nav-link:hover {
        color: rgb(9, 9, 9);
        background-color: #f1f1f1;
        text-decoration: none;
    }

    /* Adjust the tab content spacing */
    .tab-content {
        border: 1px solid #e9ecef;
        border-radius: 5px;
        padding: 20px;
        background-color: #ffffff;
        margin-top: -1px;
        /* Removes gap between tab and content */
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-header {
        background-color: #f9a825;
        /* Yellow theme */
        color: #fff;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        padding: 15px;
    }

    .card-body p {
        margin: 5px 0;
        font-size: 15px;
    }

    .card-body h4 {
        margin-top: 15px;
        color: #333;
    }

    .table-responsive {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-warning {
        background-color: #fdd835;
        border: none;
        color: #333;
        font-weight: bold;
    }

    .btn-warning:hover {
        background-color: #fbc02d;
    }

    .btn-primary {
        background-color: #4caf50;
        border: none;
    }

    .btn-primary:hover {
        background-color: #45a049;
    }

    .form-check-label {
        font-size: 15px;
    }

    .badge-counter {
        position: absolute;
        top: 8px;
        right: 8px;
        transform: translate(50%, -50%);
        font-size: 12px;
        background-color: #ff3d3d;
        color: white;
        padding: 4px 8px;
        border-radius: 50%;
        font-weight: bold;
    }

    .mdi {
        margin-right: 5px;
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
                    <a class="navbar-brand brand-logo" href="../dist/Admin-Homepage.php">
                        <h2>Rapide</h2>
                    </a>
                    <a class="navbar-brand brand-logo-mini" href="../dist/Admin-Homepage.php">
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
                        <a class="nav-link" href="../dist/Admin-Homepage.php">
                            <i class="mdi mdi-view-dashboard-outline menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Menu</li>
                    <li class="nav-item">
                        <a class="nav-link" href="../dist/Calendar.php">
                            <i class="mdi mdi-calendar-check menu-icon"></i>
                            <span class="menu-title">Calendar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="history.php">
                            <i class="mdi mdi-calendar-check menu-icon"></i>
                            <span class="menu-title">Booking</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../dist/pages.php">
                            <i class="mdi mdi-file-multiple menu-icon"></i>
                            <span class="menu-title">Pages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../dist/service.php">
                            <i class="mdi mdi-tools menu-icon"></i>
                            <span class="menu-title">Services</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="..//dist/Users.php">
                            <i class="mdi mdi-account-multiple menu-icon"></i>
                            <span class="menu-title">Users</span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="../dist/message_inbox.php">
                            <i class="mdi mdi-message-text-outline menu-icon"></i>
                            <span class="menu-title">Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../dist/Reports.php">
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





            <div class="container my-5">
                <div class="row">
                    <!-- Booking Details -->
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <i class="mdi mdi-calendar-check"></i> Booking Details
                            </div>
                            <div class="card-body">
                                <p><strong>Booking ID:</strong> <i class="mdi mdi-identifier"></i>
                                    <?= htmlspecialchars($booking['booking_id']); ?></p>
                                <p><strong>User:</strong> <i class="mdi mdi-account"></i>
                                    <?= htmlspecialchars($booking['fname'] . ' ' . $booking['lname']); ?></p>
                                <p><strong>Email:</strong> <i class="mdi mdi-email-outline"></i>
                                    <?= htmlspecialchars($booking['email']); ?></p>
                                <p><strong>Phone:</strong> <i class="mdi mdi-phone"></i>
                                    <?= htmlspecialchars($booking['phone']); ?></p>
                                <p><strong>Address:</strong> <i class="mdi mdi-map-marker"></i>
                                    <?= htmlspecialchars($booking['city_name'] . ', ' . $booking['brgy_name']); ?></p>
                                <p><strong>Booking Date:</strong> <i class="mdi mdi-calendar"></i>
                                    <?= date('F j, Y', strtotime($booking['booking_date'])); ?></p>

                                <!-- Services Availed -->
                                <h4><i class="mdi mdi-tools"></i> Services Availed</h4>
                                <?php if ($services_result->num_rows > 0): ?>
                                <ul>
                                    <?php 
                                $grand_total = 0; 
                                while ($service = $services_result->fetch_assoc()): 
                                    $total = $service['price'] * $service['quantity'];
                                    $grand_total += $total;
                                ?>
                                    <li>
                                        <strong><?= htmlspecialchars($service['service_name']); ?></strong>
                                        - ₱<?= number_format($service['price'], 2); ?>
                                        x <?= $service['quantity']; ?>
                                        = ₱<?= number_format($total, 2); ?>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                                <p><strong>Grand Total:</strong> ₱<?= number_format($grand_total, 2); ?></p>
                                <?php else: ?>
                                <p class="text-muted"><em>No services availed for this booking.</em></p>
                                <?php endif; ?>

                                <!-- Status Update -->
                                <?php if ($booking['status'] === 'pending') : ?>
                                <h4 class="mt-4"><i class="mdi mdi-update"></i> Update Booking Status</h4>
                                <form method="post">
                                    <div class="form-group">
                                        <label for="new_status" class="form-label">Select New Status:</label>
                                        <select name="new_status" id="new_status" class="form-select">
                                            <option value="confirmed">Confirmed</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-warning mt-3"><i class="mdi mdi-check"></i>
                                        Update Status</button>
                                </form>
                                <?php elseif ($booking['status'] === 'confirmed') : ?>
                                <h4 class="text-success mt-4"><i class="mdi mdi-check-circle"></i> This booking is
                                    confirmed.</h4>
                                <?php elseif ($booking['status'] === 'completed') : ?>
                                <h4 class="text-success mt-4"><i class="mdi mdi-check-all"></i> This booking has been
                                    completed.</h4>
                                <?php elseif ($booking['status'] === 'rejected') : ?>
                                <h4 class="text-danger mt-4"><i class="mdi mdi-close-circle"></i> This booking has been
                                    rejected.</h4>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Checklist -->
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="mdi mdi-checkbox-multiple-marked"></i> Service Checklist
                            </div>
                            <div class="card-body">
                                <?php if (!empty($checklist)) : ?>
                                <?php if ($booking['status'] === 'confirmed') : ?>
                                <form method="post">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="arrival" id="arrival"
                                            <?= $checklist['arrival'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="arrival"><i class="mdi mdi-check"></i>
                                            Arrival and Initial Assessment</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="assessment"
                                            id="assessment" <?= $checklist['assessment'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="assessment"><i class="mdi mdi-check"></i>
                                            Mechanic Assigned and Assessment</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="service" id="service"
                                            <?= $checklist['service'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="service"><i class="mdi mdi-check"></i>
                                            Service and Repair</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="finalization"
                                            id="finalization" <?= $checklist['finalization'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="finalization"><i class="mdi mdi-check"></i>
                                            Finalization and Handover</label>
                                    </div>
                                    <button type="submit" name="update_checklist" class="btn btn-primary mt-3"><i
                                            class="mdi mdi-update"></i> Update Checklist</button>
                                </form>
                                <?php else : ?>
                                <ul>
                                    <li>Arrival and Initial Assessment: <?= $checklist['arrival'] ? '✔' : '✘'; ?></li>
                                    <li>Mechanic Assigned and Assessment: <?= $checklist['assessment'] ? '✔' : '✘'; ?>
                                    </li>
                                    <li>Service and Repair: <?= $checklist['service'] ? '✔' : '✘'; ?></li>
                                    <li>Finalization and Handover: <?= $checklist['finalization'] ? '✔' : '✘'; ?></li>
                                </ul>
                                <?php endif; ?>
                                <?php else : ?>
                                <p class="text-muted"><em>No checklist details are available for this booking.</em></p>
                                <?php endif; ?>
                            </div>
                        </div>
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