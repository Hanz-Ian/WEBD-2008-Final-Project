<?php
/****************      
    Name: Hanz Samonte
    Date:  December 5, 2024
    Description: Final Project - Create Product  
****************/

session_start();
require 'connect.php';


// Redirect to login page if not logged in or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
    

if ($_POST && !empty($_POST['name']) && !empty($_POST['brand']) && !empty($_POST['description']) 
&& !empty($_POST['size']) && !empty($_POST['price']) && !empty($_POST['style']) 
&& !empty($_POST['category']) && isset($_POST['stock'])) {
    // Sanitize user input to escape HTML entities and filter out dangerous characters
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
    $style = filter_input(INPUT_POST, 'style', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO items (name, brand, description, size, price, stock, style, category) VALUES (:name, :brand, :description, :size, :price, :stock, :style, :category)";
    $statement = $db->prepare($query);

    // Bind values to the parameters
    $statement->bindValue(':name', $name);
    $statement->bindValue(':brand', $brand);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':size', $size);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':stock', $stock);
    $statement->bindValue(':style', $style);
    $statement->bindValue(':category', $category);

    // Execute the Insert
    if ($statement->execute()) {
        echo "Product created successfully!";
    } else {
        echo "Error: Could not create product.";
    }
    
    // Redirect to a confirmation or product listing page
    header("Location: product_list.php");
    exit();
    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Product</title>
</head>
<body>
    <h1>Create New Product</h1>
    <form action="create_product.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
            
        <br><br>
            
        <label for="brand">Brand:</label>
        <input type="text" id="brand" name="brand" required>
            
        <br><br>
            
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
            
        <br><br>
            
        <label for="size">Size:</label>
        <input type="text" id="size" name="size" required>
            
        <br><br>
            
        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required>
            
        <br><br>
            
        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" required>
            
        <br><br>
            
        <label for="style">Style:</label>
        <input type="text" id="style" name="style" required>
            
        <br><br>
            
        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required>
            
        <br><br>
            
        <input type="submit" value="Create Product">
    </form>
</body>
</html>