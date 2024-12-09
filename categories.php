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
    <!-- Logout -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php" class="logout-button">Logout</a>
    <?php endif; ?>

    <!-- Search form -->
    <?php include 'search_form.php'; ?>

    <h1>Categories</h1>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li><a href="category.php?id=<?= $category['category_id'] ?>"><?= $category['name'] ?></a></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>