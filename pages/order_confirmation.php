<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .confirmation-container {
            margin-top: 50px;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .order-summary {
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 5px;
        }
        .order-summary h4 {
            font-weight: bold;
        }
        .btn-primary, .btn-primary:hover {
            background-color: #28a745;
            border: none;
        }
        .btn-primary {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container confirmation-container">
        <h1 class="text-success">Order Placed Successfully!</h1>
        <p class="lead">Thank you for your order. A confirmation email has been sent to you with the details of your purchase.</p>
        
        <!-- Order Summary -->
        <div class="order-summary">
            <h4>Order Summary</h4>
            <p><strong>Plant Name:</strong> <?= isset($_GET['plant_name']) ? htmlspecialchars($_GET['plant_name']) : 'N/A'; ?></p>
            <p><strong>Quantity Ordered:</strong> <?= isset($_GET['quantity']) ? htmlspecialchars($_GET['quantity']) : 'N/A'; ?></p>
            <p><strong>Delivery Date:</strong> <?= isset($_GET['delivery_date']) ? htmlspecialchars($_GET['delivery_date']) : 'N/A'; ?></p>
        </div>
        
        <a href="home.php" class="btn btn-primary btn-lg">Return to Homepage</a>
    </div>

    <!-- Optional: include Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
