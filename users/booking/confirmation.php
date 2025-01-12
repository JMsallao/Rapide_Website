<?php
include('../../sessioncheck.php');
include('../../connection.php');

// Check if booking was successful
$success = isset($_GET['success']) ? (int)$_GET['success'] : 0;
$booking_id = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;

if ($success && $booking_id) {
    // Fetch booking details to display
    $query = "SELECT * FROM bookings WHERE booking_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $booking_id, $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    // Fetch branch name using branch_id
    $branch_name = '';
    if (isset($booking['branch_id'])) {
        $branch_query = "SELECT branch_name FROM branches WHERE id = ?";
        $stmt_branch = $conn->prepare($branch_query);
        $stmt_branch->bind_param("i", $booking['branch_id']);
        $stmt_branch->execute();
        $branch_result = $stmt_branch->get_result();
        if ($branch_result->num_rows > 0) {
            $branch_row = $branch_result->fetch_assoc();
            $branch_name = $branch_row['branch_name'];
        }
    }

    // Fetch related cart items for this booking
    $cart_query = "SELECT * FROM cart WHERE booking_id = ? AND user_id = ?";
    $stmt_cart = $conn->prepare($cart_query);
    $stmt_cart->bind_param("ii", $booking_id, $_SESSION['id']);
    $stmt_cart->execute();
    $cart_result = $stmt_cart->get_result();
} else {
    echo "Invalid booking confirmation request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="shortcut icon" href="../../images\rapide_logo.png" type="image/x-icon">
    <style>
    /* General Page Styling */
    body {
        background-color: #fdf3d1;
        /* light yellow */
        color: #333;
    }

    .container {
        max-width: 700px;
        margin-top: 30px;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Header Styling */
    h2,
    h4 {
        color: #c62828;
        /* red */
        font-weight: bold;
    }

    /* Table Styling */
    .table {
        margin-top: 20px;
    }

    .table th {
        background-color: #ffc107;
        /* yellow */
        color: #333;
        font-weight: bold;
    }

    .table td {
        color: #5d4037;
        /* dark brown */
    }

    /* Button Styling */
    .btn-secondary {
        background-color: #ffca28;
        /* yellow */
        border: none;
        color: #fff;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #ffa000;
        /* darker yellow */
    }

    /* Responsive Adjustments */
    @media (max-width: 576px) {
        .container {
            padding: 15px;
        }

        h2,
        h4 {
            font-size: 20px;
        }

        .table td,
        .table th {
            font-size: 14px;
        }
    }

    .confirmation-container {
        max-width: 600px;
        margin: 50px auto;
        text-align: center;
    }

    .confirmation-message {
        font-size: 1.5rem;
        margin-bottom: 20px;
        color: #28a745;
    }

    .btn-exit {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        font-size: 1rem;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-top: 20px;
    }

    .btn-exit:hover {
        background-color: #0056b3;
        color: white;
    }
    </style>
</head>

<body>
    <div class="container mt-5">
        <?php if ($success): ?>
        <div class="alert alert-success" role="alert">
            Booking successfully completed! Your booking ID is <?php echo $booking_id; ?>.
        </div>

        <h3>Booking Details</h3>
        <p><strong>Booking Date:</strong> <?php echo $booking['booking_date']; ?></p>
        <p><strong>Branch:</strong> <?php echo !empty($branch_name) ? htmlspecialchars($branch_name) : 'Not Available'; ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($booking['status']); ?></p>
        <p><strong>Total Price:</strong> ₱<?php echo number_format($booking['total_price'], 2); ?></p>

        <h4>Booked Items</h4>
        <ul>
            <?php while ($item = $cart_result->fetch_assoc()): ?>
            <li>
                <?php echo $item['service_name']; ?> - Qty: <?php echo $item['quantity']; ?> -
                Total: ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
            </li>
            <?php endwhile; ?>
        </ul>
        <?php else: ?>
        <div class="alert alert-danger" role="alert">
            There was an issue with your booking. Please try again.
        </div>
        <?php endif; ?>
        <a href="../../users\Homepage.php" class="btn-exit">Exit</a>

    </div>

    <script>
    // Show a success notification after booking
    if (<?php echo $success; ?>) {
        alert('Booking Successfully Completed!');
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>