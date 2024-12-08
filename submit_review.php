<?php

/****************      
    Name: Hanz Samonte
    Date:  December 7, 2024
    Description: Final Project - Submit Review
****************/

session_start();
require 'connect.php';

if ($_POST && isset($_POST['item_id']) && isset($_POST['review'])) {
    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT);
    $review = filter_input(INPUT_POST, 'review', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $name = isset($_SESSION['user_id']) ? $_SESSION['username'] : filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $query = "INSERT INTO reviews (item_id, user_id, name, review) VALUES (:item_id, :user_id, :name, :review)";
    $statement = $db->prepare($query);
    $statement->bindValue(':item_id', $item_id);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':review', $review);

    if ($statement->execute()) {
        echo "Review submitted successfully!";
    } else {
        echo "Error: Could not submit review.";
    }

    header("Location: product.php?id=$item_id");
    exit();
}
?>