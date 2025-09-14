/**
 * Guest Interactions Handler
 * This script handles interactions from guest users and shows the authentication card
 */

document.addEventListener('DOMContentLoaded', function() {
    // If user is not logged in (guest)
    if (!document.body.classList.contains('user-logged-in')) {
        // Find all elements that should trigger the auth card for guests
        setupGuestInteractions();
    }
});

function setupGuestInteractions() {
    // Like buttons
    document.querySelectorAll('.guest-like-btn, .guest-interaction').forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            showAuthCard();
            return false;
        });
    });

    // Comment forms and buttons
    document.querySelectorAll('.guest-comment-form, .guest-comment-btn').forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            showAuthCard();
            return false;
        });
    });

    // Follow buttons
    document.querySelectorAll('.guest-follow-btn').forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            showAuthCard();
            return false;
        });
    });

    // General interaction buttons
    document.querySelectorAll('.require-auth').forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            showAuthCard();
            return false;
        });
    });
}

// If this script loads after the DOM is already ready
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    setupGuestInteractions();
}