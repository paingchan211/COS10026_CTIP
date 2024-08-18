<?php
include "../connection.php";

// Check if delete action is requested and to display edit and view form
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    // Handle delete
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "DELETE FROM volunteer_information WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: view_joinvolunteer.php");
        exit();
    } 
    // Handle edit or view form display
    else if ($_GET['action'] == 'view' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM volunteer_information WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $fullname = $row['first_name'] . " " . $row['last_name'];
        $email = $row['email'];
        $phone_num = $row['phone_num'];
        $street_address = $row['street_address'];
        $postcode = $row['postcode'];
        $city_or_town = $row['city_or_town'];
        $state = $row['state'];
        $organization = $row['organization'];
        $organization_type = $row['organization_type'];
        $days = $row['days'];
        $time = $row['time'];
        $message = $row['message'];
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel (Volunteer)</title>
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
            <h1>Volunteer Management</h1>
            <div id="table_top">
                <form method="get" action="view_joinvolunteer.php" id="search-form">
                    <input class="feedback-search" type="text" name="search" placeholder="SEARCH SOMETHING" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="feedback-search-btn sort_logout"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
                <form method="get" action="view_joinvolunteer.php" id="sort-form">
                    <select name="sort_by" class="sort_order" id="sort_by">
                        <option disabled selected>Sort by</option>
                        <option value="id" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'id') ? 'selected' : ''; ?>>ID</option>
                        <option value="first_name" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'first_name') ? 'selected' : ''; ?>>Name</option>
                        <option value="phone_num" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'phone_num') ? 'selected' : ''; ?>>Phone Number</option>
                        <option value="organization" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'organization') ? 'selected' : ''; ?>>Organization</option>
                        <option value="organization_type" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'organization_type') ? 'selected' : ''; ?>>Organization Type</option>
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
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Organization</th>
                    <th>Organization Type</th>
                    <th>Actions</th>
                </tr>
                <?php
                // Handle sorting and searching
                $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
                $order = isset($_GET['order']) ? $_GET['order'] : 'desc';
                $valid_sort_columns = ['id', 'first_name', 'phone_num','organization','organization_type'];
                if (!in_array($sort_by, $valid_sort_columns)) {
                    $sort_by = 'id';
                }
                
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                if (empty($_GET['search'])) {
                    $sql = "SELECT * FROM volunteer_information";
                } else {
                    $filtervalues = $_GET['search'];
                    $sql = "SELECT * FROM volunteer_information WHERE CONCAT(first_name,last_name,email,phone_num,street_address,city_or_town,state,postcode,organization,organization_type,days,time,message) LIKE '%$filtervalues%' ORDER BY $sort_by $order";
                }
                $result = $conn->query($sql); 
                if (!$result) {
                    die("Invalid query!");
                }
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>{$row['id']}</td>
                        <td>{$row['first_name']} {$row['last_name']}</td>
                        <td>{$row['phone_num']}</td>
                        <td>{$row['organization']}</td>
                        <td>{$row['organization_type']}</td>
                        <td>
                            <a id='view-button' href='view_joinvolunteer.php?action=view&id={$row['id']}'>View</a>
                            <a id='delete-button' href='view_joinvolunteer.php?action=confirm-delete&id={$row['id']}'>Delete</a>
                        </td>
                    </tr>
                    ";
                }
                ?>
            </table>

            <?php if (isset($_GET['action']) && $_GET['action'] == 'confirm-delete'): ?>
                <div class="user-edit pop-up">
                    <div id="pop-up-content-confirm-delete">
                        <a class="close-btn" href="view_joinvolunteer.php">&times;</a>
                            <h1>Confirm Delete?</h1>
                            <a id="confirm-button" href="view_joinvolunteer.php?action=delete&id=<?php echo htmlspecialchars($_GET['id']); ?>">Delete</a>
                            <a id="close-button" href="view_joinvolunteer.php" class="button cancel">Cancel</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['action']) && $_GET['action'] == 'view'): ?>
            <div class="user-edit pop-up">
                <div class="pop-up-content view">
                    <a class="close-btn" href="view_joinvolunteer.php">&times;</a>
                    <h1>View Enquiry</h1>
                    <table>
                        <tr>
                            <th>Name:</th>
                            <td><?php echo htmlspecialchars($fullname); ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?php echo htmlspecialchars($email); ?></td>
                        </tr>
                        <tr>
                            <th>Phone Number:</th>
                            <td><?php echo htmlspecialchars($phone_num); ?></td>
                        </tr>
                        <tr>
                            <th>Street Address:</th>
                            <td><?php echo htmlspecialchars($street_address); ?></td>
                        </tr>
                        <tr>
                            <th>Postcode:</th>
                            <td><?php echo htmlspecialchars($postcode); ?></td>
                        </tr>
                        <tr>
                            <th>City/Town:</th>
                            <td><?php echo htmlspecialchars($city_or_town); ?></td>
                        </tr>
                        <tr>
                            <th>State:</th>
                            <td><?php echo htmlspecialchars($state); ?></td>
                        </tr>
                        <tr>
                            <th>Organization:</th>
                            <td><?php echo htmlspecialchars($organization); ?></td>
                        </tr>
                        <tr>
                            <th>Organization Type:</th>
                            <td><?php echo htmlspecialchars($organization_type); ?></td>
                        </tr>

                        <?php if ($organization_type === 'Part-time'): ?>
                            <tr>
                                <th>Working Days:</th>
                                <td><?php echo htmlspecialchars($days); ?></td>
                            </tr>
                            <tr>
                                <th>Working Time:</th>
                                <td><?php echo htmlspecialchars($time); ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Message:</th>
                            <td><?php echo htmlspecialchars($message); ?></td>
                        </tr>
                    </table>
                    <a id="close-button" href="view_joinvolunteer.php">Close</a>
                </div>
            </div>
            <?php endif; ?>

        </section>
    </main>
</section>
</body>
</html>
