// Make A Match — Main JavaScript
// Mobile menu + smooth interactions

function toggleMenu() {
    const nav = document.getElementById('navLinks');
    nav.classList.toggle('open');
}

// Close menu on outside click
document.addEventListener('click', function(e) {
    const nav = document.getElementById('navLinks');
    const toggle = document.querySelector('.nav-toggle');
    if (nav && nav.classList.contains('open') && !nav.contains(e.target) && !toggle.contains(e.target)) {
        nav.classList.remove('open');
    }
});

// Smooth scroll reveal animation
const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -20px 0px' };
const observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Apply to cards on load
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card, .rec-card');
    cards.forEach(function(card, index) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.4s ease ' + (index * 0.05) + 's, transform 0.4s ease ' + (index * 0.05) + 's';
        observer.observe(card);
    });
});
