<header>
    <h1>Vintage Archives</h1>
    <?php
    // Check if the current page is not register.php
    $current_page = basename($_SERVER['PHP_SELF']);
    if ($current_page !== 'register.php'):
    ?>
        <!-- Include the logout link -->
        <?php include 'logout_link.php'; ?>
        <!-- Include the login link -->
        <?php include 'login_link.php'; ?>
    <?php endif; ?>
    <!-- Include the search form -->
    <?php include 'search_form.php'; ?>
</header>