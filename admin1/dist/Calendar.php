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
    

    // Fetch branch_id for the logged-in admin
    $query = "SELECT id FROM branches WHERE admin_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id); // Bind admin_id
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $branch_id = $user['id']; // Get branch_id
    } else {
        die("Branch not found for the given admin.");
    }
    $stmt->close();

    // Fetch bookings for the branch_id
    $queryBookings = "SELECT booking_id, service_type, booking_date, status FROM bookings WHERE branch_id = ?";
    $stmt = $conn->prepare($queryBookings);
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();
    $resultBookings = $stmt->get_result();

    $events = [];
    // Fetch bookings data and format it for FullCalendar
    while ($row = $resultBookings->fetch_assoc()) {
        $events[] = [
            'id' => $row['booking_id'],
            'title' => ucfirst($row['service_type']) . ' (' . ucfirst($row['status']) . ')',
            'start' => $row['booking_date'],
            'color' => ($row['status'] == 'completed') ? '#28a745' : '#f56954',
        ];
    }

    // Fetch events from the events table
    $queryEvents = "SELECT title, start_date, end_date FROM events";
    $stmt = $conn->prepare($queryEvents);
    $stmt->execute();
    $resultEvents = $stmt->get_result();

    // Fetch events data and format it for FullCalendar
    while ($row = $resultEvents->fetch_assoc()) {
        $events[] = [
            'title' => $row['title'],
            'start' => $row['start_date'],  // The 'start_date' field is in datetime format
            'end' => $row['end_date'],      // The 'end_date' field is also in datetime format
            'color' => '#f56954',  // Customize the color as needed
        ];
    }

    $events_json = json_encode($events);  // Combine the bookings and events into one JSON array
    $stmt->close();

    
   // Add Event Logic (for POST requests)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveEventBtn'])) {
        $eventTitle = $_POST['eventTitle'];
        $eventDate = $_POST['eventDate'];
        $startTime = $_POST['startTime'];  // Get the selected start time
        $endTime = $_POST['endTime'];      // Get the selected end time

        // Debugging the received data
        var_dump($_POST);  // This will print out the entire POST data to help you debug

        if (!empty($eventTitle) && !empty($eventDate) && !empty($startTime) && !empty($endTime)) {
            // Combine date with start and end times
            $startDateTime = $eventDate . " " . $startTime;  // Start time in datetime format
            $endDateTime = $eventDate . " " . $endTime;      // End time in datetime format

            // Insert event into the events table with the time range
            $query = "INSERT INTO events (title, start_date, end_date) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $eventTitle, $startDateTime, $endDateTime);  // Using $eventTitle, $startDateTime, and $endDateTime

            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error;  // Displaying any errors
            } else {
                echo "Event added successfully!";
                header("Location: Calendar.php");  // Redirect to the calendar page
            }
            $stmt->close();
        } else {
            echo "Please fill in all fields.";
        }
    }


    // View Event Logic (fetch event by date)
if (isset($_POST['viewEvent']) && isset($_POST['date'])) {
    $eventDate = $_POST['date'];
    
    // Query to fetch event based on start_date
    $query = "SELECT title, start_date, end_date FROM events WHERE DATE(start_date) = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $eventDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();

        // Format the dates for display
        $startDateFormatted = date("F j, Y, g:i a", strtotime($event['start_date']));  // Example: January 30, 2025, 8:00 AM
        $endDateFormatted = date("F j, Y, g:i a", strtotime($event['end_date']));  // Example: January 30, 2025, 12:00 PM

        echo json_encode([
            'title' => $event['title'],
            'start_date' => $startDateFormatted,
            'end_date' => $endDateFormatted
        ]);
    } else {
        echo json_encode(['message' => 'No event found']);
    }
    $stmt->close();
    exit();
}


    // Get Events for Deletion
    if (isset($_POST['getEventsForDeletion']) && isset($_POST['date'])) {
        $eventDate = $_POST['date'];
        
        // Query to fetch events based on start_date for deletion
        $query = "SELECT id, title FROM events WHERE DATE(start_date) = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $eventDate);
        $stmt->execute();
        $result = $stmt->get_result();

        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        echo json_encode($events);  // Return events to delete
        $stmt->close();
        exit();
    }

    // Delete Event Logic
    if (isset($_POST['deleteEvent']) && isset($_POST['eventId'])) {
        $eventId = $_POST['eventId'];
        
        // Query to delete the event based on eventId
        $query = "DELETE FROM events WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $eventId);

        if ($stmt->execute()) {
            echo "Event deleted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
        exit();
    } 
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Calendar</title>
    <!-- plugins:css -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
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
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
    .calendar-container {
        margin: 20px auto;
        max-width: 900px;
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

    .calendar-container {
        max-width: 600px;
        margin: 50px auto;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    #calendar {
        padding: 10px;
    }

    /* Header Styling */

    .calendar-container {
        max-width: 1000px;
        margin: 20px auto;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    h2 {
        font-size: 24px;
        font-weight: 700;
        /* Yellow Theme */
        text-align: center;
    }

    /* Buttons */
    button {
        background-color: #fdd835;
        color: #333;
        border: none;
        padding: 10px 15px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    button:hover {
        background-color: #fbc02d;
    }

    button:focus {
        outline: none;
    }

    /* Add Event Button */
    #addEventBtn {
        margin: 10px auto;
        display: block;
        display: flex;
        align-items: start;
    }

    /* Modal Styling */
    #eventModal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 400px;
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        z-index: 100000;
    }

    #eventModal h3 {
        font-size: 20px;
        font-weight: bold;
        color: #f9a825;
        /* Yellow Heading */
        text-align: center;
        margin-bottom: 15px;
    }

    #eventModal input,
    #eventModal select,
    #eventModal button {
        width: calc(100% - 20px);
        margin: 10px auto;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    #eventModal button {
        background-color: #fdd835;
        color: #333;
    }

    #eventModal button:hover {
        background-color: #fbc02d;
    }

    /* Tab Buttons */
    .tabs {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .tab-button {
        flex: 1;
        padding: 10px;
        font-size: 14px;
        text-align: center;
        cursor: pointer;
        background-color: #fdd835;
        border: 1px solid #fbc02d;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .tab-button.active {
        background-color: #f9a825;
        color: white;
    }

    /* Tab Content */
    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Calendar Styling */
    #calendar {
        padding: 20px;
        background-color: #fff8dc;
        /* Lighter yellow for calendar */
        border-radius: 10px;
    }

    .fc-toolbar {
        background-color: #fdd835;
        /* Yellow Header */
        padding: 10px;
        border-radius: 10px;
    }

    .fc-toolbar-title {
        font-size: 20px;
        font-weight: bold;
        color: #333;
    }

    .fc-button {
        background-color: #f9a825 !important;
        color: white !important;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        font-size: 14px;
        font-weight: bold;
    }

    .fc-button:hover {
        background-color: #fbc02d !important;
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
                        <a class="nav-link" href="Admin-Homepage.php">
                            <i class="mdi mdi-view-dashboard-outline menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Menu</li>
                    <li class="nav-item">
                        <a class="nav-link" href="Calendar.php">
                            <i class="mdi mdi-calendar-check menu-icon"></i>
                            <span class="menu-title">Calendar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../booking/history.php">
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
                        <a class="nav-link" href="service.php">
                            <i class="mdi mdi-tools menu-icon"></i>
                            <span class="menu-title">Services</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="Users.php">
                            <i class="mdi mdi-account-multiple menu-icon"></i>
                            <span class="menu-title">Users</span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="message_inbox.php">
                            <i class="mdi mdi-message-text-outline menu-icon"></i>
                            <span class="menu-title">Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Reports.php">
                            <i class="mdi mdi-file-chart menu-icon"></i>
                            <span class="menu-title">Reports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../login/login.php">
                            <i class="mdi mdi-logout user"></i> <!-- Changed the icon to mdi-logout -->
                            <span class="menu-title">Sign Out</span>
                        </a>
                    </li>
                </ul>
            </nav>


            <div class="calendar-container flex-grow-1">
                <h2 class="text-center mb-4">Admin Calendar</h2>

                <!-- Button to trigger Add Event Modal -->
                <button id="addEventBtn">
                    <i class="mdi mdi-calendar-plus"></i> Add Event
                </button>

                <!-- Modal to Add, View, or Delete Event -->
                <div id="eventModal">
                    <h3>Manage Event</h3>

                    <!-- Tabs for Add, View, and Delete -->
                    <div class="tabs">
                        <button id="addTab" class="tab-button active" onclick="showTab('add')">
                            <i class="mdi mdi-plus"></i> Add Event
                        </button>
                        <button id="viewTab" class="tab-button" onclick="showTab('view')">
                            <i class="mdi mdi-eye"></i> View Event
                        </button>
                        <button id="deleteTab" class="tab-button" onclick="showTab('delete')">
                            <i class="mdi mdi-delete"></i> Delete Event
                        </button>
                    </div>

                    <!-- Add Event Tab -->
                    <div id="addTabContent" class="tab-content active">
                        <form method="POST" action="Calendar.php">
                            <input type="text" name="eventTitle" id="eventTitle" placeholder="Event Title" required>
                            <input type="date" name="eventDate" id="eventDate" required>
                            <!-- Time Range Selection -->
                            <label for="startTime">Start Time:</label>
                            <select name="startTime" id="startTime" required>
                                <option value="08:00">08:00</option>
                                <option value="09:00">09:00</option>
                                <option value="10:00">10:00</option>
                                <option value="11:00">11:00</option>
                                <option value="12:00">12:00</option>
                                <option value="13:00">01:00</option>
                                <option value="14:00">02:00</option>
                                <option value="15:00">03:00</option>
                                <option value="16:00">04:00</option>
                                <option value="17:00">05:00</option>
                            </select>

                            <label for="endTime">End Time:</label>
                            <select name="endTime" id="endTime" required>
                                <option value="09:00">09:00</option>
                                <option value="10:00">10:00</option>
                                <option value="11:00">11:00</option>
                                <option value="12:00">12:00</option>
                                <option value="13:00">01:00</option>
                                <option value="14:00">02:00</option>
                                <option value="15:00">03:00</option>
                                <option value="16:00">04:00</option>
                                <option value="17:00">05:00</option>
                            </select>
                            <button type="submit" name="saveEventBtn" id="saveEventBtn">
                                <i class="mdi mdi-check"></i> Save Event
                            </button>
                            <button type="button" onclick="closeModal()">
                                <i class="mdi mdi-close"></i> Close
                            </button>
                        </form>
                    </div>

                    <!-- View Event Tab -->
                    <div id="viewTabContent" class="tab-content">
                        <div id="eventDetails">
                            <!-- Event details will be loaded here by JavaScript -->
                        </div>
                        <button type="button" onclick="closeModal()">
                            <i class="mdi mdi-close"></i> Close
                        </button>
                    </div>


                    <!-- Delete Event Tab -->
                    <div id="deleteTabContent" class="tab-content">
                        <div id="deleteEventList">
                            <!-- Events list will be populated here for deletion -->
                        </div>
                        <button type="button" onclick="closeModal()">
                            <i class="mdi mdi-close"></i> Close
                        </button>
                    </div>
                </div>

                <!-- FullCalendar -->
                <div id="calendar"></div>
            </div>

            <!-- FullCalendar JS -->
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth', // default view showing days in month
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek'
                    },
                    events: <?php echo $events_json; ?>, // Dynamically load events
                    dateClick: function(info) {
                        // Open the modal to add event details
                        document.getElementById('eventDate').value = info.dateStr;
                        document.getElementById('eventModal').style.display = 'block';
                    }
                });

                calendar.render();
            });

            // Open the Add Event modal
            document.getElementById('addEventBtn').addEventListener('click', function() {
                document.getElementById('eventModal').style.display = 'block';
            });

            function showTab(tab) {
                // Hide all tabs
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.classList.remove('active');
                });

                // Show the selected tab content
                document.getElementById(tab + 'TabContent').classList.add('active');
                document.getElementById(tab + 'Tab').classList.add('active');

                // Load data based on the selected tab
                if (tab === 'view') {
                    loadEventDetails(); // Fetch and display the event details
                }
                if (tab === 'delete') {
                    loadEventsForDeletion(); // Fetch and display events to delete
                }
            }

            // Function to close the modal
            function closeModal() {
                document.getElementById('eventModal').style.display = 'none';
            }

            // Fetch event details for view
            function loadEventDetails() {
                const selectedDate = document.getElementById('eventDate').value;

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "Calendar.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        let event = JSON.parse(xhr.responseText);
                        document.getElementById('eventDetails').innerHTML = `
                                <h4>Event Title: ${event.title}</h4>
                                <p>Start Date : ${event.start_date}</p>
                                <p>End Date : ${event.end_date}</p>

                            `;
                    }
                };
                xhr.send("viewEvent=true&date=" + selectedDate);
            }

            // Fetch events for deletion
            function loadEventsForDeletion() {
                const selectedDate = document.getElementById('eventDate').value;

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "Calendar.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        let events = JSON.parse(xhr.responseText);
                        let eventList = '<ul>';
                        events.forEach(event => {
                            eventList += `
                                    <li>
                                        ${event.title} 
                                        <button onclick="deleteEvent(${event.id})">Delete</button>
                                    </li>
                                `;
                        });
                        eventList += '</ul>';
                        document.getElementById('deleteEventList').innerHTML = eventList;
                    }
                };
                xhr.send("getEventsForDeletion=true&date=" + selectedDate);
            }

            // Function to delete an event
            function deleteEvent(eventId) {
                if (confirm('Are you sure you want to delete this event?')) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "Calendar.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onload = function() {
                        if (xhr.status == 200) {
                            alert(xhr.responseText); // Show success message
                            location.reload(); // Reload the page to reflect the changes
                        }
                    };
                    xhr.send("deleteEvent=true&eventId=" + eventId);
                }
            }
            </script>
            <!-- page-body-wrapper ends -->
            <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

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