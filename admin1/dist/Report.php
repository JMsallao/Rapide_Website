<?php
session_start();
include('../../connection.php'); // Use your existing connection file

// Handle branch ID and date filters
$branch_id = isset($_GET['branch_id']) ? intval($_GET['branch_id']) : 1; // Default to branch ID 1
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Build the query for the branch-specific report
$query = "
    SELECT 
        b.booking_id, 
        b.service_type, 
        b.status, 
        b.total_price, 
        b.booking_date 
    FROM bookings AS b
    WHERE b.branch_id = $branch_id
";

if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND b.booking_date BETWEEN '$from_date' AND '$to_date'";
}

$query .= " ORDER BY b.booking_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Booking Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Branch Booking Report</h1>

        <!-- Filter Form -->
        <form method="GET" class="row g-3 mb-4">
            <input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">

            <div class="col-md-4">
                <label for="from_date" class="form-label">From Date:</label>
                <input type="date" name="from_date" id="from_date" class="form-control" value="<?php echo $from_date; ?>" required>
            </div>

            <div class="col-md-4">
                <label for="to_date" class="form-label">To Date:</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="<?php echo $to_date; ?>" required>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Generate Report</button>
                <a href="branch_booking_report.php?branch_id=<?php echo $branch_id; ?>" class="btn btn-secondary ms-2">Reset</a>
            </div>
        </form>

        <!-- Booking Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Service Type</th>
                    <th>Status</th>
                    <th>Total Price (₱)</th>
                    <th>Booking Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['booking_id']; ?></td>
                            <td><?php echo ucfirst($row['service_type']); ?></td>
                            <td><?php echo ucfirst($row['status']); ?></td>
                            <td>₱<?php echo number_format($row['total_price'], 2); ?></td>
                            <td><?php echo date('F j, Y, g:i A', strtotime($row['booking_date'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No bookings found for the selected dates.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- PDF Download Button -->
        <form method="POST" action="generate_pdf.php" class="text-center mt-4">
            <input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
            <input type="hidden" name="from_date" value="<?php echo $from_date; ?>">
            <input type="hidden" name="to_date" value="<?php echo $to_date; ?>">
            <button type="submit" class="btn btn-success">Download PDF</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
