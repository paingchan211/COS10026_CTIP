<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
function isActive($page) {
    $current_page = basename($_SERVER['PHP_SELF']);
    return $current_page == $page ? 'active' : '';
}
?>

<aside class="sidebar">
    <div class="logo"><img src="../images/logo2.png" alt="logo"></div>
    <nav id="admin-nav">
        <ul>
            <li class="<?php echo isActive('users.php'); ?>"><a href="users.php">User Management</a></li>
            <li class="<?php echo isActive('view_enquiryservice.php'); ?>"><a href="view_enquiryservice.php">Enquiry Forms</a></li>
            <li class="<?php echo isActive('view_joinvolunteer.php'); ?>"><a href="view_joinvolunteer.php">Volunteer Forms</a></li>
            <li class="<?php echo isActive('management.php'); ?>"><a href="management.php">Activity Management</a></li>
            <li class="<?php echo isActive('feedback.php'); ?>"><a href="feedback.php">Feedback Management</a></li>
            <li>                    <a class="sort_logout" href="../index.php">Logout</a>
            </li>
        </ul>
    </nav>
</aside>

    
</body>
</html>