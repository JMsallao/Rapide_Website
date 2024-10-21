<?php
// Include your database connection file
include ('connection.php');
include ("index-css.php");
// Query to get package services from the database
$sql_package = "SELECT * FROM package_list";
$result_package = $conn->query($sql_package);
$sql_about = "SELECT * FROM about";
$result_about = $conn->query($sql_about);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="images/fevicon.png" type="image/x-icon">
    <title>Rapide.ph</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css"
        integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/responsive.css" rel="stylesheet" />
</head>

<body>
    <div class="hero_area">
        <div class="hero_bg_box">
            <img src="images/bg3.jpg" alt="">
        </div>
        <!-- header section strats -->
        <header class="header_section">
            <div class="header_bottom">
                <div class="container-fluid">
                    <nav class="navbar navbar-expand-lg custom_nav-container ">
                        <a class="navbar-brand " href="user_landing.php"> Rapide </a>

                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class=""> </span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav  ">
                                <li class="nav-item">
                                    <a class="nav-link" href="login\login.php">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        <span>
                                            Login
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </header>
        <!-- end header section -->
        <!-- slider section -->
        <section class="slider_section ">
            <div id="customCarousel1" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php
                if ($result_package->num_rows > 0) {
                    $isActive = true; // To make the first item active
                    while ($row = $result_package->fetch_assoc()) {
                        // Add the "active" class to the first carousel item
                        $activeClass = $isActive ? 'active' : '';
                        echo '<div class="carousel-item ' . $activeClass . '">';
                        echo '<div class="container">';
                        echo '<div class="row">';
                        echo '<div class="col-lg-10 col-md-11 mx-auto">';
                        echo '<div class="detail-box">';
                        echo '<h1>' . $row["name"] . '</h1>';
                        echo '<h3>' . $row["services_inclusion"] . '</h3>';
                        echo '<p> Starts at ₱ 1600</p>';
                        echo '<div class="btn-box">';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    // After the first item, don't add the active class
                    $isActive = false;
                }
            } else {
                echo '<p>No package services available at the moment.</p>';
            }
            ?>
                </div>
                <div class="carousel_btn-box">
                    <a class="carousel-control-prev" href="#customCarousel1" role="button" data-slide="prev">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#customCarousel1" role="button" data-slide="next">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </section>
        <!-- end slider section -->
    </div>
    <!-- service section -->
    <section class="service_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center ">
                <h2 class="">
                    Our Services
                </h2>
                <p class="col-lg-8 px-0">
                    "Our auto repair shop offers expert maintenance and repair services, including engine
                    diagnostics,
                    brake repairs, oil changes, tire services, and more. We ensure fast, reliable work to keep your
                    vehicle running smoothly and safely. Your car, our care!"
                </p>
            </div>
            <div class="service_container">
                <div class="carousel-wrap ">
                    <div class="service_owl-carousel owl-carousel">
                        <div class="item">
                            <div class="box ">
                                <div class="img-box">
                                    <img src="images/s1.png" alt="" />
                                </div>
                                <div class="detail-box">
                                    <h5>
                                        Home Welding
                                    </h5>
                                    <p>
                                        when looking at its layout. The point of using Lorem Ipsum is
                                        that it has a more-or-less normal
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="box ">
                                <div class="img-box">
                                    <img src="images/s4.png" alt="" />
                                </div>
                                <div class="detail-box">
                                    <h5>
                                        Machine Welding
                                    </h5>
                                    <p>
                                        when looking at its layout. The point of using Lorem Ipsum is
                                        that it has a more-or-less normal
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="box ">
                                <div class="img-box">
                                    <img src="images/s6.png" alt="" />
                                </div>
                                <div class="detail-box">
                                    <h5>
                                        Car Welding
                                    </h5>
                                    <p>
                                        when looking at its layout. The point of using Lorem Ipsum is
                                        that it has a more-or-less normal
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="box ">
                                <div class="img-box">
                                    <img src="images/s1.png" alt="" />
                                </div>
                                <div class="detail-box">
                                    <h5>
                                        Home Welding
                                    </h5>
                                    <p>
                                        when looking at its layout. The point of using Lorem Ipsum is
                                        that it has a more-or-less normal
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="box ">
                                <div class="img-box">
                                    <img src="images/s4.png" alt="" />
                                </div>
                                <div class="detail-box">
                                    <h5>
                                        Machine Welding
                                    </h5>
                                    <p>
                                        when looking at its layout. The point of using Lorem Ipsum is
                                        that it has a more-or-less normal
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="box ">
                                <div class="img-box">
                                    <img src="images/s6.png" alt="" />
                                </div>
                                <div class="detail-box">
                                    <h5>
                                        Car Welding
                                    </h5>
                                    <p>
                                        when looking at its layout. The point of using Lorem Ipsum is
                                        that it has a more-or-less normal
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- about section -->
    <section class="about_section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5 offset-md-1">
                    <div class="detail-box pr-md-2">
                        <div class="heading_container">
                            <h2 class="">
                                About Us
                            </h2>
                        </div>
                        <p class="detail_p_mt">
                            <?php 
                        if ($result_about->num_rows > 0) {
                            $row = $result_about->fetch_assoc();
                            echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                        }
                            else {
                                echo '<p>No about us available at the moment.</p>';
                            }
                            
                            // Close the database connection
                            $conn->close();
                            ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-6 px-0">
                    <div class="img-box ">
                        <img src="images/bg1.jpg" class="box_img" alt="about img">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="info_section ">
        <div class="container">
            <div class="info_top">
                <div class="row">
                    <div class="col-md-3 ">
                        <a class="navbar-brand" href="index.html">
                            Rapide
                        </a>
                    </div>
                    <div class="col-md-5 ">
                        <div class="info_contact">
                            <a href="">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <span>
                                    Location
                                </span>
                            </a>
                            <a href="">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span>
                                    Call : 0966 061 9979
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="social_box">
                            <a href="">
                                <i class="fa fa-facebook" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="info_bottom">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="info_detail">
                            <h5>
                                Company
                            </h5>
                            <p>
                                Randomised words which don't look even slightly believable. If you are going to use
                                a
                                passage of
                                Lorem
                                Ipsum, you need to be sure
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info_form">
                            <h5>
                                NEWSLETTER
                            </h5>
                            <form action="">
                                <input type="text" placeholder="Enter Your Email" />
                                <button type="submit">
                                    Subscribe
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="info_detail">
                            <h5>
                                Services
                            </h5>
                            <p>
                                Randomised words which don't look even slightly believable. If you are going to use
                                a
                                passage of
                                Lorem
                                Ipsum, you need to be sure
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end info_section -->
    <!-- footer section -->
    <footer class="footer_section">
        <div class="container">
            <p>
                &copy; <span id="displayYear"></span> All Rights Reserved By
                <a href="">Rapide.ph</a>
            </p>
        </div>
    </footer>
    <!-- footer section -->
    <!-- jQery -->
    <script src="js\jquery-3.4.1.min.js"></script>
    <!-- popper js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.js"></script>
    <!-- owl slider -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!-- nice select -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"
        integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>
    <!-- custom js -->
    <script src="js\custom.js"></script>
    <!-- Google Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76A7a9Hr8lFztXXwjbK6g3Kbt1Lz6Y3auD8r5c6EwHgjV4ldtJgROZXB6ZGdvep" crossorigin="anonymous">
    </script>
    <!-- End Google Map -->
</body>


</html>