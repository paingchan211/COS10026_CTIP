<?php
include "../connection.php";

$id = $title = $date = $description = $photo = $error = "";

// Check if delete action is requested and to display edit and view form
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    // Handle delete
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "DELETE FROM activities WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: management.php");
        exit();
    } 
    // Handle edit or view form display
    else if ($_GET['action'] == 'edit' || $_GET['action'] == 'view' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM activities WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $date = $row['date'];
        $description = $row['description'];
        $photo = $row['photo'];
    } 
}

// Check if form is submitted - for edit and add activities
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        // Handle create and update
        $title = $_POST['title'];
        $date = $_POST['date'];
        $description = $_POST['description'];
        $photo = $_POST['original_photo'];

        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
            $target_dir = "../images/";
            $target_file = $target_dir . basename($_FILES["photo"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $allowed_types = ["jpg", "jpeg", "png", "gif"];
            if (in_array($imageFileType, $allowed_types)) {
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                    $photo = basename($_FILES["photo"]["name"]);
                } else {
                    $error = "Sorry, there was an error uploading your file.";
                }
            } else {
                $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        }

        if (isset($_POST['id']) && $_POST['id']) {
            // Update existing activity
            $id = $_POST['id'];
            $sql = "UPDATE activities SET title=?, date=?, description=?, photo=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $title, $date, $description, $photo, $id);
            $stmt->execute();
        } else {
            // Create new activity
            $sql = "INSERT INTO activities (title, date, description, photo) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $title, $date, $description, $photo);
            $stmt->execute();
        }

        header("Location: management.php");
        exit();
    } else if (isset($_POST['cancel'])) {
        header("Location: management.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel (Activities)</title>
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
            <h1>Activities Management</h1>
            <div id="table_top">
                <form method="get" action="management.php" id="search-form">
                    <input id="search" type="text" name="search" placeholder="SEARCH SOMETHING" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" id="search-btn" class="sort_logout"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <a id="add-new" href="management.php?action=add">Add New</a>
                </form>
                <form method="get" action="management.php" id="sort-form">
                    <select name="sort_by" class="sort_order" id="sort_by">
                        <option disabled selected>Sort by</option>
                        <option value="id" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'id') ? 'selected' : ''; ?>>ID</option>
                        <option value="title" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'title') ? 'selected' : ''; ?>>Title</option>
                        <option value="date" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'date') ? 'selected' : ''; ?>>Date</option>
                    </select>
                    <select name="order" class="sort_order">
                        <option disabled selected>Order by</option>
                        <option value="asc" <?php echo (isset($_GET['order']) && $_GET['order'] == 'asc') ? 'selected' : ''; ?>>Ascending</option>
                        <option value="desc" <?php echo (isset($_GET['order']) && $_GET['order'] == 'desc') ? 'selected' : ''; ?>>Descending</option>
                    </select>
                    <input class="sort_logout" type="submit" value="Sort">
                </form>

            </div>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Photo</th>
                    <th>Actions</th>
                </tr>
                <?php
                // Handle sorting and searching
                $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
                $order = isset($_GET['order']) ? $_GET['order'] : 'desc';
                $valid_sort_columns = ['id', 'title', 'date'];
                if (!in_array($sort_by, $valid_sort_columns)) {
                    $sort_by = 'id';
                }

                $search = isset($_GET['search']) ? $_GET['search'] : '';
                if (empty($_GET['search'])) {
                    $sql = "SELECT * FROM activities";
                } else {
                    $filtervalues = $_GET['search'];
                    $sql = "SELECT * FROM activities WHERE CONCAT(title,date,description) LIKE '%$filtervalues%' ORDER BY $sort_by $order";
                }
                $result = $conn->query($sql);
                if (!$result) {
                    die("Invalid query!");
                }
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>{$row['id']}</td>
                        <td>{$row['title']}</td>
                        <td>{$row['date']}</td>
                        <td><img id='activity-img' src='../images/{$row['photo']}' alt='photo'></td>
                        <td>
                            <a id='view-button' href='management.php?action=view&id={$row['id']}'>View</a>
                            <a id='edit-button' href='management.php?action=edit&id={$row['id']}'>Edit</a>
                            <a id='delete-button' href='management.php?action=confirm-delete&id={$row['id']}'>Delete</a>
                        </td>
                    </tr>
                    ";
                }
                ?>
            </table>

            <?php if (isset($_GET['action']) && $_GET['action'] == 'confirm-delete'): ?>
                <div class="user-edit pop-up">
                    <div id="pop-up-content-confirm-delete">
                        <a class="close-btn" href="management.php">&times;</a>
                            <h1>Confirm Delete?</h1>
                            <a id="confirm-button" href="management.php?action=delete&id=<?php echo htmlspecialchars($_GET['id']); ?>">Delete</a>
                            <a id="close-button" href="management.php" class="button cancel">Cancel</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'edit')): ?>
            <div class="user-edit pop-up">
                <div class="pop-up-content">
                    <a class="close-btn" href="management.php">&times;</a>
                    <form method="post" enctype="multipart/form-data">
                        <h1><?php echo $_GET['action'] == 'edit' ? 'Update Activity' : 'Create New Activity'; ?></h1>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                        <input type="hidden" name="original_photo" value="<?php echo htmlspecialchars($photo); ?>">
                        <label for="title">TITLE:</label>
                        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($title); ?>"> <br>
                        <label for="date">DATE:</label>
                        <input type="text" name="date" id="date" value="<?php echo htmlspecialchars($date); ?>"> <br>
                        <label for="description">DESCRIPTION:</label>
                        <textarea rows="10" cols="42" name="description" id="description"><?php echo htmlspecialchars($description); ?></textarea> <br>
                        <label for="photo">PHOTO:</label>
                        <?php if ($photo) {
                            echo "<img src='../images/$photo' alt='photo'> <br>";
                        } ?>
                        <input type="file" name="photo" id="photo"> <br>
                        <input type="submit" name="submit" value="Submit">
                        <input type="submit" name="cancel" value="Cancel">
                    </form>
                </div>
            </div>  
            <?php endif; ?>

            <?php if (isset($_GET['action']) && $_GET['action'] == 'view'): ?>
            <div class="user-edit pop-up">
                <div class="pop-up-content">
                    <a class="close-btn" href="management.php">&times;</a>
                    <h1>View Activity</h1>
                    <p><strong>ID:</strong> <?php echo htmlspecialchars($id); ?></p>
                    <p><strong>Title:</strong> <?php echo htmlspecialchars($title); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($date); ?></p>
                    <p><strong>Description:</strong> <?php echo $description; ?></p>
                    <p><strong>Photo:</strong></p>
                    <img src="../images/<?php echo htmlspecialchars($photo); ?>" alt="photo">
                    <a id="close-button" href="management.php">Close</a>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($confirmNeeded) && $confirmNeeded): ?>
                    <div class="user-edit pop-up">
                    <div id="pop-up-content-confirm-delete">
                        <a class="close-btn" href="management.php">&times;</a>
                        <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <h1>Confirm Delete?</h1>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="confirm" value="yes">
                            <input id="confirm-button" type="submit" value="Confirm">
                            <a id="close-button" href="management.php" class="button cancel">Cancel</a>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </main>
</section>
</body>
</html>
