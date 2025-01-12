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

    // Validate and get emergency ID from the URL
    if (!isset($_GET['emergency_ID']) || !is_numeric($_GET['emergency_ID'])) {
        die("Invalid Emergency ID.");
    }

    $emergency_id = $_GET['emergency_ID'];

    // Fetch emergency details (removed branch_id and branch_name)
    $emergency_query = "
        SELECT e.emergency_ID, e.emergency_type, e.car_type, e.contact, e.location, e.userLat, e.userLng, e.created_at, e.withinRadius, e.status,
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

        $query = "SELECT branch_name, lat, lng FROM branches";
        $result = $conn->query($query);
        $branches = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $branches[] = $row;
            }
        }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqV1Tf4ZH_FZ4EWldoeMoiLI_kCwxfR7U&libraries=geometry&callback=initMap"
        async defer></script>

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
    <link rel="shortcut icon" href="../../images/LogoRapide.png" type="image/x-icon">

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
        background-color: #f8f9fc;
        /* Light gray for professional background */
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
        background-color: #3a3f51;
        /* Professional dark gray */
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

        .table th,
        .table td {
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
                        <a class="nav-link" href="../dist/Users.php">
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



            <!-- Emergency Details HTML (No branch info) -->

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <!-- Required meta tags -->
                <meta charset="utf-8">
                <script
                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqV1Tf4ZH_FZ4EWldoeMoiLI_kCwxfR7U&libraries=geometry&callback=initMap"
                    async defer></script>

                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <title>RAPIDE ADMIN </title>
                <!-- plugins:css -->
                <link rel="stylesheet" href="../../admin1/dist/assets/vendors/feather/feather.css">
                <link rel="stylesheet" href="../../admin1/dist/assets/vendors/mdi/css/materialdesignicons.min.css">
                <link rel="stylesheet" href="../../admin1/dist/assets/vendors/ti-icons/css/themify-icons.css">
                <link rel="stylesheet" href="../../admin1/dist/assets/vendors/font-awesome/css/font-awesome.min.css">
                <link rel="stylesheet" href="../../admin1/dist/assets/vendors/typicons/typicons.css">
                <link rel="stylesheet"
                    href="../../admin1/dist/assets/vendors/simple-line-icons/css/simple-line-icons.css">
                <link rel="stylesheet" href="../../admin1/dist/assets/vendors/css/vendor.bundle.base.css">
                <link rel="stylesheet"
                    href="../../admin1/dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
                <!-- endinject -->
                <!-- Plugin css for this page -->
                <link rel="stylesheet"
                    href="../../admin1/dist/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
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
                    background-color: #f8f9fc;
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
                    background-color: #3a3f51;
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

                    .table th,
                    .table td {
                        font-size: 14px;
                        padding: 8px 10px;
                    }
                }

                #map {
                    width: 100%;
                    height: 500px;
                    border-radius: 10px;
                }
                </style>
            </head>

            <body>
                <div class="container my-5">
                    <div class="row">
                        <!-- Map Section -->
                        <div class="col-lg-6 col-md-12 mb-4">
                            <h2 class="text-center mt-4">Location Map</h2>
                            <div id="map" style="height: 500px; border-radius: 10px;"></div>
                        </div>

                        <!-- Details Section -->
                        <div class="col-lg-6 col-md-12">
                            <h1 class="text-center mb-4"><i class="fa fa-ambulance text-primary me-2"></i>Emergency
                                Details</h1>
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th style="color: #212529;">
                                                <i class="fa fa-info-circle me-2" style="color: #fbc02d;"></i> Emergency
                                                Type
                                            </th>
                                            <td><?php echo htmlspecialchars($emergency['emergency_type']); ?></td>
                                        </tr>
                                        <tr>
                                            <th style=" color: #212529;">
                                                <i class="fa fa-car me-2" style="color: #fbc02d;"></i> Car Type
                                            </th>
                                            <td><?php echo htmlspecialchars($emergency['car_type']); ?></td>
                                        </tr>
                                        <tr>
                                            <th style=" color: #212529;">
                                                <i class="fa fa-phone me-2" style="color: #fbc02d;"></i> Contact
                                            </th>
                                            <td><?php echo htmlspecialchars($emergency['contact']); ?></td>
                                        </tr>
                                        <tr>
                                            <th style=" color: #212529;">
                                                <i class="fa fa-map-marker-alt me-2" style="color: #fbc02d;"></i>
                                                Location
                                            </th>
                                            <td><?php echo htmlspecialchars($emergency['location']); ?></td>
                                        </tr>
                                        <tr>
                                            <th style=" color: #212529;">
                                                <i class="fa fa-flag me-2" style="color: #fbc02d;"></i> Status
                                            </th>
                                            <td><?php echo htmlspecialchars($emergency['status']); ?></td>
                                        </tr>
                                        <tr>
                                            <th style=" color: #212529;">
                                                <i class="fa fa-check-circle me-2" style="color: #fbc02d;"></i> Within
                                                Radius
                                            </th>
                                            <td><?php echo $emergency['withinRadius'] ? 'Yes' : 'No'; ?></td>
                                        </tr>
                                        <tr>
                                            <th style=" color: #212529;">
                                                <i class="fa fa-user me-2" style="color: #fbc02d;"></i> Requested By
                                            </th>
                                            <td><?php echo htmlspecialchars($emergency['requested_by']); ?></td>
                                        </tr>
                                        <tr>
                                            <th style=" color: #212529;">
                                                <i class="fa fa-calendar me-2" style="color: #fbc02d;"></i> Date
                                                Requested
                                            </th>
                                            <td><?php echo date('F j, Y, g:i A', strtotime($emergency['created_at'])); ?>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                                <div class="card-footer text-end">
                                    <a href="history.php" class="btn btn-secondary btn-sm me-2"><i
                                            class="fa fa-arrow-left"></i> Back to List</a>
                                    <div class="dropdown d-inline">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                            id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-sync-alt"></i> Update Status
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                            <?php if ($emergency['status'] === 'pending') : ?>
                                            <li>
                                                <form method="POST" action="update_emergency.php" class="d-inline">
                                                    <input type="hidden" name="emergency_ID"
                                                        value="<?php echo $emergency['emergency_ID']; ?>">
                                                    <button class="dropdown-item" type="submit" name="status"
                                                        value="confirmed"><i class="fa fa-check"></i> Confirm</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="update_emergency.php" class="d-inline">
                                                    <input type="hidden" name="emergency_ID"
                                                        value="<?php echo $emergency['emergency_ID']; ?>">
                                                    <button class="dropdown-item" type="submit" name="status"
                                                        value="rejected"><i class="fa fa-times"></i> Reject</button>
                                                </form>
                                            </li>
                                            <?php else : ?>
                                            <li><span class="dropdown-item disabled"><i class="fa fa-ban"></i> Update
                                                    not allowed</span></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <script>
                var map;
                var userLocation = {
                    lat: <?php echo $emergency['userLat']; ?>,
                    lng: <?php echo $emergency['userLng']; ?>
                };
                var branches = <?php echo json_encode($branches); ?>;

                function initMap() {
                    console.log("Initializing Map...");

                    if (!userLocation.lat || !userLocation.lng) {
                        console.error("Invalid user location data:", userLocation);
                        alert("Invalid user location data. Map cannot be loaded.");
                        return;
                    }

                    map = new google.maps.Map(document.getElementById('map'), {
                        center: userLocation,
                        zoom: 14
                    });

                    // Add user marker
                    new google.maps.Marker({
                        position: userLocation,
                        map: map,
                        title: "Emergency Location",
                        icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                    });

                    console.log("User Location Marker Added:", userLocation);

                    if (!branches || branches.length === 0) {
                        console.error("No branches available:", branches);
                        alert("No branch data available.");
                        return;
                    }

                    // Find nearest branch
                    let nearestBranch = null;
                    let shortestDistance = Infinity;

                    branches.forEach(branch => {
                        const branchLatLng = new google.maps.LatLng(branch.lat, branch.lng);
                        const distance = google.maps.geometry.spherical.computeDistanceBetween(
                            new google.maps.LatLng(userLocation.lat, userLocation.lng),
                            branchLatLng
                        );

                        if (distance < shortestDistance) {
                            shortestDistance = distance;
                            nearestBranch = branch;
                        }

                        // Add branch markers
                        new google.maps.Marker({
                            position: branchLatLng,
                            map: map,
                            title: branch.branch_name,
                            icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
                        });
                    });

                    console.log("Branch Markers Added:", branches);

                    if (nearestBranch) {
                        const nearestLatLng = new google.maps.LatLng(nearestBranch.lat, nearestBranch.lng);

                        // Draw line to nearest branch
                        const line = new google.maps.Polyline({
                            path: [userLocation, nearestLatLng],
                            geodesic: true,
                            strokeColor: "#FF0000",
                            strokeOpacity: 1.0,
                            strokeWeight: 2
                        });
                        line.setMap(map);

                        console.log("Line Drawn to Nearest Branch:", nearestBranch);

                        // Calculate and display distance
                        const distanceInKm = (shortestDistance / 1000).toFixed(2);
                        alert(`Nearest Branch: ${nearestBranch.branch_name}\nDistance: ${distanceInKm} km`);
                    } else {
                        alert("No branches found.");
                    }
                }
                </script>


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