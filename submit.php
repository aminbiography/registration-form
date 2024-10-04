<?php
// Check if the form has been submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data from the POST request
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $nicName = $_POST['nicName'];
    $gender = $_POST['gender'];
    $courseName = $_POST['courseName'];
    $passingYear = $_POST['passingYear'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

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
    Phone/WHATSAPP: $phone
    ";

    // Set the email headers, including the sender's email
    $headers = "From: $email";

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
