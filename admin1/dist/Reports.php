<?php
    include('../../connection.php');
    include('../../sessioncheck.php');
    require_once __DIR__ . '/../../vendor/autoload.php';

    $action = isset($_GET['action']) ? $_GET['action'] : '';

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

        $fname = $user['fname'];
        $lname = $user['lname'];
    } else {
        die("User not found.");
    }
    $stmt->close();

    // Handle filters from GET
    $branch_id = isset($_GET['branch_id']) ? intval($_GET['branch_id']) : 1; // Default branch ID
    $from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
    $to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';
    $service_type = isset($_GET['service_type']) ? $_GET['service_type'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    // Updated query to calculate total sales
$query = "
SELECT 
    b.booking_id, 
    b.service_type, 
    b.status, 
    b.total_price, 
    b.booking_date
FROM bookings AS b
WHERE b.branch_id = $branch_id
";

// Apply filters for display
if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND b.booking_date BETWEEN '$from_date' AND '$to_date'";
}
if (!empty($service_type)) {
    $query .= " AND b.service_type = '$service_type'";
}
if (!empty($status)) {
    $query .= " AND b.status = '$status'";
}
$query .= " ORDER BY b.booking_date DESC";

// Query to get total sales
$total_sales_query = "
SELECT SUM(total_price) AS total_sales
FROM bookings
WHERE branch_id = $branch_id
";
if (!empty($from_date) && !empty($to_date)) {
    $total_sales_query .= " AND booking_date BETWEEN '$from_date' AND '$to_date'";
}
if (!empty($service_type)) {
    $total_sales_query .= " AND service_type = '$service_type'";
}
if (!empty($status)) {
    $total_sales_query .= " AND status = '$status'";
}

// Execute total sales query
$total_sales_result = $conn->query($total_sales_query);
$total_sales_row = $total_sales_result->fetch_assoc();
$total_sales = $total_sales_row['total_sales'] ? number_format($total_sales_row['total_sales'], 2) : "0.00";

// Execute the main query to get the bookings data
$result = $conn->query($query);

    // Generate PDF
    if ($action === 'download_pdf') {
        $pdf_query = $query; // Use the same query
        $pdf_result = $conn->query($pdf_query);

        // Generate PDF
$mpdf = new \Mpdf\Mpdf();
$html = '<h1>Rapide Branch Booking Report</h1>';
$html .= '<p>Admin: ' . $fname . ' ' . $lname . '</p>';
$html .= '<p>From: ' . (!empty($from_date) ? $from_date : 'N/A') . ' To: ' . (!empty($to_date) ? $to_date : 'N/A') . '</p>';
$html .= '<p>Service Type: ' . (!empty($service_type) ? ucfirst($service_type) : 'All') . '</p>';
$html .= '<p>Status: ' . (!empty($status) ? ucfirst($status) : 'All') . '</p>';
$html .= '<p><strong>Total Sales: ₱' . $total_sales . '</strong></p>';
$html .= '<table border="1" style="border-collapse: collapse; width: 100%;">';
$html .= '<thead>
            <tr>
                <th>Booking ID</th>
                <th>Service Type</th>
                <th>Status</th>
                <th>Total Price (₱)</th>
                <th>Booking Date</th>
            </tr>
        </thead>';
$html .= '<tbody>';

if ($pdf_result && $pdf_result->num_rows > 0) {
    while ($row = $pdf_result->fetch_assoc()) {
        $html .= '<tr>
                    <td>' . $row['booking_id'] . '</td>
                    <td>' . ucfirst($row['service_type']) . '</td>
                    <td>' . ucfirst($row['status']) . '</td>
                    <td>₱' . number_format($row['total_price'], 2) . '</td>
                    <td>' . date('F j, Y, g:i A', strtotime($row['booking_date'])) . '</td>
                </tr>';
    }
} else {
    $html .= '<tr><td colspan="5">No bookings found for the selected filters.</td></tr>';
}

$html .= '</tbody></table>';
$mpdf->WriteHTML($html);
$mpdf->Output('Booking_Report.pdf', 'D');
exit;
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

    .container1 {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: flex-start;
        /* Align items to the left */
        gap: 15px;
    }

    .filter-form .form-control,
    .filter-form .form-select {
        max-width: 180px;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .table-container {
        margin-top: 30px;
        overflow-x: auto;
        /* Scrollable table for smaller screens */
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-container th,
    .table-container td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .table-container th {
        background-color: #f5f5f5;
    }

    .total-sales {
        margin-top: 20px;
        text-align: right;
        font-size: 18px;
        font-weight: bold;
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
                        <a class="nav-link" href="../../login/logout.php">
                            <i class="mdi mdi-logout user"></i> <!-- Changed the icon to mdi-logout -->
                            <span class="menu-title">Sign Out</span>
                        </a>
                    </li>
                </ul>
            </nav>




            <!-- partial -->




            <div class="container mt-5">
                <h1 class="mb-4 text-center">Branch Booking Report</h1>

                <!-- Filter Form -->
                <form method="GET" class="row g-3 mb-12">
                    <input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
                    <div class="row">
                        <!-- From Date -->
                        <div class="col-4">
                            <label for="from_date" class="form-label">From Date:</label>
                            <input type="date" name="from_date" id="from_date" class="form-control"
                                value="<?php echo $from_date; ?>" required>


                            <!-- To Date -->
                            <div class="col-6>
                            <label for=" to_date" class="form-label">To Date:</label>
                                <input type="date" name="to_date" id="to_date" class="form-control"
                                    value="<?php echo $to_date; ?>" required>
                            </div>
                        </div>

                        <!-- Service Type -->
                        <div>
                            <label for="service_type" class="form-label">Service Type:</label>
                            <select name="service_type" id="service_type" class="form-select">
                                <option value="">Service Type</option>
                                <option value="standard" <?php if ($service_type === 'standard') echo 'selected'; ?>>
                                    Standard</option>
                                <option value="emergency" <?php if ($service_type === 'emergency') echo 'selected'; ?>>
                                    Emergency</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="form-label">Status:</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Status</option>
                                <option value="pending" <?php if ($status === 'pending') echo 'selected'; ?>>Pending
                                </option>
                                <option value="confirmed" <?php if ($status === 'confirmed') echo 'selected'; ?>>
                                    Confirmed
                                </option>
                                <option value="completed" <?php if ($status === 'completed') echo 'selected'; ?>>
                                    Completed
                                </option>
                                <option value="canceled" <?php if ($status === 'canceled') echo 'selected'; ?>>Canceled
                                </option>
                                <option value="rejected" <?php if ($status === 'rejected') echo 'selected'; ?>>Rejected
                                </option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="filter-buttons">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                            <a href="Reports.php?branch_id=<?php echo $branch_id; ?>"
                                class="btn btn-secondary">Reset</a>
                        </div>

                </form>

                <!-- Booking Table -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Service Type</th>
                                <th>Status</th>
                                <th>Total Price (₱)</th>
                                <th>Booking Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['booking_id']; ?></td>
                                <td><?php echo ucfirst($row['service_type']); ?></td>
                                <td><?php echo ucfirst($row['status']); ?></td>
                                <td>₱<?php echo number_format($row['total_price'], 2); ?></td>
                                <td><?php echo date('F j, Y, g:i A', strtotime($row['booking_date'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No bookings found for the selected dates.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Total Sales -->
                <div class="total-sales">
                    <h3>Total Sales: ₱<?php echo $total_sales; ?></h3>
                </div>



                <!-- PDF Download Button -->
                <form method="GET" class="text-left mt-4">
                    <input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
                    <input type="hidden" name="from_date" value="<?php echo $from_date; ?>">
                    <input type="hidden" name="to_date" value="<?php echo $to_date; ?>">
                    <input type="hidden" name="service_type" value="<?php echo $service_type; ?>">
                    <input type="hidden" name="status" value="<?php echo $status; ?>">
                    <input type="hidden" name="action" value="download_pdf">
                    <button type="submit" class="btn btn-success">Download PDF</button>
                </form>

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