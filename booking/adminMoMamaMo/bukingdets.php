<?php
    include('../../sessioncheck.php');
    include('../../connection.php');

    if (!isset($_GET['booking_id'])) {
        header("Location: ../../admin/homepage.php");
        exit();
    }

    $booking_id = (int) $_GET['booking_id'];

    // Fetch booking and user details
    $booking_query = "SELECT b.*, u.fname, u.lname, u.email, u.phone, cl.city_name, bl.brgy_name 
                    FROM bookings b
                    JOIN users u ON b.user_id = u.id
                    LEFT JOIN city_list cl ON u.city = cl.id
                    LEFT JOIN brgy_list bl ON u.brgy = bl.id
                    WHERE b.booking_id = ?";
    $stmt = $conn->prepare($booking_query);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $booking_result = $stmt->get_result();
    $booking = $booking_result->fetch_assoc();

    // Fetch checklist progress only if the booking is confirmed
    $checklist = [];
    if ($booking['status'] === 'confirmed') {
        $checklist_query = "SELECT * FROM checklist WHERE booking_id = ?";
        $stmt_checklist = $conn->prepare($checklist_query);
        $stmt_checklist->bind_param("i", $booking_id);
        $stmt_checklist->execute();
        $checklist_result = $stmt_checklist->get_result();
        $checklist = $checklist_result->fetch_assoc();

        // Initialize checklist if it doesn't exist
        if (!$checklist) {
            $init_query = "INSERT INTO checklist (booking_id, arrival, assessment, service, finalization) VALUES (?, 0, 0, 0, 0)";
            $stmt_init = $conn->prepare($init_query);
            $stmt_init->bind_param("i", $booking_id);
            $stmt_init->execute();
            $stmt_checklist->execute();
            $checklist = $stmt_checklist->get_result()->fetch_assoc();
        }
    }

    // Update booking status and checklist if form submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['new_status'])) {
            // Update the booking status
            $new_status = $_POST['new_status'];
            $update_status_query = "UPDATE bookings SET status = ? WHERE booking_id = ?";
            $stmt_update_status = $conn->prepare($update_status_query);
            $stmt_update_status->bind_param("si", $new_status, $booking_id);
            $stmt_update_status->execute();

            // Refresh booking data after status update
            $stmt->execute();
            $booking = $stmt->get_result()->fetch_assoc();

            header("Location: bukingdets.php?booking_id=$booking_id&status_updated=1");
            exit();
        }

        if (isset($_POST['update_checklist']) && $booking['status'] === 'confirmed') {
            // Fetch updated checklist values
            $arrival = isset($_POST['arrival']) ? 1 : 0;
            $assessment = isset($_POST['assessment']) ? 1 : 0;
            $service = isset($_POST['service']) ? 1 : 0;
            $finalization = isset($_POST['finalization']) ? 1 : 0;

            // Update checklist in the database
            $update_query = "UPDATE checklist SET arrival = ?, assessment = ?, service = ?, finalization = ? WHERE booking_id = ?";
            $stmt_update = $conn->prepare($update_query);
            $stmt_update->bind_param("iiiii", $arrival, $assessment, $service, $finalization, $booking_id);
            $stmt_update->execute();

            // Check if all fields are marked as complete
            if ($arrival && $assessment && $service && $finalization) {
                // Mark booking as completed
                $status_query = "UPDATE bookings SET status = 'completed' WHERE booking_id = ?";
                $stmt_status = $conn->prepare($status_query);
                $stmt_status->bind_param("i", $booking_id);
                $stmt_status->execute();

                // Insert notification for the user
                $notification_query = "INSERT INTO notifications (user_id, title, message, status, created_at) VALUES (?, ?, ?, 'unread', NOW())";
                $notification_stmt = $conn->prepare($notification_query);
                $message = "Your booking #$booking_id has been completed. Please leave us a rating!";
                $notification_stmt->bind_param("iss", $booking['user_id'], $message, $message);
                $notification_stmt->execute();
            }

            // Refresh booking data after checklist update
            $stmt->execute();
            $booking = $stmt->get_result()->fetch_assoc();

            // Redirect after updating
            header("Location: bukingdets.php?booking_id=$booking_id&checklist_updated=1");
            exit();
        }
    }

    if (in_array($booking['status'], ['confirmed', 'completed'])) {
        $checklist_query = "SELECT * FROM checklist WHERE booking_id = ?";
        $stmt_checklist = $conn->prepare($checklist_query);
        $stmt_checklist->bind_param("i", $booking_id);
        $stmt_checklist->execute();
        $checklist_result = $stmt_checklist->get_result();
        $checklist = $checklist_result->fetch_assoc();
    
        if (!$checklist && $booking['status'] === 'confirmed') {
            // Initialize checklist if missing for a confirmed booking
            $init_query = "INSERT INTO checklist (booking_id, arrival, assessment, service, finalization) VALUES (?, 0, 0, 0, 0)";
            $stmt_init = $conn->prepare($init_query);
            $stmt_init->bind_param("i", $booking_id);
            $stmt_init->execute();
    
            // Fetch the newly created checklist
            $stmt_checklist->execute();
            $checklist = $stmt_checklist->get_result()->fetch_assoc();
        }
    } else {
        $checklist = []; // Default empty checklist for other statuses
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }



    .container {
        margin-top: 20px;
        padding: 15px;
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #007bff;
        color: white;
        text-align: center;
        padding: 10px;
        font-size: 1.2rem;
        font-weight: bold;
    }

    .card-body {
        padding: 15px;
    }

    .btn-primary,
    .btn-warning {
        width: 100%;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    .form-check-label {
        font-size: 0.95rem;
    }

    /* Sticky Checklist */
    .sticky-checklist {
        position: sticky;
        top: 20px;
        /* Sticks 20px from the top */
        z-index: 1000;
    }

    /* Ensure Parent Allows Scrolling */
    .row {
        display: flex;
        flex-wrap: wrap;
    }


    /* Enhanced Responsive Design */
    @media (max-width: 768px) {
        .card-header {
            font-size: 1rem;
        }

        .card-body {
            padding: 10px;
        }

        .form-check-label {
            font-size: 0.9rem;
        }

        .btn {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .card-header {
            font-size: 0.9rem;
        }

        .card-body {
            padding: 8px;
        }

        .form-check-label {
            font-size: 0.85rem;
        }

        .btn {
            font-size: 0.85rem;
        }

        .container {
            padding: 10px;
        }
    }
    </style>

</head>

<body>
    <div class="container">
        <div class="row">
            <!-- Booking Details -->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">Booking Details</div>
                    <div class="card-body">
                        <p><strong>Booking ID:</strong> <?= htmlspecialchars($booking['booking_id']); ?></p>
                        <p><strong>User:</strong> <?= htmlspecialchars($booking['fname'] . ' ' . $booking['lname']); ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($booking['email']); ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($booking['phone']); ?></p>
                        <p><strong>Address:</strong>
                            <?= htmlspecialchars($booking['city_name'] . ', ' . $booking['brgy_name']); ?></p>
                        <p><strong>Booking Date:</strong> <?= date('F j, Y', strtotime($booking['booking_date'])); ?></p>

                        <!-- Only show status update for non-completed bookings -->
                        <?php if ($booking['status'] !== 'completed') : ?>
                            <h4 class="mt-4">Update Booking Status</h4>
                            <form method="post">
                                <div class="form-group">
                                    <label for="new_status" class="form-label">Select New Status:</label>
                                    <select name="new_status" id="new_status" class="form-select">
                                        <option value="pending" <?= $booking['status'] == 'pending' ? 'selected' : ''; ?>>
                                            Pending</option>
                                        <option value="confirmed" <?= $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>
                                            Confirmed</option>
                                        <option value="rejected" <?= $booking['status'] == 'rejected' ? 'selected' : ''; ?>>
                                            Rejected</option>
                                        <option value="canceled" <?= $booking['status'] == 'canceled' ? 'selected' : ''; ?>>
                                            Canceled</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-warning mt-3">Update Status</button>
                            </form>
                        <?php else : ?>
                            <h4 class="text-success mt-4">This booking has been completed.</h4>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Checklist -->
            <div class="col-lg-6 col-md-12 sticky-checklist">
                <div class="card">
                    <div class="card-header">
                        Service Checklist <?= $booking['status'] === 'completed' ? 'for Completed Booking:' : ''; ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($checklist)) : ?>
                            <?php if ($booking['status'] === 'confirmed') : ?>
                                <!-- Editable Checklist -->
                                <form method="post">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="arrival" id="arrival"
                                            <?= isset($checklist['arrival']) && $checklist['arrival'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="arrival">Arrival and Initial Assessment</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="assessment" id="assessment"
                                            <?= isset($checklist['assessment']) && $checklist['assessment'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="assessment">Mechanic Assigned and Assessment</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="service" id="service"
                                            <?= isset($checklist['service']) && $checklist['service'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="service">Service and Repair</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="finalization" id="finalization"
                                            <?= isset($checklist['finalization']) && $checklist['finalization'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="finalization">Finalization and Handover</label>
                                    </div>
                                    <button type="submit" name="update_checklist" class="btn btn-primary mt-3">Update Checklist</button>
                                </form>
                            <?php elseif ($booking['status'] === 'completed') : ?>
                                <!-- View-Only Checklist -->
                                <ul>
                                    <li>Arrival and Initial Assessment: <?= isset($checklist['arrival']) && $checklist['arrival'] ? '✔' : '✘'; ?></li>
                                    <li>Mechanic Assigned and Assessment: <?= isset($checklist['assessment']) && $checklist['assessment'] ? '✔' : '✘'; ?></li>
                                    <li>Service and Repair: <?= isset($checklist['service']) && $checklist['service'] ? '✔' : '✘'; ?></li>
                                    <li>Finalization and Handover: <?= isset($checklist['finalization']) && $checklist['finalization'] ? '✔' : '✘'; ?></li>
                                </ul>
                            <?php else : ?>
                                <p class="text-muted"><em>Checklist is not available for this booking status.</em></p>
                            <?php endif; ?>
                        <?php else : ?>
                            <p class="text-muted"><em>No checklist details are available for this booking.</em></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>

</html>