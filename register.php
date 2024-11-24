<?php
// Define new variables
$email = $password = $confirm_password = "";
$student_register_number = $student_name = $student_mobile = $parent_name = $parent_mobile = $address = $room_number = "";
$academic_year = $engineering_dept = $gender = "";  // Add gender variable
$email_err = $password_err = $confirm_password_err = "";
$student_register_number_err = $student_name_err = $student_mobile_err = $parent_name_err = $parent_mobile_err = $address_err = $room_number_err = "";
$academic_year_err = $engineering_dept_err = $gender_err = ""; // Add gender error variable

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once "config/config.php";

    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = "Please enter an email address.";
    } elseif (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        $email = trim($_POST['email']);
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

    // Validate student register number
    if (empty(trim($_POST['student_register_number']))) {
        $student_register_number_err = "Please enter the student register number.";
    } else {
        // Check if student register number is already registered
        $sql = "SELECT id FROM users WHERE student_register_number = ?";
        if ($stmt = $mysql_db->prepare($sql)) {
            $stmt->bind_param("s", $param_student_register_number);
            $param_student_register_number = trim($_POST['student_register_number']);

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $student_register_number_err = "This student register number is already registered.";
                } else {
                    $student_register_number = trim($_POST['student_register_number']);
                }
            } else {
                echo "Something went wrong. Please try again.";
            }

            $stmt->close();
        }
    }

    // Validate student name
    if (empty(trim($_POST['student_name']))) {
        $student_name_err = "Please enter the student name.";
    } else {
        $student_name = trim($_POST['student_name']);
    }

    // Validate student mobile
    if (empty(trim($_POST['student_mobile']))) {
        $student_mobile_err = "Please enter the student mobile number.";
    } elseif (!preg_match('/^[0-9]{10}$/', trim($_POST['student_mobile']))) {
        $student_mobile_err = "Please enter a valid 10-digit mobile number.";
    } else {
        $student_mobile = trim($_POST['student_mobile']);
    }

    // Validate parent name
    if (empty(trim($_POST['parent_name']))) {
        $parent_name_err = "Please enter the father/mother's name.";
    } else {
        $parent_name = trim($_POST['parent_name']);
    }

    // Validate parent mobile and check if it is different from student mobile
    if (empty(trim($_POST['parent_mobile']))) {
        $parent_mobile_err = "Please enter the father/mother's mobile number.";
    } elseif (!preg_match('/^[0-9]{10}$/', trim($_POST['parent_mobile']))) {
        $parent_mobile_err = "Please enter a valid 10-digit mobile number.";
    } elseif (trim($_POST['parent_mobile']) === trim($_POST['student_mobile'])) {
        $parent_mobile_err = "Parent mobile number cannot be the same as the student mobile number. Please enter a different mobile number.";
    } else {
        $parent_mobile = trim($_POST['parent_mobile']);
    }

    // Validate address
    if (empty(trim($_POST['address']))) {
        $address_err = "Please enter the address.";
    } else {
        $address = trim($_POST['address']);
    }

    // Validate room number
    if (empty(trim($_POST['room_number']))) {
        $room_number_err = "Please enter the room number.";
    } else {
        $room_number = trim($_POST['room_number']);
    }

    // Validate academic year
    if (empty(trim($_POST['academic_year']))) {
        $academic_year_err = "Please enter the academic year.";
    } else {
        $academic_year = trim($_POST['academic_year']);
    }

    // Validate engineering department
    if (empty(trim($_POST['engineering_dept']))) {
        $engineering_dept_err = "Please select an engineering department.";
    } else {
        $engineering_dept = trim($_POST['engineering_dept']);
    }

    // Validate gender
    if (empty(trim($_POST['gender']))) {
        $gender_err = "Please select a gender.";
    } else {
        $gender = trim($_POST['gender']);
    }

    // Check for input errors before inserting into the database
    if (empty($email_err) && empty($password_err) && empty($confirm_password_err) &&
        empty($student_register_number_err) && empty($student_name_err) && empty($student_mobile_err) &&
        empty($parent_name_err) && empty($parent_mobile_err) && empty($address_err) && 
        empty($room_number_err) && empty($academic_year_err) && empty($engineering_dept_err) && empty($gender_err)) {

        // Prepare the SQL statement
        $sql = 'INSERT INTO users (email, password, student_register_number, student_name, student_mobile, parent_name, parent_mobile, address, room_number, academic_year, engineering_dept, gender) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        if ($stmt = $mysql_db->prepare($sql)) {
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

            // Bind parameters
            $stmt->bind_param('ssssssssssss', $param_email, $param_password, $student_register_number, $student_name, $student_mobile, $parent_name, $parent_mobile, $address, $room_number, $academic_year, $engineering_dept, $gender);

            if ($stmt->execute()) {
                // Redirect to login page
                header('location: login.php');
            } else {
                echo "Something went wrong. Please try again.";
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
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has_error' : '';?>">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" class="form-control" value="<?php echo $email ?>">
                <span style="color:red" class="help-block"><?php echo $email_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($student_register_number_err)) ? 'has_error' : '';?>">
                <label for="student_register_number">Student Register Number</label>
                <input type="text" name="student_register_number" id="student_register_number" class="form-control" value="<?php echo $student_register_number ?>">
                <span style="color:red" class="help-block"><?php echo $student_register_number_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($student_name_err)) ? 'has_error' : '';?>">
                <label for="student_name">Student Name</label>
                <input type="text" name="student_name" id="student_name" class="form-control" value="<?php echo $student_name ?>">
                <span style="color:red" class="help-block"><?php echo $student_name_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($student_mobile_err)) ? 'has_error' : '';?>">
                <label for="student_mobile">Student Mobile Number</label>
                <input type="text" name="student_mobile" id="student_mobile" class="form-control" value="<?php echo $student_mobile ?>">
                <span style="color:red" class="help-block"><?php echo $student_mobile_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($parent_name_err)) ? 'has_error' : '';?>">
                <label for="parent_name">Father/Mother Name</label>
                <input type="text" name="parent_name" id="parent_name" class="form-control" value="<?php echo $parent_name ?>">
                <span style="color:red" class="help-block"><?php echo $parent_name_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($parent_mobile_err)) ? 'has_error' : '';?>">
                <label for="parent_mobile">Father/Mother Mobile Number</label>
                <input type="text" name="parent_mobile" id="parent_mobile" class="form-control" value="<?php echo $parent_mobile ?>">
                <span style="color:red" class="help-block"><?php echo $parent_mobile_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($gender_err)) ? 'has_error' : '';?>">
             <label for="gender">Gender</label>
             <div>
             <label><input type="radio" name="gender" value="Male" <?php echo ($gender === 'Male') ? 'checked' : ''; ?>> Male</label>
             <label><input type="radio" name="gender" value="Female" <?php echo ($gender === 'Female') ? 'checked' : ''; ?>> Female</label>
             <label><input type="radio" name="gender" value="Other" <?php echo ($gender === 'Other') ? 'checked' : ''; ?>> Other</label>
             </div>
             <span style="color:red" class="help-block"><?php echo $gender_err; ?></span>
            </div>


            <div class="form-group <?php echo (!empty($room_number_err)) ? 'has_error' : '';?>">
                <label for="room_number">Room Number</label>
                <input type="text" name="room_number" id="room_number" class="form-control" value="<?php echo $room_number ?>">
                <span style="color:red" class="help-block"><?php echo $room_number_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($address_err)) ? 'has_error' : '';?>">
                <label for="address">Address</label>
                <textarea name="address" id="address" class="form-control" rows="3"><?php echo $address ?></textarea>
                <span style="color:red" class="help-block"><?php echo $address_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($academic_year_err)) ? 'has_error' : '';?>">
                <label for="academic_year">Academic Year</label>
                <input type="text" name="academic_year" id="academic_year" class="form-control" value="<?php echo $academic_year ?>">
                <span style="color:red" class="help-block"><?php echo $academic_year_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($engineering_dept_err)) ? 'has_error' : '';?>">
                <label for="engineering_dept">Engineering Department</label>
                <select name="engineering_dept" id="engineering_dept" class="form-control">
                    <option value="">Select Department</option>
                    <option value="Computer Engineering" <?php echo ($engineering_dept === 'Computer Engineering') ? 'selected' : ''; ?>>Computer Engineering</option>
                    <option value="Mechanical Engineering" <?php echo ($engineering_dept === 'Mechanical Engineering') ? 'selected' : ''; ?>>Mechanical Engineering</option>
                    <option value="Civil Engineering" <?php echo ($engineering_dept === 'Civil Engineering') ? 'selected' : ''; ?>>Civil Engineering</option>
                    <option value="Electrical Engineering" <?php echo ($engineering_dept === 'Electrical Engineering') ? 'selected' : ''; ?>>Electrical Engineering</option>
                    <option value="Chemical Engineering" <?php echo ($engineering_dept === 'Chemical Engineering') ? 'selected' : ''; ?>>Chemical Engineering</option>
                    <option value="Chemical Engineering" <?php echo ($engineering_dept === 'Chemical Engineering') ? 'selected' : ''; ?>>Mca</option>
                    <option value="Chemical Engineering" <?php echo ($engineering_dept === 'Chemical Engineering') ? 'selected' : ''; ?>>Mba</option>
                </select>
                <span style="color:red" class="help-block"><?php echo $engineering_dept_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($password_err)) ? 'has_error' : '';?>">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" value="<?php echo $password ?>">
                <span style="color:red" class="help-block"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has_error' : '';?>">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span style="color:red" class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-block btn-outline-success" value="Submit">
                <input type="reset" class="btn btn-block btn-outline-primary" value="Reset">
            </div>

            <p class="text-center">Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
