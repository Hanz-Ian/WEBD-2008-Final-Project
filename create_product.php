<?php
/****************      
    Name: Hanz Samonte
    Date:  December 5, 2024
    Description: Final Project - Create Product  
****************/

session_start();
require_once 'connect.php';
require 'image-resize/ImageResize.php';
require 'image-resize/ImageResizeException.php';

use \Gumlet\ImageResize;

// Redirect to login page if not logged in or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Function to build the file upload path
function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
    $current_folder = dirname(__FILE__);
    
    // Build an array of paths segment names to be joined using OS specific slashes.
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    
    return join(DIRECTORY_SEPARATOR, $path_segments);
}

// Fetch Categories from the database
$query = "SELECT * FROM categories ORDER BY name ASC";
$statement = $db->prepare($query);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_POST && !empty($_POST['name']) && !empty($_POST['brand']) && !empty($_POST['description']) 
&& !empty($_POST['size']) && !empty($_POST['price']) && !empty($_POST['style']) 
&& !empty($_POST['category_id'])) {
    // Sanitize user input to escape HTML entities and filter out dangerous characters
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $style = filter_input(INPUT_POST, 'style', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);

    // Handle image upload
    $image_filename = null;
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

        // Ensure the image is uploaded and resized correctly
        if ($file_extension_valid && $mime_type_valid) {
            if (move_uploaded_file($temporary_image_path, $new_image_path)) {
                // Resize the image
                $image = new ImageResize($new_image_path);
                $image->resizeToWidth(400);
                $image->save($new_image_path);
            } else {
                $image_filename = null;
            }
        } else {
            $image_filename = null;
        }
    }

    // Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO items (name, brand, description, size, price, style, category_id, image) 
    VALUES (:name, :brand, :description, :size, :price, :style, :category_id, :image)";
    $statement = $db->prepare($query);

    // Bind values to the parameters
    $statement->bindValue(':name', $name);
    $statement->bindValue(':brand', $brand);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':size', $size);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':style', $style);
    $statement->bindValue(':category_id', $category_id);
    $statement->bindValue(':image', $image_filename);

    // Execute the Insert
    if ($statement->execute()) {
        echo "Product created successfully!";
    } 
    else {
        echo "Error: Could not create product.";
    }
    
    // Redirect to a confirmation or product listing page
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
    <title>Create New Product</title>
</head>
<body>
<div id="container">
        <!-- Include Header -->
        <?php include 'header.php' ?>

        <h1>Create New Product</h1>
        <form action="create_product.php" method="post" enctype="multipart/form-data" class="create-form">
            <label for="image">Image:</label>
            <input type="file" id="image" name="image">
                
            <br><br>
                
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
                
            <label for="style">Style:</label>
            <input type="text" id="style" name="style" required>
                
            <br><br>
                
            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['category_id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
                
            <br><br>
                
            <input type="submit" value="Create Product">
        </form>

        <!-- Include Footer -->
        <?php include 'footer.php' ?>
    </div>
</body>
</html>