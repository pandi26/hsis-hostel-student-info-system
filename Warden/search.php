<?php
// Start session if not started
session_start();

// Database connection
require_once "config/config.php";

// Initialize variables
$student_details = [];
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the room number from the form
    $room_number = $_POST['room_number'];

    // SQL query to fetch the student details
    $sql = "SELECT * FROM users WHERE room_number = ?";

    // Prepare the statement
    if ($stmt = $mysql_db->prepare($sql)) {
        // Bind the parameter (s for string)
        $stmt->bind_param("s", $room_number);
        
        // Execute the query
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();
            
            // Check if any students were found
            if ($result->num_rows > 0) {
                // Fetch all student details
                while ($row = $result->fetch_assoc()) {
                    $student_details[] = $row;
                }
            } else {
                $error_message = "No students found in this room number.";
            }
        } else {
            $error_message = "Error executing query: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $error_message = "Error preparing statement: " . $mysql_db->error;
    }

    // Close the database connection
    $mysql_db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Students</title>
    <link rel="stylesheet" href="css/search.css">
    <script src="js/welcome.js" defer></script>
</head>
<body>
<header><h2>HSIS</h2>
        <nav class="navbar">
            
            <span class="hamburger-btn material-symbols-rounded">menu</span>
            <ul class="links">
                <span class="close-btn material-symbols-rounded">close</span>
                <li><a href="#">Home</a></li>
                <li><a href="password_reset.php">Password Reset</a></li>
                <li><a href="search.php">search</a></li>
                <li><a href="warden_leave_req.php">Leave Applicarions</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>


    <div class="container">
        <h1>Search Students by Room Number</h1>
        <form method="POST" action="">
            <input type="text" name="room_number" placeholder="Enter Room Number" required>
            <button type="submit">Search</button>
        </form>

        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php elseif (!empty($student_details)): ?>
            <div class="student-details">
                <h2>Student Details</h2>
                <?php foreach ($student_details as $student): ?>
                    <div class="student-card">
                        <p><strong>Student Name:</strong> <?php echo htmlspecialchars($student['student_name']); ?></p>
                        <p><strong>Room Number:</strong> <?php echo htmlspecialchars($student['room_number']); ?></p>
                        <p><strong>S Mobile:</strong> <?php echo htmlspecialchars($student['student_mobile']); ?></p>
                        <p><strong>Email ID:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($student['address']); ?></p>
                        <p><strong>Parent Name:</strong> <?php echo htmlspecialchars($student['parent_name']); ?></p>
                        <p><strong>Parent Mobile:</strong> <?php echo htmlspecialchars($student['parent_mobile']); ?></p>
                        <p><strong>Academic Year:</strong> <?php echo htmlspecialchars($student['academic_year']); ?></p>
                        <p><strong>Engineering department:</strong> <?php echo htmlspecialchars($student['engineering_dept']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
