<?php
include ('../connection.php');
include('../header.php');
include('user_navbar.php');

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="../images/fevicon.png" type="image/x-icon">
    <title>Rapide.ph</title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!-- nice select -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css"
        integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
    <!-- font awesome style -->
    <link href="../css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="../css/style3.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="../css/responsive.css" rel="stylesheet" />
</head>

<body class="sub_page">
    <!-- service section -->

    <section class="service_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center ">
                <h2 class="">
                    Our Services
                </h2>
                <p class="col-lg-8 px-0">
                    If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything
                    believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't
                    anything
                </p>
            </div>
            <div class="service_container">
            <div class="carousel-wrap ">
                <div class="service_owl-carousel owl-carousel">
                    <?php
                    // Define the categories and corresponding tables
                    $categories = [
                        'PMS Package' => ['package_list'],
                        'Periodic Services' => ['service_list'],
                        'AC Services & Repair' => ['ac_service'],
                        'Brakes Services' => ['brake_service', 'brakes_table']
                    ];

                    // Loop through each category and display its name
                    foreach ($categories as $category_name => $tables) {
                        echo "
                        <div class='item'>
                            <div class='box'>
                                <div class='img-box'>
                                    <img src='images/default_service.png' alt='' />
                                </div>
                                <div class='detail-box'>
                                    <h5>$category_name</h5>
                                    <p>Explore the $category_name offered by our shop. Click below for more details.</p>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="btn-box">
            <a href="../booking/service_list.php?; ?>">
                Read More
            </a>
        </div>
        </div>
    </section>
    <!-- footer section -->
    <footer class="footer_section">
        <div class="container">
            <p>
                &copy; <span id="displayYear"></span> All Rights Reserved By
                <a href="https://html.design/">Free Html Templates</a>
            </p>
        </div>
    </footer>
    <!-- footer section -->

    <!-- jQery -->
    <script src="../js/jquery-3.4.1.min.js"></script>
    <!-- popper js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <!-- bootstrap js -->
    <script src="../js/bootstrap.js"></script>
    <!-- owl slider -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!-- nice select -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"
        integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>
    <!-- custom js -->
    <script src="../js/custom.js"></script>
    <!-- Google Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
    </script>
    <!-- End Google Map -->
</body>

</html>