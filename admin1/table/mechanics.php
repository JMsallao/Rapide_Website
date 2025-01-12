<?php
    include('../../connection.php');
    include('../../sessioncheck.php');

    if (isset($_SESSION['id'])) {
        $admin_id = $_SESSION['id']; // Assuming admin's ID is stored in the session
    } else {
        die("Admin not logged in.");
    }

    // Fetch all mechanics data
    $sql = "SELECT id, fname, lname, phone, email, role FROM mechanics";
    $result = $conn->query($sql);

    // Check if any mechanics are found
    if ($result->num_rows > 0) {
        $mechanics = [];
        while ($row = $result->fetch_assoc()) {
            $mechanics[] = $row;
        }
    } else {
        $mechanics = [];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags, CSS includes -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mechanics List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Mechanics List</h2>

        <!-- Mechanics Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (count($mechanics) > 0) {
                        foreach ($mechanics as $mechanic) {
                            echo "
                            <tr>
                                <td>{$mechanic['id']}</td>
                                <td>{$mechanic['fname']}</td>
                                <td>{$mechanic['lname']}</td>
                                <td>{$mechanic['phone']}</td>
                                <td>{$mechanic['email']}</td>
                                <td>{$mechanic['role']}</td>
                            </tr>
                            ";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No mechanics found.</td></tr>";
                    }
                ?>
            </tbody>
        </table>
        <a href="../dist/Admin-Homepage.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
