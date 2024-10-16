function showServiceArea(event) {
    event.preventDefault();
    window.location.href = '../home.php#services';
}

function showContactArea(event) {
    event.preventDefault();
    window.location.href = '../home.php#contact';
}

// If the URL contains the #services or #contact hash, show the respective area
document.addEventListener("DOMContentLoaded", function() {
    const serviceArea = document.getElementById('service-area');
    const contactArea = document.getElementById('contact-area');

    if (window.location.hash === "#services") {
        serviceArea.style.display = 'block';
        serviceArea.scrollIntoView({
            behavior: 'smooth'
        });
    } else if (window.location.hash === "#contact") {
        contactArea.style.display = 'block';
        contactArea.scrollIntoView({
            behavior: 'smooth'
        });
    } else {}
});


const sr = ScrollReveal({
    distance: '50px',
    duration: 2500,
    reset: false
});

sr.reveal('.head-text', {
    delay: 70,
    origin: 'left'
});
sr.reveal('.services-text', {
    delay: 70,
    origin: 'bottom'
});
sr.reveal('.slider-text', {
    delay: 70,
    origin: 'bottom'
});
sr.reveal('.service1', {
    delay: 70,
    origin: 'left'
});
sr.reveal('.service2', {
    delay: 70,
    origin: 'right'
}); // Corrected typo here