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
                                        <li class="active"><a href="#">Home</a>
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
                                        <li><a href="Act.php">Activites</a></li>

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
    <!-- End Header Area -->

    <!-- Slider Area -->
    <section class="slider">
        <div class="hero-slider">
            <?php foreach ($sliders as $slider): ?>
            <?php foreach ($slider['images'] as $image_url): ?>
            <!-- Start Single Slider -->
            <div class="single-slider" style="background-image:url('<?php echo htmlspecialchars($image_url); ?>')">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="text">
                                <!-- Display dynamically styled heading -->
                                <h1><?php echo $slider['heading']; ?></h1>
                                <!-- Display description -->
                                <p><?php echo htmlspecialchars($slider['description']); ?></p>
                                <!-- Display button -->
                                <div class="button">
                                    <a href="<?php echo htmlspecialchars($slider['button_link']); ?>" class="btn">
                                        <?php echo htmlspecialchars($slider['button_text']); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Single Slider -->
            <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <!--/ End Slider Area -->

    <!-- Start Schedule Area -->
    <section class="schedule">
        <div class="container">
            <div class="schedule-inner">
                <div class="row">
                    <?php foreach ($schedule as $item): ?>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-schedule">
                            <div class="inner">
                                <div class="icon">
                                    <i class="fa fa-ambulance"></i> <!-- Dynamically adjust if needed -->
                                </div>
                                <div class="single-content">
                                    <span><?php echo $item['subhead']; ?></span>
                                    <h4><?php echo $item['heading']; ?></h4>
                                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                                    <a href="<?php echo htmlspecialchars($item['button_link']); ?>">
                                        <?php echo htmlspecialchars($item['button_text']); ?><i
                                            class="fa fa-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!--/End Start schedule Area -->


    <!-- Start Fun-facts -->
    <div id="fun-facts" class="fun-facts section overlay">
        <div class="container">
            <div class="row">
                <?php foreach ($stats as $key => $stat): ?>
                    <div class="col-lg-3 col-md-6 col-12">
                        <!-- Start Single Fun -->
                        <div class="single-fun">
                            <?php if ($key == 0): ?>
                                <!-- <i class="icofont icofont-home"></i> -->
                            <?php elseif ($key == 1): ?>
                                <!-- <i class="icofont icofont-user-alt-3"></i> -->
                            <?php elseif ($key == 2): ?>
                                <!-- <i class="icofont icofont-simple-smile"></i> -->
                            <?php elseif ($key == 3): ?>
                                <!-- <i class="icofont icofont-table"></i> -->
                            <?php endif; ?>
                            <div class="content">
                                <span class="counter"><?= htmlspecialchars($stat['subhead'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <p><?= htmlspecialchars($stat['heading'], ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                        </div>
                        <!-- End Single Fun -->
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!--/ End Fun-facts -->




    <!-- Start Cleanliness Section -->
    <section class="portfolio section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <?php foreach ($clean_content as $clean): ?>
                            <h2><?php echo htmlspecialchars($clean['heading']); ?></h2>
                            <p><?php echo htmlspecialchars($clean['description']); ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <!-- Owl Carousel Slider -->
                    <div class="owl-carousel portfolio-slider">
                        <?php foreach ($clean_content as $clean): ?>
                            <?php foreach ($clean['images'] as $image_url): ?>
                                <div class="single-pf">
                                    <img src="<?php echo htmlspecialchars($image_url); ?>" alt="Cleanliness Image" style="width: 100%; height: auto;">
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Cleanliness Section -->

    <section class="services section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2><?php echo htmlspecialchars($general_section['heading']); ?></h2>
                    <p><?php echo htmlspecialchars($general_section['description']); ?></p>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach ($contents as $content): ?>
                <?php if ($content['type'] === 'service'): ?>
                    <div class="col-lg-6 col-md-6 col-12">
                        <!-- Start Single Service -->
                        <div class="single-service">
                            <?php if (!empty($content['images'])): ?>
                                <img src="<?php echo htmlspecialchars($content['images'][0]); ?>" alt="<?php echo htmlspecialchars($content['heading']); ?>">
                            <?php else: ?>
                                <img src="img/default.png" alt="Default Image">
                            <?php endif; ?>
                            <h4><a href="<?php echo htmlspecialchars($content['button_link']); ?>"><?php echo htmlspecialchars($content['heading']); ?></a></h4>
                            <p><?php echo htmlspecialchars($content['description']); ?></p>
                        </div>
                        <!-- End Single Service -->
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        </div>
    </section>


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