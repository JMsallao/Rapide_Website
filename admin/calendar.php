<?php 
session_start();
require_once "../connection.php";
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../img/logo.png" />
    <link rel="stylesheet" href="../css/admin/db-notif.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/admin/calendar.css">
    <link rel="stylesheet" href="../css/admin/db-no-content.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar" class="js-sidebar">
            <!-- Sidebar Content -->
            <div class="h-100">
                <div class="sidebar-logo">
                    <img src="../img/logo-header.png" width="70px" height="auto" alt="Icon">
                    <a href="db-home.html" class="logo">A.C Tech</a>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="db-home.php" class="sidebar-link">
                            <i class="fa-solid fa-chart-simple pe-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-calendar pe-2"></i>
                            Booking Appointments
                        </a>
                        <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="db-calendar.php" class="sidebar-link">Calendar</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link collapsed" data-bs-target="#bookings"
                                    data-bs-toggle="collapse" aria-expanded="false"><i
                                        class="fa-solid fa-bookmark pe-2"></i>
                                    List of Bookings
                                </a>
                                <ul id="bookings" class="sidebar-dropdown list-unstyled collapse"
                                    data-bs-parent="#pages">
                                    <li class="sidebar-item">
                                        <a href="db-appointment-list.php" class="sidebar-link">Pendings</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="db-ap-approved.php" class="sidebar-link">Approved</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="db-ap-rejected.php" class="sidebar-link">Rejected</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="db-ap-cancelled.php" class="sidebar-link">Cancelled</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="db-users-manage.php" class="sidebar-link">
                            <i class="fa-solid fa-user pe-2"></i>
                            Users
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="db-reports.php" class="sidebar-link">
                            <i class="fa-solid fa-chart-pie pe-2"></i>
                            Reports
                        </a>
                    </li>
                </ul>
            </div>
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
                <button class="btn" id="sidebar-toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <li>
                            <i class="fa-regular fa-bell notification-icon" onclick="togglePopup()"></i>
                            <span class="notification-dot"></span>
                            <div class="notification-popup" id="notificationPopup">
                                <div class="notif-header">Notifications</div>
                                <p>No notifications available.</p>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <i id="avatar" class="fa-regular fa-user"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="db-settings.php" class="dropdown-item">Setting</a>
                                <a href="a-logout.php" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="content px-0 py-2">
                <div class="container-fluid">
                    <div class="container">
                        <div class="calendar">
                            <div class="header">
                                <div class="month"></div>
                                <div class="btns">
                                    <div class="btn today-btn">
                                        <i class="fas fa-calendar-day"></i>
                                    </div>
                                    <div class="btn prev-btn">
                                        <i class="fas fa-chevron-left"></i>
                                    </div>
                                    <div class="btn next-btn">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="weekdays">
                                <div class="day">Sun</div>
                                <div class="day">Mon</div>
                                <div class="day">Tue</div>
                                <div class="day">Wed</div>
                                <div class="day">Thu</div>
                                <div class="day">Fri</div>
                                <div class="day">Sat</div>
                            </div>
                            <div class="days">
                                <div class="dot-dot">
                                    <div class="event-dot event-dot-admin"></div>
                                    <div class="event-dot-second event-dot-booking"></div>
                                </div>
                                <!-- Days will be added here by JS -->
                            </div>
                        </div>
                        <div class="head-addevent">
                            <button type="button" class="btn btn-primary1" data-bs-toggle="modal"
                                data-bs-target="#addEventModal">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </main>
            <!-- Add Event Modal -->
            <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addEventModalLabel">Add Event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="eventForm">
                                <div class="mb-3">
                                    <label for="eventDate" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="eventDate" name="eventDate" required>
                                </div>
                                <div class="mb-3">
                                    <label for="eventDescription" class="form-label">Note</label>
                                    <textarea class="form-control" id="eventDescription" name="eventDescription"
                                        required></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="saveEventButton">Save Event</button>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#" class="theme-toggle">
                <i class="fa-regular fa-moon"></i>
                <i class="fa-regular fa-sun"></i>
            </a>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a href="#" class="text-muted">
                                    <strong>A.C TECH</strong>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/switch-toggle.js"></script>
    <script src="../js/admin/calendar.js"></script>
    <script src="db-notif.js"></script>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const daysContainer = document.querySelector(".days");
        const month = document.querySelector(".month");
        const todayBtn = document.querySelector(".today-btn");
        const prevBtn = document.querySelector(".prev-btn");
        const nextBtn = document.querySelector(".next-btn");

        todayBtn.addEventListener('click', () => {
            const currentDate = new Date();
            currentMonth = currentDate.getMonth();
            currentYear = currentDate.getFullYear();
            renderCalendar();
        });

        prevBtn.addEventListener('click', () => {
            if (currentMonth === 0) { // If January
                currentMonth = 11; // Set to December
                currentYear -= 1;
            } else {
                currentMonth -= 1;
            }
            renderCalendar();
        });

        nextBtn.addEventListener('click', () => {
            currentMonth = (currentMonth + 1) % 12;
            if (currentMonth === 0) { // If January
                currentYear += 1;
            }
            renderCalendar();
        });

        const months = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        const currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        let events = [];
        let bookings = [];

        function fetchEvents() {
            fetch('../admin/db-calendar-fetch-event.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Fetched events:', data); // Add this line to log fetched events
                    events = data.map(event => ({
                        eventDate: new Date(event.eventDate),
                        eventDescription: event.eventDescription,
                        eventStatus: event.eventStatus
                    }));
                    renderCalendar();
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                });
        }

        function fetchBookings() {
            fetch('db-calendar-fetch-book.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error('Server error: ' + data.error);
                    }
                    bookings = data.map(booking => ({
                        date: new Date(booking.date),
                        time: booking.time
                    }));
                    renderCalendar();
                })
                .catch(error => {
                    console.error('Error fetching bookings:', error);
                });
        }

        function renderCalendar() {
            const firstDay = new Date(currentYear, currentMonth, 1);
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            const lastDayPrevMonth = new Date(currentYear, currentMonth, 0).getDate();

            daysContainer.innerHTML = '';
            month.textContent = `${months[currentMonth]} ${currentYear}`;

            const prevDays = firstDay.getDay();
            const nextDays = 7 - lastDay.getDay() - 1;
            const daysInMonth = lastDay.getDate();

            for (let x = prevDays; x > 0; x--) {
                daysContainer.innerHTML += `<div class="day prev-date">${lastDayPrevMonth - x + 1}</div>`;
            }

            for (let i = 1; i <= daysInMonth; i++) {
                const currentDateClass = (i === currentDate.getDate() && currentMonth === currentDate
                    .getMonth() && currentYear === currentDate.getFullYear()) ? 'today' : '';
                let hasEventClass = '';
                let eventDotHTML = '';
                let bookingDotHTML = '';

                const eventsForDay = events.filter(event => {
                    const eventDate = new Date(event.eventDate);
                    return eventDate.getDate() === i &&
                        eventDate.getMonth() === currentMonth &&
                        eventDate.getFullYear() === currentYear;
                });

                const bookingsForDay = bookings.filter(booking => {
                    const bookingDate = new Date(booking.date);
                    return bookingDate.getDate() === i &&
                        bookingDate.getMonth() === currentMonth &&
                        bookingDate.getFullYear() === currentYear;
                });

                if (eventsForDay.length > 0) {
                    eventDotHTML = '<div class="event-dot event-dot-admin"></div>';
                    hasEventClass = 'has-event';
                }

                if (bookingsForDay.length > 0) {
                    bookingDotHTML = '<div class="event-dot event-dot-booking"></div>';
                    hasEventClass = 'has-event';
                }

                daysContainer.innerHTML += `<div class="day ${currentDateClass} ${hasEventClass}">
                                    ${i}
                                    ${eventDotHTML}
                                    ${bookingDotHTML}
                                    <div class="event-status">
                                    <div class="event-book">
                                      ${eventsForDay.map(event => `<div class="event-info"><strong>NOT AVAILABLE</strong></div>
                                      <div class="event-description"><i><span style="color: red;">Note: </span>${event.eventDescription}</i></div>`).join('')}
                                      ${bookingsForDay.map(booking => `<div class="booking-info"><strong>BOOKED</strong>
                                      <div class="booking-description"><i>${booking.time}</i></div></div>`)}
                                      </div>
                                  </div>`;
            }

            for (let j = 1; j <= nextDays; j++) {
                daysContainer.innerHTML += `<div class="day next-date">${j}</div>`;
            }
        }

        todayBtn.addEventListener('click', () => {
            currentMonth = currentDate.getMonth();
            currentYear = currentDate.getFullYear();
            renderCalendar();
        });


        saveEventButton.addEventListener('click', function(event) {
            event.preventDefault();

            const eventDate = document.getElementById('eventDate').value;
            const eventDescription = document.getElementById('eventDescription').value;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'db-calendar-save-event.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        $('#addEventModal').modal('hide');
                        fetchEvents();
                        document.getElementById('eventForm').reset();
                        alert('Event added successfully!');


                    } else {
                        alert('Failed to add event: ' + response.message);
                    }
                } else {
                    alert('Error: ' + xhr.status);
                }
            };

            xhr.onerror = function() {
                alert('Request failed');
            };

            const formData =
                `&eventDate=${encodeURIComponent(eventDate)}&eventDescription=${encodeURIComponent(eventDescription)}`;
            xhr.send(formData);
        });


        fetchEvents();
        fetchBookings();
    });
    </script>


</body>

</html>