<?php
include ('../connection.php');
include('../header.php');
include('../user/user_navbar.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .category-nav {
            margin-bottom: 20px;
        }
        .category-nav a {
            margin-right: 10px;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            color: black;
            text-decoration: none;
            font-weight: bold;
        }
        .category-nav a.active {
            background-color: #ffc107;
            color: white;
        }
        .service-item {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .service-item img {
            max-width: 100px;
            height: auto;
        }
        .service-item-details {
            flex-grow: 1;
            margin-left: 20px;
        }
        .add-to-cart-btn {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            cursor: pointer;
        }
        .add-to-cart-btn:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
