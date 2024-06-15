<?php
include("config.php");

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id = $_POST['id'];
    $plantName = $_POST['plantName'];
    $quantity = $_POST['quantity'];
    $plasticSize = $_POST['plasticSize'];
    $plantationDate = $_POST['plantationDate'];
    $value = $_POST['value'];

    // Update record in the database
    $sql_update = "UPDATE optional_plants SET plant_name = ?, quantity = ?, plastic_size = ?, plantation_date = ?, value = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sisssi", $plantName, $quantity, $plasticSize, $plantationDate, $value, $id);

    if ($stmt->execute()) {
        echo "Record updated successfully";
        echo '<script>window.location.href = "sidenav/cuttings.php";</script>';
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
