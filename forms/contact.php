<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type to JSON
header('Content-Type: application/json');

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Sanitize and validate input
    $name = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'])) : '';
    $email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : '';
    $subject = isset($_POST['subject']) ? trim(htmlspecialchars($_POST['subject'])) : '';
    $message = isset($_POST['message']) ? trim(htmlspecialchars($_POST['message'])) : '';
    $recipient_emails = isset($_POST['recipient_emails']) ? $_POST['recipient_emails'] : 'IT22133618@my.sliit.lk,IT22091352@my.sliit.lk,IT22135384@my.sliit.lk,IT22239952@my.sliit.lk';
    
    // Validate fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }
    
    // Prepare email headers
    $to = $recipient_emails;
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    
    // Prepare email body
    $email_body = "
    <html>
        <head>
            <title>SignBridge - New Contact Form Submission</title>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #ff570d; color: white; padding: 15px; border-radius: 5px; }
                .content { background-color: #f5f5f5; padding: 20px; margin-top: 20px; border-radius: 5px; }
                .footer { margin-top: 20px; font-size: 12px; color: #666; }
                .field { margin-bottom: 15px; }
                .field-label { font-weight: bold; color: #333; }
                .field-value { color: #555; margin-top: 5px; word-wrap: break-word; }
            </style>
        </head>
        <body>
            <div class=\"container\">
                <div class=\"header\">
                    <h2>SignBridge - New Contact Form Submission</h2>
                </div>
                
                <div class=\"content\">
                    <div class=\"field\">
                        <div class=\"field-label\">Name:</div>
                        <div class=\"field-value\">" . $name . "</div>
                    </div>
                    
                    <div class=\"field\">
                        <div class=\"field-label\">Email:</div>
                        <div class=\"field-value\">" . $email . "</div>
                    </div>
                    
                    <div class=\"field\">
                        <div class=\"field-label\">Subject:</div>
                        <div class=\"field-value\">" . $subject . "</div>
                    </div>
                    
                    <div class=\"field\">
                        <div class=\"field-label\">Message:</div>
                        <div class=\"field-value\">" . nl2br($message) . "</div>
                    </div>
                </div>
                
                <div class=\"footer\">
                    <p>This is an automated email from the SignBridge contact form.</p>
                </div>
            </div>
        </body>
    </html>
    ";
    
    // Send email
    $mail_sent = mail($to, "SignBridge Contact: " . $subject, $email_body, $headers);
    
    if ($mail_sent) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Your message has been sent successfully!']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to send email. Please try again later.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>
