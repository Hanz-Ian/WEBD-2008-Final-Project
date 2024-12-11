<?php
/****************      
    Name: Hanz Samonte
    Date:  December 7, 2024
    Description: Final Project - Categories Page
****************/

session_start();
require_once 'connect.php';

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
    <link rel="stylesheet" href="styles.css">
    <title>Categories - Vintage Archives</title>
</head>
<body>
    <div id="container">
        <!-- Include Header -->
         <?php include 'header.php' ?>

        <h1>Categories</h1>
        <ul class="category-list">
            <?php foreach ($categories as $category): ?>
                <li>
                    <a href="category.php?id=<?= htmlspecialchars($category['category_id']) ?>" 
                    class="category-link"><?= htmlspecialchars($category['name']) ?></a>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="edit_category.php?id=<?= htmlspecialchars($category['category_id']) ?>" class="admin-link">Edit</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Include Footer -->
        <?php include 'footer.php' ?>
    </div> 
</body>
</html>