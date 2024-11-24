<?php
// Include config file
require_once 'config/config.php';

// Define variables and initialize with empty values
$username = $password = $confirm_password = $warden_age = $warden_phone = $warden_aadhar = $warden_address = "";
$username_err = $password_err = $confirm_password_err = $warden_age_err = $warden_phone_err = $warden_aadhar_err = $warden_address_err = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement to check if the username already exists
        $sql = 'SELECT id FROM warden WHERE username = ?';

        if ($stmt = $mysql_db->prepare($sql)) {
            $param_username = trim($_POST['username']);
            $stmt->bind_param('s', $param_username);

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $username_err = 'This username is already taken.';
                } else {
                    $username = trim($_POST['username']);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate warden age
    if (empty(trim($_POST['warden_age']))) {
        $warden_age_err = "Please enter the warden's age.";
    } elseif (!is_numeric(trim($_POST['warden_age'])) || trim($_POST['warden_age']) < 18) {
        $warden_age_err = "Please enter a valid age (greater than 18).";
    } else {
        $warden_age = trim($_POST['warden_age']);
    }

    // Validate warden phone number
    if (empty(trim($_POST['warden_phone']))) {
        $warden_phone_err = "Please enter the warden's phone number.";
    } elseif (!preg_match('/^[0-9]{10}$/', trim($_POST['warden_phone']))) {
        $warden_phone_err = "Please enter a valid 10-digit phone number.";
    } else {
        $warden_phone = trim($_POST['warden_phone']);
    }

    // Validate warden Aadhar number and check for duplicates
    if (empty(trim($_POST['warden_aadhar']))) {
        $warden_aadhar_err = "Please enter the warden's Aadhar number.";
    } elseif (!preg_match('/^[0-9]{12}$/', trim($_POST['warden_aadhar']))) {
        $warden_aadhar_err = "Please enter a valid 12-digit Aadhar number.";
    } else {
        $sql = 'SELECT id FROM warden WHERE warden_aadhar = ?';
        if ($stmt = $mysql_db->prepare($sql)) {
            $param_warden_aadhar = trim($_POST['warden_aadhar']);
            $stmt->bind_param('s', $param_warden_aadhar);

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $warden_aadhar_err = 'This Aadhar number is already registered.';
                } else {
                    $warden_aadhar = trim($_POST['warden_aadhar']);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }

    // Validate warden address
    if (empty(trim($_POST['warden_address']))) {
        $warden_address_err = "Please enter the warden's address.";
    } else {
        $warden_address = trim($_POST['warden_address']);
    }

    // Check if there are any errors before inserting data into the database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($warden_age_err) && empty($warden_phone_err) && empty($warden_aadhar_err) && empty($warden_address_err)) {
        
        // Prepare an insert query to insert warden details into the database
        $sql = 'INSERT INTO warden (username, password, warden_age, warden_phone, warden_aadhar, warden_address) VALUES (?, ?, ?, ?, ?, ?)';

        if ($stmt = $mysql_db->prepare($sql)) {
            // Bind parameters to the SQL statement
            $stmt->bind_param('ssisss', $param_username, $param_password, $param_warden_age, $param_warden_phone, $param_warden_aadhar, $param_warden_address);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            $param_warden_age = $warden_age;
            $param_warden_phone = $warden_phone;
            $param_warden_aadhar = $warden_aadhar;
            $param_warden_address = $warden_address;

            // Execute query
            if ($stmt->execute()) {
                // Redirect to the login page after successful registration
                header('Location: welcome.php');
            } else {
                echo "Something went wrong. Please try again later.";
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
    <title>Sign Up</title>
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/cosmo/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .wrapper {
            max-width: 500px;
            margin: 5% auto;
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .wrapper h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
        }
        .wrapper p {
            text-align: center;
            font-size: 1rem;
            margin-bottom: 30px;
        }
        .form-control {
            height: 45px;
            font-size: 1rem;
            border-radius: 8px;
        }
        .btn-block {
            height: 45px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 8px;
        }
        .btn-outline-success {
            margin-top: 20px;
        }
        .btn-outline-primary {
            margin-top: 10px;
        }
        .help-block {
            color: red;
        }
        .text-center p {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <main>
        <section class="container">
            <div class="wrapper">
                <h2>Add Warden</h2>
                <p>Please fill in your credentials.</p>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <!-- Username -->
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has_error' : '';?>">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" value="<?php echo $username ?>">
                        <span class="help-block"><?php echo $username_err; ?></span>
                    </div>

                    <!-- Password -->
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has_error' : '';?>">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" value="<?php echo $password ?>">
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has_error' : '';?>">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>

                    <!-- Warden Age -->
                    <div class="form-group <?php echo (!empty($warden_age_err)) ? 'has_error' : '';?>">
                        <label for="warden_age">Age</label>
                        <input type="text" name="warden_age" id="warden_age" class="form-control" value="<?php echo $warden_age ?>">
                        <span class="help-block"><?php echo $warden_age_err; ?></span>
                    </div>

                    <!-- Warden Phone -->
                    <div class="form-group <?php echo (!empty($warden_phone_err)) ? 'has_error' : '';?>">
                        <label for="warden_phone">Phone Number</label>
                        <input type="text" name="warden_phone" id="warden_phone" class="form-control" value="<?php echo $warden_phone ?>">
                        <span class="help-block"><?php echo $warden_phone_err; ?></span>
                    </div>

                    <!-- Warden Aadhar -->
                    <div class="form-group <?php echo (!empty($warden_aadhar_err)) ? 'has_error' : '';?>">
                        <label for="warden_aadhar">Aadhar Number</label>
                        <input type="text" name="warden_aadhar" id="warden_aadhar" class="form-control" value="<?php echo $warden_aadhar ?>">
                        <span class="help-block"><?php echo $warden_aadhar_err; ?></span>
                    </div>

                    <!-- Warden Address -->
                    <div class="form-group <?php echo (!empty($warden_address_err)) ? 'has_error' : '';?>">
                        <label for="warden_address">Address</label>
                        <textarea name="warden_address" id="warden_address" class="form-control"><?php echo $warden_address ?></textarea>
                        <span class="help-block"><?php echo $warden_address_err; ?></span>
                    </div>

                    <!-- Submit and Reset -->
                    <div class="form-group">
                        <input type="submit" class="btn btn-block btn-outline-success" value="Submit">
                        <input type="reset" class="btn btn-block btn-outline-primary" value="Reset">
                    </div>

                    <p class="text-center">Already have an account? <a href="login.php">Login here</a>.</p>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
