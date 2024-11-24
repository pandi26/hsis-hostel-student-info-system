<?php 
// Initialize the session
session_start();

// Check if the user is logged in; if not, redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

// Include config file
require_once 'config/config.php';

// Define variables and initialize with empty values
$email = $student_mobile = $parent_name = $address = $room_number = $academic_year = $engineering_dept = '';
$email_err = $student_mobile_err = $parent_name_err = $address_err = $room_number_err = $academic_year_err = $engineering_dept_err = '';
$new_password = $confirm_password = '';
$new_password_err = $confirm_password_err = '';

// Processing form data when profile update form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    // Validate and sanitize email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter your email.';
    } else {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    }

    // Additional fields validation (example for student_mobile)
    $student_mobile = trim($_POST['student_mobile']);
    $parent_name = trim($_POST['parent_name']);
    $address = trim($_POST['address']);
    $room_number = trim($_POST['room_number']);
    $academic_year = trim($_POST['academic_year']);
    $engineering_dept = trim($_POST['engineering_dept']);

    // Check for input errors before updating the database
    if (empty($email_err)) {
        // Prepare an update statement
        $sql = 'UPDATE users SET email = ?, student_mobile = ?, parent_name = ?, address = ?, room_number = ?, academic_year = ?, engineering_dept = ? WHERE id = ?';

        if ($stmt = $mysql_db->prepare($sql)) {
            $stmt->bind_param('sssssssi', $email, $student_mobile, $parent_name, $address, $room_number, $academic_year, $engineering_dept, $_SESSION['id']);
            
            if ($stmt->execute()) {
                echo 'Profile updated successfully!';
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            $stmt->close();
        }
        $mysql_db->close();
    }
}

// Processing form data when password reset form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    if (empty(trim($_POST['new_password']))) {
        $new_password_err = 'Please enter the new password.';
    } elseif (strlen(trim($_POST['new_password'])) < 6) {
        $new_password_err = 'Password must have at least 6 characters.';
    } else {
        $new_password = trim($_POST['new_password']);
    }

    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = 'Please confirm the password.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = 'Password did not match.';
        }
    }

    if (empty($new_password_err) && empty($confirm_password_err)) {
        $sql = 'UPDATE users SET password = ? WHERE id = ?';

        if ($stmt = $mysql_db->prepare($sql)) {
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt->bind_param('si', $param_password, $_SESSION['id']);

            if ($stmt->execute()) {
                session_destroy();
                header('location: login.php');
                exit();
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            $stmt->close();
        }
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
        padding-left:20px;
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
                <li><a href="welcome.php">Home</a></li>
                <li><a href="edit_data.php">Update details</a></li>
                <li><a href="hostel-leave-apply.php">Apply Leave</a></li>
                <li><a href="request.php">request</a></li>
                <li><a href="data-fetch.php">details</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main class="container">
        <section class="wrapper">
            <h2>Update Profile</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="update_profile" value="1">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                </div>
                <div class="form-group">
                    <label>Student Mobile</label>
                    <input type="text" name="student_mobile" class="form-control" value="<?php echo $student_mobile; ?>">
                </div>
                <div class="form-group">
                    <label>Parent Name</label>
                    <input type="text" name="parent_name" class="form-control" value="<?php echo $parent_name; ?>">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
                </div>
                <div class="form-group">
                    <label>Room Number</label>
                    <input type="text" name="room_number" class="form-control" value="<?php echo $room_number; ?>">
                </div>
                <div class="form-group">
                    <label>Academic Year</label>
                    <input type="text" name="academic_year" class="form-control" value="<?php echo $academic_year; ?>">
                </div>
                <div class="form-group">
                    <label>Engineering Department</label>
                    <input type="text" name="engineering_dept" class="form-control" value="<?php echo $engineering_dept; ?>">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Update Profile">
                </div>
            </form>

            <h2>Reset Password</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="reset_password" value="1">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Reset Password">
                </div>
            </form>
        </section>
    </main>
</body>
</html>
