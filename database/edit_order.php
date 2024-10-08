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

// Update the order if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plantName = $_POST['plant_name'];
    $quantity = $_POST['quantity'];
    $deliveryDate = $_POST['delivery_date'];
    $customerName = $_POST['customer_name'];
    $customerEmail = $_POST['customer_email'];
    $customerPhone = $_POST['customer_phone'];
    $comments = $_POST['comments'];

    $updateStmt = $conn->prepare("UPDATE orders SET plant_name = ?, quantity = ?, delivery_date = ?, customer_name = ?, customer_email = ?, customer_phone = ?, comments = ? WHERE id = ?");
    $updateStmt->bind_param("sisssssi", $plantName, $quantity, $deliveryDate, $customerName, $customerEmail, $customerPhone, $comments, $orderId);

    if ($updateStmt->execute()) {
        echo "<script>alert('Order updated successfully.'); window.location.href='receive_orders.php';</script>";
    } else {
        echo "<script>alert('Error updating order.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
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
    <h1>Edit Order</h1>
    <form method="POST">
        <div class="form-group">
            <label for="plant_name">Plant Name</label>
            <input type="text" class="form-control" id="plant_name" name="plant_name" value="<?= htmlspecialchars($order['plant_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($order['quantity']); ?>" required>
        </div>
        <div class="form-group">
            <label for="delivery_date">Delivery Date</label>
            <input type="date" class="form-control" id="delivery_date" name="delivery_date" value="<?= htmlspecialchars($order['delivery_date']); ?>" required>
        </div>
        <div class="form-group">
            <label for="customer_name">Customer Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?= htmlspecialchars($order['customer_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="customer_email">Email</label>
            <input type="email" class="form-control" id="customer_email" name="customer_email" value="<?= htmlspecialchars($order['customer_email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="customer_phone">Phone</label>
            <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="<?= htmlspecialchars($order['customer_phone']); ?>" required>
        </div>
        <div class="form-group">
            <label for="comments">Comments</label>
            <textarea class="form-control" id="comments" name="comments"><?= htmlspecialchars($order['comments']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Order</button>
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
