<?php
include("../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $costDescription = $_POST['costDescription'];
    $costAmount = $_POST['costAmount'];

    // Insert cost data into database
    $sql_insert = "INSERT INTO costs (description, cost_amount) VALUES (?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("sd", $costDescription, $costAmount);

    if ($stmt->execute()) {
        echo "Cost registered successfully.";
    } else {
        echo "Error registering cost: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
