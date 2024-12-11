<?php
/****************      
    Name: Hanz Samonte
    Date:  December 5, 2024
    Description: Final Project - Delete Product  
****************/

session_start();
require_once 'connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Redirect to login page if not logged in or not an admin
    exit();
}

// Fetch the product details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT items.*, categories.name AS category_name FROM items 
              JOIN categories ON items.category_id = categories.category_id 
              WHERE items.item_id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $product = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Product not found.";
        exit();
    }
} 
else {
    echo "Invalid product ID.";
    exit();
}

// Handle the delete confirmation
if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    $query = "DELETE FROM items WHERE item_id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    // Execute the Delete
    if ($statement->execute()) {
        $_SESSION['delete_success'] = "Product '{$product['name']}' has been deleted successfully!";
    } 
    else {
        $_SESSION['delete_error'] = "Error: Could not delete product.";
    }

    // Redirect to the home page
    header("Location: index.php");
    exit();
} 
// Redirect to the home page if user chooses not to delete
elseif (isset($_POST['confirm']) && $_POST['confirm'] === 'no') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Delete <?= "{$product['name']}" ?></title>
</head>
<body>
<div id="container">
        <!-- Include Header -->
        <?php include 'header.php' ?>
        
        <h1>Delete Product</h1>
        <div class="product-details">
            <!-- Show image if it exists within the item -->
            <?php if ($product['image']): ?>
                <p><strong>Image:</strong><br><img src="uploads/<?= htmlspecialchars($product['image']) ?>" 
                alt="<?= htmlspecialchars($product['name']) ?>" class="product-image"></p>
            <?php endif; ?>
            
            <p>Are you sure you want to delete the following product?</p>
            <p><strong>Name:</strong> <?= htmlspecialchars($product['name']) ?></p>
            <p><strong>Brand:</strong> <?= htmlspecialchars($product['brand']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($product['description']) ?></p>
            <p><strong>Size:</strong> <?= htmlspecialchars($product['size']) ?></p>
            <p><strong>Price:</strong> $<?= htmlspecialchars($product['price']) ?></p>
            <p><strong>Style:</strong> <?= htmlspecialchars($product['style']) ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
        </div>

        <!-- "Yes" form -->
        <form action="delete_product.php?id=<?= htmlspecialchars($product['item_id']) ?>" method="post" class="delete-form">
            <input type="hidden" name="confirm" value="yes">
            <input type="submit" value="Yes, delete it">
        </form>

        <!-- "No" form -->
        <form action="delete_product.php?id=<?= htmlspecialchars($product['item_id']) ?>" method="post" class="delete-form">
            <input type="hidden" name="confirm" value="no">
            <input type="submit" value="No, go back">
        </form>

        <!-- Include Footer -->
        <?php include 'footer.php' ?>
    </div>
</body>
</html>