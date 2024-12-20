<?php
/****************      
    Name: Hanz Samonte
    Date:  December 6, 2024
    Description: Final Project - Registration  
****************/

session_start();
require_once 'connect.php';

if ($_POST && !empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['password']) 
&& !empty($_POST['confirm_password'])) {
    // Sanitize user input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } 
    else {
        // Check if username or email already exists
        $query = "SELECT COUNT(*) FROM users WHERE email = :email OR username = :username";
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $user_exists = $statement->fetchColumn();

        if ($user_exists) {
            $error_message = "Username or email already exists.";
        } 
        else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $query = "INSERT INTO users (email, username, password, role) VALUES (:email, :username, :password, 'user')";
            $statement = $db->prepare($query);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':username', $username);
            $statement->bindValue(':password', $hashed_password);

            // Redirect to the success page if user successfully registered
            if ($statement->execute()) {
                $_SESSION['registration_success'] = $username;
                header('Location: index.php'); 
                exit();
            } 
            else {
                $error_message = "Error: Could not register user.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Register User</title>
</head>
<body>
    <div id="container">
        <!-- Include Header -->
        <?php include 'header.php' ?>

        <!-- Show error message after invalid registration -->
        <h1>Register</h1>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form action="register.php" method="post" class="register-form">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
                
            <br><br>
                
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
                
            <br><br>
                
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
                
            <br><br>
                
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
                
            <br><br>
                
            <input type="submit" value="Register">
        </form>

        <!-- Include Footer -->
        <?php include 'footer.php' ?>
    </div> 
</body>
</html>