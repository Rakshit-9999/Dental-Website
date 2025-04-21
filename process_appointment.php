<?php
// process_appointment.php - Processes the appointment form and sends confirmation email

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    // Require the Composer autoloader
    require 'vendor/autoload.php';
    
    // Get form data and sanitize
    $fname = htmlspecialchars($_POST['fname']);
    $lname = htmlspecialchars($_POST['lname']);
    $full_name = $fname . ' ' . $lname;
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mobile = htmlspecialchars($_POST['mobile']);
    $date = htmlspecialchars($_POST['date']);
    $time = htmlspecialchars($_POST['appointment_time']);
    $treatment = htmlspecialchars($_POST['treatment']);
    $note = isset($_POST['note']) ? htmlspecialchars($_POST['note']) : '';
    
    // Format the date for display
    $formatted_date = date('l, F j, Y', strtotime($date));
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Return to index with SweetAlert for invalid email
        showSweetAlert("Error!", "Invalid email address.", "error", "index.php");
        exit;
    }
    
    // Validate date (ensure it's not in the past)
    $appointment_date = new DateTime($date);
    $today = new DateTime();
    $today->setTime(0, 0, 0); // Set time to beginning of day for proper comparison
    
    if ($appointment_date < $today) {
        // Return to index with SweetAlert for past date
        showSweetAlert("Error!", "Appointment date cannot be in the past.", "error", "index.php");
        exit;
    }

    try {
        // Create a new instance of MailerSend with your API token
        $mailersend = new \MailerSend\MailerSend(['api_key' => 'mlsn.bd13b62e26e4115da877bbc3afc45f47b82555e26c0e43f1216584d4ee96c43a']);

        // Define your recipients (sending to the patient)
        $recipients = [
            new \MailerSend\Helpers\Builder\Recipient($email, $full_name),
        ];

        // Build the custom message with appointment details
        $custom_message = "
            <p>Dear $full_name,</p>
            <p>Thank you for booking an appointment with Modern Dental Clinic. Your appointment details are:</p>
            <ul>
                <li><strong>Date:</strong> $formatted_date</li>
                <li><strong>Time:</strong> $time</li>
                <li><strong>Treatment:</strong> $treatment</li>
                <li><strong>Mobile:</strong> $mobile</li>
            </ul>
            " . (!empty($note) ? "<p><strong>Your notes:</strong> $note</p>" : "") . "
            <p>If you need to reschedule or cancel your appointment, please contact us at least 24 hours in advance.</p>
            <p>We look forward to seeing you!</p>
            <p>Best regards,<br>Modern Dental Clinic Team</p>
        ";

        // Set up the email parameters - simplify by sending a direct HTML email
        $emailParams = (new \MailerSend\Helpers\Builder\EmailParams())
            ->setFrom('noreply@test-68zxl2779j54j905.mlsender.net')
            ->setFromName('Modern Dental Clinic')
            ->setRecipients($recipients)
            ->setSubject('Your Dental Appointment Confirmation')
            ->setHtml($custom_message);  // Just use the HTML directly instead of template

        // Send the email
        $response = $mailersend->email->send($emailParams);
        
        // Debug: Log the success response
        error_log(date('Y-m-d H:i:s') . " - Email sent successfully.", 3, "logs/email_success.log");
        
        // Show success SweetAlert and redirect
        showSweetAlert("Congrats!", "Your appointment is booked successfully! A confirmation email has been sent to your email address.", "success", "index.php");
        exit;
        
    } catch (\Exception $e) {
        // Log the detailed error for debugging
        error_log(date('Y-m-d H:i:s') . " - Appointment email error: " . $e->getMessage() . "\n", 3, "logs/email_errors.log");
        
        // Show error SweetAlert and redirect
        showSweetAlert("Error!", "We couldn't send the confirmation email, but your appointment was received. Please contact us for confirmation.", "error", "index.php");
        exit;
    }
} else {
    // If someone tries to access this file directly, redirect to the form
    header("Location: index.php");
    exit;
}

// Function to show SweetAlert and redirect
function showSweetAlert($title, $message, $type, $redirect) {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Processing</title>
        <!-- Include SweetAlert library -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body>
        <script>
            function JSalert() {
                Swal.fire({
                    title: "' . $title . '",
                    text: "' . $message . '",
                    icon: "' . $type . '",
                    confirmButtonText: "OK",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: true,
                    timer: null  // Remove timer to prevent auto-closing
                }).then(function(result) {
                    if (result.isConfirmed) {
                        window.location.href = "' . $redirect . '";
                    }
                });
            }
            
            // Call the alert function when page loads
            document.addEventListener("DOMContentLoaded", JSalert);
            // Also call immediately in case DOMContentLoaded already fired
            JSalert(); 
        </script>
    </body>
    </html>';
}
?>