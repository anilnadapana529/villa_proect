<?php
include __DIR__ . "/../helpers/api.php";

if (API::isLoggedIn()) {
    header("Location: /dashboard");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        $endpoint = $role === 'owner' ? 'owner-register' : 'user-register';
        $response = API::post($endpoint, [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        if ($response['status'] ?? false) {
            $success = 'Registration successful! Please login.';
        } else {
            $error = $response['message'] ?? 'Registration failed';
        }
    }
}

include __DIR__ . "/../includes/header.php";
?>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.register-container {
    min-height: calc(100vh - 56px);
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px 20px;
    animation: fadeIn 0.5s ease;
}

.register-card {
    background: white;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    max-width: 500px;
    width: 100%;
    animation: fadeInUp 0.6s ease;
}

.register-card h2 {
    text-align: center;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
}

.register-card p {
    text-align: center;
    color: #718096;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
}

.form-group input, .form-group select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.form-group input:focus, .form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-register {
    width: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 14px;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
}

.login-link {
    text-align: center;
    margin-top: 20px;
    color: #718096;
}

.login-link a {
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s;
}

.login-link a:hover {
    color: #764ba2;
}

@media (max-width: 576px) {
    .register-card {
        padding: 30px 20px;
    }

    .register-card h2 {
        font-size: 1.5rem;
    }

    .register-container {
        padding: 20px 15px;
    }
}
</style>

<div class="register-container">
    <div class="register-card">
        <h2>Create Account</h2>
        <p>Join us and start booking luxury villas</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Register As</label>
                <select name="role" required>
                    <option value="user">User / Guest</option>
                    <option value="owner">Villa Owner</option>
                </select>
            </div>

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Create a password" required minlength="6">
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm your password" required minlength="6">
            </div>

            <button type="submit" class="btn-register">Create Account</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="/login">Sign in</a>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
