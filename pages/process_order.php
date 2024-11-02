<?php
include("../database/config.php");

// Function to sanitize user input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $plantId = sanitizeInput($_POST['plant_id']);
    $plantName = sanitizeInput($_POST['plant_name']);
    $quantity = sanitizeInput($_POST['quantity']);
    $deliveryDate = sanitizeInput($_POST['delivery_date']);
    $customerName = sanitizeInput($_POST['customer_name']);
    $customerEmail = sanitizeInput($_POST['customer_email']);
    $customerPhone = sanitizeInput($_POST['customer_phone']);
    $comments = sanitizeInput($_POST['comments']);
    $transactionNumber = sanitizeInput($_POST['transaction_number']);
    
    // Validate required fields
    if (empty($plantId) || empty($plantName) || empty($quantity) || empty($deliveryDate) || 
        empty($customerName) || empty($customerEmail) || empty($customerPhone)) {
        die("Error: All fields are required.");
    }

    // Handle payment confirmation file upload
    if (isset($_FILES['payment_confirmation']) && $_FILES['payment_confirmation']['error'] == UPLOAD_ERR_OK) {
        $uploadsDir = '../database/images/';
        $paymentFileName = basename($_FILES['payment_confirmation']['name']);
        $paymentFilePath = $uploadsDir . $paymentFileName;

        // Move the uploaded file to the designated folder
        if (!move_uploaded_file($_FILES['payment_confirmation']['tmp_name'], $paymentFilePath)) {
            die("Error: Could not upload payment confirmation file.");
        }
    } else {
        die("Error: Payment confirmation file is required.");
    }

    // Fetch the selling price for the plant
    $stmt = $conn->prepare("SELECT value FROM plants WHERE id = ?");
    $stmt->bind_param("i", $plantId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $sellingPrice = $row['value'];
        $totalCost = $sellingPrice * $quantity; // Calculate total cost
    } else {
        die("Error: Plant not found.");
    }

    $stmt->close();

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO orders (plant_id, plant_name, quantity, delivery_date, customer_name, customer_email, customer_phone, comments, transaction_number, payment_confirmation, total_cost) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isisssssssd", $plantId, $plantName, $quantity, $deliveryDate, $customerName, $customerEmail, $customerPhone, $comments, $transactionNumber, $paymentFilePath, $totalCost);

    // Execute the statement
    if ($stmt->execute()) {
        // Send confirmation email
        $to = $customerEmail;
        $subject = "Order Confirmation";
        $message = "Dear $customerName,\n\nThank you for your order of $quantity $plantName(s).\nDelivery Date: $deliveryDate.\nTotal Cost: $" . number_format($totalCost, 2) . "\n\nBest regards,\nYour Plant Nursery";
        $headers = "From: noreply@yourplantnursery.com";

        mail($to, $subject, $message, $headers);

        // Redirect to a confirmation page with order details
        header("Location: order_confirmation.php?status=success&plant_name=" . urlencode($plantName) . "&quantity=" . urlencode($quantity) . "&delivery_date=" . urlencode($deliveryDate) .  "&transaction_number=" . urlencode($transactionNumber));
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
