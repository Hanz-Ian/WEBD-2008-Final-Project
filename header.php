<!-- /****************      
    Name: Hanz Samonte
    Date:  December 9, 2024
    Description: Final Project - Header  
****************/ -->

<header>
    <h1>Vintage Archives</h1>
    <!-- Include the search form -->
    <?php include 'search_form.php'; ?>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="categories.php">Category</a></li>
            <!-- If user is admin, show admin priviliges -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="dropdown">
                        <a href="#" class="dropbtn"><?= htmlspecialchars($_SESSION['username']) ?></a>
                        <div class="dropdown-content">
                            <a href="create_product.php">Create Product</a>
                            <a href="create_category.php">Create Category</a>
                        </div>
                    </li>
                <!-- Else if user is just a regular logged in user -->
                <?php else: ?>
                    <li><a href="#"><?= htmlspecialchars($_SESSION['username']) ?></a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            <!-- Else, show the Login or Register -->
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>