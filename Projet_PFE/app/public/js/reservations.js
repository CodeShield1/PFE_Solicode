/**
 * Reservations Management JavaScript
 * Handles expand/collapse cards and alert auto-hide
 */

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    var alerts = document.querySelectorAll('.res-alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() { alert.remove(); }, 500);
        }, 5000);
    });
});

// Toggle reservation card expand/collapse
function toggleReservation(id) {
    var card = document.getElementById('res-card-' + id);
    var details = document.getElementById('res-details-' + id);
    if (!card || !details) return;

    var isExpanded = card.classList.contains('expanded');

    if (isExpanded) {
        // Collapse
        details.style.maxHeight = details.scrollHeight + 'px';
        // Force reflow
        details.offsetHeight;
        details.style.maxHeight = '0';
        card.classList.remove('expanded');
    } else {
        // Expand
        card.classList.add('expanded');
        details.style.maxHeight = details.scrollHeight + 'px';
        // Remove max-height after animation for dynamic content
        setTimeout(function() {
            if (card.classList.contains('expanded')) {
                details.style.maxHeight = 'none';
            }
        }, 400);
    }
}