<?php
require_once __DIR__ . '/helpers/api.php';

if (API::isLoggedIn()) {
    $role = API::getUserRole();
    if ($role === 'admin') header("Location: admin-dashboard.php");
    elseif ($role === 'owner') header("Location: owner-dashboard.php");
    else header("Location: user-dashboard.php");
    exit;
}

include __DIR__ . '/includes/header.php';
?>

<div class="container" style="max-width: 500px; margin: 60px auto; padding: 20px;">
    <div style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #1e3a8a; margin-bottom: 30px;">Create Account</h2>

        <div id="message"></div>

        <form id="registerForm">
            <div class="mb-3">
                <label class="form-label" style="color: #1e3a8a; font-weight: 600;">Full Name</label>
                <input type="text" name="name" class="form-control" required style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px;">
            </div>

            <div class="mb-3">
                <label class="form-label" style="color: #1e3a8a; font-weight: 600;">Email</label>
                <input type="email" name="email" class="form-control" required style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px;">
            </div>

            <div class="mb-3">
                <label class="form-label" style="color: #1e3a8a; font-weight: 600;">Phone</label>
                <input type="tel" name="phone" class="form-control" required style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px;">
            </div>

            <div class="mb-3">
                <label class="form-label" style="color: #1e3a8a; font-weight: 600;">Password</label>
                <input type="password" name="password" class="form-control" required style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px;">
            </div>

            <div class="mb-3">
                <label class="form-label" style="color: #1e3a8a; font-weight: 600;">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px;">
            </div>

            <button type="submit" class="btn w-100" style="background: #1e3a8a; color: white; padding: 12px; font-weight: 600; border-radius: 8px; border: none; margin-top: 20px;">Sign Up</button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #718096;">
            Already have an account? <a href="login.php" style="color: #1e3a8a; font-weight: 600;">Login</a>
        </p>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    if (data.password !== data.confirm_password) {
        document.getElementById('message').innerHTML = '<div class="alert alert-danger">Passwords do not match!</div>';
        return;
    }

    try {
        const response = await fetch('/api/user-register', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                name: data.name,
                email: data.email,
                phone: data.phone,
                password: data.password
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        console.log('API Response:', result);

        if (result.status) {
            localStorage.setItem('token', result.token);
            localStorage.setItem('role', 'user');
            localStorage.setItem('user', JSON.stringify(result.user));
            window.location.href = 'user-dashboard.php';
        } else {
            document.getElementById('message').innerHTML = '<div class="alert alert-danger">' + (result.message || 'Registration failed') + '</div>';
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('message').innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
