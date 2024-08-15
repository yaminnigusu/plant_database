<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the plant ID and new selling price from the form submission
    $id = $_POST['id'];
    $new_selling_price = $_POST['selling_price'];

    // Update the selling price in the database
    $stmt = $conn->prepare("UPDATE sold SET selling_price = ? WHERE id = ?");
    $stmt->bind_param("di", $new_selling_price, $id);

    if ($stmt->execute()) {
        // Redirect back to the sold.php page after successful update
        header("Location: sold.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
