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

// Check for form submission to accept or deny the order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accept'])) {
        // Update the database to accept the order and reduce the quantity of the plant
        $plantName = $order['plant_name'];
        $quantityOrdered = $order['quantity'];

        // Reduce the quantity of the plant in the database
        $updateStmt = $conn->prepare("UPDATE plants SET quantity = quantity - ? WHERE plant_name = ?");
        $updateStmt->bind_param("is", $quantityOrdered, $plantName);
        if ($updateStmt->execute()) {
            // Optionally, update order status to accepted (if you have a status field)
            $stmt = $conn->prepare("UPDATE orders SET status = 'Accepted' WHERE id = ?");
            $stmt->bind_param("i", $orderId);
            $stmt->execute();

            echo "<script>alert('Order accepted successfully.'); window.location.href='receive_orders.php';</script>";
        } else {
            echo "<script>alert('Failed to update plant quantity.');</script>";
        }
    } elseif (isset($_POST['deny'])) {
        // Optionally, update order status to denied (if you have a status field)
        $stmt = $conn->prepare("UPDATE orders SET status = 'Denied' WHERE id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();

        echo "<script>alert('Order denied.'); window.location.href='receive_orders.php';</script>";
    }
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
        h1 {
            color: #4CAF50;
        }
        .btn-custom {
            background-color: #4CAF50;
            color: white;
        }
        .btn-custom:hover {
            background-color: #45a049;
        }
        .image-preview {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            cursor: pointer;
        }
        .modal-body img {
            max-width: 100%;
            height: auto;
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
        <tr>
            <th>Transaction Number</th>
            <td><?= htmlspecialchars($order['transaction_number']); ?></td>
        </tr>
        <tr>
            <th>Payment Confirmation Image</th>
            <td>
                <?php if (!empty($order['payment_confirmation'])): ?>
                    <?php $imagePath = "../images/" . htmlspecialchars($order['payment_confirmation']); ?>
                    <?php if (file_exists($imagePath)): ?>
                        <img src="<?= $imagePath; ?>" alt="Payment Confirmation" class="image-preview" data-toggle="modal" data-target="#imageModal">
                    <?php else: ?>
                        <p>Image not found at <?= $imagePath; ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    No image uploaded.
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <!-- Accept and Deny buttons -->
    <form method="post">
        <button type="submit" name="accept" class="btn btn-success">Accept Order</button>
        <button type="submit" name="deny" class="btn btn-danger">Deny Order</button>
    </form>
<br>
<br>

    <a href="receive_orders.php" class="btn btn-custom">Back to Orders</a>
</div>

<!-- Modal for enlarged image view -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Payment Confirmation Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="<?= $imagePath; ?>" alt="Payment Confirmation Image">
            </div>
        </div>
    </div>
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
