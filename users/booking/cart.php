<?php
    // Include necessary files
    include('../../sessioncheck.php');
    include('../../connection.php');

    include('../../users/style.php');

    // Ensure the session user ID is set correctly
    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
    } else {
        die("User ID is not set in the session.");
    }

    // Fetch cart items for the logged-in user, including the image path
    $query = "SELECT cart.*, package_list.image AS service_image 
            FROM cart 
            LEFT JOIN package_list ON cart.service_id = package_list.id 
            WHERE cart.user_id = ? AND cart.status = 'pending'";
    $stmtCart = $conn->prepare($query);

    $cart_items = [];
    $grandTotal = 0; // Initialize grand total

    if ($stmtCart) {
        $stmtCart->bind_param("i", $user_id); // Bind the user ID
        $stmtCart->execute();
        $resultCart = $stmtCart->get_result();

        while ($row = $resultCart->fetch_assoc()) {
            $cart_items[] = $row;

            // Calculate total price for each item and add to grand total
            $unit_price = $row['price'];
            $quantity = $row['quantity'];
            $total_price_per_item = $unit_price * $quantity;
            $grandTotal += $total_price_per_item;
        }
        $stmtCart->close();
    } else {
        die("Failed to prepare the cart query.");
    }
    // Query to get package services from the database
    $sql_package = "SELECT * FROM package_list";
    $result_package = $conn->query($sql_package);

    // Query to get 'about' information
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
    <link rel="shortcut icon" href="../../images/rapide_logo.png" type="image/x-icon">

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
    h2 {
        color: #dc3545;
        font-weight: bold;
    }

    /* Cart Summary */
    .cart-summary {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        color: #333;
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 10;
        margin-bottom: 20px;
    }

    .buttons {
        display: flex;
        gap: 10px;
    }

    /* Action Buttons */
    .action-btn {
        padding: 10px 20px;
        border-radius: 20px;
        transition: background-color 0.3s ease;
    }

    .action-btn:hover {
        background-color: #e0a800;
        color: #fff;
    }

    .d-none {
        display: none !important;
    }

    /* Cart Items */
    .cart-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .cart-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .item-checkbox {
        margin-right: 10px;
    }

    .item-image {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        object-fit: cover;
    }

    /* Responsive Design */
    @media (max-width: 768px) {


        .cart-summary {
            flex-direction: column;
            gap: 10px;
        }

        .buttons {
            flex-direction: column;
            width: 100%;
        }
    }



    .custom-checkbox {
        width: 20px;
        height: 20px;
        border: 2px solid #dc3545;
        /* Red border */
        border-radius: 5px;
        /* Optional: Rounded edges */
        background-color: #fff;
        /* White background */
        cursor: pointer;
        display: inline-block;
        position: relative;
        margin-right: 10px;
        transition: all 0.3s ease-in-out;
    }

    .custom-checkbox::after {
        content: '';
        width: 20px;
        height: 20px;
        background-color: #dc3545;
        /* Red fill for checked state */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        border-radius: 3px;
        /* Optional: Rounded edges */
        transition: all 0.3s ease-in-out;
    }

    .item-checkbox:checked+.custom-checkbox::after {
        transform: translate(-50%, -50%) scale(1);
        /* Scale up when checked */
    }

    .custom-checkbox:hover {
        background-color: #f8d7da;
        /* Light red on hover */
        border-color: #b02a37;
        /* Darker red border */
    }



    h2 {
        color: #dc3545;
        font-weight: bold;
    }

    /* Custom Navbar Styling */
    .custom-navbar {
        position: sticky;
        top: 0;
        z-index: 1055;
        /* Ensure navbar is above sticky-header */
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 10px 20px;
        color: black;
        font-size: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .navbar-left {
        flex: 0;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-left: 20px;
    }

    .navbar-center {
        flex: 7;
        text-align: center;
        font-weight: bold;
        font-size: 14px;
    }

    .navbar-right {
        flex: 3;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;

        /* Space between Edit button and chat icon */
    }

    .back-button h5 {
        color: black;
        text-decoration: none;
        font-size: 15px;
        display: flex;


    }



    #edit-toggle {
        color: black;
        font-size: 14px;
        text-decoration: none;
        background: none;
        border: none;
        cursor: pointer;
        background-color: yellow;
    }

    #edit-toggle:hover {
        text-decoration: underline;
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


    /* Sticky Cart Summary */
    .cart-summary {
        position: sticky;
        top: 60px;
        /* Adjust to leave room for the navbar */
        z-index: 1050;
        /* Ensure it appears below the navbar */
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .buttons {
        display: flex;
        gap: 10px;
    }

    /* General Styling for Action Buttons */
    .action-btn {
        padding: 10px 20px;
        border-radius: 20px;
        transition: background-color 0.3s ease;
    }

    .action-btn:hover {
        background-color: #e0a800;
        color: #fff;
    }

    .d-none {
        display: none !important;
    }


    .delete-btn {
        background-color: #dc3545;
        color: #fff;
        font-weight: bold;
        height: 40px;
    }

    .cart-items-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* 
    .cart-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .cart-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
    } */

    .cart-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 15px;
    }

    .details {
        text-align: left;
        flex: 1;
    }

    .item-checkbox {
        margin-left: 15px;
    }

    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }

        .cart-summary {
            flex-direction: column;
            gap: 10px;
        }

        .buttons {
            flex-direction: column;
            width: 100%;
        }
    }



    @media (max-width: 320px) {
        .back-button h5 {

            left: 0;
        }


    }

    /* Sticky Footer Styling */
    .footer-container {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #fff;
        padding: 15px 20px;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        z-index: 10;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .footer-container .grand-total {
        font-size: 15px;
        font-weight: bold;
        color: #333;
    }

    .footer-container .btn-proceed {
        background-color: #dc3545;
        color: #fff;
        font-weight: bold;
        border: none;
        padding: 10px 20px;
        border-radius: 20px;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .footer-container .btn-proceed:hover {
        background-color: #b02a37;
        color: #fff;
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






    <div class="container mt-3">
        <!-- Navbar -->
        <nav class="custom-navbar">
            <div class="navbar-left">
                <a href="service_list.php" class="back-button">
                    <h5>Back</h5>
                </a>
            </div>
            <div class="navbar-center">
                <span>Cart Summary (<?php echo count($cart_items); ?>)</span>
            </div>
            <button type="button" class="btn btn-secondary" id="edit-toggle" onclick="toggleEditMode()">Edit</button>
        </nav>


        <?php if (count($cart_items) > 0): ?>
        <!-- Form for selecting items to delete or move -->
        <form id="cart-form" action="proceed.php" method="POST" onsubmit="return validateSelection()">

            <!-- Sticky Cart Summary at the Top -->
            <div class="cart-summary">
                <div><strong>Grand Total: ₱<?php echo number_format($grandTotal, 2); ?></strong></div>
                <div class="buttons">

                    <button type="button" class="btn btn-secondary action-btn d-none" id="select-all"
                        onclick="toggleSelectAll()">Select All</button>
                    <button type="button" class="btn btn-danger action-btn d-none" id="delete-selected"
                        onclick="deleteSelectedItems()">Delete Selected</button>
                </div>
            </div>

            <div class="cart-items-container">
                <?php foreach ($cart_items as $index => $item): ?>
                <?php
                $unit_price = $item['price'];
                $quantity = $item['quantity'];
                $total_price_per_item = $unit_price * $quantity;

                // Use the image path from the database or set a default if not found
                $image_path = !empty($item['service_image']) ? '../../' . $item['service_image'] : '../../images/default-image.jpg';
                ?>
                <div class="cart-item" id="cart-item-<?php echo $item['id']; ?>">
                    <div class="cart-left">
                        <input type="checkbox" name="cart_ids[]" value="<?php echo $item['id']; ?>"
                            class="item-checkbox d-none" id="checkbox-<?php echo $item['id']; ?>"
                            data-price="<?php echo $unit_price; ?>" data-quantity="<?php echo $quantity; ?>">
                        <label for="checkbox-<?php echo $item['id']; ?>" class="custom-checkbox"></label>

                        <!-- Display the fetched image -->
                        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Item Image" class="item-image">
                    </div>

                    <div class="details">
                        <strong><?php echo htmlspecialchars($item['service_name']); ?></strong><br>
                        <span class="price">₱<?php echo number_format($unit_price, 2); ?></span><br>
                        <span class="total-price">Total: ₱<?php echo number_format($total_price_per_item, 2); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>

            </div>
        </form>
        <?php else: ?>
        <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
    <div class="footer-container">
        <span class="grand-total">Total: ₱<?php echo number_format($grandTotal, 2); ?></span>
        <button type="button" class="btn-proceed" id="proceed-button-footer" onclick="proceedToBooking()">Proceed to
            Booking</button>
    </div>


    <script>
        let isEditMode = false;
        let allSelected = false;

        // Toggle Edit Mode
        function toggleEditMode() {
            isEditMode = !isEditMode;

            // Toggle the visibility of buttons
            document.getElementById('delete-selected').classList.toggle('d-none', !isEditMode);
            document.getElementById('select-all').classList.toggle('d-none', !isEditMode);

            // Toggle checkboxes
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.classList.toggle('d-none', !isEditMode);
            });

            // Update the Edit button text
            document.getElementById('edit-toggle').textContent = isEditMode ? 'Done' : 'Edit';
        }

        // Toggle Select All / Deselect All
        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            allSelected = !allSelected;

            checkboxes.forEach(checkbox => {
                checkbox.checked = allSelected;
            });

            document.getElementById('select-all').textContent = allSelected ? 'Deselect All' : 'Select All';
            updateVisibilityAndTotal();
        }

        // Delete selected items
        function deleteSelectedItems() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const selected = Array.from(checkboxes).filter(checkbox => checkbox.checked);

            if (selected.length === 0) {
                alert('Please select at least one item to delete.');
                return;
            }

            if (confirm('Are you sure you want to delete the selected items?')) {
                // Collect IDs of selected items
                const ids = selected.map(checkbox => checkbox.value);

                // Send a request to delete the items
                fetch('delete_item.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ ids })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Items successfully deleted!');
                        window.location.reload();
                    } else {
                        alert('Error deleting items: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // Proceed to Booking
        function proceedToBooking() {
            const selectedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
            if (selectedCheckboxes.length === 0) {
                alert('You need to select items before proceeding to checkout.');
                return;
            }
            document.getElementById('cart-form').submit();
        }

        // Calculate and update the total dynamically based on selected items
        function updateTotal() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            let total = 0;

            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    const itemPrice = parseFloat(checkbox.dataset.price);
                    const itemQuantity = parseInt(checkbox.dataset.quantity);
                    total += itemPrice * itemQuantity;
                }
            });

            // Update the footer total
            const grandTotalElement = document.querySelector('.grand-total');
            grandTotalElement.textContent = `Total: ₱${total.toFixed(2)}`;

            // Hide or show footer container
            const footerContainer = document.querySelector('.footer-container');
            if (total > 0) {
                footerContainer.style.display = 'flex';
            } else {
                footerContainer.style.display = 'none';
            }
        }

        // Update visibility of "Total" and "Proceed to Checkout" button
        function updateVisibilityAndTotal() {
            const selectedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
            const footerContainer = document.querySelector('.footer-container');

            if (selectedCheckboxes.length > 0) {
                footerContainer.style.display = 'flex';
            } else {
                footerContainer.style.display = 'none';
            }

            updateTotal();
        }

        // Add event listeners to checkboxes to trigger total update
        document.querySelectorAll('.item-checkbox').forEach((checkbox) => {
            checkbox.addEventListener('change', updateVisibilityAndTotal);
        });

        // Hide the footer container initially if no items are selected
        document.addEventListener('DOMContentLoaded', () => {
            updateVisibilityAndTotal();
        });

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