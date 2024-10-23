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
    <link rel="shortcut icon" href="images/favicon.png" type="">

    <title> Rapide </title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="../user/css/bootstrap.css" />

    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!-- nice select  -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css"
        integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ=="
        crossorigin="anonymous" />
    <!-- font awesome style -->
    <link href="../user/css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="../user/css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="../user/css/responsive.css" rel="stylesheet" />
    <style>
    body {
        background-color: #ffd650;
    }

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




    /* General Container Styling */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Category Navigation */
    .category-nav {
        margin-bottom: 30px;
    }

    .category-nav a {
        display: inline-block;
        margin: 0 10px;
        padding: 10px 20px;
        border-radius: 30px;
        background-color: grey;
        color: white;
        font-size: 18px;
        font-weight: 500;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .category-nav a.active {
        background-color: #f1542d;
    }

    .category-nav a:hover {
        background-color: #c28400;
    }

    /* Services Grid */
    .services {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    /* Service Item Design */
    .service-item {
        background-color: #d6d6d6;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .service-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    /* Image Styling */
    .service-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    /* Service Item Details */
    .service-item-details {
        padding: 20px;
        text-align: center;
    }

    .service-item-details h4 {
        font-size: 22px;
        margin-bottom: 10px;
        color: #343a40;
    }

    .service-item-details p {
        font-size: 16px;
        color: #6c757d;
        margin-bottom: 20px;
    }

    .service-item-details h5 {
        font-size: 20px;
        color: #28a745;
        font-weight: 700;
    }

    /* Add to Cart Button */
    .add-to-cart-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #f1542d;
        color: white;
        border: none;
        border-radius: 30px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .add-to-cart-btn:hover {
        background-color: grey;
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
            <img src="../images/bg1.jpg" alt="">
        </div>
        <!-- header section strats -->
        <header class="header_section">
            <div class="container">
                <nav class="navbar navbar-expand-lg custom_nav-container ">
                    <a class="navbar-brand" href="../user\u-homepage.php">
                        <span>
                            Rapide
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
                                <a class="nav-link" href="../user/u-homepage.php">Home <span
                                        class="sr-only">(current)</span></a>
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
                    </div>
                </nav>
            </div>
        </header>
        <!-- end header section -->
    </div>

    <!-- food section -->
    <div class="container mt-5">

        <h2 class="text-center">Services List</h2>
        <!-- Category Navigation -->
        <div class="category-nav text-center">
            <?php
            // Connect to the database
            include('../connection.php');

            // Define the categories and corresponding tables (some categories have multiple tables)
            $categories = [
                'PMS Package' => ['package_list'],
                'Periodic Services' => ['service_list'],
                'AC Services & Repair' => ['ac_service'],
                'Brakes Services' => ['brake_service', 'brakes_table']
            ];

            // Check if category is selected, otherwise default to first category
            $selected_category = isset($_GET['category']) ? $_GET['category'] : array_key_first($categories);

            // Display category buttons
            foreach ($categories as $category_name => $table_names) {
                $active_class = ($category_name == $selected_category) ? 'active' : '';
                echo "<a href='?category=$category_name' class='$active_class'>$category_name</a>";
            }
            ?>
        </div>

        <!-- Services List -->
        <div class="services">
            <?php
            if (array_key_exists($selected_category, $categories)) {
                // Get all the tables associated with the selected category
                $table_names = $categories[$selected_category];

                // Loop through each table for the selected category
                foreach ($table_names as $table_name) {
                    // Query to get services from the current table
                    $query = "SELECT id, name, description, image, price FROM $table_name";
                    $result = mysqli_query($conn, $query);

                    // Check if any services are available
                    if (mysqli_num_rows($result) > 0) {
                        // Display each service item
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '
                            <div class="service-item">
                                <img src="' . $row['image'] . '" alt="Service Image">
                                <div class="service-item-details">
                                    <h4>' . $row['name'] . '</h4>
                                    <p>' . $row['description'] . '</p>
                                    <h5>₱ ' . number_format($row['price'], 2) . '</h5>
                                </div>
                                <button class="add-to-cart-btn">ADD TO CART</button>
                            </div>';
                        }
                    } else {
                        echo "<p>No services found in $selected_category for table $table_name.</p>";
                    }
                }
            } else {
                echo "<p>Invalid category selected.</p>";
            }
            ?>
        </div>
    </div>



    <!-- end food section -->

    <!-- jQery -->
    <script src="../user/js/jquery-3.4.1.min.js"></script>
    <!-- popper js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <!-- bootstrap js -->
    <script src="../user/js/bootstrap.js"></script>
    <!-- owl slider -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
    </script>
    <!-- isotope js -->
    <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
    <!-- nice select -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
    <!-- custom js -->
    <script src="../user/js/custom.js"></script>
    <!-- Google Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
    </script>
    <!-- End Google Map -->

</body>

</html>