<?php
/****************      
    Name: Hanz Samonte
    Date:  December 7, 2024
    Description: Final Project - Login Page
****************/

session_start();
require_once 'connect.php';

if ($_POST && !empty($_POST['username']) && !empty($_POST['password'])) {
    // Sanitize user input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password'];

    // Fetch the user from the database
    $query = "SELECT * FROM users WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // If password is correct, start a session
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_success'] = "Welcome, " . $user['username'] . "!"; // Success message
        header('Location: index.php'); // Redirect to the home page
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>
<body>
    <div id="container">
        <!-- Header -->
            <?php include 'header.php'; ?>

        <h1>Login</h1>
        <!-- Show error message after invalid login -->
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form action="login.php" method="post" class="login-form">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
                
            <br><br>
                
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
                
            <br><br>
                
            <input type="submit" value="Login">
        </form>

        <!-- Include Footer -->
        <?php include 'footer.php' ?>
    </div>
</body>
</html>