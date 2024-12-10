<?php
/****************      
    Name: Hanz Samonte
    Date:  December 7, 2024
    Description: Final Project - Categories Page
****************/

session_start();
require 'connect.php';

// Fetch all categories from the database
$query = "SELECT * FROM categories ORDER BY name ASC";
$statement = $db->prepare($query);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Vintage Archives</title>
</head>
<body>
    <!-- Include the search form -->
    <?php include 'search_form.php'; ?>

    <!-- Include the logout link -->
    <?php include 'logout_link.php'; ?>

    <!-- Include the login link -->
    <?php include 'login_link.php'; ?>

    <h1>Categories</h1>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li><a href="category.php?id=<?= $category['category_id'] ?>"><?= $category['name'] ?></a></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>