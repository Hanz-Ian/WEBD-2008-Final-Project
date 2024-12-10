<?php
/****************      
    Name: Hanz Samonte
    Date:  December 8, 2024
    Description: Final Project - Search Form
****************/

// Fetch categories from the database
require_once 'connect.php';
$query = "SELECT * FROM categories ORDER BY name ASC";
$statement = $db->prepare($query);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<form action="search_results.php" method="get">
    <select name="category">
        <option value="all">All Categories</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['category_id'] ?>"><?= $category['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="keyword" placeholder="Search..." required>
    <input type="submit" value="Search">
</form>