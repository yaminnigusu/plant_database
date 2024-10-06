<?php
// order_form.php
include("../database/config.php");

// Get plant information from the query parameters
$plantId = $_GET['plant_id'] ?? '';
$plantName = $_GET['plant_name'] ?? '';

// Fetch available plastic sizes and quantity for the selected plant
$plasticSizes = [];
$availableQuantity = 0; // Initialize available quantity

if ($plantId) {
    $stmt = $conn->prepare("SELECT plastic_size, quantity FROM plants WHERE id = ?");
    $stmt->bind_param("i", $plantId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $plantData = $result->fetch_assoc();
        // Assume 'plastic_size' is a comma-separated string
        $plasticSizes = explode(',', $plantData['plastic_size']);
        $availableQuantity = $plantData['quantity']; // Get the available quantity
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
    <style>
        body {
            background: linear-gradient(to right, #e2f0d9, #c2e1b8);
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin-top: 50px;
            transition: all 0.3s ease;
        }
        .container:hover {
            transform: scale(1.02);
        }
        h1 {
            color: #28a745;
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .order-summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 5px solid #28a745;
            font-weight: bold;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
            font-size: 1.25rem;
        }
        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        .additional-link {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }
        .additional-link a {
            color: #007bff;
            text-decoration: none;
        }
        .additional-link a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function updateOrderSummary() {
            const plantName = document.getElementById('plant_name').value;
            const quantity = document.getElementById('quantity').value;
            const deliveryDate = document.getElementById('delivery_date').value;
            const selectedSizes = Array.from(document.querySelectorAll('input[name="plastic_size"]:checked'))
                .map(checkbox => checkbox.value)
                .join(', ');

            const summary = `Plant: ${plantName}, Quantity: ${quantity}, Delivery Date: ${deliveryDate}`;
            document.getElementById('order-summary').innerText = summary;
        }
    </script>
</head>

<body>
    
    <div class="container">
        <h1>Order Form</h1>
        <form action="process_order.php" method="POST" oninput="updateOrderSummary()">
            <input type="hidden" name="plant_id" value="<?= htmlspecialchars($plantId); ?>">
            <div class="mb-3">
                <label for="plant_name" class="form-label">Plant Name</label>
                <input type="text" class="form-control" id="plant_name" name="plant_name" value="<?= htmlspecialchars($plantName); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity (Available: <?= htmlspecialchars($availableQuantity); ?>)</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="<?= htmlspecialchars($availableQuantity); ?>" required>
            </div>
            
           
            <div class="mb-3">
                <label for="delivery_date" class="form-label">Preferred Delivery Date</label>
                <input type="date" class="form-control" id="delivery_date" name="delivery_date" required>
            </div>
            <div class="mb-3">
                <label for="customer_name" class="form-label">Your Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>
            <div class="mb-3">
                <label for="customer_email" class="form-label">Your Email</label>
                <input type="email" class="form-control" id="customer_email" name="customer_email" required>
            </div>
            <div class="mb-3">
                <label for="customer_phone" class="form-label">Your Phone Number</label>
                <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required>
            </div>
            <div class="mb-3">
                <label for="comments" class="form-label">Additional Comments</label>
                <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
            </div>
            <h5>Order Summary</h5>
            <p id="order-summary" class="order-summary"></p>
            <button type="submit" class="btn btn-success btn-lg btn-block">Submit Order</button>
            <div class="additional-link">
                <p>Didn't get what you want? <a href="place_order.php">Place an order here</a>.</p>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- Bootstrap JS -->
</body>
</html>
