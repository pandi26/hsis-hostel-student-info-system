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
          //  header('Location: welcome.php');
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Form - Modern Minimal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #e0f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            padding: 25px;
            max-width: 600px;
            width: 100%;
            color: #333;
            font-size: 16px;
        }
        h2 {
            text-align: center;
            color: #00796b;
        }
        label {
            color: #00695c;
            margin-bottom: 10px;
            display: block;
        }
        input, select, textarea {
            border: 1px solid #b2dfdb;
            border-radius: 10px;
            padding: 10px;
            width: 100%;
        }
        button {
            background-color: #00796b;
            border: none;
            color: #fff;
            padding: 15px;
            width: 100%;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #004d40;
        }
        #session_wrapper, #hour_wise_wrapper {
            display: none;
        }
        .checkbox-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .checkbox-group label {
            display: inline-block;
            margin-right: 10px;
        }
        .checkbox-group input[type="checkbox"] {
            margin-right: 5px;
        }
        .hour-box {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            background-color: #00796b;
            color: white;
            margin: 5px;
            cursor: pointer;
            border-radius: 50%;
        }
        .hour-box:hover {
            background-color: #004d40;
        }
        .selected-hour {
            background-color: #004d40;
        }

        /* Notification Styles */
        #notification {
            display: none; /* Hidden by default */
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px;
            background-color: #4CAF50; /* Green */
            color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
    </style>

    <script>
        function setCurrentDate() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('from_date').value = today;
            document.getElementById('to_date').value = today;
        }

        function toggleSessionField() {
            var hourWise = document.getElementById('hour_wise').checked;
            var halfDay = document.getElementById('half_day').checked;
            var sessionWrapper = document.getElementById('session_wrapper');
            var sessionField = document.getElementById('session');
            var hourWiseWrapper = document.getElementById('hour_wise_wrapper');

            if (halfDay) {
                sessionWrapper.style.display = 'block';
                sessionField.setAttribute('required', 'required');
            } else {
                sessionWrapper.style.display = 'none';
                sessionField.removeAttribute('required');
            }

            if (hourWise) {
                hourWiseWrapper.style.display = 'block';
            } else {
                hourWiseWrapper.style.display = 'none';
            }
        }

        function onlyOneCheckbox(checkbox) {
            var checkboxes = document.getElementsByClassName('exclusive-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] !== checkbox) {
                    checkboxes[i].checked = false;
                }
            }
            toggleSessionField();
        }

        function selectHourBox(box) {
            box.classList.toggle('selected-hour');

            var selectedHours = [];
            var boxes = document.getElementsByClassName('hour-box');
            for (var i = 0; i < boxes.length; i++) {
                if (boxes[i].classList.contains('selected-hour')) {
                    selectedHours.push(boxes[i].innerHTML);
                }
            }

            document.getElementById('selected_hour').value = selectedHours.join(',');
        }

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


    <div id="notification"></div> <!-- Notification Container -->
    <div class="card">
        <h2>Leave Request <?php echo htmlspecialchars($_SESSION['student_register_number']); ?></h2>
            
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="hidden" name="student_register_number" value="<?php echo $student_register_number; ?>">
            <input type="hidden" name="student_name" value="<?php echo $student_details['student_name']; ?>">

            <!-- Leave Type -->
            <label for="leave_type">* Leave Type</label>
            <select id="leave_type" name="leave_type" required>
                <option value="">-- Select leave type --</option>
                <option value="sick">Sick Leave</option>
                <option value="casual">Casual Leave</option>
                <option value="annual">Annual Leave</option>
            </select>

            <!-- Hour Wise and Half Day Options -->
            <div class="checkbox-group">
                <div>
                    <input type="checkbox" id="hour_wise" name="hour_wise" value="1" class="exclusive-checkbox" onclick="onlyOneCheckbox(this)">
                    <label for="hour_wise">Hour Wise?</label>
                </div>
                <div>
                    <input type="checkbox" id="half_day" name="half_day" value="1" class="exclusive-checkbox" onclick="onlyOneCheckbox(this)">
                    <label for="half_day">Is Half Day?</label>
                </div>
            </div>

            <!-- Hour Wise Wrapper -->
            <div id="hour_wise_wrapper">
                <label for="hours">Select Hours</label>
                <div id="hour_boxes">
                    <div class="hour-box" onclick="selectHourBox(this)">1</div>
                    <div class="hour-box" onclick="selectHourBox(this)">2</div>
                    <div class="hour-box" onclick="selectHourBox(this)">3</div>
                    <div class="hour-box" onclick="selectHourBox(this)">4</div>
                    <div class="hour-box" onclick="selectHourBox(this)">5</div>
                    <div class="hour-box" onclick="selectHourBox(this)">6</div>
                    <div class="hour-box" onclick="selectHourBox(this)">7</div>
                </div>
                <input type="hidden" id="selected_hour" name="selected_hour" value="">
            </div>

            <!-- Session -->
            <div id="session_wrapper">
                <label for="session">Session</label>
                <select id="session" name="session">
                    <option value="">-- Select session --</option>
                    <option value="morning">Morning</option>
                    <option value="afternoon">Afternoon</option>
                </select>
            </div>

            <!-- From Date -->
            <label for="from_date">* From Date</label>
            <input type="date" id="from_date" name="from_date" required>

            <!-- From Time -->
            <label for="from_time">* From Time</label>
            <input type="time" id="from_time" name="from_time" required>

            <!-- To Date -->
            <label for="to_date">* To Date</label>
            <input type="date" id="to_date" name="to_date" required>

            <!-- To Time -->
            <label for="to_time">* To Time</label>
            <input type="time" id="to_time" name="to_time" required>

            <!-- Leave Reason -->
            <label for="leave_reason">* Leave Reason</label>
            <textarea id="leave_reason" name="leave_reason" rows="4" placeholder="Enter leave reason" required></textarea>

            <!-- Submit Button -->
            <button type="submit">Submit Leave Request</button>
            <br></br>
            
        </form>
        <a href="welcome.php"><button style="background-color:blue">back</button></a>
    </div>

</body>
</html>
