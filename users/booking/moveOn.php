<?php
include('../../sessioncheck.php');
include('../../connection.php');

$user_id = $_SESSION['id'];
$booking_id = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;

// Fetch booking details
$query = "SELECT * FROM bookings WHERE booking_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    echo "Invalid booking ID.";
    exit();
}

// Fetch items associated with the booking
$cart_query = "SELECT * FROM cart WHERE booking_id = ? AND user_id = ?";
$stmt_cart = $conn->prepare($cart_query);
$stmt_cart->bind_param("ii", $booking_id, $user_id);
$stmt_cart->execute();
$cart_result = $stmt_cart->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="shortcut icon" href="../../images\rapide_logo.png" type="image/x-icon">
    <style>
    /* General Page Styling */
    body {
        background-color: white;
        /* light yellow */
        color: #333;
    }

    .container {
        max-width: 700px;
        margin-top: 30px;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
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
        color: black;
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
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Booking Details - ID: <?php echo $booking['booking_id']; ?></h2>
        <p><strong>Date:</strong> <?php echo date('F j, Y, g:i A', strtotime($booking['booking_date'])); ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($booking['status']); ?></p>
        <p><strong>Total Price:</strong> ₱<?php echo number_format($booking['total_price'], 2); ?></p>

        <h4>Items in this Booking</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $cart_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $item['service_name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₱<?php echo number_format($item['price'], 2); ?></td>
                    <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="past_is_past.php" class="btn btn-secondary">Back to Booking History</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>