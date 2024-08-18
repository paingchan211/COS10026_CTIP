<?php

include "../connection.php";

$id = $userid = $email = $password = $error ="";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cancel'])) {
        header("Location: users.php");
        exit();
    }

    $userid = trim($_POST["userid"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate inputs
    if (empty($userid)) {
        $error = "User ID is required";
    } elseif (empty($email)) {
        $error = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif (empty($password)) {
        $error = "Password is required";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long";
    }

    if (empty($error)) {
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);
        if (isset($_POST['submit']) && $_POST['submit'] == 'Create') {
            $sql = "INSERT INTO users (userid, email, password, password_hashed) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $userid, $email, $password, $password_hashed);
            if ($stmt->execute()) {
                header("Location: users.php?success=New user created");
                exit();
            } else {
                $error = "Error creating user.";
            }
        } elseif (isset($_POST['submit']) && $_POST['submit'] == 'Update') {
            $id = $_POST["id"];
            $sql = "UPDATE users SET userid=?, email=?, password=?, password_hashed=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $userid, $email, $password, $password_hashed, $id);
            if ($stmt->execute()) {
                header("Location: users.php?success=User updated");
                exit();
            } else {
                $error = "No changes were made or the user does not exist.";
            }
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    // Check for request to delete
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "DELETE FROM users WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header('Location: users.php');
        exit();
    }
    // Check for request to edit
    else if ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM users WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            $userid = $row["userid"];
            $email = $row["email"];
            $password = $row["password"];
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel (Users)</title>
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
            <h1>User management</h1>
            <div id="table_top">
                <form method="get" action="users.php" id="search-form">
                    <input id="search" type="text" name="search" placeholder="SEARCH SOMETHING" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" id="search-btn" class="sort_logout"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <a id="add-new" href="users.php?action=add">Add New</a>
                </form>
                <form method="get" action="users.php" id="sort-form">
                    <select name="sort_by" class="sort_order" id="sort_by">
                        <option disabled selected>Sort by</option>
                        <option value="id" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'id') ? 'selected' : ''; ?>>ID</option>
                        <option value="userid" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'userid') ? 'selected' : ''; ?>>Username</option>
                        <option value="email" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'email') ? 'selected' : ''; ?>>Email</option>
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
                        <th>Password</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                
                    <?php
                    // Handle sorting
                    $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
                    $order = isset($_GET['order']) ? $_GET['order'] : 'asc';
                    $valid_sort_columns = ['id','userid', 'email'];
                    if (!in_array($sort_by, $valid_sort_columns)) {
                        $sort_by = 'id';    
                    }
                    
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    if (empty($_GET['search'])) {
                        $sql = "SELECT * FROM users";
                    } else {
                        $filtervalues = $_GET['search'];
                        $sql = "SELECT * FROM users WHERE CONCAT(userid,email,password) LIKE '%$filtervalues%' ORDER BY $sort_by $order";
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
                            <td>{$row['password']}</td>
                            <td>{$row['email']}</td>
                            <td>";
                        if ($row['userid'] != 'admin') {
                            echo "<a id='edit-button' href='users.php?action=edit&id={$row['id']}'>Edit</a>";
                            echo "<a id='delete-button' href='users.php?action=confirm-delete&id={$row['id']}'>Delete</a>";
                        }
                        echo "
                            </td>
                        </tr>
                        ";
                    }                        
                    ?>

            </table>

            <?php if (isset($_GET['action']) && $_GET['action'] == 'confirm-delete'): ?>
                <div class="user-edit pop-up">
                    <div id="pop-up-content-confirm-delete">
                        <a class="close-btn" href="users.php">&times;</a>
                            <h1>Confirm Delete?</h1>
                            <a id="confirm-button" href="users.php?action=delete&id=<?php echo htmlspecialchars($_GET['id']); ?>">Delete</a>
                            <a id="close-button" href="users.php" class="button cancel">Cancel</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['action']) && ($_GET['action'] == 'add' || ($_GET['action'] == 'edit' && isset($_GET['id'])))): ?>
            <div class="user-edit pop-up">
                <div class="pop-up-content users">
                    <a class="close-btn" href="users.php">&times;</a>
                    <form method="post">
                        <h1><?php echo $_GET['action'] == 'edit' ? 'Update User' : 'Create New User'; ?></h1>
                        <?php
                            if (!empty($error)) {
                                echo "<p class='user_error'>". $error ."</p>";
                            }
                        ?>
                        <?php if ($_GET['action'] == 'edit'): ?>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                        <?php endif; ?>
                        <label for="userid"> USER ID: </label>
                        <input type="text" name="userid" id="userid" value="<?php echo htmlspecialchars($userid); ?>"> <br>
                        <label for="email1"> EMAIL: </label>
                        <input type="text" name="email" id="email1" value="<?php echo htmlspecialchars($email); ?>"> <br>
                        <label for="password"> PASSWORD: </label>
                        <input type="text" name="password" id="password" value="<?php echo htmlspecialchars($password); ?>"> <br>
                        <input type="submit" name="submit" value="<?php echo $_GET['action'] == 'edit' ? 'Update' : 'Create'; ?>">
                        <input type="submit" name="cancel" value="Cancel">
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </section>
    </main>
    
    </section>
</body>
</html>
