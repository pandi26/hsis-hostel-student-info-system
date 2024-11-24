<?php
// Start session if not started
session_start();

// Redirect if not logged in
if (!isset($_SESSION['student_register_number'])) {
    header('Location: login.php');
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
    // Bind the parameter
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
            $error_message = "No student found with this register number.";
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/data-fetch.css">
    <script src="js/welcome.js" defer></script>
    
    <!-- Custom Styles -->
    <style>
        
    </style>
</head>
<body>
<header><h2>HSIS</h2>
        <nav class="navbar">
            
            <span class="hamburger-btn material-symbols-rounded">menu</span>
            <ul class="links">
                <span class="close-btn material-symbols-rounded">close</span>
                <li><a href="welcome.php">Home</a></li>
                <li><a href="edit_data.php">Update details</a></li>
                <li><a href="hostel-leave-apply.php">Apply Leave</a></li>
                <li><a href="request.php">request</a></li>
                <li><a href="data-fetch.php">details</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="blur-bg-overlay"></div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                Student Register Number: <?php echo htmlspecialchars($_SESSION['student_register_number']); ?>
            </div>

            <div class="card-body">
                <?php if (isset($student_details)): ?>
                    <p><strong>Student Name:</strong> <?php echo htmlspecialchars($student_details['student_name']); ?></p>
                    <p><strong>Room Number:</strong> <?php echo htmlspecialchars($student_details['room_number']); ?></p>
                    <p><strong>Student Mobile:</strong> <?php echo htmlspecialchars($student_details['student_mobile']); ?></p>
                    <p><strong>Email ID:</strong> <?php echo htmlspecialchars($student_details['email']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($student_details['address']); ?></p>
                    <p><strong>Parent Name:</strong> <?php echo htmlspecialchars($student_details['parent_name']); ?></p>
                    <p><strong>Parent Mobile:</strong> <?php echo htmlspecialchars($student_details['parent_mobile']); ?></p>
                    <p><strong>Academic Year:</strong> <?php echo htmlspecialchars($student_details['academic_year']); ?></p>
                    <p><strong>Department:</strong> <?php echo htmlspecialchars($student_details['engineering_dept']); ?></p>
                <?php elseif (isset($error_message)): ?>
                    <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poTj4mrslJCptbX6sTTKviWv+9Ijk8mIFVYhAduZnkwVj0E2E00" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldWr9YYJXCMo3p2QrE5z5eNq1LfOWf66O65MVi" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnju3xCjENtN5yY6kDR964uZ" crossorigin="anonymous"></script>

</body>
</html>
