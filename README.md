Live URL:


https://aminbiography.github.io/registration-form/

```
Instructions for Handling the Registration Form and PHP Backend
1. Overview
The code consists of two parts:

An HTML form that collects user registration data.
A PHP script that processes the form data and sends it via email.
2. Setting Up the HTML Form
Create an HTML File

Save the provided HTML code as registration_form.html.
Structure of the HTML Form

The form includes fields for:
First Name
Last Name
Nickname
Gender
Graduation Course Name
Passing Year
Address
Date of Birth
Email Address
Phone/WhatsApp Number
Form Submission

The form uses the POST method to submit data to a specified endpoint (in this case, Formspree or a local PHP script).
Styling

The form includes CSS for styling, including gradients for the heading and button, and responsive design for mobile devices.
JavaScript Validation

A simple JavaScript function validates required fields before submission.
3. Setting Up the PHP Script
Create a PHP File

Save the provided PHP code as process_registration.php.
PHP Form Handling

The PHP script checks if the form is submitted via POST.
It retrieves form data using $_POST[] and constructs an email message with the submitted information.
Email Configuration

Update the $to variable with the recipient's email address where you want to receive form submissions.
Set the email headers to include the sender's email.
Email Functionality

The mail() function sends the email. Ensure your server is configured to send emails, or you might want to use a service like PHPMailer or SMTP for reliability.
Feedback Messages

The script provides success or error messages based on whether the email was sent successfully.
4. Testing the Form
Host the Files

Upload both files (registration_form.html and process_registration.php) to your web server.
Access the Form

Open registration_form.html in your browser.
Fill Out the Form

Complete the registration form and click the "Submit" button.
Check Email Delivery

Verify that you receive the registration details at the specified email address. If not, check your server’s email settings.
5. Troubleshooting Common Issues
Email Not Sending:

Ensure your web server is configured to send emails (check PHP mail settings).
Check spam/junk folders for the emails.
Form Validation Issues:

Ensure that all required fields are filled out before submission.
Check the console for JavaScript errors during form submission.
Form Styling:

Adjust CSS as needed for better responsiveness or aesthetics.
Server Configuration:

Make sure your server allows the execution of PHP scripts and that PHP is installed.
6. Security Considerations
Sanitize Input:

To prevent XSS and other attacks, consider sanitizing the input data before processing and storing it.
Email Injection Prevention:

Validate and sanitize email addresses to prevent header injection attacks.
7. Further Enhancements
Database Storage:

Consider storing form submissions in a database for future reference instead of just sending an email.
User Feedback:

Redirect users to a "Thank You" page after successful registration instead of displaying a message on the same page.
Email Confirmation:

Implement an email confirmation feature where users receive a confirmation email after registration.
```            
