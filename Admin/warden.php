<?php
// Include config file for database connection
require_once 'config/config.php';

// SQL query to select all warden details
$sql = "SELECT id, username, warden_age, warden_phone, warden_aadhar, warden_address, created_at FROM warden";

// Prepare and execute the SQL query
if ($stmt = $mysql_db->prepare($sql)) {
    // Execute the statement
    if ($stmt->execute()) {
        // Store the result
        $stmt->store_result();
        
        // Check if any rows are returned
        if ($stmt->num_rows > 0) {
            // Bind the result variables
            $stmt->bind_result($id, $username, $warden_age, $warden_phone, $warden_aadhar, $warden_address, $created_at);
            
            // Output the data in a table with custom CSS
            echo "
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>Warden Details</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f6f9;
                        padding-top: 30px;
                    }
                    .container {
                        max-width: 1000px;
                        margin: 0 auto;
                        padding: 20px;
                        background-color: white;
                        border-radius: 8px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    }
                    h2 {
                        text-align: center;
                        color: #333;
                    }
                    table {
                        width: 100%;
                        margin-top: 20px;
                        border-collapse: collapse;
                    }
                    th, td {
                        padding: 12px;
                        text-align: left;
                        border: 1px solid #ddd;
                    }
                    th {
                        background-color: #007bff;
                        color: white;
                        font-weight: bold;
                    }
                    td {
                        background-color: #f9f9f9;
                    }
                    tr:nth-child(even) td {
                        background-color: #f2f2f2;
                    }
                    tr:hover td {
                        background-color: #f1f1f1;
                    }
                    .no-data {
                        font-size: 1.2rem;
                        text-align: center;
                        color: #999;
                        margin-top: 20px;
                    }
                    .btn {
                        padding: 10px 15px;
                        background-color: #28a745;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        text-decoration: none;
                        display: inline-block;
                        margin-top: 20px;
                    }
                    .btn:hover {
                        background-color: #218838;
                        cursor: pointer;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h2>Warden Details</h2>
                    <table class='table table-bordered'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Age</th>
                                <th>Phone</th>
                                <th>Aadhar</th>
                                <th>Address</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>";
            
            // Fetch and display the results
            while ($stmt->fetch()) {
                echo "<tr>";
                echo "<td>" . $id . "</td>";
                echo "<td>" . $username . "</td>";
                echo "<td>" . $warden_age . "</td>";
                echo "<td>" . $warden_phone . "</td>";
                echo "<td>" . $warden_aadhar . "</td>";
                echo "<td>" . $warden_address . "</td>";
                echo "<td>" . $created_at . "</td>";
                echo "</tr>";
            }
            
            echo "</tbody>
                </table>
                </div>
            </body>
            </html>";
        } else {
            echo "<div class='no-data'>No wardens found.</div>";
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
    $stmt->close();
}

// Close the database connection
$mysql_db->close();
?>
