<?php
session_start();

// Set session timeout duration (30 minutes)
$timeoutDuration = 1800; // 30 minutes in seconds

// Check if the user is logged in and if the session is set
if (isset($_SESSION['username'])) {
    // Check if the last activity timestamp is set
    if (isset($_SESSION['last_activity'])) {
        // Calculate the time since the last activity
        $elapsedTime = time() - $_SESSION['last_activity'];

        // If the time elapsed is greater than the timeout duration, log the user out
        if ($elapsedTime >= $timeoutDuration) {
            session_unset(); // Remove session variables
            session_destroy(); // Destroy the session
            header("Location: login.php?message=Session expired. Please log in again."); // Redirect to login page
            exit();
        }
    }
    
    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
} else {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Store the requested URL
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Include your database connection file
include("../database/config.php");

// Get the order ID from the URL
$orderId = $_GET['id'];

// Fetch the order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo "<script>alert('Order not found.'); window.location.href='receive_orders.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            padding: 30px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Order Details</h1>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td><?= htmlspecialchars($order['id']); ?></td>
        </tr>
        <tr>
            <th>Plant Name</th>
            <td><?= htmlspecialchars($order['plant_name']); ?></td>
        </tr>
        <tr>
            <th>Quantity</th>
            <td><?= htmlspecialchars($order['quantity']); ?></td>
        </tr>
        <tr>
            <th>Delivery Date</th>
            <td><?= htmlspecialchars($order['delivery_date']); ?></td>
        </tr>
        <tr>
            <th>Customer Name</th>
            <td><?= htmlspecialchars($order['customer_name']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= htmlspecialchars($order['customer_email']); ?></td>
        </tr>
        <tr>
            <th>Phone</th>
            <td><?= htmlspecialchars($order['customer_phone']); ?></td>
        </tr>
        <tr>
            <th>Comments</th>
            <td><?= htmlspecialchars($order['comments']); ?></td>
        </tr>
    </table>
    <a href="receive_orders.php" class="btn btn-primary">Back to Orders</a>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
