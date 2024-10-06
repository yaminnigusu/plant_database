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

    // Validate required fields
    if (empty($plantId) || empty($plantName) || empty($quantity) || empty($deliveryDate) || empty($customerName) || empty($customerEmail) || empty($customerPhone)) {
        die("Error: All fields are required.");
    }

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO orders (plant_id, plant_name, quantity, delivery_date, customer_name, customer_email, customer_phone, comments) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isisssss", $plantId, $plantName, $quantity, $deliveryDate, $customerName, $customerEmail, $customerPhone, $comments);

    // Execute the statement
    if ($stmt->execute()) {
        // Send confirmation email
        $to = $customerEmail;
        $subject = "Order Confirmation";
        $message = "Dear $customerName,\n\nThank you for your order of $quantity $plantName(s).\nDelivery Date: $deliveryDate.\n\nBest regards,\nYour Plant Nursery";
        $headers = "From: noreply@yourplantnursery.com";

        mail($to, $subject, $message, $headers);

        // Redirect to a confirmation page
       // Redirect to a confirmation page with order details
header("Location: order_confirmation.php?status=success&plant_name=" . urlencode($plantName) . "&quantity=" . urlencode($quantity) . "&delivery_date=" . urlencode($deliveryDate));
exit();

    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
