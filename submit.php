<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    $to = "browsing.amin@gmail.com";
    $subject = "Registration Form Submission";
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

    $headers = "From: $email";

    if (mail($to, $subject, $message, $headers)) {
        echo "Thank you for registering. Your details have been sent.";
    } else {
        echo "Sorry, there was an issue sending your data. Please try again.";
    }
}
?>
