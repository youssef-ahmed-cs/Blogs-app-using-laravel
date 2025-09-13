document.addEventListener('DOMContentLoaded', function() {
    // Reset any loading buttons that might be stuck
    setTimeout(function() {
        $('.new-comment-form button[type="submit"]').each(function() {
            $(this).prop('disabled', false).html('إضافة');
        });
    }, 500);
    
    // Force specific styles with !important to overcome any conflicts
    if (document.body.classList.contains('retro-theme')) {
        // Add extra specificity for comment styling
        document.querySelectorAll('.comment-bubble').forEach(bubble => {
            bubble.style.backgroundColor = 'rgba(255, 17, 167, 0.15)';
            bubble.style.border = '1px solid #FF11A7';
            bubble.style.boxShadow = '0 0 5px #FF11A7';
        });
        
        document.querySelectorAll('.comment-bubble p').forEach(p => {
            p.style.color = '#FF79C6';
            p.style.textShadow = '0 0 2px rgba(255, 121, 198, 0.5)';
        });
        
        document.querySelectorAll('.comment-bubble strong').forEach(strong => {
            strong.style.color = '#fff';
            strong.style.textShadow = '0 0 3px #FF11A7';
        });
        
        document.querySelectorAll('.comment-actions button small').forEach(small => {
            small.style.color = '#FF79C6';
        });
    } else {
        // Light theme styles
        document.querySelectorAll('.comment-bubble').forEach(bubble => {
            bubble.style.backgroundColor = '#f0f2f5';
            bubble.style.borderRadius = '18px';
        });
        
        document.querySelectorAll('.comment-bubble p').forEach(p => {
            p.style.color = '#333';
        });
    }
});