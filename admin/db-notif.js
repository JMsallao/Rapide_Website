document.addEventListener('DOMContentLoaded', function() {
    updateNotificationDot();

    setInterval(updateNotificationDot, 5000);
});

function togglePopup() {
    var popup = document.getElementById('notificationPopup');
    var notificationDot = document.querySelector('.notification-dot');
    popup.style.display = (popup.style.display === 'none' || popup.style.display === '') ? 'block' : 'none';

    // Hide the notification dot and mark notifications as read when the popup is opened
    if (popup.style.display === 'block') {
        notificationDot.style.display = 'none';
        fetch('db-p-fetch-notification.php?markAsRead=true')
            .then(response => response.json())
            .then(data => {
                updateNotificationPopup(data.notifications);
            })
            .catch(error => console.error('Error marking notifications as read:', error));
    }
}

function updateNotificationDot() {
    fetch('db-p-fetch-notification.php')
        .then(response => response.json())
        .then(data => {
            const notificationDot = document.querySelector('.notification-dot');
            if (data.hasNotifications) {
                notificationDot.style.display = 'block';
            } else {
                notificationDot.style.display = 'none';
            }
            updateNotificationPopup(data.notifications);
        })
        .catch(error => console.error('Error fetching notification status:', error));
}

function updateNotificationPopup(notifications) {
    const notificationPopup = document.getElementById('notificationPopup');
    notificationPopup.innerHTML = '<div class="notif-header">Notifications</div>';

    if (notifications.length > 0) {
        notifications.forEach(notification => {
            const notifItem = document.createElement('ul');
            notifItem.classList.add('notif-list');
            notifItem.innerHTML = `
                <li class='message'>${notification.message}</li>
                <li class='timestamp'>${notification.created_at}</li>
            `;
            notificationPopup.appendChild(notifItem);
        });
    } else {
        notificationPopup.innerHTML += "<p>No notifications available.</p>";
    }
}
