<!-- /****************      
    Name: Hanz Samonte
    Date:  December 9, 2024
    Description: Final Project - Logout Link  
****************/ -->

<?php if (isset($_SESSION['user_id'])): ?>
    <a href="logout.php" class="logout-button">Logout</a>
<?php endif; ?>