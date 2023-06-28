<?php
// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the user dashboard page
    header("Location: dashboard.php");
    exit();
}

// Connect to the database (replace with your database credentials)
$conn = mysqli_connect('localhost', 'username', 'password', 'ticket_system');

// Check if the connection was successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the reset form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $email = $_POST['email'];

    // Generate a random token
    $token = bin2hex(random_bytes(32));

    // Check if the email exists in the database
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        // Update the user's token and token expiry time in the database
        $sql = "UPDATE users SET reset_token = '$token', reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = '$email'";
        if (mysqli_query($conn, $sql)) {
            // Send the reset link via email
            $resetLink = "http://your-domain.com/reset_password.php?email=" . urlencode($email) . "&token=" . urlencode($token);
            $subject = "Password Reset";
            $message = "Hello,\n\nPlease click the following link to reset your password:\n\n" . $resetLink;
            $headers = "From: Your Name <your-email@example.com>";

            if (mail($email, $subject, $message, $headers)) {
                $success = "A password reset link has been sent to your email address.";
            } else {
                $error = "Failed to send the password reset email.";
            }
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    } else {
        $error = "Email address not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Account</title>
       <!-- Compiled and minified CSS -->
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        

</head>
<body>
    <h2>Reset Account Password</h2>
    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
