<?php
require_once "config/config.php";
use Twilio\Twiml;

// Initialize the Twilio response
$response = new Twiml();

// Get the incoming message body
$incoming_message = $_POST['Body'];  // This will contain the button payload

// Check if the message is a confirmation response from a button
if (strpos($incoming_message, 'safe_reached_') !== false) {
    $student_id = str_replace('safe_reached_', '', $incoming_message);
    
    // Update your database to mark the student as having reached home
    $sql = "UPDATE students SET status = 'reached' WHERE student_register_number = ?";
    if ($stmt = $mysql_db->prepare($sql)) {
        $stmt->bind_param("s", $student_id);
        if ($stmt->execute()) {
            $response->message('Thank you! Your confirmation has been received.');
        } else {
            $response->message('Sorry, there was an issue updating the status.');
        }
        $stmt->close();
    }
} elseif (strpos($incoming_message, 'not_reached_') !== false) {
    $student_id = str_replace('not_reached_', '', $incoming_message);
    
    // Update your database to mark the student as not reached home
    $sql = "UPDATE students SET status = 'not_reached' WHERE student_register_number = ?";
    if ($stmt = $mysql_db->prepare($sql)) {
        $stmt->bind_param("s", $student_id);
        if ($stmt->execute()) {
            $response->message('Thank you! Your response has been received.');
        } else {
            $response->message('Sorry, there was an issue updating the status.');
        }
        $stmt->close();
    }
} else {
    // If the message doesn't match any expected format, send a default reply
    $response->message('Sorry, we did not understand your response.');
}

// Send the response back to Twilio
header("Content-Type: text/xml");
echo $response;
?>
