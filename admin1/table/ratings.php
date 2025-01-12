<?php
    include('../../connection.php');
    include('../../sessioncheck.php');

    // Fetch ratings breakdown
    $sql_ratings = "SELECT stars, COUNT(*) AS count FROM ratings GROUP BY stars";
    $result_ratings = $conn->query($sql_ratings);

    $ratings = [];
    while ($row = $result_ratings->fetch_assoc()) {
        $ratings[] = $row;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Ratings Breakdown</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Ratings Breakdown</h2>

        <!-- Ratings Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Stars</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (count($ratings) > 0) {
                        foreach ($ratings as $rating) {
                            echo "
                            <tr>
                                <td>{$rating['stars']} Stars</td>
                                <td>{$rating['count']}</td>
                            </tr>
                            ";
                        }
                    } else {
                        echo "<tr><td colspan='2' class='text-center'>No ratings found.</td></tr>";
                    }
                ?>
            </tbody>
        </table>

        <a href="../dist/Admin-Homepage.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
