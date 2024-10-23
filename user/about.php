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
    <link rel="shortcut icon" href="images/favicon.png" type="">

    <title> Feane </title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!-- nice select  -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css"
        integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ=="
        crossorigin="anonymous" />
    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
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
    </style>

</head>

<body class="sub_page">
    <div>
        <a class="chat d-flex justify-content-center align-items-center pb-1"
            href="../message_kineme/user_ansya/chat_kineme.php">
            <!-- <button value=" <?php echo $row['chatroomid']; ?>" type="button" class="btn  border-0" -->
            <!-- data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top"> -->
            <img src="../images/chat.png" />
            <!-- </button> -->
        </a>
    </div>

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
                                <a class="nav-link" href="u-menu.php">Menu</a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="about.php">About</a>
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

    <!-- about section -->

    <section class="about_section layout_padding">
        <div class="container  ">

            <div class="row">
                <div class="col-md-6 ">
                    <div class="img-box">
                        <img src="../images\bg3.jpg" alt="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <div class="heading_container">
                            <h2>
                                We Are Feane
                            </h2>
                        </div>
                        <p>
                            There are many variations of passages of Lorem Ipsum available, but the majority have
                            suffered alteration
                            in some form, by injected humour, or randomised words which don't look even slightly
                            believable. If you
                            are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything
                            embarrassing hidden in
                            the middle of text. All
                        </p>
                        <a href="">
                            Read More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- end about section -->

    <!-- footer section -->
    <footer class="footer_section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-col">
                    <div class="footer_contact">
                        <h4>
                            Contact Us
                        </h4>
                        <div class="contact_link_box">
                            <a href="">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <span>
                                    Location
                                </span>
                            </a>
                            <a href="">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span>
                                    Call +01 1234567890
                                </span>
                            </a>
                            <a href="">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span>
                                    demo@gmail.com
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 footer-col">
                    <div class="footer_detail">
                        <a href="" class="footer-logo">
                            Feane
                        </a>
                        <p>
                            Necessary, making this the first true generator on the Internet. It uses a dictionary of
                            over 200 Latin words, combined with
                        </p>
                        <div class="footer_social">
                            <a href="">
                                <i class="fa fa-facebook" aria-hidden="true"></i>
                            </a>
                            <a href="">
                                <i class="fa fa-twitter" aria-hidden="true"></i>
                            </a>
                            <a href="">
                                <i class="fa fa-linkedin" aria-hidden="true"></i>
                            </a>
                            <a href="">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </a>
                            <a href="">
                                <i class="fa fa-pinterest" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 footer-col">
                    <h4>
                        Opening Hours
                    </h4>
                    <p>
                        Everyday
                    </p>
                    <p>
                        10.00 Am -10.00 Pm
                    </p>
                </div>
            </div>
            <div class="footer-info">
                <p>
                    &copy; <span id="displayYear"></span> All Rights Reserved By
                    <a href="https://html.design/">Free Html Templates</a><br><br>
                    &copy; <span id="displayYear"></span> Distributed By
                    <a href="https://themewagon.com/" target="_blank">ThemeWagon</a>
                </p>
            </div>
        </div>
    </footer>
    <!-- footer section -->

    <!-- jQery -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- popper js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.js"></script>
    <!-- owl slider -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
    </script>
    <!-- isotope js -->
    <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
    <!-- nice select -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
    <!-- custom js -->
    <script src="js/custom.js"></script>
    <!-- Google Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
    </script>
    <!-- End Google Map -->

</body>

</html>