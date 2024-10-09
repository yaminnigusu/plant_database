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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plantName = $_POST['plant_name'];
    $quantity = $_POST['quantity'];
    $deliveryDate = $_POST['delivery_date'];
    $customerName = $_POST['customer_name'];
    $customerEmail = $_POST['customer_email'];
    $customerPhone = $_POST['customer_phone'];
    $comments = $_POST['comments'];
    $transactionNumber = $_POST['transaction_number'];
    
    // Handle payment confirmation image upload
    $targetDir = "../images/";
    $paymentConfirmation = basename($_FILES["payment_confirmation"]["name"]);
    $targetFilePath = $targetDir . $paymentConfirmation;
    $uploadOk = 1;
    
    // Check if file is an image
    $check = getimagesize($_FILES["payment_confirmation"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('File is not an image.');</script>";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["payment_confirmation"]["size"] > 2000000) {
        echo "<script>alert('Sorry, your file is too large.');</script>";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<script>alert('Sorry, your file was not uploaded.');</script>";
    } else {
        // Try to upload the file
        if (move_uploaded_file($_FILES["payment_confirmation"]["tmp_name"], $targetFilePath)) {
            // Update the order in the database
            $updateStmt = $conn->prepare("UPDATE orders SET plant_name = ?, quantity = ?, delivery_date = ?, customer_name = ?, customer_email = ?, customer_phone = ?, comments = ?, transaction_number = ?, payment_confirmation = ? WHERE id = ?");
            $updateStmt->bind_param("sissssssi", $plantName, $quantity, $deliveryDate, $customerName, $customerEmail, $customerPhone, $comments, $transactionNumber, $paymentConfirmation, $orderId);

            if ($updateStmt->execute()) {
                echo "<script>alert('Order updated successfully.'); window.location.href='receive_orders.php';</script>";
            } else {
                echo "<script>alert('Error updating order.');</script>";
            }
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
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
    <form method="POST" enctype="multipart/form-data">
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
        <div class="form-group">
            <label for="transaction_number">Transaction Number</label>
            <input type="text" class="form-control" id="transaction_number" name="transaction_number" value="<?= htmlspecialchars($order['transaction_number']); ?>" required>
        </div>
        <div class="form-group">
            <label for="payment_confirmation">Payment Confirmation Image</label>
            <input type="file" class="form-control" id="payment_confirmation" name="payment_confirmation">
            <?php if (!empty($order['payment_confirmation'])): ?>
                <img src="../images/<?= htmlspecialchars($order['payment_confirmation']); ?>" alt="Current Payment Confirmation" class="image-preview" style="max-width: 100px; max-height: 100px; object-fit: cover; margin-top: 10px;">
                <p>Current Payment Confirmation Image</p>
            <?php endif; ?>
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
