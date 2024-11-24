<?php
// confirm.php
require_once "config.php";

if (isset($_GET['student_id'])) {
    $student_id = urldecode($_GET['student_id']);

    // Update the student's status in the database to "Reached Home"
    $sql = "UPDATE leave_requests SET status = 'Reached Home' WHERE student_register_number = ?";
    if ($stmt = $mysql_db->prepare($sql)) {
        $stmt->bind_param("s", $student_id);
        if ($stmt->execute()) {
            echo "Thank you for confirming that your child has reached home.";
        } else {
            echo "Error updating status: " . $stmt->error;
        }
        $stmt->close();
    }
    $mysql_db->close();
} else {
    echo "Invalid confirmation link.";
}
?>
