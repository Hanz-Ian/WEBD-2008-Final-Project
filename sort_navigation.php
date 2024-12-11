<?php
/****************      
    Name: Hanz Samonte
    Date:  December 10, 2024
    Description: Final Project - Sort Navigation
****************/

// Determine the sorting column and direction
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$sort_direction = isset($_GET['direction']) && $_GET['direction'] === 'desc' ? 'DESC' : 'ASC';

// Determine the new sorting direction
$new_sort_direction = $sort_direction === 'ASC' ? 'desc' : 'asc';

// Include category ID for category.php
$category_id = isset($_GET['id']) ? '&id=' . htmlspecialchars($_GET['id']) : '';

// Include keyword and category parameters for search_results.php
$keyword = isset($_GET['keyword']) ? '&keyword=' . htmlspecialchars($_GET['keyword']) : '';
$category = isset($_GET['category']) ? '&category=' . htmlspecialchars($_GET['category']) : '';
?>

<nav class="sort-nav">
    <ul>
        <li><a href="?sort=name&direction=<?= $new_sort_direction . $category_id . $keyword . $category ?>" class="sort-link">Sort by Name</a></li>
        <li><a href="?sort=created_at&direction=<?= $new_sort_direction . $category_id . $keyword . $category ?>" class="sort-link">Sort by Created Date</a></li>
        <li><a href="?sort=updated_at&direction=<?= $new_sort_direction . $category_id . $keyword . $category ?>" class="sort-link">Sort by Updated Date</a></li>
    </ul>
</nav>