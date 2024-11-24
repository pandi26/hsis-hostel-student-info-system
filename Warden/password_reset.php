<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: warden_login.php');
    exit;
}

// Include config file
require_once 'config/config.php';

// Define variables and initialize with empty values
$new_password = $confirm_password = '';
$new_password_err = $confirm_password_err = '';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate new password
    if (empty(trim($_POST['new_password']))) {
        $new_password_err = 'Please enter the new password.';
    } elseif (strlen(trim($_POST['new_password'])) < 6) {
        $new_password_err = 'Password must have at least 6 characters.';
    } else {
        $new_password = trim($_POST['new_password']);
    }

    // Validate confirm password
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = 'Please confirm the password.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = 'Password did not match.';
        }
    }

    // Check input errors before updating the database
    if (empty($new_password_err) && empty($confirm_password_err)) {
        // Prepare an update statement
        $sql = 'UPDATE users SET password = ? WHERE id = ?';

        if ($stmt = $mysql_db->prepare($sql)) {
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION['id'];

            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('si', $param_password, $param_id);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Password updated successfully. Destroy the session and redirect to login page
                session_destroy();
                header('location: login.php');
                exit();
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }

            // Close statement
            $stmt->close();
        }

        // Close connection
        $mysql_db->close();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-qdQEsAI45WFCO5QwXBelBe1rR9Nwiss4rGEqiszC+9olH1ScrLrMQr1KmDR964uZ" crossorigin="anonymous">
    <link rel="stylesheet">
    <script src="js/welcome.js" defer></script>
    <style type="text/css">
        /* Custom Styling */
        
body {
    background-color: #f8f9fa;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

header {
    background-color: #007bff;
    padding: 15px 20px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header h2 {
    font-size: 1.5rem;
    margin: 0;
    font-family: 'Garamond', 'Times New Roman', serif;
    font-style: oblique;
    font-weight: bold;
}

/* Responsive Navbar Styles */




/* css */

.navbar {
    display: flex;
    padding: 22px 0;
    max-width: 1200px;
    margin: 10 auto;
    justify-content: space-between;
    
}

.navbar .hamburger-btn {
    display: none;
    color: #fff;
    cursor: pointer;
    font-size: 1.5rem;
}


.navbar .logo {
    gap: 10px;
    display: flex;
    align-items: center;
    text-decoration: none;
}

.navbar .logo img {
    width: 40px;
    border-radius: 50%;
}

.navbar .logo h2 {
    color: #fff;
    font-weight: 600;
    font-size: 1.7rem;
}

.navbar .links {
    display: flex;
    gap: 35px;
    list-style: none;
    align-items: center;
    
}

.navbar .close-btn {
    position: absolute;
    right: 20px;
    top: 20px;
    display: none;
    color: #000;
    cursor: pointer;
}

.navbar .links a {
    color: #fff;
    font-size: 1.1rem;
    font-weight: 500;
    text-decoration: none;
    transition: 0.1s ease;
}

.navbar .links a:hover {
    color: #19e8ff;
}





.form-popup {
    position: fixed;
    top: 50%;
    left: 50%;
    z-index: 10;
    width: 100%;
    opacity: 0;
    pointer-events: none;
    max-width: 720px;
    background: #fff;
    border: 2px solid #fff;
    transform: translate(-50%, -70%);
}

.show-popup .form-popup {
    opacity: 1;
    pointer-events: auto;
    transform: translate(-50%, -50%);
    transition: transform 0.3s ease, opacity 0.1s;
}

.form-popup .close-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    color: #878484;
    cursor: pointer;
}

.blur-bg-overlay {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 10;
    height: 100%;
    width: 100%;
    opacity: 0;
    pointer-events: none;
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    transition: 0.1s ease;
}

.show-popup .blur-bg-overlay {
    opacity: 1;
    pointer-events: auto;
}

.form-popup  {
    display: flex;
}




.form-popup {
    display: none;
}

.form-popup {
    display: flex;
}



@media (max-width: 950px) {
    .navbar :is(.hamburger-btn, .close-btn) {
        display: flex;
        padding-left: 340px;
        font-size: medium;
    }

    .navbar {
        padding: 15px 0;
    }

    

    .navbar .links {
        position: fixed;
        top: 0;
        z-index: 10;
        left: -100%;
        display: block;
        height: 100vh;
        width: 95%;
        padding-top: 60px;
        text-align: center;
        background: #fff;
        transition: 0.2s ease;
    }

    .navbar .links.show-menu {
        left: 10px;
    }

    .navbar .links a {
        display: inline-flex;
        margin: 20px 0;
        font-size: 1.2rem;
        color: #000;
    }

    .navbar .links a:hover {
        color: #f38209;
    }

    
}

@media (max-width: 760px) {
    .form-popup {
        width: 95%;
    }

    
}
/* Dashboard Card */
.dashboard-card {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.dashboard-card h2 {
    margin-bottom: 20px;
    font-size: 2rem;
}

@media (max-width: 576px) {
    .dashboard-card {
        padding: 15px;
    }

    .dashboard-card h2 {
        font-size: 1.5rem;
    }
}
        .wrapper { 
            max-width: 450px; 
            margin: 60px auto; 
            padding: 30px; 
            background-color: #f8f9fa;
            border-radius: 8px; 
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }
        .form-group span {
            color: red;
        }
        .btn-primary, .btn-link {
            margin-top: 10px;
        }
        @media (max-width: 576px) {
            .wrapper {
                padding: 20px;
            }
        }
    </style>
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
    <main class="container">
        <section class="wrapper">
            <h2>Reset Password</h2>
            <p class="text-center">Please fill out this form to reset your password.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                    <span class="help-block"><?php echo $new_password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block" value="Submit">
                    <a class="btn btn-link btn-block" href="welcome.php">Cancel</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
