<?php
require_once 'db/connection.php';
// Check if the registration form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $fullname = $_POST['fullname'];

    // Validate form inputs (you can add more validation as per your requirements)
    if (empty($username) || empty($email) || empty($password) || empty($fullname)) {
        $error = "Please fill in all fields.";
    } else {
       
        // Check if the email is already registered
        $sql = "SELECT user_id FROM users WHERE email = '$email'";
        $result = mysqli_query($conn,$sql);


        if (mysqli_num_rows($result) > 0) {
            $error = "Email is already registered. Please choose a different email.";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the user into the database
            $sql = "INSERT INTO users (username,fullname, email, password,category_id) VALUES ('$username','$fullname', '$email', '$hashedPassword','1')";
            if (mysqli_query($conn, $sql)) {
                $success = "Registration successful. You can now login.";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }

        // Close the database connection
        mysqli_close($conn);
    }
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
            
            
            <form method="POST" class="card col-sm-4 p-4" action="register.php">
                <h2>User Registration</h2>
                <br>
                <?php if (isset($success)): ?>
                    <p class="alert alert-success"><?php echo $success; ?></p>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <p class="alert alert-danger"><?php echo $error; ?></p>
                <?php endif; ?>
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" class="form-control" name="fullname" required><br>
                
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" class="form-control" name="email" required><br>

                <label for="password">Password:</label>
                <input type="password" id="password" class="form-control" name="password" required><br>

                <input type="submit" class="btn btn-lg btn-primary" value="Register"> <br>
                <a href="index.php">I already has an account</a>
            </form>
        </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>


