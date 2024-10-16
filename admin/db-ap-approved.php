<?php
session_start();
require_once "../connection.php";

// Check if the user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    echo "<script>alert('Access denied!'); window.location.href = '../home/login-form.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $book_id = $_POST['book_id'];
    $action = $_POST['action'];

    if ($action === 'done') {
        $sql_update = "UPDATE booking SET status = 'done' WHERE booking_id = ?";
    } elseif ($action === 'rate') {
        $sql_update = "UPDATE booking SET status = 'rate' WHERE booking_id = ?";
    }

    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param('i', $book_id);
    if ($stmt->execute()) {
        echo "<script>alert('Booking status updated.'); window.location.href = 'db-ap-approved.php';</script>";
    } else {
        echo "<script>alert('Error updating booking status.');</script>";
    }
    $stmt->close();
}

// Fetch bookings
$sql = "SELECT * FROM booking WHERE status IN ('approved', 'done') ORDER BY booking_id DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../img/logo.png"/>
    <link rel="stylesheet" href="../css/admin/db-notif.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/admin/db-no-content.css">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar" class="js-sidebar">
            <!-- Content For Sidebar -->
            <div class="h-100">
                <div class="sidebar-logo"><img src="../img/logo-header.png" width="70px" height="auto" alt="Icon">
                    <a href="dashboard.html" class="logo">A.C Tech</a></div>
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
                                <a href="#" class="sidebar-link collapsed" data-bs-target="#bookings" data-bs-toggle="collapse"
                                aria-expanded="false"><i class="fa-solid fa-bookmark pe-2"></i>
                                List of Bookings
                                </a>
                                <ul id="bookings" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#pages">
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
                    <div class="mb-3">
                        <h4>Appointment</h4>
                    </div>

                    <div class="card p-2 border-0">
                        <div class="card-header">
                            <h5 class="card-title">
                            </h5>
                            <h6 class="card-subtitle text-muted">
                                Approved Bookings
                            </h6>
                        </div>
                        <div class="card-body scrollable-table">                       
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Book ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Service</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <style>
                                .btn-primaryr{
                                    margin-bottom: 10px;
                                    width: 60px;
                                    background-color: #1A5F6B;
                                    font-size: 12px;
                                    border: none;
                                    height: 30px;
                                    color: white;
                                }
                                .btn-primaryd{
                                    margin-bottom: 10px;
                                    width: 900px;
                                    background-color: #1A5F6B;
                                    font-size: 12px;
                                    border: none;
                                    height: 30px;
                                    color: white;
                                }
                                .btn-sm{
                                    width: 120px;
                                    background-color: #1A5F6B;
                                    font-size: 12px;
                                    border: none;
                                    height: 30px;
                                }
                                .btn-primarye{
                                    width: 30px;
                                    height: 30px;
                                    color: white;
                                    margin-bottom: 10px;
                                }
                                .btn-primaryd:hover, .btn-primarye:hover, .btn-primaryr:hover {
                                    background-color: #0056b3; 
                                    color: white; 
                                }
                            </style>

                                <tbody>
                                <?php while($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['booking_id']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['city']; ?></td>
                                        <td><?php echo $row['date']; ?></td>
                                        <td><?php echo $row['time']; ?></td>
                                        <td><?php echo $row['service']; ?></td>
                                        <td>
                                            <?php if ($row['status'] == 'done') { ?>
                                                <span class="badge" style="background-color:#4ad12e;">Done</span>
                                            <?php } else { ?>
                                                <span class="badge" style="background-color:#4ad12e;">Approved</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($row['status'] == 'done') { ?>
                                                <button class="btn btn-sm btn-secondary" disabled>Service Done</button>
                                            <?php } else { ?>
                                                <form action="" method="post">
                                                    <input type="hidden" name="book_id" value="<?php echo $row['booking_id']; ?>">
                                                    <button type="submit" name="action" value="rate" class="btn btn-primaryr" onclick="return confirm('Allow user to rate?');">Rate</button>
                                                    <button type="submit" name="action" value="done" class="btn btn-sm btn-primaryd" onclick="return confirm('Are you sure the service is done?');">Service Done</button>
                                                </form>
                                                <form action="ap-done-service-message.php" method="post">
                                                <input type="hidden" name="book_id" value="<?php echo $row['booking_id']; ?>">
                                                <button type="submit" name="action" value="done" class="btn btn-sm btn-primarye" onclick="return confirm('Sent email?');">@</button>
                                                </form>
                                                <?php } ?>

                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                        $conn->close();
                    ?>
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
