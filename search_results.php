<?php
/****************      
    Name: Hanz Samonte
    Date:  December 8, 2024
    Description: Final Project - Search Results
****************/

session_start();
require_once 'connect.php';

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
    <title>Search Results - Vintage Archives</title>
</head>
<body>
    <!-- Include the search form -->
    <?php include 'search_form.php'; ?>

    <!-- Include the logout link -->
    <?php include 'logout_link.php'; ?>

    <!-- Include the login link -->
    <?php include 'login_link.php'; ?>

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