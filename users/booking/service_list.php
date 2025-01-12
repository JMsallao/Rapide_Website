<?php
    include('../../sessioncheck.php');
    include('../../connection.php');
    include('../../header.php');
    include('../../users/style.php');


    // Initialize cart count to prevent undefined variable error
    $cartCount = 0;

    // Ensure the session user ID is set correctly
    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];

        // Fetch cart item count for the logged-in user
        $countQuery = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = ? AND status = 'pending'";
        $stmtCount = $conn->prepare($countQuery);

        if ($stmtCount) {
            $stmtCount->bind_param("i", $user_id);
            $stmtCount->execute();
            $resultCount = $stmtCount->get_result();
            if ($resultCount) {
                $row = $resultCount->fetch_assoc();
                $cartCount = $row['cart_count'];
            }
            $stmtCount->close();
        }
    } else {
        die("User ID is not set in the session.");
    }

    // Query to get package services from the database
    $sql_package = "SELECT * FROM package_list";
    $result_package = $conn->query($sql_package);

    $sql_about = "SELECT * FROM about";
    $result_about = $conn->query($sql_about);

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



<!DOCTYPE html>
<html lang="en">

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
    <link rel="shortcut icon" href="../../images\rapide_logo.png" type="image/x-icon">

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

    <link rel="stylesheet" href="css\normalizeee.css">
    <link rel="stylesheet" href="styleeeeee.css">
    <link rel="stylesheet" href="css/responsive.css">
    <style>
    :root {
        --primary-color: #ffc107;
        --secondary-color: #dc3545;
        --text-color: #333;
        --background-light: #f9f9f9;
        --shadow-color: rgba(0, 0, 0, 0.3);
        --transition: all 0.3s ease;
    }

    .custom-navbar {
        display: flex;
        justify-content: space-between;
        /* Ensures buttons are at the edges */
        align-items: center;
        padding: 10px 20px;
        color: black;
        font-size: 16px;
        top: 0;
        z-index: 10;
 
        height: 55px;
        width: 100%;
        /* Makes navbar full width */
    }

    body>header>div.header-inner>div>div>div>div.col-lg-3.col-md-3.col-12>div.logo {
        margin-top: -35px;
        width: 150px;
        position: absolute;
    }

    .navbar-right {
        display: flex;
        justify-content: space-between;
        /* Space out Cart and History */
        align-items: center;
        flex: 1;
        padding: 10px;
        /* Takes up remaining space in the navbar */
    }

    .nav-link {
        text-decoration: none;
        font-size: 16px;
        color: black;
    }

    #cart-icon {
        margin-left: 0;
    }

    .nav-link:last-child {
        margin-left: auto;
        /* Push History button to the right */
    }

    .text-center {
        text-align: center;

        position: absolute;
        top: 30%;
        /* Vertically center within navbar */
        left: 50%;
        /* Horizontally center within navbar */
        transform: translate(-50%, -50%);
        font-size: 18px;
        font-weight: bold;
    }




    #edit-toggle {
        color: white;
        font-size: 16px;
        text-decoration: none;
        background: none;
        border: none;
        cursor: pointer;
    }

    /* #edit-toggle:hover {
        text-decoration: underline;
    } */

    .chat-icon {
        position: relative;
        display: inline-block;
        color: white;
        font-size: 20px;
    }

    .chat-icon i {
        font-size: 24px;
    }

    .chat-badge {
        position: absolute;
        top: -5px;
        right: -10px;
        background-color: #ffc107;
        /* Yellow badge background */
        color: #000;
        font-size: 12px;
        padding: 3px 6px;
        border-radius: 50%;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }



    /* Search Bar */
    .search-container {
        position: relative;
        width: 100%;
        max-width: 600px;
        margin: 0 auto 20px;
    }

    .search-input {
        width: 100%;
        padding: 15px 20px;
        font-size: 14px;
        border: 2px solid #ccc;
        border-radius: 10px;
        outline: none;
        background-color: white;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .search-placeholder {
        position: absolute;
        top: 85%;
        left: 10px;
        font-size: 16px;
        color: #aaa;
        pointer-events: none;
        transform: translateY(-40%);
        transition: var(--transition);
    }

    .search-input:focus+.search-placeholder,
    .search-input:not(:placeholder-shown)+.search-placeholder {
        top: 60px;
        font-size: 12px;
        color: var(--primary-color);
    }

    /* Category Navigation */
    /* Wrapper for centering the category navigation */
    .category-nav-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
    }

    .category-nav {
        display: flex;
        flex-wrap: nowrap;
        gap: 10px;
        overflow-x: auto;
        scrollbar-width: thin;
        /* For modern browsers */
        scrollbar-color: var(--primary-color) transparent;
    }

    .category-nav::-webkit-scrollbar {
        height: 6px;
    }

    .category-nav::-webkit-scrollbar-thumb {
        background-color: var(--primary-color);
        border-radius: 10px;
    }

    .category-nav::-webkit-scrollbar-track {
        background: transparent;
    }

    .category-nav a {
        padding: 10px 15px;
        color: black;
        background-color: transparent;
        border-radius: 5px;
        font-weight: bold;
        transition: var(--transition);
        text-align: center;
    }

    .category-nav a.active,
    .category-nav a:hover {
        background-color: var(--primary-color);
    }

    /* Service Item */
    /* Service Item */
    .service-item {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px var(--shadow-color);
        overflow: hidden;
        margin-bottom: 20px;
        padding: 20px;
        height: 100%;
        /* Ensure uniform height */
    }

    .service-item img {
        width: 100%;
        height: auto;
        object-fit: fill;
        margin-bottom: 15px;
        border-radius: 10px;
    }

    .service-item-details {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        /* Ensure the details stretch to fill available space */
        position: relative;
    }

    .service-item-details h4 {
        font-size: 1.4rem;
        font-weight: bold;
        margin-bottom: 5px;
        color: var(--secondary-color);
    }

    .service-item-details h5 {
        font-size: 1.2rem;
        color: var(--primary-color);
        font-weight: bold;
        margin-bottom: 10px;
    }

    .service-item-details p {
        color: var(--text-color);
        margin-bottom: 15px;
        flex-grow: 1;
        /* Allow the description to take available space */
    }

    /* Position BOOK button at the bottom */
    .add-to-cart-btn {
        background-color: yellow;
        color: black;
        border: none;
        padding: 10px 20px;
        font-weight: bold;
        border-radius: 20px;
        transition: var(--transition);
        margin-top: auto;
        width: 50%;
    }

    .add-to-cart-btn:hover {
        background-color: red;
        color: white;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .service-item {
            flex-direction: column;

        }

        .add-to-cart-btn {
            width: 100%;
            /* Ensure full-width button on smaller screens */
        }
    }

    @media (max-width: 320px) {
        .service-item img {
            width: 35%;
            margin-left: 65px;
        }

        .add-to-cart-btn {
            width: 100%;
            /* Ensure full-width button on smaller screens */
        }

        .text-center {
            font-size: 17px;
        }

        .navbar-right {
            font-size: 13px;
        }

        .nav-link {
            text-decoration: none;
            font-size: 13px;
            color: black;
        }


        .nav-link:last-child {
            margin-left: auto;
            font-size: 13px;
        }
    }

    @media (max-width: 375px) {
        .service-item img {
            width: 35%;
            left: 100px;
        }

        .add-to-cart-btn {
            width: 100%;
            /* Ensure full-width button on smaller screens */
        }

        .custom-navbar {
            size: 20px;
        }
    }

    @media (max-width: 425px) {
        .service-item img {
            width: 35%;
            margin-left: 65px;
        }

        .add-to-cart-btn {
            width: 100%;
            /* Ensure full-width button on smaller screens */
        }
    }

    /* Show labels on larger screens */
    @media (min-width: 768px) {
        .navbar-custom .nav-label {
            display: inline;
        }
    }

    /* Notification badge style */
    .navbar-custom .badge {


        background-color: rgb(255, 217, 0);
        color: #fff;
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 50%;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease;
    }

    /* Scale-up animation for the badge */
    .scale-up {
        transform: scale(1.3);
    }


    /* Landscape layout on larger screens */
    @media (min-width: 768px) {
        .service-item {
            flex-direction: row;
        }

        .service-item img {
            width: 200px;
            height: 150px;
            margin-bottom: 0;
            border-radius: 10px 0 0 10px;
        }

        .service-item-details {
            padding-left: 20px;
            width: 100%;
        }
    }

    /* Fly-to-Cart Animation */
    @keyframes flyToCart {
        0% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }

        50% {
            transform: scale(0.5) translateY(-20px);
        }

        100% {
            opacity: 0;
            transform: scale(0) translate(calc(100vw - 100px), -50px);
        }
    }

    .fly-to-cart {
        position: fixed;
        z-index: 1000;
        transition: all 0.85s ease;
    }



    #cart-icon span {
        position: absolute;
        font-size: 11px;
        margin-top: -7px;
        background-color: red;
        height: 15px;
    }
    </style>
</head>

<body>


    <!-- End Preloader -->
    <!-- Preloader -->
   
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
                                <img src="../../images\Rapide.png" alt="">
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
                                        <li ><a href="../Homepage.php">Home</a>
                                        </li>
                                        <!-- <li><a href="#">Doctos </a></li> -->
                                        <li class="active"><a href="service_list.php">Services </a></li>
                                        <li><a href="#">Map <i class="icofont-rounded-down"></i></a>
                                            <ul class="dropdown">
                                                <li><a href="../../map/gmap.php">Rapide Cavite Map</a></li>
                                                <li><a href="../../map/emergency_form.php">Emergency Map</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Chat <i class="icofont-rounded-down"></i></a>
                                        <ul class="dropdown">
                                                <?php
                                           
                                             
                                                $branch_query = "SELECT id, fname, lname FROM users WHERE is_admin = 1";
                                                $branch_result = $conn->query($branch_query);

                                                if ($branch_result && $branch_result->num_rows > 0):
                                                    while ($branch = $branch_result->fetch_assoc()):
                                                        $branch_name = $branch['fname'] . ' ' . $branch['lname'];
                                                        ?>
                                                        <li>
                                                            <a href="../message/chatbox.php?branch_id=<?php echo $branch['id']; ?>">
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
                                        <li><a href="../Act.php">Activities</a></li>
                                    </ul>
                                </nav>
                            </div>
                            <!--/ End Main Menu -->
                        </div>
                        <div class="col-lg-2 col-12 mt-3">
                        <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false" style="  width: 100%;
                                padding: 10px;
                                font-size: 16px;
                                box-sizing: border-box;">
                                    <?php echo htmlspecialchars($username); ?>
                                    <!-- Display the username -->
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="../../login/profile_setup.php">Profile</a></li>
                                    <li><a class="dropdown-item" href="../../login/logout.php">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Header Inner -->
    </header>
    <!-- Navbar -->





    <div class="container mt-4">
        <div class="search-container">
            <nav class="custom-navbar">
                <a href="cart.php" id="cart-icon" class="nav-link">
                    Cart
                    <span id="cart-notification" class="badge"><?php echo $cartCount; ?></span>
                </a>
                <h2 class="text-center mb-4">Services List</h2>
                <a href="booking_history.php" class="nav-link">
                    History
                </a>
            </nav>


            <input type="text" id="searchInput" class="search-input" placeholder=" " />
            <label for="searchInput" class="search-placeholder">Search for services...</label>
        </div>

        <!-- Category Navigation -->
        <div class="category-nav-wrapper">
            <div class="category-nav">
                <?php
        $categories = [
            'PMS Package' => ['package_list'],
            'Periodic Services' => ['service_list'],
            'Brakes Services' => ['brake_service', 'brakes_table'],
            'AC Services' => ['ac_service']
        ];
        $selected_category = isset($_GET['category']) ? $_GET['category'] : array_key_first($categories);
        foreach ($categories as $category_name => $table_names) {
            $active_class = ($category_name == $selected_category) ? 'active' : '';
            $url_category_name = urlencode($category_name);
            echo "<a href='?category=$url_category_name' class='$active_class'>$category_name</a>";
        }
        ?>
            </div>
        </div>


        <!-- Services List -->
        <div class="services" id="servicesList">
            <?php
    if (array_key_exists($selected_category, $categories)) {
        $table_names = $categories[$selected_category];
        foreach ($table_names as $table_name) {
            $query = "SELECT id, name, description, image, price FROM $table_name";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $service_id = $row['id'];
                    $unique_id = $table_name . '_' . $service_id;
                    $services_name = $row['name'];
                    $services_price = $row['price'];

                    // Get the image path from the database
                    $image_path = '../../' . htmlspecialchars($row['image']);
                    
                    // Debugging: Display the full image path and check if the file exists
                    if (!file_exists($image_path)) {
                        echo "<p>Debug: Image file not found at <strong>$image_path</strong>. Please check the file path and database value.</p>";
                    }

                    echo '
                    <div class="service-item">
                        <img src="' . $image_path . '" alt="Service Image"F$>
                        <div class="service-item-details">
                            <h4>' . htmlspecialchars($row['name']) . '</h4>
                            <h5>₱ ' . number_format($row['price'], 2) . '</h5>
                            <p>' . htmlspecialchars($row['description']) . '</p>
                            <form class="add-to-cart-form" action="addcart.php" method="POST">
                                <input type="hidden" name="service_id" value="' . htmlspecialchars($service_id, ENT_QUOTES, 'UTF-8') . '">
                                <input type="hidden" name="service_table" value="' . htmlspecialchars($table_name) . '">
                                <input type="hidden" name="service_name" value="' . htmlspecialchars($services_name) . '">
                                <input type="hidden" name="service_price" value="' . htmlspecialchars($services_price) . '">

                                <button type="submit" class="add-to-cart-btn">BOOK</button>
                            </form>
                        </div>
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

        <script>
        const cartNotification = document.getElementById('cart-notification');
        let currentCount = parseInt(cartNotification.innerText) || 0;
        cartNotification.innerText = currentCount;
        cartNotification.classList.add('scale-up');
        setTimeout(() => {
            cartNotification.classList.remove('scale-up');
        }, 300);




        document.getElementById('searchInput').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const serviceItems = document.querySelectorAll('.service-item');

            serviceItems.forEach(service => {
                const serviceName = service.querySelector('h4').textContent.toLowerCase();
                if (serviceName.includes(filter)) {
                    service.style.display = 'flex';
                } else {
                    service.style.display = 'none';
                }
            });
        });

        document.querySelectorAll('.add-to-cart-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                const serviceItem = form.closest('.service-item');
                const serviceImage = serviceItem.querySelector('img');
                const cartIcon = document.getElementById('cart-icon');
                const cloneImage = serviceImage.cloneNode(true);

                // Apply the fly-to-cart animation
                const imgRect = serviceImage.getBoundingClientRect();
                const cartRect = cartIcon.getBoundingClientRect();

                cloneImage.classList.add('fly-to-cart');
                cloneImage.style.left = `${imgRect.left}px`;
                cloneImage.style.top = `${imgRect.top}px`;
                cloneImage.style.width = `${imgRect.width}px`;
                cloneImage.style.height = `${imgRect.height}px`;
                document.body.appendChild(cloneImage);

                setTimeout(() => {
                    cloneImage.style.left = `${cartRect.left}px`;
                    cloneImage.style.top = `${cartRect.top}px`;
                    cloneImage.style.transform = 'scale(0.1)';
                    cloneImage.style.opacity = '0';
                }, 10);

                setTimeout(() => {
                    cloneImage.remove();
                }, 750);

                // Send data to backend
                fetch(form.action, {
                        method: form.method,
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => console.log(data)) // Debugging, remove this after verifying
                    .catch(error => console.error('Error:', error));

                // Update cart notification
                const cartNotification = document.getElementById('cart-notification');
                let currentCount = parseInt(cartNotification.innerText) || 0;
                cartNotification.innerText = currentCount + 1;
                cartNotification.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    cartNotification.style.transform = 'scale(1)';
                }, 300);
            });
        });
        </script>

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