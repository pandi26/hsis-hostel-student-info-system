<?php
// Start session if not started
session_start();

// Redirect if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: warden_login.php'); // Redirect to the login page
    exit;
}

// Database connection
require_once "config/config.php";

// Include Twilio SDK
require __DIR__ . '/../vendor/autoload.php'; // Adjust path to your autoload.php
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

// Twilio credentials
$account_sid = 'AC5d6c7f5736693b8a83feefb0495c64c9';
$auth_token = 'eca2003b0beed5fd259340938a310269';
$twilio_whatsapp_number = 'whatsapp:+14155238886';  // Your Twilio WhatsApp number

// Initialize notification message variable
$notification = "";

// Check if form is submitted for approval/rejection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    // Validate and sanitize inputs
    $leave_id = isset($_POST['leave_id']) ? intval($_POST['leave_id']) : 0;
    $action = in_array($_POST['action'], ['approve', 'reject']) ? $_POST['action'] : '';

    if ($leave_id && $action) {
        // Update leave request status
        $sql = "UPDATE leave_requests SET status = ? WHERE id = ?";
        if ($stmt = $mysql_db->prepare($sql)) {
            $stmt->bind_param("si", $action, $leave_id);
            if ($stmt->execute()) {
                $notification = "Leave request has been " . ($action == 'approve' ? "approved" : "rejected") . ".";

                // If approved, send WhatsApp message to parent and student
                if ($action == 'approve') {
                    // Fetch parent and student mobile numbers from the leave request
                    $sql_parent = "SELECT parent_mobile, student_mobile, student_register_number FROM leave_requests WHERE id = ?";
                    if ($stmt_parent = $mysql_db->prepare($sql_parent)) {
                        $stmt_parent->bind_param("i", $leave_id);
                        $stmt_parent->execute();
                        $stmt_parent->bind_result($parent_mobile, $student_mobile, $student_register_number);
                        if ($stmt_parent->fetch()) {
                            // Format the parent and student mobile numbers for WhatsApp
                            $parent_mobile = "whatsapp:+91" . ltrim($parent_mobile, '0');
                            $student_mobile = "whatsapp:+91" . ltrim($student_mobile, '0');  // Assuming +91 is the country code

                            // Generate unique confirmation links
                            $safe_reached_link = "https://yourwebsite.com/confirm_reached?student_id=" . urlencode($student_register_number);
                            $not_reached_link = "https://yourwebsite.com/not_reached?student_id=" . urlencode($student_register_number);

                            // Send WhatsApp message with two buttons to parent
                            $client = new Client($account_sid, $auth_token);
                            try {
                                $messageBody = 'Dear Parent, your child has requested leave from the hostel. The leave request has been approved. 
                                Please confirm whether your child has reached home by clicking one of the links below:' . "\n\n" . 
                                               "✅ [Yes, Safe Reached]()\n" . 
                                               "❌ [Not Reached]()";

                                $client->messages->create(
                                    $parent_mobile,  // Parent's WhatsApp number
                                    [
                                        'from' => $twilio_whatsapp_number,
                                        'body' => $messageBody,
                                    ]
                                );
                                $notification .= " Notification sent to parent at $parent_mobile.";

                                // Send notification to student
                                $client->messages->create(
                                    $student_mobile,  // Student's WhatsApp number
                                    [
                                        'from' => $twilio_whatsapp_number,
                                        'body' => "Dear Student, your leave request has been approved. Please make sure to inform your parents.",
                                    ]
                                );
                                $notification .= "         Notification sent to student at $student_mobile.";
                            } catch (TwilioException $e) {
                                $notification .= " Failed to send notification: " . $e->getMessage();
                            }
                        }
                        $stmt_parent->close();
                    }
                }
            } else {
                $notification = "Error updating leave request: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $notification = "Error preparing statement: " . $mysql_db->error;
        }
    } else {
        $notification = "Invalid action or leave ID.";
    }
}

// SQL query to fetch all leave requests
$sql = "SELECT lr.id, lr.room_number, lr.parent_mobile, lr.leave_type, lr.from_date, lr.from_time, lr.to_date, lr.to_time, lr.leave_reason, lr.selected_hour, lr.session, lr.status, u.student_register_number, u.student_name 
        FROM leave_requests lr 
        JOIN users u ON lr.student_register_number = u.student_register_number";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Warden - Leave Requests</title>
    <link rel="stylesheet" href="css/warden-leave-req.css">
    <script src="js/welcome.js" defer></script>
</head>
<body>
<header>
    <h2>HSIS</h2>
    <nav class="navbar">
        <span class="hamburger-btn material-symbols-rounded">menu</span>
        <ul class="links">
            <span class="close-btn material-symbols-rounded">close</span>
            <li><a href="#">Home</a></li>
            <li><a href="password_reset.php">Update details</a></li>
            <li><a href="search.php">Search</a></li>
            <li><a href="warden_leave_req.php">Leave Applications</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>
<h2>Leave Requests</h2>

<?php if ($notification): ?>

    <?php if ($notification): ?>
    <div class="notification">
        <span class="icon">✅</span> <!-- Success icon -->
        <span class="message"><?php echo htmlspecialchars($notification); ?></span>
        <button class="close" onclick="this.parentElement.style.display='none';">❌</button> <!-- Close button -->
    </div>
<?php endif; ?>



<!-- 

    <div class="notification">
        <?php echo htmlspecialchars($notification); ?>
    </div>
<?php endif; ?> -->

<?php
// Prepare the statement
if ($stmt = $mysql_db->prepare($sql)) {
    // Execute the statement
    $stmt->execute();
    // Bind result variables
    $stmt->bind_result($id, $room_number, $parent_mobile, $leave_type, $from_date, $from_time, $to_date, $to_time, $leave_reason, $selected_hour, $session, $status, $student_register_number, $student_name);

    // Fetch values
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
            <th>Action</th>
          </tr>";

    while ($stmt->fetch()) {
        echo "<tr>";
        echo "<td>$id</td>";
        echo "<td>$room_number</td>";
        echo "<td>$parent_mobile</td>";
        echo "<td>$leave_type</td>";
        echo "<td>$from_date</td>";
        echo "<td>$from_time</td>";
        echo "<td>$to_date</td>";
        echo "<td>$to_time</td>";
        echo "<td>$leave_reason</td>";
        echo "<td>$selected_hour</td>";
        echo "<td>$session</td>";
        echo "<td>$status</td>";
        echo "<td>
                <form method='post'>
                    <input type='hidden' name='leave_id' value='$id'>
                    <button type='submit' name='action' value='approve' class='approve'>Approve</button>
                    <button type='submit' name='action' value='reject' class='reject'>Reject</button>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
    $stmt->close();
} else {
    echo "Error preparing statement: " . $mysql_db->error;
}
?>

</body>
</html>
