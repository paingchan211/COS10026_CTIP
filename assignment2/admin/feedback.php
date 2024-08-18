<?php

include "../connection.php";

$id = $userid = $activity_id = $feedback = $created_at = "";

// Check for request to delete 
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM activity_feedbacks WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('Location: feedback.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel (Feedbacks)</title>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Daniel Sie, Zwe Htet Zaw, Paing Chan" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="images/love-you-gesture-svgrepo-com.svg" type="images/svg" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="../styles/style.css" />
</head>
<body>
<section id="admin">
    <div class="container">
    <input type="checkbox" id="menu-toggle" class="menu-toggle">
    <label for="menu-toggle" class="menu-toggle-label">☰</label>
    <?php include 'sidebar.php'; ?>
    </div>
    <main>
        <section class="user-management">
            <h1>Feedbacks Management</h1>
            <div id="table_top">
                <form method="get" action="feedback.php" id="search-form">
                    <input class="feedback-search" type="text" name="search" placeholder="SEARCH SOMETHING" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="feedback-search-btn sort_logout"><i class="fa-solid fa-magnifying-glass"></i></button>                    
                </form>
                <form method="get" action="feedback.php" id="sort-form">
                    <select name="sort_by" class="sort_order" id="sort_by">
                        <option disabled selected>Sort by</option>
                        <option value="id" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'id') ? 'selected' : ''; ?>>ID</option>
                        <option value="userid" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'userid') ? 'selected' : ''; ?>>Username</option>
                        <option value="activity_id" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'activity_id') ? 'selected' : ''; ?>>Activity Id</option>
                        <option value="feedback" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'feedback') ? 'selected' : ''; ?>>Feedback</option>
                        <option value="created_at" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'created_at') ? 'selected' : ''; ?>>Created At</option>
                    </select>
                    <select name="order" class="sort_order">
                        <option disabled selected>Order by</option>
                        <option value="asc" <?php echo (isset($_GET['order']) && $_GET['order'] == 'asc') ? 'selected' : ''; ?>>Ascending</option>
                        <option value="desc" <?php echo (isset($_GET['order']) && $_GET['order'] == 'desc') ? 'selected' : ''; ?>>Descending</option>
                    </select>
                    <input class="sort_logout" type="submit" value="Sort"></input>
                </form>
            </div>    
            <table>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Activity Id</th>
                        <th>Feedback</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>

                    <?php
                    // Handle sorting
                    $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
                    $order = isset($_GET['order']) ? $_GET['order'] : 'asc';
                    $valid_sort_columns = ['id', 'userid', 'activity_id', 'feedback', 'created_at'];
                    if (!in_array($sort_by, $valid_sort_columns)) {
                        $sort_by = 'id';
                    }

                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    if (empty($_GET['search'])) {
                        $sql = "SELECT * FROM activity_feedbacks";
                    } else {
                        $filtervalues = $_GET['search'];
                        $sql = "SELECT * FROM activity_feedbacks WHERE CONCAT(userid,activity_id,feedback) LIKE '%$filtervalues%' ORDER BY $sort_by $order";
                    }
                    $result = $conn->query($sql);
                    if (!$result) {
                        die("Invalid query!");
                    }
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>{$row['id']}</td>
                            <td>{$row['userid']}</td>
                            <td>{$row['activity_id']}</td>
                            <td>{$row['feedback']}</td>
                            <td>{$row['created_at']}</td>
                            <td>
                                <a id='delete-button' href='feedback.php?action=confirm-delete&id={$row['id']}'>Delete</a>
                            </td>
                        </tr>
                        ";
                    }                        
                    ?>

            </table>

            <?php if (isset($_GET['action']) && $_GET['action'] == 'confirm-delete'): ?>
                <div class="user-edit pop-up">
                    <div id="pop-up-content-confirm-delete">
                        <a class="close-btn" href="feedback.php">&times;</a>
                            <h1>Confirm Delete?</h1>
                            <a id="confirm-button" href="feedback.php?action=delete&id=<?php echo htmlspecialchars($_GET['id']); ?>">Delete</a>
                            <a id="close-button" href="feedback.php" class="button cancel">Cancel</a>
                    </div>
                </div>
            <?php endif; ?>

        </section>
    </main>
</section>
</body>
</html>
