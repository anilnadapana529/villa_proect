<?php
require_once __DIR__ . '/../helpers/api.php';

if (API::isLoggedIn()) {
    $role = API::getUserRole();
    if ($role === 'admin') header("Location: admin-dashboard.php");
    elseif ($role === 'owner') header("Location: owner-dashboard.php");
    else header("Location: user-dashboard.php");
    exit;
}

include __DIR__ . '/../includes/header.php';
?>

<div class="container" style="max-width: 500px; margin: 60px auto; padding: 20px;">
    <div style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #1e3a8a; margin-bottom: 30px;">Login</h2>

        <div id="message"></div>

        <form id="loginForm">
            <div class="mb-3">
                <label class="form-label" style="color: #1e3a8a; font-weight: 600;">Email</label>
                <input type="email" name="email" class="form-control" required style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px;">
            </div>

            <div class="mb-3">
                <label class="form-label" style="color: #1e3a8a; font-weight: 600;">Password</label>
                <input type="password" name="password" class="form-control" required style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px;">
            </div>

            <div class="mb-3">
                <label class="form-label" style="color: #1e3a8a; font-weight: 600;">Login As</label>
                <select name="role" class="form-control" style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px;">
                    <option value="user">User</option>
                    <option value="owner">Villa Owner</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <button type="submit" class="btn w-100" style="background: #1e3a8a; color: white; padding: 12px; font-weight: 600; border-radius: 8px; border: none; margin-top: 20px;">Login</button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #718096;">
            Don't have an account? <a href="register.php" style="color: #1e3a8a; font-weight: 600;">Sign Up</a>
        </p>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    const endpoint = data.role + '-login';

    try {
        const response = await fetch('/api/' + endpoint, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                email: data.email,
                password: data.password
            })
        });

        const result = await response.json();

        if (result.status) {
            localStorage.setItem('token', result.token);
            localStorage.setItem('role', data.role);
            
            if (data.role === 'admin') {
                localStorage.setItem('user', JSON.stringify(result.admin));
                window.location.href = 'admin-dashboard.php';
            } else if (data.role === 'owner') {
                localStorage.setItem('user', JSON.stringify(result.owner));
                window.location.href = 'owner-dashboard.php';
            } else {
                localStorage.setItem('user', JSON.stringify(result.user));
                window.location.href = 'user-dashboard.php';
            }
        } else {
            document.getElementById('message').innerHTML = '<div class="alert alert-danger">' + (result.message || 'Login failed') + '</div>';
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('message').innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
    }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
