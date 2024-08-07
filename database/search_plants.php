<?php
// Database connection parameters
include("config.php");

// Get the search query
$query = $_GET['query'] ?? '';

// Sanitize input
$query = $conn->real_escape_string($query);

// Fetch matching plants
$sql = "SELECT id, plant_name, plastic_size, plant_type, quantity FROM plants WHERE plant_name LIKE ? AND quantity > 0";
$stmt = $conn->prepare($sql);
$searchTerm = "%$query%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$plants = [];
while ($row = $result->fetch_assoc()) {
    $plants[] = $row;
}

// Return results as JSON
header('Content-Type: application/json');
echo json_encode($plants);

// Close connection
$conn->close();
?>
