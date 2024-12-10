<?php if (!isset($_SESSION['user_id'])): ?>
    <a href="login.php" class="login-button">Login</a>
    <a href="register.php" class="register-button">Register</a>
<?php endif; ?>