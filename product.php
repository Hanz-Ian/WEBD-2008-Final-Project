<?php
/****************      
    Name: Hanz Samonte
    Date:  December 6, 2024
    Description: Final Project - Product View Page
****************/

session_start();
require 'connect.php';

// Fetch product details
if (isset($_GET['id'])) {
    // Sanitize the id
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Validate the ID
    if ($id === null || $id === false || !is_numeric($id)) {
        // Invalid ID, redirect to index.php
        header("Location: index.php");
        exit();
    }

    // Build the parameterized SQL query using the filtered id.
    $query = "SELECT * FROM items WHERE item_id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    // Execute the SELECT and fetch the single row returned.
    $statement->execute();
    $product = $statement->fetch(PDO::FETCH_ASSOC);

    // Check if the product exists
    if ($product === false) {
        // ID does not exist, redirect to index.php
        header("Location: index.php");
        exit();
    }
} 
else {
    // No ID provided, redirect to index.php
    header("Location: index.php");
    exit();
}

// Determine whether to show all reviews or just the recent ones
$show_all = isset($_GET['show_all']) && $_GET['show_all'] == '1';

// Fetch reviews for the product
if ($show_all) {
    $query = "SELECT * FROM reviews WHERE item_id = :item_id ORDER BY created_at DESC";
} 
else {
    $query = "SELECT * FROM reviews WHERE item_id = :item_id ORDER BY created_at DESC LIMIT 3";
}
$statement = $db->prepare($query);
$statement->bindValue(':item_id', $id, PDO::PARAM_INT);
$statement->execute();
$reviews = $statement->fetchAll(PDO::FETCH_ASSOC);

// Fetch the total number of reviews
$query = "SELECT COUNT(*) FROM reviews WHERE item_id = :item_id";
$statement = $db->prepare($query);
$statement->bindValue(':item_id', $id, PDO::PARAM_INT);
$statement->execute();
$total_reviews = $statement->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product['name'] ?> - Vintage Archives</title>
</head>
<body>
    <!-- Show product if it exists -->
    <?php if ($product): ?>
        <!-- Show product's image if it exists -->
        <?php if ($product['image']): ?>
            <img src="uploads/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
        <?php endif; ?>

        <!-- Display full product details -->
        <h1><?= $product['name'] ?></h1>
        <p><?= $product['description'] ?></p>
        <p><strong>Brand:</strong> <?= $product['brand'] ?></p>
        <p><strong>Size:</strong> <?= $product['size'] ?></p>
        <p><strong>Price:</strong> $<?= $product['price'] ?></p>
        <p><strong>Style:</strong> <?= $product['style'] ?></p>

        <!-- Create Review Form -->
        <h3>Leave a Review</h3>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="submit_review.php" method="post">
                <input type="hidden" name="item_id" value="<?= $product['item_id'] ?>">
                <label for="review">Review:</label>
                <textarea id="review" name="review" required></textarea>
                <br><br>
                <input type="submit" value="Submit Review">
            </form>
        <!-- User must log in to leave a review -->
        <?php else: ?>
            <p><a href="login.php">Log in</a> to leave a review.</p>
        <?php endif; ?>
         
        <!-- Review List -->
        <h2>Reviews</h2>
        <div id="recent-reviews">
            <?php foreach ($reviews as $review): ?>
                <p><strong><?= $review['name'] ?>:</strong> <?= $review['review'] ?></p>
                <!-- If user is admin, it's able to delete reviews -->
                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                    <form id="delete-form-<?= $review['review_id'] ?>" action="delete_review.php" method="post" style="display:inline;">
                            <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
                            <input type="hidden" name="item_id" value="<?= $product['item_id'] ?>">
                            <button type="button" onclick="return confirm('Are you sure you want to delete this review?')">Delete</button>
                    </form>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <!-- If there are more than 3 reviews, implement to show all reviews -->
        <?php if (!$show_all && $total_reviews > 3): ?>
            <a href="product.php?id=<?= $product['item_id'] ?>&show_all=1">Show more</a>
        <?php endif; ?>
        <!-- Show all reviews if "Show more" is clicked -->
        <?php if ($show_all): ?>
            <div id="all-reviews">
                <?php foreach ($reviews as $review): ?>
                    <p><strong><?= $review['name'] ?>:</strong> <?= $review['review'] ?></p>
                    <!-- If user is admin, it's able to delete reviews -->
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                        <form id="delete-form-<?= $review['review_id'] ?>" action="delete_review.php" method="post" style="display:inline;">
                            <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
                            <input type="hidden" name="item_id" value="<?= $product['item_id'] ?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this review?')">Delete</button>
                        </form>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>