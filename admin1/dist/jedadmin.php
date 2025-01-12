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

    $ratings_count = [
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0,
        5 => 0
    ];

    // Ratings breakdown
    $very_satisfactory = $ratings['very_satisfactory'] ?? 0;
    $satisfactory = $ratings['satisfactory'] ?? 0;
    $neutral = $ratings['neutral'] ?? 0;
    $unsatisfactory = $ratings['unsatisfactory'] ?? 0;
    $poor = $ratings['poor'] ?? 0;

    // Total ratings
    $total_ratings = $very_satisfactory + $satisfactory + $neutral + $unsatisfactory + $poor;

    // Query to count rows in each table
    $tables = ['brakes_table', 'brake_service', 'package_list', 'service_list', 'ac_service'];
    $total_categories = 0;

    foreach ($tables as $table) {
    $sql = "SELECT COUNT(*) as count FROM $table";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) 
    {
        // Fetch the result and add it to the total categories
        $row = $result->fetch_assoc();
        $total_categories += $row['count'];
    }
}

    // Count the total branches (rows in the 'maps' table)
    $sql_branches = "SELECT COUNT(*) AS total_branches FROM map";
    $result_branches = $conn->query($sql_branches);
    $branch_count = 0;
    if ($result_branches->num_rows > 0) {
        $row = $result_branches->fetch_assoc();
        $branch_count = $row['total_branches'];
    }

    // Fetch the list of locations from the 'location' column of the 'map' table
    $sql_locations = "SELECT DISTINCT location FROM map";
    $result_locations = $conn->query($sql_locations);
    $locations = [];
    while ($row = $result_locations->fetch_assoc()) {
        $locations[] = $row['location'];
    }


    // Query to get the total number of emergencies
    $sql_total_emergencies = "SELECT COUNT(*) AS total FROM emergencies";
    $result_total_emergencies = mysqli_query($conn, $sql_total_emergencies);

    // Fetch the result
    $row_total = mysqli_fetch_assoc($result_total_emergencies);
    $total_emergencies = $row_total['total'];

    // Query to get the counts of "Yes" and "No" for the withinRadius column
    $sql_emergencies = "SELECT withinRadius, COUNT(*) AS count FROM emergencies GROUP BY withinRadius";
    $result_emergencies = mysqli_query($conn, $sql_emergencies);

    // Initialize variables to store the counts
    $withinRadiusYes = 0;
    $withinRadiusNo = 0;

    // Fetch the result and assign the values
    while ($row = mysqli_fetch_assoc($result_emergencies)) {
        if ($row['withinRadius'] == 'Yes') {
            $withinRadiusYes = $row['count'];
        } elseif ($row['withinRadius'] == 'No') {
            $withinRadiusNo = $row['count'];
        }
    }

    // Get total bookings
    $query_total_bookings = "SELECT COUNT(*) FROM bookings";
    $result_total_bookings = mysqli_query($conn, $query_total_bookings);
    $row_total_bookings = mysqli_fetch_assoc($result_total_bookings);
    $total_bookings = $row_total_bookings['COUNT(*)'];

    // Get bookings for today
    $query_today = "SELECT COUNT(*) FROM bookings WHERE DATE(booking_date) = CURDATE()";
    $result_today = mysqli_query($conn, $query_today);
    $row_today = mysqli_fetch_assoc($result_today);
    $total_today = $row_today['COUNT(*)'];

    // Get bookings for the current week
    $query_week = "SELECT COUNT(*) FROM bookings WHERE YEARWEEK(booking_date, 1) = YEARWEEK(CURDATE(), 1)";
    $result_week = mysqli_query($conn, $query_week);
    $row_week = mysqli_fetch_assoc($result_week);
    $total_week = $row_week['COUNT(*)'];

    // Get bookings for the current month
    $query_month = "SELECT COUNT(*) FROM bookings WHERE MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE())";
    $result_month = mysqli_query($conn, $query_month);
    $row_month = mysqli_fetch_assoc($result_month);
    $total_month = $row_month['COUNT(*)'];

    // Get pending bookings (assuming there is a 'status' field to track pending bookings)
    $query_pending = "SELECT COUNT(*) FROM bookings WHERE status = 'pending'";
    $result_pending = mysqli_query($conn, $query_pending);
    $row_pending = mysqli_fetch_assoc($result_pending);
    $total_pending = $row_pending['COUNT(*)'];

    
    $sql = "SELECT COUNT(*) AS total_users FROM users";
    $userResult = $conn->query($sql);

    $totalUsers = 0; // Default value
    if ($userResult && $userResult->num_rows > 0) {
        $row = $userResult->fetch_assoc();
        $totalUsers = $row['total_users'];
    } else {
        echo "Error counting users: " . $conn->error . "<br>";
    }

    // Tables to count rows from
    $tables = ['brakes_table', 'brake_service', 'package_list', 'service_list', 'ac_service'];
    $total_categories = 0;

    foreach ($tables as $table) {
    $sql = "SELECT COUNT(*) as count FROM $table";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) 
    {
        // Fetch the result and add it to the total categories
        $row = $result->fetch_assoc();
        $total_categories += $row['count'];
    }
}
        //Sales Weekly and Monthly
$weeklySalesSql = "SELECT SUM(total_price) AS total_sales FROM bookings 
WHERE status = 'confirmed' 
AND YEARWEEK(updated_at, 1) = YEARWEEK(CURDATE(), 1)";

$weeklySalesResult = $conn->query($weeklySalesSql);
$weeklySales = 0;
if ($weeklySalesResult && $weeklySalesResult->num_rows > 0) {
$row = $weeklySalesResult->fetch_assoc();
$weeklySales = $row['total_sales'];
}


$monthlySalesSql = "SELECT SUM(total_price) AS total_sales FROM bookings 
            WHERE status = 'confirmed' 
            AND YEAR(updated_at) = YEAR(CURDATE()) 
            AND MONTH(updated_at) = MONTH(CURDATE())";

$monthlySalesResult = $conn->query($monthlySalesSql);
$monthlySales = 0; 
if ($monthlySalesResult && $monthlySalesResult->num_rows > 0) {
$row = $monthlySalesResult->fetch_assoc();
$monthlySales = $row['total_sales'];
}

$totalSumsql = "SELECT SUM(total_price) AS total_sales FROM bookings WHERE status = 'confirmed'";

// Execute the query
$salesResult = $conn->query($totalSumsql);

if ($salesResult->num_rows > 0) {
    // Fetch the result and store the total sales
    $row = $salesResult->fetch_assoc();
    $totalSales = $row['total_sales']; // Store the total sales value

    // Output the result
    
} else {
    echo "No data found"; // No sales found
}


$sql = "SELECT AVG(stars) AS average_rating FROM ratings";

$ratingresult = $conn->query($sql);

$averageRating = 0; // Default value if no ratings found
if ($ratingresult && $ratingresult->num_rows > 0) {
    $row = $ratingresult->fetch_assoc();
    $averageRating = $row['average_rating']; // Store the average rating
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
    
/* Remove Bootstrap's default dropdown arrow */
.dropdown-toggle::after {
    display: none; /* Hide the default arrow */
}

/* Custom styles for the dropdown */
.dropdown-toggle {
    background: none;
    border: none;
    padding: 0;
    color: black; /* Set font color to black */
    font-size: 16px;
    text-decoration: none; /* Remove underline */
}

.dropdown-toggle:hover {
    color: black; /* Ensure font color remains black when hovered */
}

.dropdown-menu {
    min-width: 200px; /* Optional, adjust to fit your design */
}

.mdi-menu-down {
    font-size: 18px; /* Adjust the icon size as needed */
    margin-left: 5px;
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
                                href="../../booking\adminMoMamaMo\bukingdets.php?booking_id=<?= $row['booking_id']; ?>">
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
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="countDropdown">
                        <h6 class="dropdown-header">Message Center</h6>

                        <?php
                        // Query to fetch unique users who have sent messages to the specific admin
                        $query = "SELECT u.id, u.username, u.pic, MAX(m.created_at) AS last_message_time
                                FROM users u
                                JOIN message m ON u.id = m.sender
                                WHERE m.recipient = ? AND u.id != ?
                                GROUP BY u.id, u.username, u.pic
                                ORDER BY last_message_time DESC
                                LIMIT 5";

                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ii", $admin_id, $admin_id); // Bind admin_id for recipient and exclude admin's own messages
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Loop through the fetched data
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <a class="dropdown-item d-flex align-items-center" 
                                href="../../message_kineme/AdminPagod/eto_again.php?user_id=<?= $row['id']; ?>">
                                <div class="dropdown-list-image mr-3">
                                    <?php
                                    // Construct the full path
                                    $imagePath = !empty($row['pic']) ? '../../users/' . $row['pic'] : '../../img/default_profile.svg';
                                    ?>

                                    <img class="rounded-circle" 
                                        src="<?= htmlspecialchars($imagePath); ?>" 
                                        alt="Profile Picture" 
                                        style="width: 40px; height: 40px; object-fit: cover;">
                                    <div class="status-indicator bg-success"></div>
                                </div>

                        <div class="font-weight-bold">
                            <div class="text-truncate">Chat with <?= htmlspecialchars($row['username']); ?></div>
                            <div class="small text-gray-500">
                                Last message: <?= date('H:i A', strtotime($row['last_message_time'])); ?>
                            </div>
                        </div>
                    </a>

                                <?php
                            }
                        } else {
                            // If no chats are found
                            echo '<a class="dropdown-item d-flex align-items-center" href="#">';
                            echo '<div class="font-weight-bold text-center">No chats found</div>';
                            echo '</a>';
                        }

                        $stmt->close();
                        ?>

                        <!-- Option to go to full message center -->
                        <a class="dropdown-item text-center small text-gray-500" href="../../message_kineme/user_ansya/chat_kineme.php">
                            GO TO MESSAGES
                        </a>
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
                                        <div class="row statistics-details ">                                           
                                                    <div>
                                                        <p class="statistics-title">Weekly Income</p>
                                                        <h3 class="rate-percentage"><?php echo number_format($weeklySales, 2) ?></h3>                                                                                           
                                                    </div>

                                                    <div>
                                                        <p class="statistics-title">Monthly Income</p>
                                                        <h3 class="rate-percentage"><?php echo number_format($monthlySales, 2) ?></h3>
                                                    </div>

                                                    <div>
                                                        <p class="statistics-title">Overall Income</p>
                                                        <h3 class="rate-percentage"><?php echo number_format($totalSales, 2)?></h3>
                                                    </div>
                                                    
                                            <div class="col-sm-12">
                                                <div
                                                    class="statistics-details d-flex align-items-center justify-content-between">

                                                    
                                                    <div>
                                                        <p class="statistics-title">Total User</p>
                                                        <h3 class="rate-percentage"><?php echo $totalUsers ?></h3>

                                                    </div>
                                                    <div>
                                                        <p class="statistics-title">Total Service</p>
                                                        <h3 class="rate-percentage"><?php echo $total_categories ?></h3>
                                                    </div>
                                                    <div>
                                                        <p class="statistics-title">Total Categories</p>
                                                        <h3 class="rate-percentage">4</h3>
                                                    </div>
                                                    
                                                    <div class="col-md-3"> 
                                    <div class="statistics-details"> 
                                        <!-- Unattended Emergencies --> 
                                        <p class="statistics-title">Unattended Emergencies</p> 
                                        <h3 class="rate-percentage">
                                            <a href="Availability.php" style="color: inherit; text-decoration: none;">
                                                <?= $total_emergencies; ?> emergencies
                                            </a>
                                        </h3>
                                        <div class="dropdown"> 
                                            <!-- Dropdown for Emergencies (Within Radius) --> 
                                            <a class="dropdown-toggle" href="#" role="button" id="emergencyDropdown" data-bs-toggle="dropdown" aria-expanded="false"> 
                                                Emergencies Within Radius <i class="mdi mdi-menu-down"></i> 
                                            </a> 
                                            <ul class="dropdown-menu" aria-labelledby="emergencyDropdown"> 
                                                <li><a class="dropdown-item"><?= $withinRadiusYes; ?> emergencies within radius (Yes)</a></li> 
                                                <li><a class="dropdown-item"><?= $withinRadiusNo; ?> emergencies outside radius (No)</a></li> 
                                            </ul> 
                                        </div> 

                                       
                                    </div> 

                                   
                                        
                                       
                                    </div> 
                                    <div class="statistics-details"> 
                                        <!-- Unattended Emergencies --> 
                                        <p class="statistics-title">Overall Rating</p> 
                                        <h3 class="rate-percentage"><?php echo number_format($averageRating, 2) ?></h3>


                                        
                                </div> 
                            </div> 

                           
 
                            <!-- Another Row for the Remaining Stats --> 
                            <div class="row"> 
                                <div class="col-md-3"> 
                                    <div class="statistics-details"> 
                                        <!-- Branches --> 
                                        <p class="statistics-title">Total Branches</p> 
                                        <h3 class="rate-percentage"><?= $branch_count; ?> branches</h3> 
                                        <div class="dropdown"> 
                                            <!-- Dropdown for Branches --> 
                                            <a class="dropdown-toggle" role="button" id="locationDropdown" data-bs-toggle="dropdown" aria-expanded="false"> 
                                                List of Branches <i class="mdi mdi-menu-down"></i> 
                                            </a> 
                                            <ul class="dropdown-menu" aria-labelledby="locationDropdown"> 
                                                <?php foreach ($locations as $location): ?> 
                                                    <li><a class="dropdown-item" ><?= $location; ?></a></li> 
                                                <?php endforeach; ?> 
                                            </ul> 
                                        </div> 
                                    </div> 
                                </div> 
                                                   
                                                </div>
                                            </div>
                                        </div>

                                        <!--Bookings-->

                                        <div class="col-md-3"> 
                                            <div class="statistics-details"> 
                                                <!-- Total Bookings --> 
                                                <p class="statistics-title">Total Bookings</p> 
                                                <h3 class="rate-percentage"><?= $total_bookings; ?> bookings</h3> 
                                                <div class="dropdown"> 
                                                    <!-- Dropdown for Bookings --> 
                                                    <a class="dropdown-toggle" href="#" role="button" id="bookingDropdown" data-bs-toggle="dropdown" aria-expanded="false"> 
                                                        View Bookings <i class="mdi mdi-menu-down"></i> 
                                                    </a> 
                                                    <ul class="dropdown-menu" aria-labelledby="bookingDropdown"> 
                                                        <li><a class="dropdown-item"><?= $total_today; ?> bookings today</a></li> 
                                                        <li><a class="dropdown-item"><?= $total_week; ?> bookings this week</a></li> 
                                                        <li><a class="dropdown-item"><?= $total_month; ?> bookings this month</a></li> 
                                                        <li><a class="dropdown-item" href="../../booking/adminMoMamaMo/show_all.php?status=pending">Pending Bookings (<?= $total_pending; ?>)</a></li> 
                                                    </ul> 
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
                                                                    <canvas id="performanceLine" width=""></canvas>
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
                                                                    <div class="col-lg-6 d-flex justify-content-center align-items-center"> 
                                                                        <div class="circle-progress-width"> 
                                                                            <!-- Canvas for chart --> 
                                                                            <canvas id="ratingDistribution"></canvas> 
                                                                        </div> 
                                                                    </div> 
                                                                    <div class="col-lg-6"> 
                                                                        <div> 
                                                                            <p class="text-small mb-2">Rating Distribution (1 to 5 stars)</p> 
                                                                            <h4 class="mb-0 fw-bold"> 
                                                                                <?php echo $total_ratings . " Total Ratings"; ?> 
                                                                            </h4> 
                                                                            <p> </p> 
                                                                            <p class="text-small">1 star: <?= $poor; ?></p> 
                                                                            <p class="text-small">2 stars: <?= $unsatisfactory; ?></p> 
                                                                            <p class="text-small">3 stars: <?= $neutral; ?></p> 
                                                                            <p class="text-small">4 stars: <?= $satisfactory; ?></p> 
                                                                            <p class="text-small">5 stars: <?= $very_satisfactory; ?></p> 
                                                                        </div> 
                                                                    </div> 
                                                                </div> 
                                                            </div> 
                                                        </div> 
                                                    </div>
                                                </div>
                                                </div>   </div> 
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

document.addEventListener("DOMContentLoaded", function() {
        // Get total ratings
        var totalRatings = <?php echo $total_ratings; ?>;
        var ratingsData = [
            {label: '1 Star', value: <?php echo $poor; ?>, color: '#ff4136'}, // Poor (1 Star)
            {label: '2 Stars', value: <?php echo $unsatisfactory; ?>, color: '#ff851b'}, // Unsatisfactory (2 Stars)
            {label: '3 Stars', value: <?php echo $neutral; ?>, color: '#ffdc00'}, // Neutral (3 Stars)
            {label: '4 Stars', value: <?php echo $satisfactory; ?>, color: '#2ecc40'}, // Satisfactory (4 Stars)
            {label: '5 Stars', value: <?php echo $very_satisfactory; ?>, color: '#0074d9'} // Very Satisfactory (5 Stars)
        ];

        // Generate a chart or circle progress
        var ctx = document.getElementById('ratingDistribution').getContext('2d');
        new Chart(ctx, {
            type: 'pie', // Change to 'pie' for a pie chart
            data: {
                labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                datasets: [{
                    data: ratingsData.map(function(rating) { return rating.value; }),
                    backgroundColor: ratingsData.map(function(rating) { return rating.color; }),
                    borderColor: ratingsData.map(function(rating) { return rating.color; }),
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
                                var percentage = Math.round((tooltipItem.raw / totalRatings) * 100);
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' (' + percentage + '%)';
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