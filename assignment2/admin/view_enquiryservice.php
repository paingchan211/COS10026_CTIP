<?php
include "../connection.php";

// Check if delete action is requested and to display edit and view form
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    // Handle delete
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "DELETE FROM enquiry_information WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: view_enquiryservice.php");
        exit();
    } 
    // Handle edit or view form display
    else if ($_GET['action'] == 'view' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM enquiry_information WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $fullname = $row['first_name'] . " " . $row['last_name'];
        $email = $row['email'];
        $country_code = $row['countryCode'];
        $phone_number = $row['phoneNumber'];
        $service_type = $row['service_type'];
        $contact_method = $row['contact_method'];
        $appointment_option = $row['appointment_option'];
        $appointment_date = $row['appointment_date'];
        $appointment_time = $row['appointment_time'];
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel (Enquiry)</title>
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
            <h1>Enquiry Management</h1>
            <div id="table_top">
                <form method="get" action="view_enquiryservice.php" id="search-form">
                    <input class="feedback-search" type="text" name="search" placeholder="SEARCH SOMETHING" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="feedback-search-btn sort_logout"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
                <form method="get" action="view_enquiryservice.php" id="sort-form">
                    <select name="sort_by" class="sort_order" id="sort_by">
                        <option disabled selected>Sort by</option>
                        <option value="id" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'id') ? 'selected' : ''; ?>>ID</option>
                        <option value="first_name" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'first_name') ? 'selected' : ''; ?>>Name</option>
                        <option value="service_type" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'service_type') ? 'selected' : ''; ?>>Service Type</option>
                        <option value="contact_method" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'contact_method') ? 'selected' : ''; ?>>Contact Method</option>
                        <option value="appointment_option" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'appointment_option') ? 'selected' : ''; ?>>Appointment Option</option>
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
                    <th>Service Type</th>
                    <th>Contact Method</th>
                    <th>Appointment Option</th>
                    <th>Actions</th>
                </tr>
                <?php
                // Handle sorting and searching
                $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
                $order = isset($_GET['order']) ? $_GET['order'] : 'desc';
                $valid_sort_columns = ['id', 'name', 'service type','contact method','appointment option'];
                if (!in_array($sort_by, $valid_sort_columns)) {
                    $sort_by = 'id';
                }

                $search = isset($_GET['search']) ? $_GET['search'] : '';
                if (empty($_GET['search'])) {
                    $sql = "SELECT * FROM enquiry_information";
                } else {
                    $filtervalues = $_GET['search'];
                    $sql = "SELECT * FROM enquiry_information WHERE CONCAT(first_name,last_name,email,countryCode,phoneNumber,service_type,contact_method,appointment_option,appointment_date,appointment_time) LIKE '%$filtervalues%' ORDER BY $sort_by $order";
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
                        <td>{$row['service_type']}</td>
                        <td>{$row['contact_method']}</td>
                        <td>{$row['appointment_option']}</td>
                        <td>
                            <a id='view-button' href='view_enquiryservice.php?action=view&id={$row['id']}'>View</a>
                            <a id='delete-button' href='view_enquiryservice.php?action=confirm-delete&id={$row['id']}'>Delete</a>
                        </td>
                    </tr>
                    ";
                }
                ?>
            </table>

            <?php if (isset($_GET['action']) && $_GET['action'] == 'confirm-delete'): ?>
                <div class="user-edit pop-up">
                    <div id="pop-up-content-confirm-delete">
                        <a class="close-btn" href="view_enquiryservice.php">&times;</a>
                            <h1>Confirm Delete?</h1>
                            <a id="confirm-button" href="view_enquiryservice.php?action=delete&id=<?php echo htmlspecialchars($_GET['id']); ?>">Delete</a>
                            <a id="close-button" href="view_enquiryservice.php" class="button cancel">Cancel</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['action']) && $_GET['action'] == 'view'): ?>
            <div class="user-edit pop-up">
                <div class="pop-up-content view">
                    <a class="close-btn" href="view_enquiryservice.php">&times;</a>
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
                            <th>Country Code:</th>
                            <td><?php echo htmlspecialchars($country_code); ?></td>
                        </tr>
                        <tr>
                            <th>Phone Number:</th>
                            <td><?php echo htmlspecialchars($phone_number); ?></td>
                        </tr>
                        <tr>
                            <th>Service Type:</th>
                            <td><?php echo htmlspecialchars($service_type); ?></td>
                        </tr>
                        <tr>
                            <th>Contact Method:</th>
                            <td><?php echo htmlspecialchars($contact_method); ?></td>
                        </tr>
                        <tr>
                            <th>Appointment Option:</th>
                            <td><?php echo htmlspecialchars($appointment_option); ?></td>
                        </tr>
                        <tr>
                            <th>Appointment Date:</th>
                            <td><?php echo htmlspecialchars($appointment_date); ?></td>
                        </tr>
                        <tr>
                            <th>Appointment Time:</th>
                            <td><?php echo htmlspecialchars($appointment_time); ?></td>
                        </tr>
                    </table>
                    <a id="close-button" href="view_enquiryservice.php">Close</a>
                </div>
            </div>
            <?php endif; ?>

        </section>
    </main>
</section>
</body>
</html>
