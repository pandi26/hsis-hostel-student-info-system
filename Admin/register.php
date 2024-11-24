<?php
    // Include config file
    require_once 'config/config.php';

    // Define variables and initialize with empty values
    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";

    // Process submitted form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Check if username is empty
        if (empty(trim($_POST['username']))) {
            $username_err = "Please enter a username.";

        } else {
            // Prepare a select statement
            $sql = 'SELECT id FROM admin WHERE username = ?';

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
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter a password.";     
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Password must have at least 6 characters.";
        } else{
            $password = trim($_POST["password"]);
        }

        // Validate confirm password
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Please confirm password.";     
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Password did not match.";
            }
        }

        // Check input error before inserting into database
        if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
            $sql = 'INSERT INTO admin (username, password) VALUES (?, ?)';

            if ($stmt = $mysql_db->prepare($sql)) {
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT); 

                $stmt->bind_param('ss', $param_username, $param_password);

                if ($stmt->execute()) {
                    header('location: login.php');
                } else {
                    echo "Something went wrong. Try signing in again.";
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
                <h2>Sign Up</h2>
                <p>Please fill in your credentials.</p>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has_error' : '';?>">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" value="<?php echo $username ?>">
                        <span class="help-block"><?php echo $username_err; ?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($password_err)) ? 'has_error' : '';?>">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" value="<?php echo $password ?>">
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has_error' : '';?>">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>

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
