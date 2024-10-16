<?php
session_start();
include '../connection.php'; // Adjust the path to your connection file

// Check if user is logged in
if(isset($_SESSION['account_id'])) {
    $account_id = $_SESSION['account_id'];

    // Fetch user details from database
    $stmt = $conn->prepare("SELECT * FROM account WHERE account_id = ?");
    $stmt->bind_param("i", $account_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        // Handle case where user is not found
        $user = [
            'name' => '',
            'email' => '',
            'contact_no' => '',
            'address' => ''
        ];
    }
} else {
    // Redirect to login page or handle not logged in user
    header('Location: /login.php');
    exit();
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
<style>
    
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

:root {
    --bg-color-light: #c2c2c4;
    --text-color-light: #343a40;
    --bg-color-dark: #343a40;
    --text-color-dark: #e9ecef;
    --bg-con-ligt: #e4e4e6;
}

[data-bs-theme="light"] {
    --bg-color: var(--bg-color-light);
    --text-color: var(--text-color-light);

}

[data-bs-theme="dark"] {
    --bg-color: var(--bg-color-dark);
    --text-color: var(--text-color-dark);

}
    

.content .edit-profile-container {
    height: 240px;
    max-width: 450px;
    width: 100%;
    background-color: var(--bg-color);
    color: var(--text-color);
    display: flex;
    padding: 10px 30px;
    margin-bottom: 10px;
    display: flex;
    border-radius: 0px 0px 5px 5px;
}

.content .edit-profile-container-pass{
    height: 110px;
    max-width: 450px;
    width: 100%;
    background-color: var(--bg-color);
    color: var(--text-color);
    display: flex;
    padding: 10px 30px;
    margin-bottom: 10px;
    display: flex;
    border-radius: 0px 0px 5px 5px;
}

.edit-complete-info{
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    text-align: left;
    margin: 5px 15px;
}

.prof-edit{
    max-width: 450px;
    width: 100%;
    font-weight: 800;
    padding: 10px 10px;
    color: var(--text-color);
    border-radius: 5px 5px 0px 0px;
    background-color: var(--bg-color);
}

.prof-edit h1{
    background-color: rgba(26, 95, 107, 0.5);
    font-size: 16px;
    padding: 10px 15px;
    font-weight: 800;
}

.btn-primary-edit{
    margin-top: 15px;
    font-size: 12px;
    height: 40px;
    width: 150px;
    border: none;
    padding: 7px 17px;
    border-radius: 5px;
    cursor: pointer;
    color: #fff;
    background-color: #1A5F6B;
}

.btn-primary-edit:hover {
    background-color: #17545f;
    color: #e2e2e2; 
}

.btn-primary-update{
    margin-top: 15px;
    font-size: 12px;
    border: none;
    padding: 7px 23px;
    border-radius: 5px;
    cursor: pointer;
    color: #fff;
    background-color: #1A5F6B;
}

.btn-primary-update:hover {
    background-color: #17545f;
    color: #e2e2e2; 
}

.btn-secondary-cls{
    margin-top: 15px;
    font-size: 12px;
    border: none;
    padding: 7px 23px;
    border-radius: 5px;
    cursor: pointer;
    color: #fff;
    background-color: #627267;
}

.btn-secondary-cls:hover {
    background-color: #55695c;
    color: #e2e2e2; 
}

</style>
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

            <main class="content px-3 py-3">
        <div class="home-text px-4">
            <h4>Settings</h4>
        </div>
        <div class="container-fluid">
        <div class="prof-edit"><h1>Profile Information</h1></div>
        <div class="edit-profile-container">
        <div class="row mb-3">
            <div class="row mb-3">
            <div class="col-3">
                <strong>Name:</strong>
            </div>
                <div class="col">
                    <?php echo empty($user['name']) ? '<span style="color: #1A5F6B;">Add your name</span>' : htmlspecialchars($user['name']); ?>
                </div>
            </div>
            <div class="row mb-3">
            <div class="col-3">
                <strong>Email:</strong>
            </div>
                <div class="col">
                    <?php echo empty($user['email']) ? '<span style="color: #1A5F6B;">Add your email</span>' : htmlspecialchars($user['email']); ?>
                </div>
            </div>
            <div class="row mb-3">
            <div class="col-3">
                <strong>Phone:</strong>
            </div>
                <div class="col">
                    <?php echo empty($user['contact_no']) ? '<span style="color: #1A5F6B;">Add your phone number</span>' : htmlspecialchars($user['contact_no']); ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-3">
                    <strong>Address:</strong>
                </div>
            <div class="col">
                <?php echo empty($user['address']) ? '<span style="color: #1A5F6B;">Add your address</span>' : htmlspecialchars($user['address']); ?>
            </div>
        </div>
                <div class="row">
                    <button type="button" class="btn btn-primary-edit" data-bs-toggle="modal" data-bs-target="#editModal">
                        Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<main class="content px-3 py-3">
        <div class="container-fluid">
        <div class="prof-edit"><h1>Security</h1></div>
        <div class="edit-profile-container-pass">
        <div class="row mb-3">
            <div class="row">
            <div class="col-3">
                <strong>Password:</strong>
            </div>
                <div class="col px-5">
                <?php echo empty($user['password']) ? '<span style="color: #1A5F6B;">Add your password</span>' : str_repeat('*', $user['password_length']); ?>
                </div>
            </div>
            <div class="row">
                    <button type="button" class="btn btn-primary-edit" data-bs-toggle="modal" data-bs-target="#editModalPass">
                        Change Password?
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
    




        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editModalLabel">Personal Information</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form method="post" action="../clients/p-edit-prof.php">
                    <div class="mb-3">
                      <label for="name" class="form-label">Name</label>
                      <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>">
                    </div>
                    <div class="mb-3">
                      <label for="email" class="form-label">Email</label>
                      <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>">
                    </div>
                    <div class="mb-3">
                      <label for="contact_no" class="form-label">Contact Number</label>
                      <input type="text" class="form-control" id="contact_no" name="contact_no" value="<?php echo $user['contact_no']; ?>">
                    </div>
                    <div class="mb-3">
                      <label for="address" class="form-label">Address</label>
                      <input type="text" class="form-control" id="address" name="address" value="<?php echo $user['address']; ?>">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary-cls" data-bs-dismiss="modal">Close</button>
                      <button type="submit" name="submit" class="btn btn-primary-update">Save changes</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
  

          <div class="modal fade" id="editModalPass" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editModalLabel">Security</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form method="post" action="../clients/p-edit-pass.php">
                    <div class="mb-3">
                      <label for="password" class="form-label">Password</label>
                      <input type="password" class="form-control" id="password" name="password" value="<?php echo str_repeat('*', $user['password_length']); ?>">
                    </div>
                    <div class="mb-3">
                      <label for="password" class="form-label">Confirm Password</label>
                      <input type="password" class="form-control" id="repassword" name="repassword" value="<?php echo str_repeat('*', $user['password_length']); ?>">
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary-cls" data-bs-dismiss="modal">Close</button>
                      <button type="submit" name="submit" class="btn btn-primary-update">Save changes</button>
                    </div>
                  </form>
                </div>
              </div>
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
    <script src="../js/switch-toggle.js"></script>
    <script src="db-notif.js"></script>

</body>

</html>
