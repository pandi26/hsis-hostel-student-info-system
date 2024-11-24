<?php
// Start session if not started
session_start();

// Redirect if not logged in
if (!isset($_SESSION['student_register_number'])) {
    header('Location: login.php'); // Redirect to the login page
    exit;
}

// Database connection
require_once "config/config.php";

// Get the student register number from the session
$student_register_number = $_SESSION['student_register_number'];

// SQL query to fetch the student details
$sql = "SELECT * FROM users WHERE student_register_number = ?";

// Prepare the statement
if ($stmt = $mysql_db->prepare($sql)) {
    // Bind the parameter (s for string)
    $stmt->bind_param("s", $student_register_number);
    
    // Execute the query
    if ($stmt->execute()) {
        // Get the result
        $result = $stmt->get_result();
        
        // Check if a row was found
        if ($result->num_rows > 0) {
            // Fetch the student details
            $student_details = $result->fetch_assoc();
        } else {
            echo "No student found with this register number.";
        }
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Error preparing statement: " . $mysql_db->error;
}

// Initialize notification message variable
$notification = "";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $leave_type = $_POST['leave_type'];
    $from_date = $_POST['from_date'];
    $from_time = $_POST['from_time'];
    $to_date = $_POST['to_date'];
    $to_time = $_POST['to_time'];
    $leave_reason = $_POST['leave_reason'];
    $selected_hour = isset($_POST['selected_hour']) ? $_POST['selected_hour'] : null;
    $session = isset($_POST['session']) ? $_POST['session'] : null;

    // Initialize variables from the fetched student details
    $student_room_number = $student_details['room_number']; // Assuming you want to get this from the student details
    $student_mobile = $student_details['student_mobile'];
    $parent_mobile = $student_details['parent_mobile'];

    // SQL to insert data into leave_requests
    $sql = "INSERT INTO leave_requests (student_register_number, room_number, student_mobile, parent_mobile, leave_type, from_date, from_time, to_date, to_time, leave_reason, selected_hour, session) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the SQL statement
    if ($stmt = $mysql_db->prepare($sql)) {
        $stmt->bind_param("ssssssssssss", 
            $student_register_number, 
            $student_room_number, 
            $student_mobile, 
            $parent_mobile, 
            $leave_type, 
            $from_date, 
            $from_time, 
            $to_date, 
            $to_time, 
            $leave_reason, 
            $selected_hour, 
            $session
        );

        // Execute the query
        if ($stmt->execute()) {
            // Set notification message for successful submission
            $notification = "Leave request submitted successfully!";
             header('Location: process_leave.php');
        } else {
            // Set notification message for error
            $notification = "Error: Unable to submit leave request. " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $mysql_db->error;
    }

    // Close the database connection
    $mysql_db->close();
}
?>
<html>
    <head>
     
 <script>
     function showNotification(message) {
            var notification = document.getElementById('notification');
            notification.innerText = message;
            notification.style.display = 'block';
            
            // Hide the notification after 3 seconds
            setTimeout(function() {
                notification.style.display = 'none';
            }, 3000);
        }

        window.onload = function() {
            setCurrentDate();
            <?php if ($notification): ?>
                showNotification('<?php echo $notification; ?>');
            <?php endif; ?>
        };
 </script>
</head>
<body>

    <div id="notification"></div>
    </body>
</html>
