<?php
/****************      
    Name: Hanz Samonte
    Date:  December 6, 2024
    Description: Final Project - Create Category  
****************/

session_start();
require_once 'connect.php';

// Redirect to login page if not logged in or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_POST && !empty($_POST['name'])) {
    // Sanitize user input to escape HTML entities and filter out dangerous characters
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO categories (name) VALUES (:name)";
    $statement = $db->prepare($query);

    // Bind values to the parameters
    $statement->bindValue(':name', $name);

    // Execute the Insert
    if ($statement->execute()) {
        echo "Category created successfully!";
    } else {
        echo "Error: Could not create category.";
    }

    // Redirect to a confirmation or category listing page
    header("Location: categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Category</title>
</head>
<body>
    <!-- Include Header -->
    <?php include 'header.php' ?>
    
    <h1>Create Category</h1>
    <form action="create_category.php" method="post">
        <label for="name">Category Name:</label>
        <input type="text" id="name" name="name" required>
        <br><br>
        <input type="submit" value="Create Category">
    </form>

    <!-- Include Footer -->
    <?php include 'footer.php' ?>
</body>
</html>