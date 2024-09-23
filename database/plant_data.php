<?php
include 'config.php'; // Include your database connection


// Assuming database connection is already established

$query = "SELECT plant_name, scientific_name, plastic_size, photo_path, SUM(quantity) AS total_quantity
FROM plants 
GROUP BY plant_name, scientific_name, plastic_size, photo_path;
"; // Group by plant name and plastic size

$result = $conn->query($query);
$plants = array();

while ($row = $result->fetch_assoc()) {
    $plants[] = $row; // Store each record
}

echo json_encode($plants);
?>

