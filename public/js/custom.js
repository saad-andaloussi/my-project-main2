document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Flash Message Auto-Dismissal
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        setTimeout(function() {
            alerts.forEach(function(alert) {
                // Fade out effect
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000); // Dismiss after 5 seconds
    }

    // 2. Generic Delete Confirmation
    // Looks for any form with a class 'delete-form' or buttons with 'delete-confirm' class
    const deleteButtons = document.querySelectorAll('.delete-confirm');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ? Cette opération est irréversible.')) {
                e.preventDefault();
            }
        });
    });

    // 3. Dropdown enhancement for touch devices (optional)
    // Allows clicking on the dropdown trigger to toggle visibility on mobile
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(function(dropdown) {
        const trigger = dropdown.querySelector('button, .dropdown-toggle');
        if (trigger) {
            trigger.addEventListener('click', function(e) {
                // If on mobile/touch, we might want to toggle a class instead of relying on hover
                // For now, we'll leave CSS hover as primary, but prevent default link behavior if it's a # link
                if (trigger.getAttribute('href') === '#') {
                    e.preventDefault();
                }
            });
        }
    });

    console.log('Custom JS loaded successfully.');
});
