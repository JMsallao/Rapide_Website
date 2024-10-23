<?php
include ('../connection.php');
include('../header.php');

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

    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />
    <style>
    /* Styling for the user tab and dropdown */
    .user_tab {

        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    /* User button */
    .user-btn {

        background-color: transparent;
        border: none;
        padding: 5px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    .user-btn:hover {
        transform: scale(1.05);
    }

    /* User photo styling */
    .user_photo img {
        border-radius: 50%;
        height: 50px;
        width: 50px;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Dropdown menu styling */
    .dropdown-menu {
        padding: 10px;
        margin-top: 10px;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        width: 200px;
    }

    /* Dropdown container */
    .dropdown_container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 10px;
    }



    #dropdownMenuButton1 {
        background-color: transparent;
        height: 50px;
        border-radius: 50%;
        border: none;
    }

    #dropdown>div>li:nth-child(1)>form>button {
        width: 100%;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
    }

    #dropdown>div>li:nth-child(2)>form>button {
        width: 100%;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .dropdown-menu {
            width: 100%;
        }

        .user-btn {
            height: 60px;
            width: 60px;
        }

        .user_photo img {
            height: 45px;
            width: 45px;
        }

        .dropdown-item {
            padding: 12px;
            font-size: 18px;
        }
    }

    /* chat */
    .chat {
        width: 70px;
        height: 70px;
        position: fixed;
        bottom: 20px;
        right: 20px;
        overflow: hidden;
        border-radius: 50%;
        background-color: #ffff;
        transition: .2s;
        z-index: 5;
    }

    .chat:hover {
        transform: scale(105%);
    }

    .chat img {
        width: 70%;
        height: 70%;
        object-fit: cover;
    }






















    /* slider section */
    .slider_section {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .slider_section .row {
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .slider_section #customCarousel1 {
        width: 100%;
        position: unset;
    }

    .slider_section .detail-box {
        color: #ffffff;
        text-align: center;
    }

    .slider_section .detail-box h1 {
        font-weight: 600;
        margin-bottom: 15px;
        color: #ffffff;
    }

    .slider_section .detail-box .btn-box {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        margin-top: 20px;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    }

    .slider_section .detail-box .btn-box a {
        text-align: center;
        width: 165px;
    }

    .slider_section .detail-box .btn-box .btn1 {
        display: inline-block;
        padding: 10px 15px;
        background-color: #fb0;
        color: #ffffff;
        border-radius: 0;
        -webkit-transition: all .3s;
        transition: all .3s;
        border: 1px solid #fb0;
    }

    .slider_section .detail-box .btn-box .btn1:hover {
        background-color: transparent;
        color: #fb0;
    }

    .slider_section .img-box img {
        width: 100%;
    }

    .slider_section .carousel_btn-box {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        margin: 45px auto 0 auto;
        width: 110px;
        height: 50px;
    }

    .slider_section .carousel_btn-box a {
        top: 50%;
        width: 50px;
        height: 50px;
        background-color: #ffffff;
        opacity: 1;
        color: rgb(192, 142, 4);
        font-size: 14px;
        -webkit-box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.25);
        box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.25);
        -webkit-transition: all .2s;
        transition: all .2s;
    }

    .slider_section .carousel_btn-box a:hover {
        background-color: #2c7873;
        color: #ffffff;
    }

    .slider_section .carousel_btn-box .carousel-control-prev {
        left: 25px;
    }

    .slider_section .carousel_btn-box .carousel-control-next {
        right: 25px;
    }

    .service_section .heading_container {
        margin-bottom: 35px;
    }

    .service_section .box {
        margin: 10px;
        text-align: center;
        -webkit-box-shadow: 0 0 5px 2px rgba(0, 0, 0, 0.15);
        box-shadow: 0 0 5px 2px rgba(0, 0, 0, 0.15);
        padding: 25px 15px;
        -webkit-transition: all .3s;
        transition: all .3s;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .service_section .box .img-box {
        width: 65px;
        height: 65px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .service_section .box .img-box img {
        max-height: 100%;
        max-width: 100%;
        -webkit-transition: all .3s;
        transition: all .3s;
    }

    .service_section .box .detail-box {
        margin-top: 15px;
    }

    .service_section .box .detail-box h5 {
        font-weight: bold;
    }

    .service_section .box .detail-box p {
        margin: 0;
    }

    .service_section .btn-box {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        margin-top: 45px;
    }

    .service_section .btn-box a {
        display: inline-block;
        padding: 10px 45px;
        background-color: #fb0;
        color: #ffffff;
        border-radius: 0;
        -webkit-transition: all .3s;
        transition: all .3s;
        border: 1px solid #fb0;
    }

    .service_section .btn-box a:hover {
        background-color: transparent;
        color: #fb0;
    }

    .service_section .owl-stage .owl-item.active {
        -webkit-transform: scale(0.85);
        transform: scale(0.85);
        -webkit-transition: 0.6s ease;
        transition: 0.6s ease;
    }

    .service_section .owl-stage .owl-item.active.center {
        -webkit-transform: scale(1);
        transform: scale(1);
    }

    .service_section .owl-stage .owl-item.active.center .box {
        background-color: #fb0;
        color: #ffffff;
    }

    .service_section .owl-stage .owl-item.active.center .box .img-box img {
        -webkit-filter: brightness(0) invert(1);
        filter: brightness(0) invert(1);
    }

    .service_section .owl-nav {
        display: none;
    }

    .service_section .owl-dots {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        margin-top: 25px;
    }

    .service_section .owl-dots .owl-dot {
        width: 15px;
        height: 15px;
        border: none;
        border-radius: 100%;
        margin: 0 2px;
        background-color: #ccc;
        border: none;
        outline: none;
    }

    .service_section .owl-dots .owl-dot.active {
        background: #fb0;
    }
    </style>
</head>

<body class="sub_page">
    <div class="hero_area">
        <div class="bg-box">
            <img src="images/hero-bg.jpg" alt="">
        </div>
        <!-- header section strats -->
        <header class="header_section">
            <div class="container">
                <nav class="navbar navbar-expand-lg custom_nav-container ">
                    <a class="navbar-brand" href="index.html">
                        <span>
                            Feane
                        </span>
                    </a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class=""> </span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav  mx-auto ">
                            <li class="nav-item ">
                                <a class="nav-link" href="u-homepage.php">Home <span
                                        class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="service.php">Service</a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="Map.php">Map</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="book.php">Book Table</a>
                            </li>
                        </ul>
                        <div class="user_tab">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user">
                                        <div class="user_photo">
                                            <img src="../profile_picture/<?php echo $row['image_file'] ?>" alt="">
                                        </div>
                                    </div>
                                </button>
                                <ul id="dropdown" class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <div class="dropdown_container">
                                        <li>
                                            <form action="user_profile.php" method="post">
                                                <button>Profile</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="../login/login.php" method="post"><button type="submit"
                                                    name="logout">Logout</button></form>
                                        </li>
                                    </div>
                                </ul>
                            </div>
                        </div>
                </nav>
            </div>
        </header>
        <!-- end header section -->
    </div>

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
                    Book now!
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