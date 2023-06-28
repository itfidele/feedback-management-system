<?php
// Start the session
session_start();
require_once 'db/connection.php';
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the user login page
    header("Location: index.php");
    exit();
}

// Check if the ticket submission form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['logout'])) {
        # code...
        session_destroy();
        header("Location: index.php");
    }

    if (isset($_POST['add_ticket'])) {
        # code...
        // Retrieve form data
        $subject = $_POST['subject'];
        $description = $_POST['description'];
        $userId = $_SESSION['user_id'];

        if (empty($subject) || empty($description)) {
            $error = "Fill all fields before sending your report";
        }
        // Insert the ticket into the database
        $sql = "INSERT INTO tickets (user_id, subject, description) VALUES ('$userId', '$subject', '$description')";
        if (mysqli_query($conn, $sql)) {
            $success = "Ticket submitted successfully.";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}




// Retrieve the user's submitted tickets
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM tickets WHERE user_id = '$userId' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
       <!-- Compiled and minified CSS -->
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <form method="post">
        <input type="submit" name="logout" class="btn btn-danger" value="Logout"/>
    </form>

    

    <div class="container">
        <div class="row">
        <div class="col-sm-6">
            <!-- Ticket Submission Form -->
            <h3>Submit a New Ticket</h3>
            <?php if (isset($success)): ?>
                <p class="alert alert-success"><?php echo $success; ?></p>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <p class="alert alert-success"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST" action="dashboard.php">
                <label for="subject">Subject:</label>
                <input type="text" class="form-control" id="subject" name="subject" required><br>

                <label for="description">Description:</label>
                <textarea id="description" class="form-control" name="description" required></textarea><br><br>

                <input type="submit" name="add_ticket" class="btn btn-md btn-primary" value="Send Report">
            </form>
        </div>
        <div class="col-sm-6" >
            <!-- Display User's Tickets -->
            <h3>Your Recent Reports</h3>
            <div style="height:600px;overflow-x:auto;">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div style="border: 1px solid black; padding: 10px; margin-bottom: 10px;">
                        <h4><a href='view-report.php?ticket_id=<?php echo $row['id']; ?>'><?php echo $row['subject']; ?></a></h4>
                        <p><?php echo $row['description']; ?></p>
                        <p>Submitted on: <?php echo $row['created_at']; ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        </div>
    </div>
</body>
</html>
