<?php

// Include your database connection
include('../../connection.php');
include('../../sessioncheck.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch user data based on the ID
    $sql = "SELECT * FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Check if user exists
        if (!$user) {
            echo "User not found!";
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user information based on form submission
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $brgy = $_POST['brgy'];
    $bday = $_POST['bday'];

    // Update query
    $updateSql = "UPDATE users SET fname=?, lname=?, email=?, username=?, phone=?, province=?, city=?, brgy=?, bday=? WHERE id=?";
    if ($stmt = $conn->prepare($updateSql)) {
        $stmt->bind_param("sssssssssi", $fname, $lname, $email, $username, $phone, $province, $city, $brgy, $bday, $id);
        if ($stmt->execute()) {
            header("Location: users.php?msg=User updated successfully");
            exit();
        } else {
            echo "Error updating user: " . $conn->error;
        }
    }
}
?>

<!-- Start of Form HTML -->
<div class="container mt-5">
    <h2 class="mb-4">Edit User</h2>

    <form method="POST">
        <div class="mb-3">
            <label for="fname" class="form-label">First Name:</label>
            <input type="text" class="form-control" name="fname" value="<?php echo $user['fname']; ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="lname" class="form-label">Last Name:</label>
            <input type="text" class="form-control" name="lname" value="<?php echo $user['lname']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone:</label>
            <input type="text" class="form-control" name="phone" value="<?php echo $user['phone']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="province" class="form-label">Province:</label>
            <input type="text" class="form-control" name="province" value="<?php echo $user['province']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="city" class="form-label">City:</label>
            <input type="text" class="form-control" name="city" value="<?php echo $user['city']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="brgy" class="form-label">Barangay:</label>
            <input type="text" class="form-control" name="brgy" value="<?php echo $user['brgy']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="bday" class="form-label">Birthday:</label>
            <input type="date" class="form-control" name="bday" value="<?php echo $user['bday']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
        <!-- Back Button -->
        <button class="btn btn-secondary mb-3" onclick="history.back();">Back</button>
</div>

<!-- Add Bootstrap 5 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- End of Form HTML -->