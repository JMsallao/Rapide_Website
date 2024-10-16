<?php
session_start();
require_once "../connection.php";
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
                        <a href="#" class="sidebar-link">
                            <i class="fa-solid fa-message pe-2"></i>
                            Inbox
                        </a>
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
                    </div>
                <div class="card p-2 border-0">
                        <div class="card-header">
                            <h5 class="card-title">
                            </h5>
                            <h6 class="card-subtitle text-muted">
                                User Accounts
                            </h6>
                        </div>
                        <div class="card-body scrollable-table">                          
                              <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Date Join</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                <?php

                $sql_account = "SELECT account_id, name, contact_no, email, address, created_at 
                                FROM account 
                                WHERE is_admin != 1 AND is_deleted = 0"; // Filter out deleted accounts
                
                $result_account = $conn->query($sql_account);
                
                if ($result_account->num_rows > 0) {
                    // Loop through each row and display the account details
                    while ($row = $result_account->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?php echo $row["account_id"]; ?></td>
                            <td><?php echo $row["name"]; ?></td>
                            <td><?php echo $row["contact_no"]; ?></td>
                            <td><?php echo $row["email"]; ?></td>
                            <td><?php echo $row["address"]; ?></td>
                            <td><?php echo $row["created_at"]; ?></td>
                            <td>
                            <a href="db-users-manage-delete.php?account_id=<?php echo $row["account_id"]; ?>" class="link-dark" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fa-solid fa-trash fs-5"></i></a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='7'>No accounts found.</td></tr>";
                }
                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
    <script src="../js/dashboard.js"></script>
    <script src="../js/switch-toggle.js"></script>
    <script src="db-notif.js"></script>

</body>

</html>