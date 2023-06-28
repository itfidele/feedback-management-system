<?php
// Start the session
session_start();
require_once 'db/connection.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the user dashboard or home page
    header("Location: dashboard.php");
    exit();
}

// Check if the login form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $email = $_POST['email'];
    
    $password = $_POST['password'];

    // Retrieve the user from the database
    $sql = "SELECT * from users WHERE email = '$email'";

    $result = $conn->prepare($sql);
    $stmt = $result->execute();

    if ($stmt->rowCount() === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Set the user ID and username in the session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];

            // Redirect to the user dashboard or home page
            header("Location: dashboard.php");
            exit();
        }
    }

    // Invalid credentials
    $error = "Invalid email or password.";

    // Close the database connection
    mysqli_close($conn);
}
?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  </head>
  <body>

  
        <div class="d-flex my-auto justify-content-center">
            
            
            <form method="POST" class="card col-sm-4 p-4" action="index.php">
                <h2>User Login</h2>
                <br>
                <?php if (isset($error)): ?>
                    <p class="alert alert-danger"><?php echo $error; ?></p>
                <?php endif; ?>
                <label for="fullname">Email:</label>
                <input type="text" id="email" class="form-control" name="email" required><br>
                
                <label for="username">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required><br>

                <input type="submit" class="btn btn-lg btn-primary" value="Login"> <br>
                <a href="register.php">Create Account</a>
            </form>
        </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>


