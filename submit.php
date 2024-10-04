<?php
// Check if the form has been submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data from the POST request
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $nicName = htmlspecialchars(trim($_POST['nicName']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $courseName = htmlspecialchars(trim($_POST['courseName']));
    $passingYear = htmlspecialchars(trim($_POST['passingYear']));
    $address = htmlspecialchars(trim($_POST['address']));
    $dob = htmlspecialchars(trim($_POST['dob']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));

    // Specify the recipient email address
    $to = "browsing.amin@gmail.com";

    // Set the subject line for the email
    $subject = "Registration Form Submission";

    // Compose the email message with the submitted form data
    $message = "
    First Name: $firstName\n
    Last Name: $lastName\n
    Nic Name: $nicName\n
    Gender: $gender\n
    Graduation Course Name: $courseName\n
    Passing Year: $passingYear\n
    Address: $address\n
    Date of Birth: $dob\n
    Email: $email\n
    Phone/WhatsApp: $phone
    ";

    // Set the email headers, including the sender's email
    $headers = "From: $email\r\n";

    // Attempt to send the email and check if it was successful
    if (mail($to, $subject, $message, $headers)) {
        // If the email was sent successfully, display a success message
        echo "Thank you for registering. Your details have been sent.";
    } else {
        // If there was an error sending the email, display an error message
        echo "Sorry, there was an issue sending your data. Please try again.";
    }
}
?>
