<?php
// Start the session
session_start();

// Check if the student register number is set in the session (e.g., from login)
if (!isset($_SESSION['student_register_number'])) {
    header("Location: login.php");
    exit;
}

// Include database configuration file
require_once "config/config.php";

// Initialize notification message variable
$notification = "";

// Fetch only the leave requests of the logged-in student
$sql = "SELECT lr.id, lr.room_number, lr.parent_mobile, lr.leave_type, lr.from_date, lr.from_time, lr.to_date, lr.to_time, lr.leave_reason, lr.selected_hour, lr.session, lr.status, u.student_register_number, u.student_name 
        FROM leave_requests lr 
        JOIN users u ON lr.student_register_number = u.student_register_number 
        WHERE u.student_register_number = ?";

// Prepare the statement
if ($stmt = $mysql_db->prepare($sql)) {
    // Bind the session register number to the SQL query
    $stmt->bind_param("s", $_SESSION['student_register_number']);
    
    // Execute the statement
    $stmt->execute();
    
    // Bind result variables
    $stmt->bind_result($id, $room_number, $parent_mobile, $leave_type, $from_date, $from_time, $to_date, $to_time, $leave_reason, $selected_hour, $session, $status, $student_register_number, $student_name);

    // Display results in a table
    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<title>Leave Requests</title>";
    echo "<link rel='stylesheet' href='css/request.css'>";
    echo "<script src='js/welcome.js' defer></script>";
    echo "</head>";
    echo "<body>";
    
    echo "<h2>Leave Requests</h2>";

    if ($notification) {
        echo "<div class='notification'>" . htmlspecialchars($notification) . "</div>";
    }

    echo "<table>";
    echo "<tr>
            <th>ID</th>
            <th>Room Number</th>
            <th>Parent Mobile</th>
            <th>Leave Type</th>
            <th>From Date</th>
            <th>From Time</th>
            <th>To Date</th>
            <th>To Time</th>
            <th>Leave Reason</th>
            <th>Selected Hour</th>
            <th>Session</th>
            <th>Status</th>
          </tr>";

    // Fetch the rows and display them
    while ($stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($id) . "</td>";
        echo "<td>" . htmlspecialchars($room_number) . "</td>";
        echo "<td>" . htmlspecialchars($parent_mobile) . "</td>";
        echo "<td>" . htmlspecialchars($leave_type) . "</td>";
        echo "<td>" . htmlspecialchars($from_date) . "</td>";
        echo "<td>" . htmlspecialchars($from_time) . "</td>";
        echo "<td>" . htmlspecialchars($to_date) . "</td>";
        echo "<td>" . htmlspecialchars($to_time) . "</td>";
        echo "<td>" . htmlspecialchars($leave_reason) . "</td>";
        echo "<td>" . htmlspecialchars($selected_hour) . "</td>";
        echo "<td>" . htmlspecialchars($session) . "</td>";
        echo "<td>" . htmlspecialchars($status) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Close the statement
    $stmt->close();
    
    echo "</body>";
    echo "</html>";
} else {
    echo "Error preparing statement: " . $mysql_db->error;
}
?>
