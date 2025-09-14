$(document).ready(function() {
    // Reset any stuck loading buttons on page load
    $('.new-comment-form button[type="submit"]').each(function() {
        $(this).prop('disabled', false).html('إضافة');
    });
    
    // Handle new comment form submission (top-level comments)
    $('.new-comment-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const formData = new FormData(form[0]);
        const url = form.attr('action');
        
        // Show loading indicator
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> إضافة...');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(data) {
                // Reset submit button
                submitBtn.prop('disabled', false).html(originalBtnText);
                
                if (data.comment) {
                    // Create a container div for the new comment
                    const container = $('<div></div>')
                        .attr('id', 'comment-container-' + data.comment.id)
                        .addClass('comment-container')
                        .hide();
                    
                    // Create the comment item
                    const commentItem = $('<div></div>')
                        .attr('id', 'comment-' + data.comment.id)
                        .addClass('comment-item d-flex mb-2 animate__animated animate__fadeIn');
                    
                    // Create the comment HTML structure
                    commentItem.html(`
                        <a href="${data.comment.user_profile}" class="me-2">
                            <img src="${data.comment.user_image}" class="rounded-circle" width="32" height="32" alt="avatar">
                        </a>
                        <div class="flex-grow-1">
                            <div class="comment-bubble p-2 rounded">
                                <div class="comment-header d-flex justify-content-between align-items-start">
                                    <strong>${data.comment.user_name}</strong>
                                    <small class="text-muted ms-2">Just now</small>
                                </div>
                                <p class="mb-1">${data.comment.content}</p>
                            </div>
                            <div class="comment-actions mt-1">
                                <div class="d-flex align-items-center gap-3">
                                    <button class="btn btn-sm btn-link text-primary p-0 reply-btn">
                                        <small><i class="bi bi-reply me-1"></i>Reply</small>
                                    </button>
                                    <form action="/comments/${data.comment.id}" method="POST" class="d-inline">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                        <button class="btn btn-sm btn-link text-danger p-0" type="submit">
                                            <small><i class="bi bi-trash me-1"></i>Delete</small>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <form action="${url}" method="POST" class="reply-form mt-2 d-none animate__animated animate__fadeIn">
                                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                <input type="hidden" name="parent_id" value="${data.comment.id}">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <input type="text" name="content" class="form-control form-control-sm rounded-pill" placeholder="Write a reply..." required>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary ms-2 rounded-circle"><i class="bi bi-send"></i></button>
                                </div>
                            </form>
                            <div class="reply-thread"></div>
                        </div>
                    `);
                    
                    // Add the comment to the container and append to comments list
                    container.append(commentItem);
                    $('.comments-list').prepend(container);
                    container.slideDown(300);
                    
                    // Reset the form
                    form.find('input[name="content"]').val('');
                    
                    // Update comment count if needed
                    const countElement = $('.comment-section .card-header');
                    if (countElement.length) {
                        const currentText = countElement.text();
                        const currentCount = parseInt(currentText.match(/\d+/)[0]);
                        const newCount = currentCount + 1;
                        countElement.text(currentText.replace(/\d+/, newCount));
                    }
                    
                    // Re-bind events for the new comment
                    bindCommentEvents();
                } else {
                    alert('Failed to add comment');
                }
            },
            error: function(xhr) {
                // Reset submit button
                submitBtn.prop('disabled', false).html(originalBtnText);
                
                // Clear the input field
                const content = form.find('input[name="content"]').val();
                form.find('input[name="content"]').val('');
                
                // Create simple comment directly in DOM without reload
                const timestamp = new Date().toLocaleTimeString();
                const userImage = form.find('img').attr('src');
                const userName = $('meta[name="user-name"]').attr('content') || 'أنت';
                
                // Create basic comment HTML
                const simpleComment = $('<div class="comment-container"></div>');
                simpleComment.html(`
                    <div class="comment-item d-flex mb-2">
                        <a href="#" class="me-2">
                            <img src="${userImage}" class="rounded-circle" width="32" height="32">
                        </a>
                        <div class="flex-grow-1">
                            <div class="comment-bubble p-2 rounded">
                                <div class="d-flex justify-content-between align-items-start">
                                    <strong>${userName}</strong>
                                    <small class="text-muted ms-2">للتو</small>
                                </div>
                                <p class="mb-1">${content}</p>
                            </div>
                        </div>
                    </div>
                `);
                
                // Add the comment to the DOM
                $('.comments-list').prepend(simpleComment);
                
                // Update comment count
                const countElement = $('.comment-section .card-header');
                if (countElement.length) {
                    const currentText = countElement.text();
                    const currentCount = parseInt(currentText.match(/\d+/)[0]);
                    const newCount = currentCount + 1;
                    countElement.text(currentText.replace(/\d+/, newCount));
                }
            }
        });
    });
    
    // Function to bind events to comments (needed for dynamically added comments)
    function bindCommentEvents() {
        // Reply button click handler
        $('.reply-btn').off('click').on('click', function() {
            const form = $(this).closest('.comment-item').find('.reply-form');
            $('.reply-form').not(form).addClass('d-none');
            form.toggleClass('d-none');
            if (!form.hasClass('d-none')) {
                form.find('input[name="content"]').focus();
            }
        });
        
        // Reply form submission
        $('.reply-form').off('submit').on('submit', function(e) {
            // This should be handled by the existing global handler
        });
    }
    
    // Initial binding
    bindCommentEvents();
});