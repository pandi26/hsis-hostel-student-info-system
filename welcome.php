<?php
    // Initialize session
    session_start();
   // Check for session timeout
    
   if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $_SESSION['expire_time'])) {
    // Last request was more than the allowed time
    session_unset(); // Unset session variables
    session_destroy(); // Destroy the session
    header("location: login.php?session_expired=true"); // Redirect to login with session expired flag
    exit;
}
$_SESSION['last_activity'] = time(); // Update last activity time


    // Check if the user is logged in, if not then redirect to login page
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('location: login.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-qdQEsAI45WFCO5QwXBelBe1rR9Nwiss4rGEqiszC+9olH1ScrLrMQr1KmDR964uZ" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/welcome.css">
    <script src="js/welcome.js" defer></script>
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

    <main>
        <section class="container">
            <div class="dashboard-card">
                <h2 class="display-4">Welcome, <?php echo htmlspecialchars($_SESSION['student_register_number']); ?>!</h2>
                <p class="text-center">You have successfully logged in to your dashboard.</p>
            </div>
        </section>
    </main>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poTj4mrslJCptbX6sTTKviWv+9Ijk8mIFVYhAduZnkwVj0E2E00" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldWr9YYJXCMo3p2QrE5z5eNq1LfOWf66O65MVi" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnju3xCjENtN5yY6kDR964uZ" crossorigin="anonymous"></script>
</body>
</html>
