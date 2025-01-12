<?php
include('../../sessioncheck.php');
include('../../connection.php');

$user_id = $_SESSION['id'];
$booking_id = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;

// Fetch booking details
$query = "SELECT * FROM bookings WHERE booking_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    echo "Invalid booking ID.";
    exit();
}

// Handle cancellation request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) {
    if ($booking['status'] === 'pending') {
        $update_query = "UPDATE bookings SET status = 'canceled' WHERE booking_id = ? AND user_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ii", $booking_id, $user_id);
        if ($update_stmt->execute()) {
            $success_message = "Booking has been successfully canceled.";
            // Refresh to update the status
            header("Location: booking_details.php?booking_id=$booking_id");
            exit();
        } else {
            $error_message = "Failed to cancel the booking. Please try again.";
        }
    } else {
        $error_message = "Only pending bookings can be canceled.";
    }
}

// Fetch items associated with the booking
$cart_query = "SELECT * FROM cart WHERE booking_id = ? AND user_id = ?";
$stmt_cart = $conn->prepare($cart_query);
$stmt_cart->bind_param("ii", $booking_id, $user_id);
$stmt_cart->execute();
$cart_result = $stmt_cart->get_result();
?>









<?php


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
    <link rel="stylesheet" href="../../users\style2.css">
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
/* Center the container */
.container1{
    display: flex;
    justify-content: center; /* Center horizontally */
    align-items: center;    /* Center vertically */
    padding: 20px;          /* Add padding for responsiveness */
}

/* Card styling */
.booking-details-card {
    width: 100%; /* Adjust the width as needed */
    max-width: 600px; /* Limit the maximum width */
    border: 1px solid #ddd;
    border-radius: 10px;
    background-color: #fff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: left; /* Ensure text is aligned to the left */
}

/* Individual Detail */
.booking-detail {
    margin-bottom: 20px;
}

.booking-detail h4 {
    font-size: 18px;
    color: #fcbf17; /* Yellow from the palette */
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.booking-detail p {
    font-size: 16px;
    color: #555;
    margin: 0;
}

/* Buttons */
.btn1 {
    font-size: 16px;
    padding: 10px;
    border-radius: 5px;
    margin: 10px 5px 0;
    margin-top: 20px;
}

.btn1-secondary {
    background-color: #457b9d; /* Complementary blue */
    border-color: #457b9d;
    color: white;
}

.btn1-secondary:hover {
    background-color: #1d3557;
    border-color: #1d3557;
}

.btn1-danger {
    background-color: #e63946; /* Deep red */
    border-color: #e63946;
    color: #fff;
}

.btn1-danger:hover {
    background-color: #d62828;
    border-color: #d62828;
}

.btn1 i {
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
                        <li><i class="fa fa-car"></i><a href="https://gulong.ph/?utm_source=rapide.ph"> Buy Tires</a></li>
                        <li><i class="fa fa-phone"></i>0966 061 9979 (Globe)</li>
                        <li><i class="fa fa-facebook"></i><a href="https://www.facebook.com/RapideAutoServicePH"> Fb: Rapide</a></li>
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
                                <h1>Rapide</h1>
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
                                        <li ><a href="../../users\Homepage.php">Home</a>
                                        </li>
                                        <!-- <li><a href="#">Doctos </a></li> -->
                                        <li><a href="../booking/service_list.php">Services </a></li>
                                        <li><a href="#">Map <i class="icofont-rounded-down"></i></a>
                                            <ul class="dropdown">
                                                <li><a href="../../map/gmap.php">Rapide Cavite Map</a></li>
                                                <li><a href="../../map/emergency_form.php">Emergency Map</a></li>
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
                                                            <a href="message/chatbox.php?branch_id=<?php echo $branch['id']; ?>">
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
                                        <li class="active"><a href="../../users\Act.php">Activites</a></li>

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
                                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                    <li><a class="dropdown-item" href="../login\login.php">Logout</a></li>
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
    <div class="container1">
    <div class="booking-details-card p-4 mb-4">
        <h2 class="text-center mb-4">Booking Details</h2>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="booking-detail">
            <h4><i class="fa fa-id-badge"></i> Booking ID</h4>
            <p><?php echo htmlspecialchars($booking['booking_id']); ?></p>
        </div>

        <div class="booking-detail">
            <h4><i class="fa fa-calendar-alt"></i> Date</h4>
            <p><?php echo htmlspecialchars($booking['booking_date']); ?></p>
        </div>

        <div class="booking-detail">
            <h4><i class="fa fa-info-circle"></i> Status</h4>
            <p><?php echo htmlspecialchars(ucfirst($booking['status'])); ?></p>
        </div>

        <div class="booking-detail">
            <h4><i class="fa fa-dollar-sign"></i> Total Price</h4>
            <p>₱<?php echo number_format($booking['total_price'], 2); ?></p>
        </div>

        <h4><i class="fa fa-list"></i> Items in this Booking</h4>
        <ul>
            <?php while ($item = $cart_result->fetch_assoc()): ?>
                <li><?php echo htmlspecialchars($item['service_name']) . " - Quantity: " . htmlspecialchars($item['quantity']) . " - ₱" . number_format($item['price'], 2); ?></li>
            <?php endwhile; ?>
        </ul>

        <div class="text-center mt-5">
            <a href="booking_history.php" class="btn1 btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Booking History
            </a>

            <?php if ($booking['status'] === 'pending'): ?>
                <form method="post" class="d-inline">
                    <button type="submit" name="cancel_booking" class="btn1 btn-danger">
                        <i class="fa fa-times-circle"></i> Cancel Booking
                    </button>
                </form>
            <?php endif; ?>
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