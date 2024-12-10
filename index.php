<?php
/****************      
    Name: Hanz Samonte
    Date:  December 7, 2024
    Description: Final Project - Homepage
****************/

session_start();
require_once 'connect.php';

// Fetch all products from the database with their category names
$query = "SELECT items.*, categories.name AS category_name FROM items 
          JOIN categories ON items.category_id = categories.category_id";
$statement = $db->prepare($query);
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

// Determine the sorting column and direction
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$sort_direction = isset($_GET['direction']) && $_GET['direction'] === 'desc' ? 'DESC' : 'ASC';

// Fetch all products from the database with sorting
$query = "SELECT items.*, categories.name AS category_name FROM items 
          JOIN categories ON items.category_id = categories.category_id 
          ORDER BY $sort_column $sort_direction";
$statement = $db->prepare($query);
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

// Determine the new sorting direction
$new_sort_direction = $sort_direction === 'ASC' ? 'desc' : 'asc';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Vintage Archives</title>
</head>
<body>
    
    <!-- Include the Header -->
     <?php include 'header.php' ?>

    <!-- If a login was successful prior, it'll redirect to the page -->
    <?php if (isset($_SESSION['login_success'])): ?>
        <p><?= $_SESSION['login_success'] ?></p>
        <?php unset($_SESSION['login_success']); // Clear the success message ?>
    <?php endif; ?>

    <!-- If deleting a post was successful prior, it'll redirect to the page -->
    <?php if (isset($_SESSION['delete_success'])): ?>
        <p><?= $_SESSION['delete_success'] ?></p>
        <?php unset($_SESSION['delete_success']); // Clear the success message ?>
    <?php endif; ?>

    <!-- If updating a post was successful prior, it'll redirect to the page -->
    <?php if (isset($_SESSION['update_success'])): ?>
        <p><?= htmlspecialchars($_SESSION['update_success']) ?></p>
        <?php unset($_SESSION['update_success']); // Clear the success message ?>
    <?php endif; ?>
    
    <!-- Showing list of Products -->
    <h1>Product List</h1>
    <table>
        <tr>
            <th><a href="?sort=name&direction=<?= $new_sort_direction ?>">Name</a></th>
            <th>Brand</th>           
            <th>Size</th>
            <th>Price</th>
            <th>Category</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
            <td><a href="product.php?id=<?= htmlspecialchars($product['item_id']) ?>"><?= htmlspecialchars($product['name']) ?></a></td>
                <td><?= htmlspecialchars($product['brand']) ?></td>             
                <td><?= htmlspecialchars($product['size']) ?></td>
                <td><?= htmlspecialchars($product['price']) ?></td>
                <td><?= htmlspecialchars($product['category_name']) ?></td>
                <td>
                    <!-- If is admin, it's able to edit/delete products -->
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="edit_product.php?id=<?= $product['item_id'] ?>">Edit</a>
                        <a href="delete_product.php?id=<?= $product['item_id'] ?>">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Include Footer -->
    <?php include 'footer.php' ?>
</body>
</html>