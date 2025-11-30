<?php
include "../helpers/api.php";

if (API::isLoggedIn()) {
    $role = API::getUserRole();
    if ($role === 'admin') {
        header("Location: admin-dashboard.php");
    } elseif ($role === 'owner') {
        header("Location: owner-dashboard.php");
    } else {
        header("Location: user-dashboard.php");
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';
    
    $endpoint = $role . '-login';
    $response = API::post($endpoint, ['email' => $email, 'password' => $password]);
    
    if ($response['status'] ?? false) {
        API::setToken($response['token']);
        API::setUserRole($role);
        API::setUser($response[$role] ?? $response['user'] ?? []);
        
        if ($role === 'admin') {
            header("Location: admin-dashboard.php");
        } elseif ($role === 'owner') {
            header("Location: owner-dashboard.php");
        } else {
            header("Location: user-dashboard.php");
        }
        exit;
    } else {
        $error = $response['message'] ?? 'Login failed';
    }
}

include "../includes/header.php";
?>

<style>
.login-container {
    min-height: calc(100vh - 56px);
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
}

.login-card {
    background: white;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    max-width: 450px;
    width: 100%;
}

.login-card h2 {
    text-align: center;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
}

.login-card p {
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
    transition: border-color 0.3s;
}

.form-group input:focus, .form-group select:focus {
    outline: none;
    border-color: #667eea;
}

.btn-login {
    width: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 14px;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s;
}

.btn-login:hover {
    transform: translateY(-2px);
}

.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    background: #fee2e2;
    color: #991b1b;
    text-align: center;
}

.register-link {
    text-align: center;
    margin-top: 20px;
    color: #718096;
}

.register-link a {
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
}
</style>

<div class="login-container">
    <div class="login-card">
        <h2>Welcome Back</h2>
        <p>Sign in to your account</p>
        
        <?php if ($error): ?>
            <div class="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Login As</label>
                <select name="role" required>
                    <option value="user">User / Guest</option>
                    <option value="owner">Villa Owner</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" class="btn-login">Sign In</button>
        </form>
        
        <div class="register-link">
            Don't have an account? <a href="register.php">Sign up</a>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
