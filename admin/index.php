<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    // Redirect to the admin login page
    header("Location: admin_login.php");
    exit();
}

// Connect to the database (replace with your database credentials)
$conn = mysqli_connect('localhost', 'username', 'password', 'ticket_system');

// Check if the connection was successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Retrieve all tickets and their replies from the database
$sql = "SELECT t.id, t.subject, t.description, t.created_at, u.username
        FROM tickets AS t
        INNER JOIN users AS u ON t.user_id = u.id
        ORDER BY t.created_at DESC";
$result = mysqli_query($conn, $sql);

// Retrieve the admin's username from the session
$adminUsername = $_SESSION['admin'];

// Handle ticket reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketId = $_POST['ticket_id'];
    $message = $_POST['message'];

    // Insert the reply into the database
    $sql = "INSERT INTO replies (ticket_id, admin_name, message) VALUES ('$ticketId', '$adminUsername', '$message')";
    if (mysqli_query($conn, $sql)) {
        $success = "Reply sent successfully.";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Interface</title>
</head>
<body>
    <h2>Welcome, <?php echo $adminUsername; ?>!</h2>
    <a href="admin_logout.php">Logout</a>
    <h3>Tickets</h3>
    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div style="border: 1px solid black; padding: 10px; margin-bottom: 10px;">
        <h4><?php echo $row['subject']; ?></h4>
        <p><?php echo $row['description']; ?></p>
        <p>Submitted by: <?php echo $row['username']; ?></p>
        <p>Submitted on: <?php echo $row['created_at']; ?></p>

        <!-- Display ticket replies -->
        <?php
        $ticketId = $row['id'];
        $sqlReplies = "SELECT admin_name, message FROM replies WHERE ticket_id = '$ticketId'";
        $resultReplies = mysqli_query($conn, $sqlReplies);
        ?>
        <?php while ($reply = mysqli_fetch_assoc($resultReplies)): ?>
            <div style="border: 1px solid gray; padding: 5px; margin-top: 10px;">
                <p><strong>Admin:</strong> <?php echo $reply['admin_name']; ?></p>
                <p><?php echo $reply['message']; ?></p>
            </div>
        <?php endwhile; ?>

        <!-- Reply form -->
        <form method="POST" action="">
            <input type="hidden" name="ticket_id" value="<?php echo $row['id']; ?>">
            <textarea name="message" placeholder="Enter your reply..." required></textarea><br>
            <input type="submit" value="Send Reply">
        </form>
    </div>
<?php endwhile; ?>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div style="border: 1px solid black; padding: 10px; margin-bottom: 10px;">
            <h4><?php echo $row['subject']; ?></h4>
            <p><?php echo $row['description']; ?></p>
            <p>Submitted by: <?php echo $row['username']; ?></p>
            <p>Submitted on: <?php echo $row['created_at']; ?></p>
            <form method="POST" action="">
                <input type="hidden" name="ticket_id" value="<?php echo $row['id']; ?>">
                <textarea name="message" placeholder="Enter your reply..." required></textarea><br>
                <input type="submit" value="Send Reply">
            </form>
        </div>
    <?php endwhile; ?>
</body>
</html>
