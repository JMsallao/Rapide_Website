<?php
    include('../../connection.php');
    include('../../sessioncheck.php');

    if (isset($_SESSION['id'])) {
        $admin_id = $_SESSION['id']; // Assuming admin's ID is stored in the session
    } else {
        die("Admin not logged in.");
    }

    // Check if the logged-in user is an admin
    $query = "SELECT is_admin, fname, lname FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['is_admin'] != 1) {
            die("Access denied. You are not authorized to view this page.");
        }

        // Assign fname and lname for displaying on the dashboard
        $fname = $user['fname'];
        $lname = $user['lname'];
    } else {
        die("User not found.");
    }
    $stmt->close();

   // Fetch available images from the uploads folder, including subdirectories
    $upload_dir = "../../images/";
    $available_images = [];

    // Recursive function to scan directories
    function fetch_images_from_dir($directory, $base_dir, &$image_list) {
        if (is_dir($directory)) {
            $files = scandir($directory);
            foreach ($files as $file) {
                $file_path = $directory . DIRECTORY_SEPARATOR . $file;
                if (!in_array($file, ['.', '..'])) {
                    if (is_dir($file_path)) {
                        // Recursively fetch images from subdirectories
                        fetch_images_from_dir($file_path, $base_dir, $image_list);
                    } elseif (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                        // Adjust path relative to $upload_dir
                        $relative_dir = str_replace($base_dir, '', $directory); // Get relative directory
                        $relative_dir = trim($relative_dir, '/\\'); // Remove leading/trailing slashes

                        // Check if the file is in the base directory or a subdirectory
                        if ($relative_dir === '') {
                            // File is in the base directory (images/)
                            $relative_path = '../images/' . $file;
                        } else {
                            // File is in a subdirectory (e.g., services_icon/)
                            $relative_path = '../images/' . $relative_dir . '/' . $file;
                        }

                        // Add the relative path to the image list
                        $image_list[] = str_replace(['\\'], ['/'], $relative_path); // Convert backslashes to slashes
                    }
                }
            }
        }
    }

    // Call the function on the upload directory
    fetch_images_from_dir($upload_dir, $upload_dir, $available_images);

        // Fetch recent "pending" booking notifications
        $query = "SELECT b.booking_id, b.created_at, u.username 
                FROM bookings b
                JOIN users u ON b.user_id = u.id
                WHERE b.status = 'pending'
                ORDER BY b.created_at DESC
                LIMIT 5";
        $result = mysqli_query($conn, $query);
        $notif_count = mysqli_num_rows($result);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image'])) {
            $imageUrl = $_POST['delete_image_url'];
            $contentId = intval($_POST['content_id']);
            
            // Remove the image from the database
            $query = "DELETE FROM bg_img WHERE content_id = ? AND image_url = ?";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("is", $contentId, $imageUrl);
                if ($stmt->execute()) {
                    // Set a success message (optional)
                    $_SESSION['message'] = "Image removed from the database.";
                } else {
                    // Set an error message (optional)
                    $_SESSION['error'] = "Failed to remove the image from the database.";
                }
                $stmt->close();
            }
        
            // Redirect back to the page to refresh the content
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
        
        

    // Handle Edit Logic
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_content'])) {
        $id = intval($_POST['id']);
        $subhead = $_POST['subhead'];
        $heading = $_POST['heading'];
        $description = $_POST['description'];
        $button_text = $_POST['button_text'];
        $button_link = $_POST['button_link'];

        // Update homepage content
        $query = "UPDATE homepage_content SET subhead = ?, heading = ?, description = ?, button_text = ?, button_link = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("sssssi", $subhead, $heading, $description, $button_text, $button_link, $id);
            $stmt->execute();
            $stmt->close();
        }

        
        // Handle selection of existing images
    if (isset($_POST['existing_images']) && is_array($_POST['existing_images'])) {
        foreach ($_POST['existing_images'] as $selected_image) {
            // Ensure the relative path is correct
            $selected_image_path = str_replace(['../../', '\\'], ['../', '/'], $selected_image);

            // Check if the image is already in the database for this content
            $query = "SELECT COUNT(*) AS count FROM bg_img WHERE content_id = ? AND image_url = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $id, $selected_image_path);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            // Only insert if not already present
            if ($row['count'] == 0) {
                $query = "INSERT INTO bg_img (content_id, image_url) VALUES (?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("is", $id, $selected_image_path);
                $stmt->execute();
            }
            $stmt->close();
        }
    }
        // Redirect to avoid form resubmission
        header("Location: pages.php");
        exit;
    }

    // Fetch Homepage Content with Images
    $query = "SELECT hc.*, bi.image_url 
            FROM homepage_content hc
            LEFT JOIN bg_img bi ON hc.id = bi.content_id
            ORDER BY hc.id";
    $result = $conn->query($query);

    $contents = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $content_id = $row['id'];
            if (!isset($contents[$content_id])) {
                $contents[$content_id] = [
                    'id' => $row['id'],
                    'subhead' => $row['subhead'],
                    'heading' => $row['heading'],
                    'description' => $row['description'],
                    'button_text' => $row['button_text'],
                    'button_link' => $row['button_link'],
                    'images' => []
                ];
            }
            if (!empty($row['image_url'])) {
                $contents[$content_id]['images'][] = $row['image_url'];
            }
        }
    }

    
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

.image-container {
        position: relative;
        border: 1px solid #ddd;
        padding: 10px;
        width: 120px;
        height: 120px;
    }

    .image-container img {
        max-width: 100%;
        max-height: 100%;
        display: block;
        margin: 0 auto;
    }

    .delete-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: #ff4d4d;
        color: white;
        border: none;
        border-radius: 50%;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 12px;
    }

    .delete-btn:hover {
        background-color: #e60000;
    }

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

    /* Adjust bell icon positioning */
    .fa-bell {
        position: relative;
    }

    /* Adjust dropdown spacing */
    .dropdown-menu {
        margin-top: 10px;
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
                    <a class="navbar-brand brand-logo" href="../../admin1\dist\Admin-Homepage.php">
                        <h2>Rapide</h2>
                    </a>
                    <a class="navbar-brand brand-logo-mini" href="../../admin1\dist\Admin-Homepage.php">
                        <h3>R</h3>
                    </a>
                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-top">
                <ul class="navbar-nav">
                    <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                        <h1 class="welcome-text"><?php echo $fname; ?></h1> <!-- First name in H1 -->
                        <h3 class="welcome-sub-text"><?php echo $lname; ?></h3> <!-- Last name in H3 -->
                    </li>
                </ul>
            </div>
        </nav>



        <!-- Side bar -->

        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../../admin1\dist\Admin-Homepage.php">
                            <i class="mdi mdi-view-dashboard-outline menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Menu</li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../booking/adminMoMamaMo/show_all.php">
                            <i class="mdi mdi-calendar-check menu-icon"></i>
                            <span class="menu-title">Booking</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages.php">
                            <i class="mdi mdi-file-multiple menu-icon"></i>
                            <span class="menu-title">Pages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../admin1/dist/service.php">
                            <i class="mdi mdi-tools menu-icon"></i>
                            <span class="menu-title">Services</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../admin1/dist/Users.php">
                            <i class="mdi mdi-account-multiple menu-icon"></i>
                            <span class="menu-title">Users</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../admin1/dist/message_inbox.php">
                            <i class="mdi mdi-message-text-outline menu-icon"></i>
                            <span class="menu-title">Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../booking/adminMoMamaMo/bukingdets.php">
                            <i class="mdi mdi-file-chart menu-icon"></i>
                            <span class="menu-title">Reports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../login/logout.php">
                            <i class="mdi mdi-logout user"></i> <!-- Changed the icon to mdi-logout -->
                            <span class="menu-title">Sign Out</span>
                        </a>
                    </li>
                </ul>
            </nav>



            <!-- partial -->

            <div class="container mt-5">
            <h1>Homepage Content Management</h1>

            <!-- Display Homepage Content -->
            <div class="row">
            <?php foreach ($contents as $content): ?>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Display Content Details Once -->
                        <h5 class="card-title"><?= htmlspecialchars($content['subhead']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($content['heading']); ?></h6>
                        <p class="card-text"><?= htmlspecialchars($content['description']); ?></p>
                        <a href="<?= htmlspecialchars($content['button_link']); ?>" class="btn btn-primary"><?= htmlspecialchars($content['button_text']); ?></a>

                        <!-- Display Images for this Content -->
                        <div class="mt-3">
                            <h6>Images:</h6>
                            <div class="d-flex flex-wrap">
                                <?php if (!empty($content['images'])): ?>
                                    <?php foreach ($content['images'] as $image_url): ?>
                                        <div class="me-2 mb-2">
                                        <img src="<?= htmlspecialchars('../../' . ltrim($image_url, './')); ?>" alt="Content Image" class="img-fluid" style="max-width: 150px;">
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">No images available.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Edit Button -->
                        <button class="btn btn-warning mt-2" data-bs-toggle="modal" data-bs-target="#editModal<?= $content['id']; ?>">Edit</button>
                    </div>
                </div>
            </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?= $content['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $content['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel<?= $content['id']; ?>">Edit Content</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $content['id']; ?>">
                            <div class="mb-3">
                                <label for="subhead<?= $content['id']; ?>" class="form-label">Subhead</label>
                                <input type="text" class="form-control" id="subhead<?= $content['id']; ?>" name="subhead" value="<?= htmlspecialchars($content['subhead']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="heading<?= $content['id']; ?>" class="form-label">Heading</label>
                                <input type="text" class="form-control" id="heading<?= $content['id']; ?>" name="heading" value="<?= htmlspecialchars($content['heading']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="description<?= $content['id']; ?>" class="form-label">Description</label>
                                <textarea class="form-control" id="description<?= $content['id']; ?>" name="description"><?= htmlspecialchars($content['description']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="button_text<?= $content['id']; ?>" class="form-label">Button Text</label>
                                <input type="text" class="form-control" id="button_text<?= $content['id']; ?>" name="button_text" value="<?= htmlspecialchars($content['button_text']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="button_link<?= $content['id']; ?>" class="form-label">Button Link</label>
                                <input type="text" class="form-control" id="button_link<?= $content['id']; ?>" name="button_link" value="<?= htmlspecialchars($content['button_link']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Current Images</label>
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <?php if (!empty($content['images'])): ?>
                                        <?php foreach ($content['images'] as $image_url): ?>
                                            <div class="image-container">
                                                <!-- Display each image -->
                                                <img src="<?= htmlspecialchars('../../' . ltrim($image_url, './')); ?>" alt="Content Image">

                                                <!-- Delete Button (X) -->
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="delete_image_url" value="<?= htmlspecialchars($image_url); ?>">
                                                    <input type="hidden" name="content_id" value="<?= $content['id']; ?>">
                                                    <button type="submit" name="delete_image" class="delete-btn">&times;</button>
                                                </form>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted">No images available.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Select from Existing Images</label>
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <?php if (!empty($available_images)): ?>
                                        <?php foreach ($available_images as $image): ?>
                                            <div class="image-container">
                                                <label style="cursor: pointer;">
                                                    <!-- Hidden checkbox -->
                                                    <input type="checkbox" name="existing_images[]" value="<?= htmlspecialchars($image); ?>" style="display: none;">
                                                    <!-- Clickable image -->
                                                    <img src="<?= htmlspecialchars($upload_dir . $image); ?>" alt="<?= htmlspecialchars($image); ?>">
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p>No images available in the uploads directory.</p>
                                    <?php endif; ?>
                                </div>
                            </div>




                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" name="update_content">Save Changes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

    </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
        const imageContainers = document.querySelectorAll(".image-container label");

        imageContainers.forEach(label => {
            label.addEventListener("click", function (e) {
                e.preventDefault();

                const checkbox = label.querySelector("input[type='checkbox']");
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;

                    const img = label.querySelector("img");
                    if (checkbox.checked) {
                        img.style.border = "2px solid #4CAF50";
                        img.style.boxShadow = "0 0 5px #4CAF50";
                    } else {
                        img.style.border = "none";
                        img.style.boxShadow = "none";
                    }
                }
            });
        });

        // Ensure form submission works
        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", (e) => {
                const uncheckedCheckboxes = form.querySelectorAll("input[type='checkbox']:not(:checked)");
                uncheckedCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true; // Prevent unselected checkboxes from blocking submission
                });
            });
        });
    });

    document.querySelectorAll("button[name='update_content']").forEach(button => {
    button.addEventListener("click", () => {
        console.log("Save Changes button clicked!");
    });
});

    </script>

    <!-- container-scroller -->
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