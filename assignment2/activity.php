<?php
session_start();
include 'connection.php';

// Handle feedback form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activity_id = $_POST['activity_id'];
    $userid = $_SESSION['login_user'] ?? 'Anonymous';
    $feedback = $_POST['feedback'];

    $stmt = $conn->prepare("INSERT INTO activity_feedbacks (activity_id, userid, feedback) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $activity_id, $userid, $feedback);

    if ($stmt->execute()) {
        header("Location: activity.php?success=Feedback submitted");
    } else {
        header("Location: activity.php?error=Failed to submit feedback");
    }
    $stmt->close();
    $conn->close();
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Malaysian Sign Language</title>
    <meta charset="utf-8">
    <meta name="description" content="activities">
    <meta name="keywords" content="activities">
    <meta name="author" content="Daniel Sie, Zwe Htet Zaw, Paing Chan, Sherlyn Kok, Michael Wong">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/love-you-gesture-svgrepo-com.svg" type="images/svg">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>

    </style>
</head>
<body>
    <?php include 'header.php'?>

    <article id="activities_section">
        <?php
        // Fetch all activities
        $sql = "SELECT * FROM activities ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                ?>
                <section class="activities-hd-card">
                    <div class="text">
                        <h1><?= htmlspecialchars($row['title']); ?></h1>
                        <p><?= htmlspecialchars($row['date']); ?></p>
                    </div>

                    <div class="content">
                        <div class="content_text">
                            <p><img src="images/<?= $row['photo']; ?>" alt="activity_image" class="activity_img" /></p>
                            <?= $row['description']; ?>
                        </div>

                        <form class="feedback-form" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="activity_id" value="<?= $row['id']; ?>">
                            <input type="hidden" name="userid" value="<?= $_SESSION['login_user'] ?? 'Anonymous'; ?>">
                            <textarea rows="2" cols="42" name="feedback" placeholder="Leave your feedback here"></textarea>
                            <button type="submit">Submit Feedback</button>
                        </form>
                        
                        <div class="feedback-view">
                            <?php
                            $activityId = $row['id'];
                            $stmt = $conn->prepare("SELECT id, userid, feedback, created_at, likes, dislikes FROM activity_feedbacks WHERE activity_id = ?");
                            $stmt->bind_param("i", $activityId);
                            $stmt->execute();
                            $stmt->store_result();
                            $stmt->bind_result($feedback_id, $userid, $feedback, $created_at, $likes, $dislikes);

                            echo "<h3>Feedback</h3>";
                            if ($stmt->num_rows > 0) {
                                while ($stmt->fetch()) {
                                    $timeAgoString = getTimeAgoString(time() - strtotime($created_at));
                                    $user = !empty($userid) ? $userid : 'Anonymous';
                                    echo "<div class='feedback-item'>";
                                    echo "<p><strong>" . htmlspecialchars($user) . ":</strong> " . htmlspecialchars($feedback) . " - " . $timeAgoString;
                                    
                                    // Check if user has liked/disliked this feedback
                                    $currentUser = $_SESSION['login_user'] ?? 'Anonymous';
                                    $stmt2 = $conn->prepare("SELECT action FROM feedback_likes_dislikes WHERE feedback_id = ? AND user_id = ?");
                                    $stmt2->bind_param("is", $feedback_id, $currentUser);
                                    $stmt2->execute();
                                    $stmt2->store_result();
                                    $user_action = null;
                                    if ($stmt2->num_rows > 0) {
                                        $stmt2->bind_result($user_action);
                                        $stmt2->fetch();
                                    }
                                    $stmt2->close();
                                    
                                    echo "<span class='feedback-buttons'>";

                                    // like form
                                    echo "<form method='post' action='like_dislike.php' style='display:inline;'>";
                                    echo "<input type='hidden' name='feedback_id' value='$feedback_id'>";
                                    echo "<input type='hidden' name='action' value='toggle_like'>";
                                    echo "<button type='submit' class='like-btn'>";
                                    echo ($user_action == 'like') ? "<i class='fa-solid fa-thumbs-up'></i></button><span class='like-count'>$likes</span>" : "<i class='fa-regular fa-thumbs-up'></i></button>";
                                    echo "</form>";

                                    //  dislike form
                                    echo "<form method='post' action='like_dislike.php' style='display:inline;'>";
                                    echo "<input type='hidden' name='feedback_id' value='$feedback_id'>";
                                    echo "<input type='hidden' name='action' value='toggle_dislike'>";
                                    echo "<button type='submit' class='dislike-btn'>";
                                    echo ($user_action == 'dislike') ? "<i class='fa-solid fa-thumbs-down'></i></button><span class='dislike-count'>$dislikes</span>" : "<i class='fa-regular fa-thumbs-down'></i></button>";
                                    echo "</form>";
                                    echo "</span></p></div>";                                    
                                }
                            } else {
                                echo "<div class='feedback-section'>No feedback yet.</div>";
                            }

                            $stmt->close();
                            ?>
                        </div>
                    </div>
                </section>
                <?php
            }
            mysqli_free_result($result);
        } else {
            echo "<p>No activities found.</p>";
        }

        mysqli_close($conn);
        ?>
    </article>
    <?php include 'back-to-top.php';?>
    <?php include 'footer.php';?>
</body> 
</html>

<?php
function getTimeAgoString($timeAgo) {
if ($timeAgo < 60) {
    return "Less than a minute ago";
} elseif ($timeAgo < 3600) {
    return ceil($timeAgo / 60) . " minutes ago";
} elseif ($timeAgo < 86400) {
    return ceil($timeAgo / 3600) . " hours ago";
} else {
    return ceil($timeAgo / 86400) . " days ago";
}
}
?>
