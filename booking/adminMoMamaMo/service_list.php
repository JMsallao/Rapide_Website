<?php
include('../../sessioncheck.php');
include('../../connection.php');



// Define categories and their respective table mappings
$categories = [
    'PMS Package' => ['package_list'],
    'Periodic Services' => ['service_list'],
    'Brakes Services' => ['brake_service', 'brakes_table'],
    'AC Services' => ['ac_service']
];

// Get the selected category from the URL, default to the first category
$selected_category = isset($_GET['category']) ? $_GET['category'] : array_key_first($categories);
$services = [];

// Fetch data based on the selected category
if (array_key_exists($selected_category, $categories)) {
    foreach ($categories[$selected_category] as $table_name) {
        $query = "SELECT * FROM $table_name";
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $services[] = $row; // Append results to the services array
            }
        }
    }
}

    // Handle GET and POST requests for editing
    if (isset($_GET['edit_id']) && isset($_GET['category'])) {
        // Fetch service data for editing
        $edit_id = intval($_GET['edit_id']);
        $edit_category = $_GET['category'];

        if (isset($categories[$edit_category])) {
            $table_name = $categories[$edit_category][0];
            $query = "SELECT * FROM $table_name WHERE id = ?";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("i", $edit_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $service_to_edit = $result->fetch_assoc();
                $stmt->close();
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_service'])) {
        // Update service details
        $edit_id = intval($_POST['edit_id']);
        $edit_category = $_POST['edit_category'];
        $edit_name = $_POST['edit_name'];
        $edit_description = $_POST['edit_description'];
        $edit_price = $_POST['edit_price'];
        $edit_image = $_POST['edit_image'];

        if (isset($categories[$edit_category])) {
            $table_name = $categories[$edit_category][0];
            $query = "UPDATE $table_name SET name = ?, description = ?, price = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("ssdsi", $edit_name, $edit_description, $edit_price, $edit_image, $edit_id);
                if ($stmt->execute()) {
                    // Set success message
                    $_SESSION['success_message'] = "Service updated successfully!";
                } else {
                    // Set error message
                    $_SESSION['error_message'] = "Error updating service: " . $stmt->error;
                }
                $stmt->close();
            }
        }
        // Redirect to avoid re-executing the update on refresh
        header("Location: service_list.php?category=" . urlencode($edit_category));
        exit;
    }


    // Handle Deletion Logic
    if (isset($_GET['id']) && isset($_GET['category'])) {
        $id = intval($_GET['id']); // Sanitize the ID
        $category = $_GET['category'];

        // Validate category
        if (!isset($categories[$category])) {
            die("Invalid category selected.");
        }

        // Get the first table in the category mapping
        $table_name = $categories[$category][0];

        // Prepare the DELETE query
        $query = "DELETE FROM $table_name WHERE id = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("i", $id); // Bind the ID
            if ($stmt->execute()) {
                // Set success message in session
                $_SESSION['success_message'] = "Service deleted successfully!";
            } else {
                // Set error message in session
                $_SESSION['error_message'] = "Error deleting service: " . $stmt->error;
            }
            $stmt->close(); // Close the statement
        } else {
            die("Database Error: " . $conn->error);
        }

        // Redirect back to avoid re-executing the deletion on refresh
        header("Location: service_list.php?category=" . urlencode($category));
        exit;
    }


    // Handle Add Service Form Submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
        // Retrieve form data
        $category = $_POST['category']; // Selected category
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_POST['image']; // Selected image from dropdown

        // Debugging: Ensure the image path is submitted correctly
        // echo "Selected image path: $image<br>";

        // Validate category selection
        if (!isset($categories[$category])) {
            die("Invalid category selected.");
        }

        // Map category to the appropriate table
        $table_name = $categories[$category][0]; // Use the first table in the category mapping

        // Validate required fields
        if (empty($name) || empty($description) || empty($price) || empty($image)) {
            die("All fields are required.");
        }

        // Prepare and execute the SQL query to insert data
        $query = "INSERT INTO $table_name (name, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ssds", $name, $description, $price, $image); // Bind parameters
            if ($stmt->execute()) {
                // Redirect to the appropriate category after successful insertion
                echo "Service added succesfully. ";
                header("Location: service_list.php?category=" . urlencode($category));
                exit;
            } else {
                echo "Database Error: " . $stmt->error;
            }
            $stmt->close(); // Close the prepared statement
        } else {
            echo "Database Error: " . $conn->error;
        }
    }



    $query = "SELECT b.booking_id, b.created_at, u.username 
FROM bookings b
JOIN users u ON b.user_id = u.id
WHERE b.status = 'pending'
ORDER BY b.created_at DESC
LIMIT 5"; // Limiting to 5 recent pending bookings
$result = mysqli_query($conn, $query);
$notif_count = mysqli_num_rows($result); // Count the number of pending bookings

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>RAPIDE ADMIN </title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="assets/js/select.dataTables.min.css">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../../images\rapide_logo.png" type="image/x-icon">

    <style>
    /* Badge counter for notifications */
    .badge-counter {
        position: absolute;
        top: 8px;
        right: 8px;
        transform: translate(50%, -50%);
        font-size: 12px;
        background-color: #ff3d3d;
        /* Vibrant red */
        color: white;
        padding: 4px 8px;
        border-radius: 50%;
        /* Perfect circle */
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        font-weight: bold;
    }

    /* Service image styling */
    .service-image {
        max-width: 70px;
        max-height: 70px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    /* Category filter links */
    .category-filter a {
        margin: 5px;
        padding: 10px 15px;
        text-decoration: none;
        color: #333;
        /* Dark text for contrast */
        background-color: #ffc107;
        /* Yellow theme */
        border-radius: 5px;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
    }

    .category-filter a.active {
        background-color: #ffca2c;
        /* Slightly darker yellow for active */
    }

    .category-filter a:hover {
        background-color: #ffb300;
        /* Hover effect */
    }

    /* Table styling */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
    }

    table,
    th,
    td {
        border: 1px solid #ccc;
    }

    th {
        background-color: #ffe599;
        /* Light yellow header */
        color: #333;
        text-transform: uppercase;
        font-weight: bold;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    /* Action buttons */
    .action-buttons a {
        margin: 0 5px;
        padding: 5px 10px;
        text-decoration: none;
        color: #333;
        /* Dark text */
        background-color: #ffc107;
        /* Yellow theme */
        border-radius: 5px;
        font-size: 14px;
        display: inline-block;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
    }

    .action-buttons a.btn-danger:hover {
        background-color: #ffb300;
        /* Hover effect */
    }

    .action-buttons a.btn-primary:hover {
        background-color: #ffb300;
        /* Hover effect */
    }

    /* Add Service Button */
    .add-service-btn {
        margin: 20px 0;
        padding: 10px 20px;
        background-color: #ffc107;
        /* Yellow theme */
        color: #333;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
    }

    .add-service-btn:hover {
        background-color: #ffb300;
        /* Hover effect */
    }

    /* Modal styling */
    .modal-header {
        background-color: #ffe599;
        /* Light yellow */
        color: #333;
        font-weight: bold;
    }

    .modal-footer button {
        padding: 10px 20px;
        font-size: 14px;
        border-radius: 5px;
        font-weight: bold;
    }

    .modal-footer .btn-success {
        background-color: #ffc107;
        /* Yellow theme */
        color: #333;
        border: none;
    }

    .modal-footer .btn-success:hover {
        background-color: #ffb300;
    }

    .modal-footer .btn-secondary {
        background-color: #6c757d;
        /* Gray for close */
        color: white;
        border: none;
    }

    .modal-footer .btn-secondary:hover {
        opacity: 0.9;
    }
    </style>


</head>

<body class="with-welcome-text">
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <div class="me-3">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                        data-bs-toggle="minimize">
                        <span class="icon-menu"></span>
                    </button>
                </div>
                <div>
                    <a class="navbar-brand brand-logo" href="index.html">
                        <h2>Rapide</h2>
                    </a>
                    <a class="navbar-brand brand-logo-mini" href="index.html">
                        <h3>R</h3>
                    </a>
                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-top">
                <ul class="navbar-nav">
                    <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                        <h1 class="welcome-text">Rapide<span class="text-black fw-bold"> Kawit </span></h1>
                        <h3 class="welcome-sub-text">Pa backend po nito</h3>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-none d-lg-block">
                        <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
                            <span class="input-group-addon input-group-prepend border-right">
                                <span class="icon-calendar input-group-text calendar-icon"></span>
                            </span>
                            <input type="text" class="form-control">
                        </div>
                    </li>
                    <!-- <li class="nav-item">
                        <form class="search-form" action="#">
                            <i class="icon-search"></i>
                            <input type="search" class="form-control" placeholder="Search Here" title="Search here">
                        </form>
                    </li> -->


                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-bell"></i>
                            <!-- Counter - Alerts -->
                            <span
                                class="badge badge-danger badge-counter"><?= $notif_count > 0 ? $notif_count : ''; ?></span>
                        </a>
                        <!-- Dropdown - Alerts -->
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">Alerts Center</h6>

                            <?php if ($notif_count > 0): 
            while ($row = mysqli_fetch_assoc($result)): ?>
                            <a class="dropdown-item d-flex align-items-center"
                                href="../booking/adminMoMamaMo/bukingdets.php?booking_id=<?= $row['booking_id']; ?>">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-calendar-check text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">
                                        <?= date('F j, Y', strtotime($row['created_at'])); ?>
                                    </div>
                                    <span class="font-weight-bold"><?= htmlspecialchars($row['username']); ?>
                                        has a pending booking request.</span>
                                </div>
                            </a>
                            <?php endwhile;
        else: ?>
                            <a class="dropdown-item text-center small text-gray-500" href="#">No new alerts</a>
                            <?php endif; ?>

                            <a class="dropdown-item text-center small text-gray-500" href="all_alerts.php">Show All
                                Alerts</a>
                        </div>
                    </li>


                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="icon-mail icon-lg"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0"
                            aria-labelledby="countDropdown">
                            <h6 class="dropdown-header">
                                Message Center
                            </h6>

                            <?php
            // Query to fetch unique users who have sent messages to the admin, excluding admin's own messages
            $query = "SELECT u.id, u.username, MAX(m.created_at) AS last_message_time
                      FROM users u
                      JOIN message m ON u.id = m.sender
                      WHERE m.recipient = ? AND u.id != ?
                      GROUP BY u.id, u.username
                      ORDER BY last_message_time DESC
                      LIMIT 5"; // Limit to 5 recent users

            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $admin_id, $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Display each unique user who has sent messages to the admin
                while ($row = $result->fetch_assoc()) {
                    ?>
                            <a class="dropdown-item d-flex align-items-center"
                                href="../message_kineme/admin_kinems/eto_again.php?user_id=<?php echo $row['id']; ?>">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="img/default_profile.svg" alt="...">
                                    <div class="status-indicator bg-success"></div>
                                </div>
                                <div class="font-weight-bold">
                                    <div class="text-truncate">Chat with
                                        <?php echo htmlspecialchars($row['username']); ?>
                                    </div>
                                    <div class="small text-gray-500">
                                        Last message:
                                        <?php echo date('H:i A', strtotime($row['last_message_time'])); ?>
                                    </div>
                                </div>
                            </a>
                            <?php
                }
            } else {
                // If there are no chat users
                echo '<a class="dropdown-item d-flex align-items-center" href="#">';
                echo '<div class="font-weight-bold text-center">No chats found</div>';
                echo '</a>';
            }

            $stmt->close();
        ?>

                            <a class="dropdown-item text-center small text-gray-500"
                                href="../../message_kineme/user_ansya/chat_kineme.php">GO TO MESSAGES</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="img-xs rounded-circle" src="assets/images/faces/face8.jpg" alt="Profile image">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                            <div class="dropdown-header text-center">
                                <img class="img-md rounded-circle" src="assets/images/faces/face8.jpg"
                                    alt="Profile image">
                                <p class="mb-1 mt-3 fw-semibold">Allen Moreno</p>
                                <p class="fw-light text-muted mb-0">allenmoreno@gmail.com</p>
                            </div>
                            <a class="dropdown-item"><i
                                    class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile
                                <span class="badge badge-pill badge-danger">1</span></a>
                            <a class="dropdown-item"><i
                                    class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i>
                                Messages</a>
                            <a class="dropdown-item"><i
                                    class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i>
                                Activity</a>
                            <a class="dropdown-item"><i
                                    class="dropdown-item-icon mdi mdi-help-circle-outline text-primary me-2"></i>
                                FAQ</a>
                            <a class="dropdown-item"><i
                                    class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-bs-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>



        <!-- Side bar -->

        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">
                            <i class="mdi mdi-grid-large menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">UI Elements</li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
                            aria-controls="ui-basic">
                            <i class="menu-icon mdi mdi-floor-plan"></i>
                            <span class="menu-title">UI Elements</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link"
                                        href="pages/ui-features/buttons.html">Homepage</a></li>
                                <li class="nav-item"> <a class="nav-link"
                                        href="pages/ui-features/dropdowns.html">Index</a></li>
                                <li class="nav-item"> <a class="nav-link"
                                        href="pages/ui-features/typography.html">Contacts</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#form-elements" aria-controls="form-elements">
                            <i class="menu-icon mdi mdi-card-text-outline"></i>
                            <span class="menu-title">Schedule</span>
                            <i class="menu-arrow"></i>
                        </a>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../admin1\dist\pages\service_edit\service.php">
                            <i class="menu-icon mdi mdi-chart-line"></i>
                            <span class="menu-title">Services</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false"
                            aria-controls="tables">
                            <i class="menu-icon mdi mdi-table"></i>
                            <span class="menu-title">Tables</span>
                        </a>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false"
                            aria-controls="icons">
                            <i class="menu-icon mdi mdi-layers-outline"></i>
                            <span class="menu-title">Messages</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="icons">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link"
                                        href="../../message_kineme\Admin_Dasma\inbox_chariz.php">Chat</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="docs/documentation.html">
                            <i class="menu-icon mdi mdi-file-document"></i>
                            <span class="menu-title">Reports</span>
                        </a>
                    </li>
                </ul>
            </nav>






            <!-- partial -->




            <div class="container-fluid ">
                <div class="container ">
                    <h1>Admin - Service List</h1>

                    <!-- Add Service Button -->
                    <button class="btn btn-success add-service-btn" data-bs-toggle="modal"
                        data-bs-target="#addServiceModal">Add
                        New Service</button>

                    <!-- Category Filter -->
                    <div class="category-filter">
                        <?php
            foreach ($categories as $category_name => $table_names) {
                $active_class = ($category_name == $selected_category) ? 'active' : '';
                $url_category_name = urlencode($category_name);
                echo "<a href='?category=$url_category_name' class='$active_class'>$category_name</a>";
            }
            ?>
                    </div>

                    <!-- Service Table -->
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Service Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($services)) : ?>
                            <?php foreach ($services as $service) : ?>
                            <tr>
                                <td><?php echo $service['id']; ?></td>
                                <td>
                                    <?php if (!empty($service['image'])): ?>
                                    <img src="../../<?php echo $service['image']; ?>" alt="Service Image"
                                        class="service-image">
                                    <?php else: ?>
                                    <span>No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $service['name']; ?></td>
                                <td><?php echo $service['description']; ?></td>
                                <td>₱<?php echo number_format($service['price'], 2); ?></td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editServiceModal"
                                        onclick="setEditModalData('<?php echo $service['id']; ?>', '<?php echo htmlspecialchars($selected_category, ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($service['description'], ENT_QUOTES, 'UTF-8'); ?>', '<?php echo $service['price']; ?>', '<?php echo htmlspecialchars($service['image'], ENT_QUOTES, 'UTF-8'); ?>')">
                                        Edit
                                    </a>

                                    <a href="?id=<?php echo $service['id']; ?>&category=<?php echo urlencode($selected_category); ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this service?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else : ?>
                            <tr>
                                <td colspan="6">No services found for the selected category.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addServiceModalLabel">Add New Service</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Category Selection -->
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select class="form-control" id="category" name="category" required>
                                            <option value="">-- Select Category --</option>
                                            <?php
                                // Populate the categories from the $categories array
                                foreach ($categories as $category_name => $table_names) {
                                    echo "<option value='" . htmlspecialchars($category_name, ENT_QUOTES, 'UTF-8') . "'>$category_name</option>";
                                }
                                ?>
                                        </select>
                                    </div>

                                    <!-- Service Name -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Service Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description"
                                            required></textarea>
                                    </div>

                                    <!-- Price -->
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price"
                                            required>
                                    </div>

                                    <!-- Image Selection -->
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Choose Image</label>
                                        <select class="form-control" id="image" name="image" required>
                                            <option value="">-- Select Image --</option>
                                            <?php
                                $image_directory = __DIR__ . '/../../images/services_icon/';
                                if (is_dir($image_directory)) {
                                    $images = array_diff(scandir($image_directory), ['.', '..']);
                                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                                    foreach ($images as $image) {
                                        $file_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                                        if (in_array($file_extension, $allowed_extensions)) {
                                            echo "<option value='images/services_icon/" . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . "'>" 
                                                . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . "</option>";
                                        }
                                    }
                                } else {
                                    echo "<option value=''>Directory not found</option>";
                                }
                                ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success" name="add_service">Add
                                        Service</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php if (isset($service_to_edit)) : ?>


                <!-- Modal -->
                <div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="edit_id" value="<?php echo $service_to_edit['id']; ?>">
                                <input type="hidden" name="edit_category"
                                    value="<?php echo htmlspecialchars($edit_category, ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_name" class="form-label">Service Name</label>
                                        <input type="text" class="form-control" id="edit_name" name="edit_name"
                                            value="<?php echo htmlspecialchars($service_to_edit['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_description" class="form-label">Description</label>
                                        <textarea class="form-control" id="edit_description" name="edit_description"
                                            required><?php echo htmlspecialchars($service_to_edit['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_price" class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control" id="edit_price"
                                            name="edit_price" value="<?php echo $service_to_edit['price']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_image" class="form-label">Choose Image</label>
                                        <select class="form-control" id="edit_image" name="edit_image">
                                            <option
                                                value="<?php echo htmlspecialchars($service_to_edit['image'], ENT_QUOTES, 'UTF-8'); ?>">
                                                Current:
                                                <?php echo htmlspecialchars($service_to_edit['image'], ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                            <?php
              $image_directory = __DIR__ . '/../../images/services_icon/';
              if (is_dir($image_directory)) {
                  $images = array_diff(scandir($image_directory), ['.', '..']);
                  $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                  foreach ($images as $image) {
                      $file_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                      if (in_array($file_extension, $allowed_extensions)) {
                          echo "<option value='images/services_icon/" . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . "</option>";
                      }
                  }
              } else {
                  echo "<option value=''>Directory not found</option>";
              }
              ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" name="update_service">Save
                                        changes</button>
                                        <button type="submit" class="btn btn-success" name="add_service">Add
                                        Service</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div> -->

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success" name="update_service">Update
                                        Service</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- main-panel ends -->
        <!-- plugins:js -->

        <script src="assets/vendors/js/vendor.bundle.base.js"></script>
        <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <script src="assets/vendors/chart.js/chart.umd.js"></script>
        <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="assets/js/off-canvas.js"></script>
        <script src="assets/js/template.js"></script>
        <script src="assets/js/settings.js"></script>
        <script src="assets/js/hoverable-collapse.js"></script>
        <script src="assets/js/todolist.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page-->
        <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
        <script src="assets/js/dashboard.js"></script>
        <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
        <!-- End custom js for this page-->
</body>

</html>