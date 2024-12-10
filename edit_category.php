<?php
/****************      
    Name: Hanz Samonte
    Date:  December 6, 2024
    Description: Final Project - Edit Category  
****************/

session_start();
require_once 'connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Redirect to login page if not logged in or not an admin
    exit();
}

// Check if edited category contents are set
if ($_POST && isset($_POST['name']) && isset($_POST['category_id']) && !empty($_POST['name'])) {
    // Sanitize user input to escape HTML entities and filter out dangerous characters
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);

    // Build the parameterized SQL query and bind to the above sanitized values.
    $query = "UPDATE categories SET name = :name WHERE category_id = :category_id";
    $statement = $db->prepare($query);

    // Bind values to the parameters
    $statement->bindValue(':name', $name);
    $statement->bindValue(':category_id', $category_id);

    // Execute the Update
    if ($statement->execute()) {
        $_SESSION['update_success'] = "Category '{$name}' has been updated successfully!";
    } else {
        $_SESSION['update_error'] = "Error: Could not update category.";
    }

    // Redirect to the index page
    header("Location: index.php");
    exit();
} else if (isset($_GET['id'])) {
    // Retrieve category to be edited, if id GET parameter is in URL
    $category_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT * FROM categories WHERE category_id = :category_id LIMIT 1";
    $statement = $db->prepare($query);
    $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);

    // Execute the SELECT and fetch the single row returned
    $statement->execute();
    $category = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        // ID does not exist, redirect to index page
        header("Location: index.php");
        exit();
    }
} 
else {
    $category_id = false; // False if we are not UPDATING or SELECTING
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?= "{$category['name']}" ?></title>
</head>
<body>
    <!-- Include Header -->
    <?php include 'header.php' ?>
    
    <h1>Edit Category</h1>
    <?php if ($category_id): ?>
        <form action="edit_category.php?id=<?= $category['category_id'] ?>" method="post">
            <input type="hidden" name="category_id" value="<?= htmlspecialchars($category['category_id']) ?>">

            <label for="name">Category Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
                    
            <br><br>
                    
            <input type="submit" value="Update Category">
        </form>
    <?php endif; ?>

    <!-- Include Footer -->
    <?php include 'footer.php' ?>
</body>
</html>