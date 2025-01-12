<?php
    // Include necessary files
    include('../header.php');
    include('../connection.php');
    include('../sessioncheck.php');
    include('home_content.php');
    include('Responsive.php');
    

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
    $emergency_query = "SELECT * FROM emergencies WHERE user_id = ?";
    $emergency_stmt = $conn->prepare($emergency_query);
    $emergency_stmt->bind_param("i", $user_id);
    $emergency_stmt->execute();
    $emergency_result = $emergency_stmt->get_result();

    // Fetch booking data
    $booking_query = "SELECT * FROM bookings WHERE user_id = ?";
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
    <title>Mediplus - Free Medical and Doctor Directory HTML Template.</title>

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

    /* General Styles for Tabs */
    .nav-tabs {
        display: flex;
        justify-content: start;
        margin-bottom: 20px;
        border-bottom: 2px solid #ddd;
    }

    .nav-tabs .nav-item {
        margin-right: 10px;
    }

    .nav-tabs .nav-link {
        font-size: 16px;
        font-weight: bold;
        color: #555;
        text-transform: uppercase;
        padding: 10px 20px;
        border: 2px solid transparent;
        border-radius: 5px;
        transition: all 0.3s ease-in-out;
        background-color: #f9f9f9;
    }

    .nav-tabs .nav-link.active {
        color: #fff;
        background-color: #fcbf17;
        border-color: #fcbf17;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .nav-tabs .nav-link:hover {
        color: #fcbf17;
        background-color: #fff;
        border-color: #fcbf17;
    }

    .nav-tabs .nav-link:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(252, 191, 23, 0.5);
    }

    .booking-card-content {
        text-align: start;
    }

    .emergency-card-content {
        text-align: start;
    }

    /* General Grid Styling */
    .emergency-grid,
    .booking-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    /* Card Styling */
    .emergency-cards {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-top: 20px;
    }

    .emergency-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
    }

    .emergency-card:hover {
        transform: scale(1.02);
    }

    .emergency-card-content h5 {
        margin-bottom: 10px;
        font-size: 18px;
        color: #333;
    }

    .emergency-card-content p {
        margin: 5px 0;
        font-size: 14px;
        color: #555;
    }

    .emergency-card-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
    }

    .btn-view-details {
        display: inline-block;
        background-color: #fcbf17;
        color: #fff;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .btn-view-details:hover {
        background-color: #e0a600;
    }


    .btn {
        text-align: center;
        display: inline-block;
    }

    .booking-cards {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-top: 20px;
    }

    .booking-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
    }

    .booking-card:hover {
        transform: scale(1.02);
    }

    .booking-card-content h5 {
        margin-bottom: 10px;
        font-size: 18px;
        color: #333;
    }

    .booking-card-content p {
        margin: 5px 0;
        font-size: 14px;
        color: #555;
    }

    .booking-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }

    .booking-card-footer .price {
        font-size: 16px;
        font-weight: bold;
        color: #555;
    }

    .btn-view-details {
        display: inline-block;
        background-color: #fcbf17;
        color: #fff;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .btn-view-details:hover {
        background-color: #e0a600;
    }

    .nav-link i,
    .emergency-card-content i,
    .booking-card-content i {
        margin-right: 8px;
        color: #fcbf17;
        /* Icon color */
    }

    .price i {
        color: #555;
        margin-right: 5px;
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
                            <li><i class="fa fa-car"></i><a href="https://gulong.ph/?utm_source=rapide.ph"> Buy
                                    Tires</a></li>
                            <li><i class="fa fa-phone"></i>0966 061 9979 (Globe)</li>
                            <li><i class="fa fa-facebook"></i><a href="https://www.facebook.com/RapideAutoServicePH">
                                    Fb: Rapide</a></li>
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
                                        <li><a href="Homepage.php">Home</a>
                                        </li>
                                        <!-- <li><a href="#">Doctos </a></li> -->
                                        <li><a href="booking/service_list.php">Services </a></li>
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
                                                    <a
                                                        href="message/chatbox.php?branch_id=<?php echo $branch['id']; ?>">
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
    <!-- Error Page -->
    <section class="error-page section">

        <div class="container">

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs" id="historyTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="emergency-tab" data-bs-toggle="tab" href="#emergency" role="tab"
                        aria-controls="emergency" aria-selected="true">
                        <i class="fa fa-ambulance" aria-hidden="true"></i> Emergency History
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="booking-tab" data-bs-toggle="tab" href="#booking" role="tab"
                        aria-controls="booking" aria-selected="false">
                        <i class="fa fa-calendar" aria-hidden="true"></i> Booking History
                    </a>
                </li>
            </ul>



            <!-- Tab Content -->
            <!-- Tab Content -->
            <div class="tab-content mt-4" id="historyTabsContent">
                <!-- Emergency Tab -->
                <div id="emergency" class="tab-pane fade show active" role="tabpanel" aria-labelledby="emergency-tab">
                    <h4 class="text-center">Emergency History</h4>
                    <div class="emergency-cards">
                        <?php while ($row = $emergency_result->fetch_assoc()): ?>
                        <div class="emergency-card">
                            <div class="emergency-card-content">
                                <h5><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Emergency Type:
                                    <?php echo htmlspecialchars($row['emergency_type']); ?></h5>
                                <p><i class="fa fa-car" aria-hidden="true"></i> <strong>Car Type:</strong>
                                    <?php echo htmlspecialchars($row['car_type']); ?></p>
                                <p><i class="fa fa-phone" aria-hidden="true"></i> <strong>Contact:</strong>
                                    <?php echo htmlspecialchars($row['contact']); ?></p>
                                <p><i class="fa fa-map-marker" aria-hidden="true"></i> <strong>Branch:</strong>
                                    <?php echo htmlspecialchars($row['location']); ?></p>
                                <p><i class="fa fa-ruler" aria-hidden="true"></i> <strong>Within Radius:</strong>
                                    <?php echo htmlspecialchars($row['withinRadius']); ?></p>
                                <p><i class="fa fa-calendar-alt" aria-hidden="true"></i> <strong>Date:</strong>
                                    <?php echo htmlspecialchars(date('F j, Y, h:i A', strtotime($row['created_at']))); ?>
                                </p>
                                <p><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Status:</strong>
                                    <?php echo htmlspecialchars(ucfirst($row['status'])); ?></p>
                            </div>

                            <div class="emergency-card-footer">
                                <a href="emergency_details.php?emergency_id=<?php echo $row['emergency_ID']; ?>"
                                    class="btn btn-view-details">View Details</a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Booking Tab -->
                <div id="booking" class="tab-pane fade" role="tabpanel" aria-labelledby="booking-tab">
                    <h4 class="text-center">Booking History</h4>
                    <div class="booking-cards">
                        <?php while ($row = $booking_result->fetch_assoc()): ?>
                        <div class="booking-card">
                            <div class="booking-card-content">
                                <h5><i class="fa fa-book" aria-hidden="true"></i>Booking ID:
                                    <?php echo htmlspecialchars($row['booking_id']); ?></h5>
                                <p><i class="fa fa-calendar-alt" aria-hidden="true"></i> <strong>Date:</strong>
                                    <?php echo htmlspecialchars(date('F j, Y, h:i A', strtotime($row['booking_date']))); ?>
                                </p>
                                <p><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Status:</strong>
                                    <?php echo htmlspecialchars(ucfirst($row['status'])); ?></p>
                            </div>
                            <div class="booking-card-footer">
                                <span class="price"><i class="fa fa-tag"
                                        aria-hidden="true"></i>₱<?php echo number_format($row['total_price'], 2); ?></span>
                                <a href="booking/details.php?booking_id=<?php echo $row['booking_id']; ?>"
                                    class="btn btn-view-details"><i class="fa fa-eye" aria-hidden="true"></i>View
                                    Details</a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>





    </section>
    <!--/ End Error Page -->

    <!-- Footer Area -->
    <footer id="footer" class="footer ">
        <!-- Footer Top -->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="single-footer">
                            <h2>About Us</h2>
                            <p>Lorem ipsum dolor sit am consectetur adipisicing elit do eiusmod tempor incididunt ut
                                labore dolore magna.</p>
                            <!-- Social -->
                            <ul class="social">
                                <li><a href="#"><i class="icofont-facebook"></i></a></li>
                                <li><a href="#"><i class="icofont-google-plus"></i></a></li>
                                <li><a href="#"><i class="icofont-twitter"></i></a></li>
                                <li><a href="#"><i class="icofont-vimeo"></i></a></li>
                                <li><a href="#"><i class="icofont-pinterest"></i></a></li>
                            </ul>
                            <!-- End Social -->
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="single-footer f-link">
                            <h2>Quick Links</h2>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Home</a>
                                        </li>
                                        <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>About Us</a>
                                        </li>
                                        <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Services</a>
                                        </li>
                                        <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Our
                                                Cases</a></li>
                                        <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Other
                                                Links</a></li>
                                    </ul>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-caret-right"
                                                    aria-hidden="true"></i>Consuling</a></li>
                                        <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Finance</a>
                                        </li>
                                        <li><a href="#"><i class="fa fa-caret-right"
                                                    aria-hidden="true"></i>Testimonials</a></li>
                                        <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>FAQ</a></li>
                                        <li><a href="#"><i class="fa fa-caret-right" aria-hidden="true"></i>Contact
                                                Us</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
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
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="single-footer">
                            <h2>Newsletter</h2>
                            <p>subscribe to our newsletter to get allour news in your inbox.. Lorem ipsum dolor sit
                                amet, consectetur adipisicing elit,</p>
                            <form action="mail/mail.php" method="get" target="_blank" class="newsletter-inner">
                                <input name="email" placeholder="Email Address" class="common-input"
                                    onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your email address'"
                                    required="" type="email">
                                <button class="button"><i class="icofont icofont-paper-plane"></i></button>
                            </form>
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
                            <p>© Copyright 2018 | All Rights Reserved by <a href="https://www.wpthemesgrid.com"
                                    target="_blank">wpthemesgrid.com</a> </p>
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