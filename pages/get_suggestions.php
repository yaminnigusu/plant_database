<?php
include("../database/config.php"); // Include your database config

$searchTerm = $_GET['search'] ?? '';
$suggestions = [];

if (!empty($searchTerm)) {
    // Escape the search term for safety
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    
    // Query to get matching plant names
    $sql = "SELECT plant_name FROM plants WHERE plant_name LIKE '%$searchTerm%' LIMIT 10";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = htmlspecialchars($row['plant_name']);
        }
    }
}

// Generate HTML for suggestions
foreach ($suggestions as $suggestion) {
    echo "<div class='suggestion-item'>$suggestion</div>";
}

// Close database connection
$conn->close();
?>
