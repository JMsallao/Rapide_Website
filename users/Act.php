<?php
    // Include necessary files
    include('../header.php');
    include('../connection.php');
    include('../sessioncheck.php');
    include('home_content.php');

    // Query to get package services from the database
    $sql_package = "SELECT * FROM package_list";
    $result_package = $conn->query($sql_package);

    $sql_about = "SELECT * FROM about";
    $result_about = $conn->query($sql_about);

    // Ensure the session user ID is set correctly
    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
    } else {
        die("User ID is not set in the session.");
    }

    // Fetch user data from the database securely using prepared statements
    $username = "Guest"; // Default username for fallback

    $query = "SELECT username FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $user_id); // Bind the user ID to the query
        $stmt->execute(); // Execute the query
        $result = $stmt->get_result(); // Get the result of the query

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $username = $row['username']; // Assign the username
        } else {
            // Handle case where user data is not found
            $username = "Guest"; 
        }
        $stmt->close(); // Close the statement
    } else {
        die("Failed to prepare the database query.");
    }

    // Fetch emergency data
    $emergency_query = "SELECT emergency_ID, emergency_type, car_type, contact, location, userLat, userLng, withinRadius, created_at FROM emergencies WHERE user_id = ?";
    $emergency_stmt = $conn->prepare($emergency_query);
    $emergency_stmt->bind_param("i", $user_id);
    $emergency_stmt->execute();
    $emergency_result = $emergency_stmt->get_result();

    // Fetch booking data
    $booking_query = "SELECT booking_id, booking_date, status, total_price, service_type FROM bookings WHERE user_id = ?";
    $booking_stmt = $conn->prepare($booking_query);
    $booking_stmt->bind_param("i", $user_id);
    $booking_stmt->execute();
    $booking_result = $booking_stmt->get_result();

?>

<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="Site keywords here">
    <meta name="description" content="">
    <meta name='copyright' content=''>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title>Rapide</title>

    <!-- Favicon -->
    <link rel="icon" href="img/favicon.png">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="css/nice-select.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- icofont CSS -->
    <link rel="stylesheet" href="css/icofont.css">
    <!-- Slicknav -->
    <link rel="stylesheet" href="css/slicknav.min.css">
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="css/owl-carousel.css">
    <!-- Datepicker CSS -->
    <link rel="stylesheet" href="css/datepicker.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="css/animate.min.css">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/normalizeee.css">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="css/responsive.css">

    <style>
    body>header>div.header-inner>div>div>div>div.col-lg-3.col-md-3.col-12>div.logo {
        margin-top: -35px;
        width: 150px;
        position: absolute;
    }

    input,
    select,
    button {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        box-sizing: border-box;
    }
    </style>
</head>

<body>

    <!-- Preloader -->
    <div class="preloader">
        <div class="loader">
            <div class="loader-outter"></div>
            <div class="loader-inner"></div>
            <div class="indicator">
                <svg width="16px" height="12px">
                </svg>
            </div>
        </div>
    </div>
    <!-- End Preloader -->


    <!-- Header Area -->
    <header class="header">
        <!-- Topbar -->
        <div class="topbar">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-5 col-12">
                    </div>
                    <div class="col-lg-6 col-md-7 col-12">
                        <!-- Top Contact -->
                        <ul class="top-contact">
                            <li><i class="fa fa-phone"></i>0966 061 9979 (Globe)
                            </li>
                            <li><i class="fa fa-envelope"></i><a href="https://www.facebook.com/RapideAutoServicePH">Fb:
                                    Rapide</a></li>
                        </ul>
                        <!-- End Top Contact -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Topbar -->
        <!-- Header Inner -->
        <div class="header-inner">
            <div class="container">
                <div class="inner">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-12">
                            <!-- Start Logo -->
                            <div class="logo">
                                <img src="../images\Rapide.png" alt="">
                                <!-- <a href="index.html"><img src="" alt="Rapide"></a> -->
                            </div>
                            <!-- End Logo -->
                            <!-- Mobile Nav -->
                            <div class="mobile-nav"></div>
                            <!-- End Mobile Nav -->
                        </div>
                        <div class="col-lg-7 col-md-9 col-12">
                            <!-- Main Menu -->
                            <div class="main-menu">
                                <nav class="navigation">
                                    <ul class="nav menu">
                                        <li ><a href="Homepage.php">Home</a>
                                        </li>
                                        <!-- <li><a href="#">Doctos </a></li> -->
                                        <li><a href="../booking/customerAlwaysRight/service_list.php">Services </a></li>
                                        <li><a href="#">Map <i class="icofont-rounded-down"></i></a>
                                            <ul class="dropdown">
                                                <li><a href="../map/gmap.php">Rapide Cavite Map</a></li>
                                                <li><a href="../map/emergency_form.php">Emergency Map</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Chat <i class="icofont-rounded-down"></i></a>
                                        <ul class="dropdown">
                                                <?php
                                                // Fetch all branches from the users table where is_admin = 1 (assuming branches are admins)
                                                $branch_query = "SELECT id, fname, lname FROM users WHERE is_admin = 1";
                                                $branch_result = $conn->query($branch_query);

                                                if ($branch_result && $branch_result->num_rows > 0):
                                                    while ($branch = $branch_result->fetch_assoc()):
                                                        $branch_name = $branch['fname'] . ' ' . $branch['lname'];
                                                        ?>
                                                        <li>
                                                            <a href="../message_kineme/user_ansya/chat_kinems.php?branch_id=<?php echo $branch['id']; ?>">
                                                                <?php echo htmlspecialchars($branch_name); ?>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    endwhile;
                                                else:
                                                    ?>
                                                    <li><a href="#">No branches available</a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </li>
                                        <li class="active"><a href="Act.php">Activites</a></li>

                                    </ul>
                                    
                                </nav>
                            </div>
                            <!--/ End Main Menu -->
                        </div>
                        <div class="col-lg-2 col-12 mt-3">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo htmlspecialchars($username); ?>
                                    <!-- Display the username -->
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="../login/profile_setup.php">Profile</a></li>
                                    <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                                    <li><a class="dropdown-item" href="../login\logout.php">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Header Inner -->
    </header>
    <!-- End Header Area -->

    <div class="container mt-5">
        <h2 class="text-center mb-4">Activity History</h2>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="historyTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="emergency-tab" data-bs-toggle="tab" href="#emergency" role="tab" aria-controls="emergency" aria-selected="true">Emergency History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="booking-tab" data-bs-toggle="tab" href="#booking" role="tab" aria-controls="booking" aria-selected="false">Booking History</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-4" id="historyTabsContent">
            <!-- Emergency Tab -->
            <div class="tab-pane fade show active" id="emergency" role="tabpanel" aria-labelledby="emergency-tab">
                <h4>Emergency Requests</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Emergency ID</th>
                            <th>Type</th>
                            <th>Car Type</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Within Radius</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        // Fetch emergency data
                        $emergency_query = "SELECT * FROM emergencies WHERE user_id = ?";
                        $emergency_stmt = $conn->prepare($emergency_query);
                        $emergency_stmt->bind_param("i", $user_id);
                        $emergency_stmt->execute();
                        $emergency_result = $emergency_stmt->get_result();

                        while ($row = $emergency_result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['emergency_ID']}</td>
                                <td>{$row['emergency_type']}</td>
                                <td>{$row['car_type']}</td>
                                <td>{$row['contact']}</td>
                                <td>{$row['location']}</td>
                                <td>{$row['userLat']}</td>
                                <td>{$row['userLng']}</td>
                                <td>{$row['withinRadius']}</td>
                                <td>{$row['created_at']}</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Booking Tab -->
            <div class="tab-pane fade" id="booking" role="tabpanel" aria-labelledby="booking-tab">
                <h4>Booking History</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Booking Date</th>
                            <th>Status</th>
                            <th>Total Price</th>
                            <th>Service Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch booking data
                        $booking_query = "SELECT * FROM bookings WHERE user_id = ?";
                        $booking_stmt = $conn->prepare($booking_query);
                        $booking_stmt->bind_param("i", $user_id);
                        $booking_stmt->execute();
                        $booking_result = $booking_stmt->get_result();

                        while ($row = $booking_result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['booking_id']}</td>
                                <td>{$row['booking_date']}</td>
                                <td>{$row['status']}</td>
                                <td>₱" . number_format($row['total_price'], 2) . "</td>
                                <td>{$row['service_type']}</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Footer Area -->
    <footer id="footer" class="footer ">
        <!-- Footer Top -->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-footer">
                            <h2>About Us</h2>
                            <p>CASA-quality services at affordable prices.</p>
                            <!-- Social -->
                            <ul class="social">
                                <li><a href="https://www.facebook.com/RapideKawitCavite"><i
                                            class="icofont-facebook"></i></a></li>
                                <!-- <li><a href="#"><i class="icofont-twitter"></i></a></li>
                                <li><a href="#"><i class="icofont-vimeo"></i></a></li>
                                <li><a href="#"><i class="icofont-pinterest"></i></a></li> -->
                            </ul>
                            <!-- End Social -->
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-footer">
                            <h2>Open Hours</h2>
                            <p>Lorem ipsum dolor sit ame consectetur adipisicing elit do eiusmod tempor incididunt.</p>
                            <ul class="time-sidual">
                                <li class="day">Monday - Fridayp <span>8.00-20.00</span></li>
                                <li class="day">Saturday <span>9.00-18.30</span></li>
                                <li class="day">Monday - Thusday <span>9.00-15.00</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-footer">
                            <h2>Support & Help</h2>
                            <p>0966 061 9979 (Globe)</p>
                            <p>0919 269 4103 (Smart)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Footer Top -->
        <!-- Copyright -->
        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="copyright-content">
                            <p>© Copyright 2018 | All Rights Reserved by <a href="https://rapide.ph/about-us"
                                    target="_blank">Rapide.ph</a> </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Copyright -->
    </footer>
    <!--/ End Footer Area -->

    <!-- jquery Min JS -->
    <script src="js/jquery.min.js"></script>
    <!-- jquery Migrate JS -->
    <script src="js/jquery-migrate-3.0.0.js"></script>
    <!-- jquery Ui JS -->
    <script src="js/jquery-ui.min.js"></script>
    <!-- Easing JS -->
    <script src="js/easing.js"></script>
    <!-- Color JS -->
    <script src="js/colors.js"></script>
    <!-- Popper JS -->
    <script src="js/popper.min.js"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="js/bootstrap-datepicker.js"></script>
    <!-- Jquery Nav JS -->
    <script src="js/jquery.nav.js"></script>
    <!-- Slicknav JS -->
    <script src="js/slicknav.min.js"></script>
    <!-- ScrollUp JS -->
    <script src="js/jquery.scrollUp.min.js"></script>
    <!-- Niceselect JS -->
    <script src="js/niceselect.js"></script>
    <!-- Tilt Jquery JS -->
    <script src="js/tilt.jquery.min.js"></script>
    <!-- Owl Carousel JS -->
    <script src="js/owl-carousel.js"></script>
    <!-- counterup JS -->
    <script src="js/jquery.counterup.min.js"></script>
    <!-- Steller JS -->
    <script src="js/steller.js"></script>
    <!-- Wow JS -->
    <script src="js/wow.min.js"></script>
    <!-- Magnific Popup JS -->
    <script src="js/jquery.magnific-popup.min.js"></script>
    <!-- Counter Up CDN JS -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>
</body>

</html><?php
    // Include necessary files
    include('../header.php');
    include('../connection.php');
    include('../sessioncheck.php');
    include('home_content.php');

    // Query to get package services from the database
    $sql_package = "SELECT * FROM package_list";
    $result_package = $conn->query($sql_package);

    $sql_about = "SELECT * FROM about";
    $result_about = $conn->query($sql_about);

    // Ensure the session user ID is set correctly
    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
    } else {
        die("User ID is not set in the session.");
    }

    // Fetch user data from the database securely using prepared statements
    $username = "Guest"; // Default username for fallback

    $query = "SELECT username FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $user_id); // Bind the user ID to the query
        $stmt->execute(); // Execute the query
        $result = $stmt->get_result(); // Get the result of the query

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $username = $row['username']; // Assign the username
        } else {
            // Handle case where user data is not found
            $username = "Guest"; 
        }
        $stmt->close(); // Close the statement
    } else {
        die("Failed to prepare the database query.");
    }

    // Fetch emergency data
    $emergency_query = "SELECT emergency_ID, emergency_type, car_type, contact, location, userLat, userLng, withinRadius, created_at FROM emergencies WHERE user_id = ?";
    $emergency_stmt = $conn->prepare($emergency_query);
    $emergency_stmt->bind_param("i", $user_id);
    $emergency_stmt->execute();
    $emergency_result = $emergency_stmt->get_result();

    // Fetch booking data
    $booking_query = "SELECT booking_id, booking_date, status, total_price, service_type FROM bookings WHERE user_id = ?";
    $booking_stmt = $conn->prepare($booking_query);
    $booking_stmt->bind_param("i", $user_id);
    $booking_stmt->execute();
    $booking_result = $booking_stmt->get_result();

?>

<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="Site keywords here">
    <meta name="description" content="">
    <meta name='copyright' content=''>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title>Rapide</title>

    <!-- Favicon -->
    <link rel="icon" href="img/favicon.png">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="css/nice-select.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- icofont CSS -->
    <link rel="stylesheet" href="css/icofont.css">
    <!-- Slicknav -->
    <link rel="stylesheet" href="css/slicknav.min.css">
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="css/owl-carousel.css">
    <!-- Datepicker CSS -->
    <link rel="stylesheet" href="css/datepicker.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="css/animate.min.css">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/normalizeee.css">
    <link rel="stylesheet" href="style3.css">
    <link rel="stylesheet" href="css/responsive.css">

    <style>
    body>header>div.header-inner>div>div>div>div.col-lg-3.col-md-3.col-12>div.logo {
        margin-top: -35px;
        width: 150px;
        position: absolute;
    }

    input,
    select,
    button {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        box-sizing: border-box;
    }
    </style>
</head>

<body>

    <!-- Preloader -->
    <div class="preloader">
        <div class="loader">
            <div class="loader-outter"></div>
            <div class="loader-inner"></div>
            <div class="indicator">
                <svg width="16px" height="12px">
                </svg>
            </div>
        </div>
    </div>
    <!-- End Preloader -->


    <!-- Header Area -->
    <header class="header">
        <!-- Topbar -->
        <div class="topbar">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-5 col-12">
                    </div>
                    <div class="col-lg-6 col-md-7 col-12">
                        <!-- Top Contact -->
                        <ul class="top-contact">
                            <li><i class="fa fa-phone"></i>0966 061 9979 (Globe)
                            </li>
                            <li><i class="fa fa-envelope"></i><a href="https://www.facebook.com/RapideAutoServicePH">Fb:
                                    Rapide</a></li>
                        </ul>
                        <!-- End Top Contact -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Topbar -->
        <!-- Header Inner -->
        <div class="header-inner">
            <div class="container">
                <div class="inner">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-12">
                            <!-- Start Logo -->
                            <div class="logo">
                                <img src="../images\Rapide.png" alt="">
                                <!-- <a href="index.html"><img src="" alt="Rapide"></a> -->
                            </div>
                            <!-- End Logo -->
                            <!-- Mobile Nav -->
                            <div class="mobile-nav"></div>
                            <!-- End Mobile Nav -->
                        </div>
                        <div class="col-lg-7 col-md-9 col-12">
                            <!-- Main Menu -->
                            <div class="main-menu">
                                <nav class="navigation">
                                    <ul class="nav menu">
                                        <li ><a href="Homepage.php">Home</a>
                                        </li>
                                        <!-- <li><a href="#">Doctos </a></li> -->
                                        <li><a href="../booking/customerAlwaysRight/service_list.php">Services </a></li>
                                        <li><a href="#">Map <i class="icofont-rounded-down"></i></a>
                                            <ul class="dropdown">
                                                <li><a href="../map/gmap.php">Rapide Cavite Map</a></li>
                                                <li><a href="../map/emergency_form.php">Emergency Map</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Chat <i class="icofont-rounded-down"></i></a>
                                        <ul class="dropdown">
                                                <?php
                                                // Fetch all branches from the users table where is_admin = 1 (assuming branches are admins)
                                                $branch_query = "SELECT id, fname, lname FROM users WHERE is_admin = 1";
                                                $branch_result = $conn->query($branch_query);

                                                if ($branch_result && $branch_result->num_rows > 0):
                                                    while ($branch = $branch_result->fetch_assoc()):
                                                        $branch_name = $branch['fname'] . ' ' . $branch['lname'];
                                                        ?>
                                                        <li>
                                                            <a href="../message_kineme/user_ansya/chat_kinems.php?branch_id=<?php echo $branch['id']; ?>">
                                                                <?php echo htmlspecialchars($branch_name); ?>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    endwhile;
                                                else:
                                                    ?>
                                                    <li><a href="#">No branches available</a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </li>
                                        <li class="active"><a href="Act.php">Activites</a></li>

                                    </ul>
                                    
                                </nav>
                            </div>
                            <!--/ End Main Menu -->
                        </div>
                        <div class="col-lg-2 col-12 mt-3">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo htmlspecialchars($username); ?>
                                    <!-- Display the username -->
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="../login/profile_setup.php">Profile</a></li>
                                    <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                                    <li><a class="dropdown-item" href="../login\logout.php">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Header Inner -->
    </header>
    <!-- End Header Area -->

    <div class="container mt-5">
        <h2 class="text-center mb-4">Activity History</h2>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="historyTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="emergency-tab" data-bs-toggle="tab" href="#emergency" role="tab" aria-controls="emergency" aria-selected="true">Emergency History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="booking-tab" data-bs-toggle="tab" href="#booking" role="tab" aria-controls="booking" aria-selected="false">Booking History</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-4" id="historyTabsContent">
            <!-- Emergency Tab -->
           <!-- Emergency Tab -->
<div class="tab-pane fade show active" id="emergency" role="tabpanel" aria-labelledby="emergency-tab">
    <h4>Emergency Requests</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Emergency ID</th>
                <th>Type</th>
                <th>Car Type</th>
                <th>Contact</th>
                <th>Location</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Within Radius</th>
                <th>Date</th>
                <th>Action</th> <!-- Added Action column -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch emergency data
            $emergency_query = "SELECT * FROM emergencies WHERE user_id = ?";
            $emergency_stmt = $conn->prepare($emergency_query);
            $emergency_stmt->bind_param("i", $user_id);
            $emergency_stmt->execute();
            $emergency_result = $emergency_stmt->get_result();

            while ($row = $emergency_result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['emergency_ID']}</td>
                    <td>{$row['emergency_type']}</td>
                    <td>{$row['car_type']}</td>
                    <td>{$row['contact']}</td>
                    <td>{$row['location']}</td>
                    <td>{$row['userLat']}</td>
                    <td>{$row['userLng']}</td>
                    <td>{$row['withinRadius']}</td>
                    <td>{$row['created_at']}</td>
                    <td>
                        <a href='emergency_details.php?emergency_id={$row['emergency_ID']}' class='btn btn-primary'>View Details</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Booking Tab -->
<div class="tab-pane fade" id="booking" role="tabpanel" aria-labelledby="booking-tab">
    <h4>Booking History</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th>Total Price</th>
                <th>Service Type</th>
                <th>Action</th> <!-- Added Action column -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch booking data
            $booking_query = "SELECT * FROM bookings WHERE user_id = ?";
            $booking_stmt = $conn->prepare($booking_query);
            $booking_stmt->bind_param("i", $user_id);
            $booking_stmt->execute();
            $booking_result = $booking_stmt->get_result();

            while ($row = $booking_result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['booking_id']}</td>
                    <td>{$row['booking_date']}</td>
                    <td>{$row['status']}</td>
                    <td>₱" . number_format($row['total_price'], 2) . "</td>
                    <td>{$row['service_type']}</td>
                    <td>
                        <a href='booking_details.php?booking_id={$row['booking_id']}' class='btn btn-primary'>View Details</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Footer Area -->
    <footer id="footer" class="footer ">
        <!-- Footer Top -->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-footer">
                            <h2>About Us</h2>
                            <p>CASA-quality services at affordable prices.</p>
                            <!-- Social -->
                            <ul class="social">
                                <li><a href="https://www.facebook.com/RapideKawitCavite"><i
                                            class="icofont-facebook"></i></a></li>
                                <!-- <li><a href="#"><i class="icofont-twitter"></i></a></li>
                                <li><a href="#"><i class="icofont-vimeo"></i></a></li>
                                <li><a href="#"><i class="icofont-pinterest"></i></a></li> -->
                            </ul>
                            <!-- End Social -->
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-footer">
                            <h2>Open Hours</h2>
                            <p>Lorem ipsum dolor sit ame consectetur adipisicing elit do eiusmod tempor incididunt.</p>
                            <ul class="time-sidual">
                                <li class="day">Monday - Fridayp <span>8.00-20.00</span></li>
                                <li class="day">Saturday <span>9.00-18.30</span></li>
                                <li class="day">Monday - Thusday <span>9.00-15.00</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-footer">
                            <h2>Support & Help</h2>
                            <p>0966 061 9979 (Globe)</p>
                            <p>0919 269 4103 (Smart)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Footer Top -->
        <!-- Copyright -->
        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="copyright-content">
                            <p>© Copyright 2018 | All Rights Reserved by <a href="https://rapide.ph/about-us"
                                    target="_blank">Rapide.ph</a> </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Copyright -->
    </footer>
    <!--/ End Footer Area -->

    <!-- jquery Min JS -->
    <script src="js/jquery.min.js"></script>
    <!-- jquery Migrate JS -->
    <script src="js/jquery-migrate-3.0.0.js"></script>
    <!-- jquery Ui JS -->
    <script src="js/jquery-ui.min.js"></script>
    <!-- Easing JS -->
    <script src="js/easing.js"></script>
    <!-- Color JS -->
    <script src="js/colors.js"></script>
    <!-- Popper JS -->
    <script src="js/popper.min.js"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="js/bootstrap-datepicker.js"></script>
    <!-- Jquery Nav JS -->
    <script src="js/jquery.nav.js"></script>
    <!-- Slicknav JS -->
    <script src="js/slicknav.min.js"></script>
    <!-- ScrollUp JS -->
    <script src="js/jquery.scrollUp.min.js"></script>
    <!-- Niceselect JS -->
    <script src="js/niceselect.js"></script>
    <!-- Tilt Jquery JS -->
    <script src="js/tilt.jquery.min.js"></script>
    <!-- Owl Carousel JS -->
    <script src="js/owl-carousel.js"></script>
    <!-- counterup JS -->
    <script src="js/jquery.counterup.min.js"></script>
    <!-- Steller JS -->
    <script src="js/steller.js"></script>
    <!-- Wow JS -->
    <script src="js/wow.min.js"></script>
    <!-- Magnific Popup JS -->
    <script src="js/jquery.magnific-popup.min.js"></script>
    <!-- Counter Up CDN JS -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>
</body>

</html>