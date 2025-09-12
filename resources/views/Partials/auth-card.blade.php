<!-- Authentication Card - Shows for guest users when trying to interact -->
<div id="auth-required-card" class="card auth-required-card shadow" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%; max-width: 400px; z-index: 1050;">
    <div class="card-body text-center py-5">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" onclick="hideAuthCard()"></button>
        <i class="bi bi-lock fs-1 text-primary mb-3"></i>
        <h5>Authentication Required</h5>
        <p class="text-muted">Please login or create an account to interact with posts and users.</p>
        <div class="d-grid gap-2 col-12 col-md-8 mx-auto">
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary">Create Account</a>
        </div>
    </div>
</div>

<!-- Overlay background -->
<div id="auth-overlay" class="auth-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 1040;"></div>

<script>
function showAuthCard() {
    document.getElementById('auth-required-card').style.display = 'block';
    document.getElementById('auth-overlay').style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

function hideAuthCard() {
    document.getElementById('auth-required-card').style.display = 'none';
    document.getElementById('auth-overlay').style.display = 'none';
    document.body.style.overflow = ''; // Restore scrolling
}

// Close the card when clicking on the overlay
document.getElementById('auth-overlay').addEventListener('click', hideAuthCard);
</script>