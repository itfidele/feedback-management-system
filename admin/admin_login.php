<?php
// Start the session
session_start();

// Check if the admin is already logged in
if (isset($_SESSION['admin'])) {
    // Redirect to the admin interface
    header("Location: admin.php");
    exit();
}

// Check if the login form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the database (replace with your database credentials)
    $conn = mysqli_connect('localhost', 'username', 'password', 'ticket_system');

    // Check if the connection was successful
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Retrieve the admin from the database
    $sql = "SELECT password FROM admins WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Set the admin username in the session
            $_SESSION['admin'] = $username;

            // Redirect to the admin interface
            header("Location: admin.php");
            exit();
        }
    }

    // Invalid credentials
    $error = "Invalid username or password.";

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
