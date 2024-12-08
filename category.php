<?php
/****************      
    Name: Hanz Samonte
    Date:  December 7, 2024
    Description: Final Project - Category Page
****************/

session_start();
require 'connect.php';

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
    <title><?= $category['name'] ?> - Vintage Archives</title>
</head>
<body>
    <!-- Logout -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php" class="logout-button">Logout</a>
    <?php endif; ?>

    <h1>Category: <?= $category['name'] ?></h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Brand</th>
            <th>Size</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><a href="product.php?id=<?= $product['item_id'] ?>"><?= $product['name'] ?></a></td>
                <td><?= $product['brand'] ?></td>
                <td><?= $product['size'] ?></td>
                <td><?= $product['price'] ?></td>
                <td>
                    <!-- If is admin, it's able to edit/delete products -->
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="edit_product.php?id=<?= $product['item_id'] ?>">Edit</a>
                        <a href="delete_product.php?id=<?= $product['item_id'] ?>" 
                        onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>