<?php
/****************      
    Name: Hanz Samonte
    Date:  December 8, 2024
    Description: Final Project - Search Results
****************/

session_start();
require 'connect.php';

if (isset($_GET['keyword'])) {
    // Sanitize the keyword
    $keyword = filter_input(INPUT_GET, 'keyword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Search for products by name
    $query = "SELECT * FROM items WHERE name LIKE :keyword ORDER BY name ASC";
    $statement = $db->prepare($query);
    $statement->bindValue(':keyword', '%' . $keyword . '%');
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Vintage Archives</title>
</head>
<body>
    <!-- Include the search form -->
    <?php include 'search_form.php'; ?>

    <!-- Logout -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php" class="logout-button">Logout</a>
    <?php endif; ?>

    <h1>Search Results for "<?= $keyword ?>"</h1>
    <!-- If there are products, show -->
    <?php if ($products): ?>
        <ul>
            <?php foreach ($products as $product): ?>
                <li><a href="product.php?id=<?= $product['item_id'] ?>"><?= $product['name'] ?></a></li>
            <?php endforeach; ?>
        </ul>
    <!-- If there are no products, show "no products" message -->
    <?php else: ?>
        <p>No results found for "<?= $keyword ?>".</p>
    <?php endif; ?>
</body>
</html>