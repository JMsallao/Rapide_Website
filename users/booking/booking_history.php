<?php
    include('../../sessioncheck.php');
    include('../../connection.php');
    include('../../users/style.php');

    // Fetch package services from the database
    $sql_package = "SELECT * FROM package_list";
    $result_package = $conn->query($sql_package);

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

    // Fetch bookings filtered by status if provided
    $selected_status = isset($_GET['status']) ? $_GET['status'] : '';
    $query = "SELECT * FROM bookings WHERE user_id = ?";
    if (!empty($selected_status)) {
        $query .= " AND status = ?";
    }
    $query .= " ORDER BY booking_date DESC";

    $stmt = $conn->prepare($query);
    if (!empty($selected_status)) {
        $stmt->bind_param("is", $user_id, $selected_status);
    } else {
        $stmt->bind_param("i", $user_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();
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
    body {
        background-color: #f7f7f7;
        color: #333;
    }


    h2 {
        color: black;
        font-weight: bold;
        text-align: center;
        margin: 0;
        font-size: 15px;
    }


    /* Search Bar Styling */
    .search-bar {
        display: flex;
        padding: 5px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .search-bar input[type="text"] {
        width: 100%;
        max-width: 600px;
        padding: 20px;
        padding-top: 20px;
        border-radius: 5px;
        border: 1px solid #ccc;
        outline: none;
        font-size: 16px;
        height: 20px;
    }

    .search-placeholder {
        position: absolute;
        padding-left: 10px;
        font-size: 15px;
        color: #999;
        transition: all 0.3s ease;
        pointer-events: none;

    }

    .search-bar input:focus+.search-placeholder,
    .search-bar input:not(:placeholder-shown)+.search-placeholder {
        top: 210px;
        font-size: 12px;
        color: #ffc107;

    }

    /* Filter Dropdown Styling */
    .filter-dropdown {
        margin-top: 12px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    /* Card Styling */

    .booking-card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        padding: 20px;
        margin-bottom: 20px;
        transition: opacity 0.3s ease;
        display: flex;
        flex-direction: column;

        width: 100%;
    }

    .booking-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        /* Allow wrapping for smaller screens */
        gap: 10px;
        /* Add spacing between elements */
    }

    .booking-details {
        color: #5d4037;

    }

    .booking-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .booking-details {
        color: #5d4037;

    }

    .view-button {
        background-color: #ffc107;
        color: #333;
        font-weight: bold;
        font-size: 14px;
        border: none;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .view-button:hover {
        background-color: #ffa000;
        color: #fff;
    }

    /* Custom Navbar Styling */
    /* Custom Navbar Styling */
    .custom-navbar {
        position: sticky;
        top: 0;
        z-index: 1;
        /* Ensure navbar is above sticky-header */
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 10px 20px;
        color: black;
        font-size: 16px;

    }

    .navbar-left {
        flex: 1;
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .navbar-center {
        flex: 1;
        text-align: center;
        font-weight: bold;
        font-size: 18px;
    }

    .navbar-right {
        flex: 1;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        /* Space between Edit button and chat icon */
    }

    .back-button {

        text-decoration: none;
        font-size: 14px;
        display: flex;
        align-items: center;

        padding: 13px;
        border-radius: 3px;
    }

    .back-button i {
        margin-right: 10px;
    }

    #edit-toggle {
        background-color: yellow;
        color: black;
    }


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


    #clearHistory {
        margin-left: 10px;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 5px;
        display: inline-block;
        transition: all 0.3s ease;
    }

    #clearHistory:hover {
        background-color: #b02a37;
        color: white;
    }

    @media (min-width: 320px) {
        .search-bar input[type="text"] {
            width: 90%;
        }

        .filter-dropdown {
            width: 40%;
            margin-top: 15px;
            padding-left: 2px;
            padding-top: 2px;
        }

    }
    </style>
</head>

<body>
    
  
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
                                        <li><a href="../Homepage.php">Home</a>
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
                                        <li><a href="../Act.php">Activities</a></li>
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
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="../../login/profile_setup.php">Profile</a></li>
                                        <li><a class="dropdown-item" href="../../login/logout.php">Logout</a></li>
                                    </ul>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Header Inner -->
    </header>





    <!-- Booking History -->
    <div class="container">


        <!-- Search Bar with Floating Placeholder and Filter Dropdown -->
        <div class="sticky-header">

            <nav class="custom-navbar">
                <div class="navbar-left">
                    <a href="service_list.php" class="back-button">
                        Back
                    </a>
                </div>
                <h2>Your Booking History</h2>
                <div class="navbar-right">
                    <button id="edit-toggle" class="btn btn-link " onclick="toggleEditMode()">Edit</button>
                    <div class="chat-icon">
                        <a href="../../message_kineme\user_ansya\chat_kineme.php">
                            <i class="fas fa-comment-dots"></i>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Search Bar with Floating Placeholder and Filter Dropdown -->
            <div class="search-bar">
                <input type="text" id="searchInput" class="form-control" placeholder=" " autocomplete="off">
                <label class="search-placeholder" for="searchInput">Search by Booking ID, Date...</label>
                <select id="statusFilter" class="filter-dropdown">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="rejected">Rejected</option>
                    <option value="canceled">Canceled</option>
                    <option value="requested">Requested</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <button id="clearHistory" class="btn btn-danger d-none" onclick="clearHistory()">Clear History</button>
        </div>


        <!-- Booking Cards -->
        <div class="booking-grid" id="bookingGrid">
            <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="booking-card" data-id="<?php echo $row['booking_id']; ?>"
                data-status="<?php echo strtolower($row['status']); ?>"
                data-date="<?php echo date('F j, Y, g:i A', strtotime($row['booking_date'])); ?>">
                <div class="booking-info">
                    <h4 class="booking-details">Booking ID: <?php echo $row['booking_id']; ?></h4>
                    <h5 class="booking-details">₱<?php echo number_format($row['total_price'], 2); ?></h5>
                </div>
                <p class="booking-details">Date: <?php echo date('F j, Y, g:i A', strtotime($row['booking_date'])); ?>
                </p>
                <p class="booking-details">Status: <?php echo ucfirst($row['status']); ?></p>
                <a href="moveOn.php?booking_id=<?php echo $row['booking_id']; ?>" class="view-button">View Details</a>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
            <p class="text-center">You have no booking history.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        // Filter bookings by Booking ID
        $('#searchInput').on('keyup', function() {
            const searchValue = $(this).val().toLowerCase();
            $('.booking-card').each(function() {
                const bookingID = $(this).data('id').toString()
                    .toLowerCase(); // Convert to string for comparison
                if (bookingID.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Filter bookings by status
        $('#statusFilter').on('change', function() {
            const selectedStatus = $(this).val();

            // AJAX request to filter bookings
            $.ajax({
                url: '', // Current page URL
                type: 'GET',
                data: {
                    status: selectedStatus
                },
                success: function(response) {
                    // Replace the booking grid with the filtered data
                    const newContent = $(response).find('#bookingGrid').html();
                    $('#bookingGrid').html(newContent);
                }
            });
        });
    });




    // Toggle Edit Mode
    let isEditMode = false;

    function toggleEditMode() {
        isEditMode = !isEditMode;
        const clearHistoryButton = document.getElementById('clearHistory');

        // Toggle visibility of Clear History button
        if (isEditMode) {
            clearHistoryButton.classList.remove('d-none');
        } else {
            clearHistoryButton.classList.add('d-none');
        }
    }

    // Clear History functionality
    function clearHistory() {
        if (confirm('Are you sure you want to clear your booking history? This action cannot be undone.')) {
            // Here you can send an AJAX request or redirect to a server-side script
            // For example, redirect to a PHP script to handle clearing history:
            window.location.href = "clear_history.php"; // Replace with your actual endpoint
        }
    }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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