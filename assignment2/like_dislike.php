<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $feedback_id = $_POST['feedback_id'];
    $action = $_POST['action'];
    $user_id = isset($_SESSION['login_user']) ? $_SESSION['login_user'] : 'Anonymous';

    // Check if the user has already liked or disliked this feedback
    $stmt = $conn->prepare("SELECT action FROM feedback_likes_dislikes WHERE feedback_id = ? AND user_id = ?");
    $stmt->bind_param("is", $feedback_id, $user_id);
    $stmt->execute();
    $stmt->store_result();
    $user_action = null;
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_action);
        $stmt->fetch();
    }
    $stmt->close();

    if ($action == 'toggle_like') {
        if ($user_action == 'like') {
            // Remove like
            $sql = "UPDATE activity_feedbacks SET likes = likes - 1 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $feedback_id);
            $stmt->execute();
            $stmt->close();

            // Remove from feedback_likes_dislikes
            $sql = "DELETE FROM feedback_likes_dislikes WHERE feedback_id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $feedback_id, $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            // Add like
            $sql = "UPDATE activity_feedbacks SET likes = likes + 1 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $feedback_id);
            $stmt->execute();
            $stmt->close();

            if ($user_action == 'dislike') {
                // Remove dislike
                $sql = "UPDATE activity_feedbacks SET dislikes = dislikes - 1 WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $feedback_id);
                $stmt->execute();
                $stmt->close();
            }

            // Add to feedback_likes_dislikes
            $sql = "REPLACE INTO feedback_likes_dislikes (feedback_id, user_id, action) VALUES (?, ?, 'like')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $feedback_id, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    } elseif ($action == 'toggle_dislike') {
        if ($user_action == 'dislike') {
            // Remove dislike
            $sql = "UPDATE activity_feedbacks SET dislikes = dislikes - 1 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $feedback_id);
            $stmt->execute();
            $stmt->close();

            // Remove from feedback_likes_dislikes
            $sql = "DELETE FROM feedback_likes_dislikes WHERE feedback_id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $feedback_id, $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            // Add dislike
            $sql = "UPDATE activity_feedbacks SET dislikes = dislikes + 1 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $feedback_id);
            $stmt->execute();
            $stmt->close();

            if ($user_action == 'like') {
                // Remove like
                $sql = "UPDATE activity_feedbacks SET likes = likes - 1 WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $feedback_id);
                $stmt->execute();
                $stmt->close();
            }

            // Add to feedback_likes_dislikes
            $sql = "REPLACE INTO feedback_likes_dislikes (feedback_id, user_id, action) VALUES (?, ?, 'dislike')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $feedback_id, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    $conn->close();
    header("Location: activity.php?success=Feedback updated");
    exit();
}
?>
