<?php
// Initialize sessions
session_start();

// Set session expiration time (e.g., 30 minutes)
$_SESSION['last_activity'] = time(); // Timestamp of the last activity
$_SESSION['expire_time'] = 3 * 60; // Session timeout period (3 minutes)

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "config/config.php";

// Define variables and initialize with empty values
$student_register_number = $password = '';
$student_register_number_err = $password_err = '';

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if username is empty
    if (empty(trim($_POST['student_register_number']))) {
        $student_register_number_err = 'Please enter username.';
    } else {
        $student_register_number = trim($_POST['student_register_number']);
    }

    // Check if password is empty
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate credentials
    if (empty($student_register_number_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = 'SELECT id, student_register_number, password FROM users WHERE student_register_number = ?';

        if ($stmt = $mysql_db->prepare($sql)) {
            // Set parameter
            $param_student_register_number = $student_register_number;

            // Bind param to statement
            $stmt->bind_param('s', $param_student_register_number);

            // Attempt to execute
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if username exists. Verify user exists then verify
                if ($stmt->num_rows == 1) {
                    // Bind result into variables
                    $stmt->bind_result($id, $student_register_number, $hashed_password);

                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Start a new session
                            session_start();

                            // Store data in sessions
                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['student_register_number'] = $student_register_number;

                            // Redirect to user to page
                            header('location: welcome.php');
                        } else {
                            // Display an error for password mismatch
                            $password_err = 'Invalid password';
                        }
                    }
                } else {
                    $student_register_number_err = "Username does not exist.";
                }
            } else {
                echo "Oops! Something went wrong please try again.";
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
    <title>Sign in</title>
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/cosmo/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .wrapper {
            max-width: 400px;
            margin: 5% auto;
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .wrapper h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .wrapper form .form-group span {
            color: red;
        }
        .form-control {
            height: 45px;
            font-size: 1rem;
            border-radius: 8px;
        }
        .btn-block {
            background-color: #007bff;
            border: none;
            height: 45px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 8px;
            margin-top: 20px;
        }
        .btn-block:hover {
            background-color: #0056b3;
        }
        .text-center p {
            margin-top: 15px;
            font-size: 0.9rem;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <main>
        <section class="container">
            <div class="wrapper">
                <h2>Login</h2>
                <p class="text-center">Please fill this form to login.</p>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <div class="form-group <?php echo (!empty($student_register_number_err)) ? 'has_error' : ''; ?>">
                        <label for="student_register_number">Student Register Number</label>
                        <input type="text" name="student_register_number" id="student_register_number" class="form-control" value="<?php echo $student_register_number; ?>">
                        <span class="help-block"><?php echo $student_register_number_err; ?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($password_err)) ? 'has_error' : ''; ?>">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" value="<?php echo $password; ?>">
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-block btn-primary" value="Login">
                    </div>
                    <p class="text-center">Don't have an account? <a href="register.php">Sign up here</a>.</p>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
