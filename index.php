<?php
session_start();
require 'connect.php';

// Fetch all products from the database
$query = "SELECT * FROM items";
$statement = $db->prepare($query);
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vintage Archives</title>
</head>
<body>
    <!-- Logout -->
    <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="logout-button">Logout</a>
    <?php endif; ?>

    <!-- If a login was successful prior, it'll redirect to the page -->
    <?php if (isset($_SESSION['login_success'])): ?> 
        <p><?= $_SESSION['login_success'] ?></p>
        <?php unset($_SESSION['login_success']); ?>
    <?php endif; ?>
    
    <!-- Showing list of Products -->
    <h1>Product List</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Brand</th>
            <th>Description</th>
            <th>Size</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Style</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['name'] ?></td>
                <td><?= $product['brand'] ?></td>
                <td><?= $product['description'] ?></td>
                <td><?= $product['size'] ?></td>
                <td><?= $product['price'] ?></td>
                <td><?= $product['stock'] ?></td>
                <td><?= $product['style'] ?></td>
                <td><?= $product['category'] ?></td>
                <td>
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