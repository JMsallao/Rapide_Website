<?php
session_start();
include('../../connection.php');

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

// Admin ID
$admin_id = $_SESSION['id'];


// Query to fetch distinct users that the admin has chatted with, including their profile picture
$query = "SELECT DISTINCT u.id, u.username, u.pic 
          FROM users u 
          JOIN message m ON u.id = m.sender OR u.id = m.recipient 
          WHERE (m.sender = ? OR m.recipient = ?) AND u.is_admin = 0"; // Exclude admin

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $admin_id, $admin_id);
$stmt->execute();
$result = $stmt->get_result();
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
    body {
        background-color: #f5f6fa;
        margin: 0;
        padding: 0;
    }



    .chat-list {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .chat-item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-bottom: 1px solid #f1f1f1;
        transition: background-color 0.2s ease-in-out;

    }

    .chat-item:last-child {
        border-bottom: none;
    }

    .chat-item:hover {
        background-color: #f0f0f5;
        cursor: pointer;
    }

    .profile-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
        border: 2px solid #ccc;
    }

    .chat-details {
        flex: 1;
        overflow: hidden;
    }

    .chat-username {
        font-weight: bold;
        font-size: 16px;
        color: #333;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-preview {
        font-size: 14px;
        color: #888;
        margin: 5px 0 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-time {
        font-size: 12px;
        color: #999;
    }

    .no-users {
        text-align: center;
        padding: 20px;
        font-size: 16px;
        color: #888;
    }

    .chat-link {
        text-decoration: none;
    }

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
                    <a class="navbar-brand brand-logo" href="Admin-Homepage.php">
                        <h2>Rapide</h2>
                    </a>
                    <a class="navbar-brand brand-logo-mini" href="Admin-Homepage.php">
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
                        <a class="nav-link" href="../login/login.php">
                            <i class="mdi mdi-logout user"></i> <!-- Changed the icon to mdi-logout -->
                            <span class="menu-title">Sign Out</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- partial -->
            <div class="main-panel">







                <div class="main-panel">
                    <div class="container">
                        <h2 class="text-center mb-4">Inbox</h2>
                        <div class="chat-list">
                        <?php
                            // Display users who have chatted with the admin
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $userId = $row['id'];
                                    $username = htmlspecialchars($row['username']); 

                                    // Fetch user profile picture from the result
                                    $userPic = !empty($row['pic']) ? '../../users/' . htmlspecialchars($row['pic'], ENT_QUOTES, 'UTF-8') : '../../images/default-user.png';

                                    // Fetch the latest message for this user
                                    $latestMessageQuery = "SELECT message, created_at FROM message 
                                                        WHERE (sender = ? AND recipient = ?) OR (sender = ? AND recipient = ?) 
                                                        ORDER BY created_at DESC LIMIT 1";
                                    $stmtMessage = $conn->prepare($latestMessageQuery);
                                    $stmtMessage->bind_param("iiii", $admin_id, $userId, $userId, $admin_id);
                                    $stmtMessage->execute();
                                    $latestMessageResult = $stmtMessage->get_result();
                                    $latestMessage = $latestMessageResult->fetch_assoc();

                                    $messagePreview = $latestMessage ? htmlspecialchars($latestMessage['message']) : "No messages yet.";
                                    $messageTime = $latestMessage ? date("H:i", strtotime($latestMessage['created_at'])) : "";
                                    
                                    echo "
                                    <a href='../message/chatbox.php?user_id={$userId}' class='chat-link'>
                                        <div class='chat-item'>
                                            <img src='{$userPic}' alt='Profile' class='profile-img'> <!-- User's profile picture -->
                                            <div class='chat-details'>
                                                <p class='chat-username'>{$username}</p>
                                                <p class='chat-preview'>{$messagePreview}</p>
                                            </div>
                                            <span class='chat-time'>{$messageTime}</span>
                                        </div>
                                    </a>
                                    ";

                                    $stmtMessage->close();
                                }
                            } else {
                                echo "<div class='no-users'>No users to display.</div>";
                            }
                            ?>

                        </div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
                </div>
                <!-- page-body-wrapper ends -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- endinject -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->


    <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <!-- End custom js for this page-->

    <!-- Plugin js for this page -->
    <script src="assets/vendors/chart.js/chart.umd.js"></script>
    <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->

    <!-- Custom js for this page-->
    <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="assets/js/dashboard.js"></script>
    <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
    <!-- End custom js for this page-->
</body>

</html>