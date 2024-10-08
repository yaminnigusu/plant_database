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
$orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the order details for confirmation
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

// If no order found, redirect back to the orders page
if (!$order) {
    echo "<script>alert('Order not found.'); window.location.href='receive_orders.php';</script>";
    exit();
}

// If the form is submitted to confirm deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $orderId);

    if ($stmt->execute()) {
        echo "<script>alert('Order deleted successfully.'); window.location.href='receive_orders.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error deleting order.'); window.location.href='receive_orders.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Order</title>
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
    <h1>Confirm Deletion</h1>
    <p>Are you sure you want to delete the following order?</p>
    <table class="table table-bordered">
        <tr>
            <th>Order ID</th>
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
            <th>Customer Name</th>
            <td><?= htmlspecialchars($order['customer_name']); ?></td>
        </tr>
        <tr>
            <th>Customer Email</th>
            <td><?= htmlspecialchars($order['customer_email']); ?></td>
        </tr>
        <tr>
            <th>Customer Phone</th>
            <td><?= htmlspecialchars($order['customer_phone']); ?></td>
        </tr>
    </table>
    <form method="POST">
        <button type="submit" class="btn btn-danger">Delete Order</button>
        <a href="receive_orders.php" class="btn btn-secondary">Cancel</a>
    </form>
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
