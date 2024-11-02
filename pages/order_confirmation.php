<?php
session_start();
include("../database/config.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get order details from query parameters
$status = $_GET['status'] ?? '';
$plantName = $_GET['plant_name'] ?? '';
$quantity = $_GET['quantity'] ?? '';
$deliveryDate = $_GET['delivery_date'] ?? '';
$transactionNumber = $_GET['transaction_number'] ?? '';
$totalCost = $_GET['total_cost'] ?? 0; // Get total cost from the query

// Clear the order session data to prevent double orders
if ($status === 'success') {
    unset($_SESSION['order_data']); // Assuming you are storing order data in this session variable
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            text-align: center; /* Centered content */
        }

        h1 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .alert {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: left; /* Left-align alert text */
        }

        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }

        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }

        .order-details {
            list-style-type: none;
            padding: 0;
            margin: 20px 0;
            text-align: left; /* Left-align order details */
        }

        .order-details li {
            padding: 10px;
            border-bottom: 1px solid #e5e5e5;
            font-size: 18px;
        }

        .order-details li:last-child {
            border-bottom: none;
        }

        h3, h4 {
            margin-top: 20px;
            color: #555;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin-top: 20px;
            font-size: 18px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            background-color: #4CAF50;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #d9534f;
        }

        .btn-danger:hover {
            background-color: #c9302c;
        }

        .contact-info {
            margin-top: 20px;
            font-size: 16px;
            text-align: center;
            color: #555;
        }

        .contact-info a {
            color: #4CAF50;
            text-decoration: none;
        }

        .contact-info a:hover {
            text-decoration: underline;
        }

        .reminder {
            margin: 20px 0;
            color: #ff5722; /* Highlight color for reminder */
            font-weight: bold;
        }
    </style>
    <script>
        function goToHomePage() {
            window.location.href = '../pages/home.php'; // Redirect to home page
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Order Confirmation</h1>

        <div class="reminder">
            Reminder: Please take a screenshot of this message for your records.
        </div>

        <?php if ($status === 'success'): ?>
            <div class="alert alert-success">
                <h2>Thank You for Your Order!</h2>
                <p>Your order is currently pending payment verification.</p>
            </div>

            <h3>Order Details:</h3>
            <ul class="order-details">
                <li><strong>Plant Name:</strong> <?php echo htmlspecialchars($plantName); ?></li>
                <li><strong>Quantity:</strong> <?php echo htmlspecialchars($quantity); ?></li>
                <li><strong>Delivery Date:</strong> <?php echo htmlspecialchars($deliveryDate); ?></li>
                <li><strong>Transaction Number:</strong> <?php echo htmlspecialchars($transactionNumber); ?></li>
               <!-- Added total cost -->
            </ul>

            <p>We will contact you shortly with further details regarding your order and delivery.</p>

            <p>If you have any questions, feel free to reach out to us at <a href="mailto:nigusuyamin@gmail.com">nigusuyamin@gmail.com  </a>.</p>

            <div class="contact-info">
                <p>Contact us: +251 940384999</p>
            </div>

            <a href="javascript:void(0);" class="btn" onclick="goToHomePage()">Back to Home</a>
        <?php else: ?>
            <div class="alert alert-danger">
                <h2>Order Failed!</h2>
                <p>There was an issue with your order. Please try again.</p>
            </div>
            <a href="../pages/order_form.php" class="btn btn-danger">Return to Order Form</a>
        <?php endif; ?>
    </div>
</body>
</html>
