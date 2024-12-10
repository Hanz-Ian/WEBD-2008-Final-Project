<?php
/****************      
    Name: Hanz Samonte
    Date:  December 5, 2024
    Description: Final Project - Edit Product  
****************/

session_start();
require_once 'connect.php';

require 'image-resize/ImageResize.php';
require 'image-resize/ImageResizeException.php';

use \Gumlet\ImageResize;

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Redirect to login page if not logged in or not an admin
    exit();
}

// Function to build the file upload path
function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
    $current_folder = dirname(__FILE__);
    
    // Build an array of paths segment names to be joined using OS specific slashes.
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    
    return join(DIRECTORY_SEPARATOR, $path_segments);
}

// Check if edited product contents are set
if ($_POST && isset($_POST['name']) && isset($_POST['brand']) && isset($_POST['description']) 
&& isset($_POST['size']) && isset($_POST['price']) && isset($_POST['style']) && isset($_POST['category']) 
&& isset($_POST['stock']) && isset($_POST['id']) && !empty($_POST['name']) && !empty($_POST['brand']) 
&& !empty($_POST['description']) && !empty($_POST['size']) && !empty($_POST['price']) 
&& !empty($_POST['style']) && !empty($_POST['category']) && !empty($_POST['stock'])) {
    // Sanitize user input to escape HTML entities and filter out dangerous characters
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
    $style = filter_input(INPUT_POST, 'style', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Handle image upload
    $image_filename = $_POST['existing_image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_filename = $_FILES['image']['name'];
        $temporary_image_path = $_FILES['image']['tmp_name'];
        $new_image_path = file_upload_path($image_filename);

        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        $actual_mime_type = mime_content_type($temporary_image_path);

        $allowed_file_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $actual_file_extension = pathinfo($new_image_path, PATHINFO_EXTENSION);

        // Variables for validating if file extensions and mime types are valid
        $file_extension_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_valid = in_array($actual_mime_type, $allowed_mime_types);

        // When the variables of file extensions and mime types are valid
        if ($file_extension_valid && $mime_type_valid) {
            if (move_uploaded_file($temporary_image_path, $new_image_path)) {
                // Resize the image
                $image = new ImageResize($new_image_path);
                $image->resizeToWidth(400);
                $image->save($new_image_path);
            } 
            else {
                $image_filename = $_POST['existing_image'];
            }
        } 
        else {
            $image_filename = $_POST['existing_image'];
        }
    }

    // Check if the image should be deleted
    if (isset($_POST['delete_image']) && $_POST['delete_image'] == 'yes') {
        if (file_exists(file_upload_path($image_filename))) {
            unlink(file_upload_path($image_filename));
        }
        $image_filename = null;
    }

    // Build the parameterized SQL query and bind to the above sanitized values.
    $query = "UPDATE items SET name = :name, brand = :brand, description = :description, size = :size, 
    price = :price, stock = :stock, style = :style, category = :category, 
    image = :image WHERE item_id = :id";
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
    $statement->bindValue(':image', $image_filename);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    // Execute the Update
    if ($statement->execute()) {
        $_SESSION['update_success'] = "Product '{$name}' has been updated successfully!";
    } 
    else {
        $_SESSION['update_error'] = "Error: Could not update product.";
    }

    // Redirect to the home page
    header("Location: index.php");
    exit();
} 
else if (isset($_GET['id'])) {
    // Retrieve product to be edited, if id GET parameter is in URL
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT * FROM items WHERE item_id = :id LIMIT 1";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    // Execute the SELECT and fetch the single row returned
    $statement->execute();
    $product = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        // ID does not exist, redirect to index.php
        header("Location: index.php");
        exit();
    }
} 
else {
    $id = false; // False if we are not UPDATING or SELECTING
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?= "{$product['name']}" ?></title>
</head>
<body>

    <!-- Include Header -->
    <?php include 'header.php' ?>

    <h1>Edit Product</h1>
    <?php if ($id): ?>
        <form action="edit_product.php?id=<?= $product['item_id'] ?>" method="post">

            <label for="image">Image:</label>
            <?php if ($product['image']): ?>
                <img src="uploads/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                <br><br>
                <label for="delete_image">Delete Image:</label>
                <input type="checkbox" id="delete_image" name="delete_image" value="yes">
            <?php else: ?>
                <p>No image available</p>
                <label for="image">Choose New Image:</label>
                <input type="file" id="image" name="image">
            <?php endif; ?>

            <br><br>

            <input type="hidden" name="id" value="<?= $product['item_id'] ?>">
            <input type="hidden" name="existing_image" value="<?= $product['image'] ?>">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= $product['name'] ?>" required>
                
            <br><br>
                
            <label for="brand">Brand:</label>
            <input type="text" id="brand" name="brand" value="<?= $product['brand'] ?>" required>
                
            <br><br>
                
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?= $product['description'] ?></textarea>
                
            <br><br>
                
            <label for="size">Size:</label>
            <input type="text" id="size" name="size" value="<?= $product['size'] ?>" required>
                
            <br><br>
                
            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" value="<?= $product['price'] ?>" required>
                
            <br><br>
                
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" value="<?= $product['stock'] ?>" required>
            
            <br><br>
                
            <label for="style">Style:</label>
            <input type="text" id="style" name="style" value="<?= $product['style'] ?>" required>
                
            <br><br>
                
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" value="<?= $product['category'] ?>" required>
                
            <br><br>
                
            <input type="submit" value="Update Product">
        </form>
    <?php endif; ?>

    <!-- Include Footer -->
    <?php include 'footer.php' ?>
</body>
</html>