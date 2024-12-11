<?php
/****************      
    Name: Hanz Samonte
    Date:  December 7, 2024
    Description: Final Project - Category Page
****************/

session_start();
require_once 'connect.php';

// Fetch category details
if (isset($_GET['id'])) {
    // Sanitize the id
    $category_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Validate the ID
    if ($category_id === null || $category_id === false || !is_numeric($category_id)) {
        // Invalid ID, redirect to categories.php
        header("Location: categories.php");
        exit();
    }

    // Fetch category name
    $query = "SELECT name FROM categories WHERE category_id = :category_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
    $statement->execute();
    $category = $statement->fetch(PDO::FETCH_ASSOC);

    // Check if the category exists
    if ($category === false) {
        // ID does not exist, redirect to categories.php
        header("Location: categories.php");
        exit();
    }

    // Fetch products for the category
    $query = "SELECT * FROM items WHERE category_id = :category_id ORDER BY name ASC";
    $statement = $db->prepare($query);
    $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
} 
else {
    // If no ID provided, redirect to categories.php
    header("Location: categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title><?= htmlspecialchars($category['name']) ?> - Vintage Archives</title>
</head>
<body>
    <div id="container">
        <!-- Include Header -->
        <?php include 'header.php' ?>

        <h1>Category: <?= htmlspecialchars($category['name']) ?></h1>

        <!-- Include Sorting Navigation -->
        <?php include 'sort_navigation.php' ?>

        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <?php if ($product['image']): ?>
                        <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php endif; ?>
                    <h2><a href="product.php?id=<?= htmlspecialchars($product['item_id']) ?>"><?= htmlspecialchars($product['name']) ?></a></h2>
                    <p>$<?= htmlspecialchars($product['price']) ?></p>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="edit_product.php?id=<?= htmlspecialchars($product['item_id']) ?>" class="admin-link">Edit</a>
                        <a href="delete_product.php?id=<?= htmlspecialchars($product['item_id']) ?>" class="admin-link">Delete</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Include Footer -->
        <?php include 'footer.php' ?>
    </div>
</body>
</html>