<?php
include('../../sessioncheck.php');
include('../../connection.php');

// Check if any items were selected for booking
if (isset($_POST['cart_ids']) && count($_POST['cart_ids']) > 0) {
    $selected_cart_ids = $_POST['cart_ids'];

    // Retrieve the selected cart items from the database
    $cart_ids_imploded = implode(',', array_map('intval', $selected_cart_ids));
    $query = "SELECT * FROM cart WHERE id IN ($cart_ids_imploded) AND user_id = '{$_SESSION['id']}'";
    $result = mysqli_query($conn, $query);
    $selected_items = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // No items selected, redirect back to the cart
    header('Location: kariton.php');
    exit();
}

// Fetch confirmed bookings to disable on the calendar
$confirmed_query = "SELECT booking_date FROM bookings WHERE status = 'confirmed'";
$confirmed_result = mysqli_query($conn, $confirmed_query);

$confirmed_dates = [];
while ($row = mysqli_fetch_assoc($confirmed_result)) {
    $confirmed_dates[] = $row['booking_date']; // Collect confirmed booking dates
}

// Fetch branches from the database
$branch_query = "SELECT id, branch_name FROM branches";
$branch_result = mysqli_query($conn, $branch_query);
$branches = mysqli_fetch_all($branch_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceed to Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        .time-slot-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .time-slot {
            padding: 10px 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            flex: 1 1 80px;
            max-width: 120px;
            transition: background-color 0.3s ease;
        }

        .time-slot:hover {
            background-color: #ffe6e6;
        }

        .time-slot.selected {
            background-color: #ffc107;
            color: #fff;
            font-weight: bold;
        }

        #calendar {
            max-width: 300px;
            margin: auto;
        }
        .calendar-container {
            margin-top: 20px;
        }

        .selected-items {
            margin-bottom: 20px;
        }

        /* General Page Styling */
        body {
            background-color: white;
            color: #333;
        }

        .container {
            max-width: 700px;
            margin-top: 30px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        /* Header Styling */
        h2,
        h4 {
            color: #c62828;
            font-weight: bold;
        }

        /* Selected Items List */
        .selected-items ul {
            padding-left: 20px;
        }

        .selected-items li {
            margin-bottom: 10px;
            color: #5d4037;
            font-weight: 500;
        }

        /* Calendar Container */
        .calendar-container {
            margin-top: 20px;
        }

        /* Input and Button Styling */
        .form-label {
            color: #c62828;
            font-weight: 600;
        }

        .form-control {
            background-color: #fff3e0;
            border-color: #ffcc80;
        }

        .btn-primary {
            background-color: #ffca28;
            border: none;
            color: #fff;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #ffa000;
        }

        /* Flatpickr Custom Styling */
        .flatpickr-calendar {
            font-family: Arial, sans-serif;
            border: none;
            box-shadow: none;
        }

        .flatpickr-months {
            background-color: #ff6b6b;
            color: #fff;
            border-radius: 8px 8px 0 0;
        }

        .flatpickr-current-month {
            font-size: 1.2em;
            font-weight: bold;
            padding: 10px;
        }

        .flatpickr-weekday {
            color: #555;
            font-weight: bold;
        }

        .flatpickr-day {
            width: 40px;
            height: 40px;
            line-height: 40px;
            color: #333;
            border-radius: 50%;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .flatpickr-day.selected {
            background-color: #ff6b6b;
            color: #fff;
            font-weight: bold;
        }

        .flatpickr-day:hover:not(.selected):not(.flatpickr-day.disabled) {
            background-color: #ffb3b3;
            color: #fff;
        }

        .flatpickr-day.disabled,
        .flatpickr-day.disabled:hover {
            color: #ccc;
            background-color: #f9f9f9;
            cursor: not-allowed;
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

            .selected-items li {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center my-4">Confirm Your Booking</h2>

        <!-- Display Selected Cart Items -->
        <div class="selected-items mb-4">
            <h4 class="text-danger">Selected Services:</h4>
            <ul>
                <?php foreach ($selected_items as $item): ?>
                <li>
                    <?php echo $item['service_name']; ?> - Qty: <?php echo $item['quantity']; ?> - 
                    ₱<?php echo number_format($item['price'], 2); ?> each - 
                    Total: ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Branch Selection -->
        <div class="branch-selection mb-4">
            <h4 class="text-danger">Select a Branch:</h4>
            <select name="branch" id="branch" class="form-control" required>
                <option value="">-- Select a Branch --</option>
                <?php foreach ($branches as $branch): ?>
                <option value="<?php echo $branch['id']; ?>"><?php echo $branch['branch_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Calendar and Time Slots -->
        <div class="calendar-container mb-4">
            <h4 class="text-danger">Select a Date and Time for Your Booking</h4>
            <div id="calendar" class="mb-4"></div>

            <div id="time-slots" class="d-none">
                <h5>Available Time Slots:</h5>
                <div class="time-slot-container">
                    <!-- Time slots will be dynamically generated -->
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <form id="bookingForm" action="../function/finalDestination.php" method="POST">
            <input type="hidden" id="selected_date" name="selected_date" required>
            <input type="hidden" id="selected_time" name="selected_time" required>
            <input type="hidden" id="branch_id" name="branch_id" required>
            
            <!-- Pass selected cart items to the backend -->
            <?php foreach ($selected_cart_ids as $cart_id): ?>
            <input type="hidden" name="cart_ids[]" value="<?php echo $cart_id; ?>">
            <?php endforeach; ?>

            <!-- Confirm Button -->
            <button type="submit" id="confirm-button" class="btn btn-primary w-100 mt-3 d-none">
                Confirm Booking
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendar = flatpickr("#calendar", {
                inline: true,
                minDate: "today",
                onChange: function (selectedDates, dateStr) {
                    document.getElementById('selected_date').value = dateStr;
                    generateTimeSlots(); // Generate time slots when a date is selected
                },
            });

            const timeSlots = [
                "8:00 - 9:00",
                "9:00 - 10:00",
                "10:00 - 11:00",
                "11:00 - 12:00",
                "12:00 - 1:00",
                "1:00 - 2:00",
                "2:00 - 3:00",
                "3:00 - 4:00",
                "4:00 - 5:00",
            ];

            function generateTimeSlots() {
                const container = document.querySelector(".time-slot-container");
                container.innerHTML = ""; // Clear previous time slots
                document.getElementById("time-slots").classList.remove("d-none");

                timeSlots.forEach(slot => {
                    const div = document.createElement("div");
                    div.classList.add("time-slot");
                    div.textContent = slot;
                    div.onclick = function () {
                        document.querySelectorAll(".time-slot").forEach(el => el.classList.remove("selected"));
                        div.classList.add("selected");
                        document.getElementById("selected_time").value = slot; // Save the selected time
                        document.getElementById("confirm-button").classList.remove("d-none"); // Show confirm button
                    };
                    container.appendChild(div);
                });
            }

            // Update hidden branch_id input on branch selection
            document.getElementById('branch').addEventListener('change', function () {
                document.getElementById('branch_id').value = this.value;
            });

            // Validate form and dynamically append datetime
            document.getElementById('bookingForm').addEventListener('submit', function (e) {
                const branchId = document.getElementById('branch_id').value;
                const selectedDate = document.getElementById('selected_date').value;
                const selectedTime = document.getElementById('selected_time').value;

                if (!branchId || !selectedDate || !selectedTime) {
                    alert('Please complete all fields before submitting.');
                    e.preventDefault();
                } else {
                    const datetime = `${selectedDate} ${selectedTime}`;
                    const datetimeInput = document.createElement('input');
                    datetimeInput.type = 'hidden';
                    datetimeInput.name = 'datetime';
                    datetimeInput.value = datetime;
                    this.appendChild(datetimeInput); // Append datetime to the form
                }
            });
        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
