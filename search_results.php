<?php
/****************      
    Name: Hanz Samonte
    Date:  December 8, 2024
    Description: Final Project - Search Results
****************/

session_start();
require_once 'connect.php';

$category_name = "All Categories";

if (isset($_GET['keyword'])) {
    // Sanitize the keyword
    $keyword = filter_input(INPUT_GET, 'keyword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT);

    // If a search included a specific category, execute this query
    if ($category && $category !== 'all') {
        $query = "SELECT * FROM items WHERE name LIKE :keyword AND category_id = :category ORDER BY name ASC";
        $statement = $db->prepare($query);
        $statement->bindValue(':keyword', '%' . $keyword . '%');
        $statement->bindValue(':category', $category, PDO::PARAM_INT);
        
        // Fetch the category name
        $category_query = "SELECT name FROM categories WHERE category_id = :category_id";
        $category_statement = $db->prepare($category_query);
        $category_statement->bindValue(':category_id', $category, PDO::PARAM_INT);
        $category_statement->execute();
        $category_result = $category_statement->fetch(PDO::FETCH_ASSOC);
        if ($category_result) {
            $category_name = $category_result['name'];
        }
    }
    // Else, if "all" category was selected, execute the normal search query
    else {
        $query = "SELECT * FROM items WHERE name LIKE :keyword ORDER BY name ASC";
        $statement = $db->prepare($query);
        $statement->bindValue(':keyword', '%' . $keyword . '%');
    }
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Search Results - Vintage Archives</title>
</head>
<body>
<div id="container">
        <!-- Include Header -->
        <?php include 'header.php' ?>

        <h1>Search Results for "<?= htmlspecialchars($keyword) ?>" in <?= htmlspecialchars($category_name) ?></h1>

        <!-- Include Sorting Navigation -->
        <?php include 'sort_navigation.php' ?>

        <!-- If there are products, show -->
        <?php if ($products): ?>
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
        <!-- If there are no products, show "no products" message -->
        <?php else: ?>
            <h1>No results found for "<?= htmlspecialchars($keyword) ?>".</h1>
        <?php endif; ?>

        <!-- Include Footer -->
        <?php include 'footer.php' ?>
    </div>
</body>
</html>