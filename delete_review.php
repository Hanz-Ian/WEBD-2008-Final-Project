<?php
/****************      
    Name: Hanz Samonte
    Date:  December 5, 2024
    Description: Final Project - Delete Review  
****************/

session_start();
require_once 'connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_POST && isset($_POST['review_id'])) {
    $review_id = filter_input(INPUT_POST, 'review_id', FILTER_SANITIZE_NUMBER_INT);

    $query = "DELETE FROM reviews WHERE review_id = :review_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':review_id', $review_id, PDO::PARAM_INT);
    $statement->execute();

    header('Location: product.php?id=' . $_POST['item_id']);
    exit();
}
?>