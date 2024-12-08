<?php
/****************      
    Name: Hanz Samonte
    Date:  December 6, 2024
    Description: Final Project - Logout
****************/

session_start();
// Destroy session
session_destroy();

// Redirect to home page
header('Location: index.php');
exit();
?>