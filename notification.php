<?php
session_start();

// Set a notification message in session (this can be done based on any condition)
if (!isset($_SESSION['notification'])) {
    $_SESSION['notification'] = "This is a sample notification!";
}

// Function to display notification
function displayNotification() {
    if (isset($_SESSION['notification'])) {
        echo "<script type='text/javascript'>
                window.onload = function() {
                    showNotification('" . $_SESSION['notification'] . "');
                };
              </script>";
        // Clear the notification after displaying it
        unset($_SESSION['notification']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Example</title>
    <style>
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
        function showNotification(message) {
            var notification = document.getElementById('notification');
            notification.innerText = message;
            notification.style.display = 'block';
            
            // Hide the notification after 3 seconds
            setTimeout(function() {
                notification.style.display = 'none';
            }, 3000);
        }
    </script>
</head>
<body>

<div id="notification"></div>

<?php displayNotification(); // Call the function to display notification ?>

</body>
</html>
