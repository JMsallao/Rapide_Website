<?php
    include('../../connection.php');
    include('../../sessioncheck.php');

    // Check if admin is logged in
    if (isset($_SESSION['id'])) {
        $admin_id = $_SESSION['id']; // Assuming admin's ID is stored in the session
    } else {
        die("Admin not logged in.");
    }

    // Fetch all non-admin users (is_admin = 0)
    $sql = "SELECT id, fname, lname, email, phone, username FROM users WHERE is_admin = 0";
    $result = $conn->query($sql);

    // Check if any users are found
    if ($result->num_rows > 0) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    } else {
        $users = [];
    }

    // Handle Delete User
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        
        // Delete user from the database
        $delete_sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();

        // Redirect after deletion
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Handle Edit User
    if (isset($_POST['edit_user'])) {
        $edit_id = $_POST['edit_id'];
        $edit_fname = $_POST['edit_fname'];
        $edit_lname = $_POST['edit_lname'];
        $edit_email = $_POST['edit_email'];
        $edit_phone = $_POST['edit_phone'];
        $edit_username = $_POST['edit_username'];

        // Update the user in the database
        $update_sql = "UPDATE users SET fname = ?, lname = ?, email = ?, phone = ?, username = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssssi", $edit_fname, $edit_lname, $edit_email, $edit_phone, $edit_username, $edit_id);
        $stmt->execute();

        // Redirect after update
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Handle Edit User Form (pre-fill with current values)
    $edit_user_data = null;
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $sql_edit_user = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql_edit_user);
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $edit_user_data = $stmt->get_result()->fetch_assoc();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../../images/rapide_logo.png" type="image/x-icon">
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Users List</h2>

        <!-- Users Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (count($users) > 0) {
                        foreach ($users as $user) {
                            echo "
                            <tr>
                                <td>{$user['id']}</td>
                                <td>{$user['fname']}</td>
                                <td>{$user['lname']}</td>
                                <td>{$user['email']}</td>
                                <td>{$user['phone']}</td>
                                <td>{$user['username']}</td>
                                <td>
                                    <a href='" . $_SERVER['PHP_SELF'] . "?edit_id={$user['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='" . $_SERVER['PHP_SELF'] . "?delete_id={$user['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                                </td>
                            </tr>
                            ";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No users found.</td></tr>";
                    }
                ?>
            </tbody>
        </table>

        <hr>

        <!-- Edit User Form (only visible if edit_id is set) -->
        <?php if ($edit_user_data): ?>
            <h3>Edit User</h3>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="edit_id" value="<?php echo $edit_user_data['id']; ?>">
                <div class="mb-3">
                    <label for="edit_fname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="edit_fname" name="edit_fname" value="<?php echo $edit_user_data['fname']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="edit_lname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="edit_lname" name="edit_lname" value="<?php echo $edit_user_data['lname']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="edit_email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="edit_email" name="edit_email" value="<?php echo $edit_user_data['email']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="edit_phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="edit_phone" name="edit_phone" value="<?php echo $edit_user_data['phone']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="edit_username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="edit_username" name="edit_username" value="<?php echo $edit_user_data['username']; ?>" required>
                </div>
                <button type="submit" name="edit_user" class="btn btn-primary">Save Changes</button>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">Cancel</a>
            </form>
        <?php endif; ?>

        <a href="../dist/Admin-homepage.php" class="btn btn-primary mt-3">Back to Dashboard</a>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
