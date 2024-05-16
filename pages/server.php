<?php
include("../database/config.php");

$search = $_GET['search'] ?? '';
$typeFilter = $_GET['type'] ?? '';

$sql = "SELECT * FROM plants WHERE 1=1";

if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (plant_name LIKE '%$search%' OR scientific_name LIKE '%$search%')";
}

if (!empty($typeFilter)) {
    $typeFilters = explode(',', $typeFilter);
    $typeConditions = [];

    foreach ($typeFilters as $filter) {
        $filter = trim($filter);
        $typeConditions[] = "FIND_IN_SET('$filter', plant_type)";
    }

    $sql .= " AND (" . implode(' OR ', $typeConditions) . ")";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $plantTypes = explode(', ', $row['plant_type']);
        $valuePerQuantity = $row['value'] / $row['quantity'];

        // Generate HTML content for each plant
        echo '<div class="product-box">';
        echo '<img src="../database/uploads/' . htmlspecialchars($row['photo_path']) . '" alt="' . htmlspecialchars($row['plant_name']) . '" class="product-image">';
        echo '<div class="product-details">';
        echo '<h2>' . htmlspecialchars($row['plant_name']) . '</h2>';
        echo '<p>Price: Birr <b>' . number_format($valuePerQuantity, 2) . '</b></p>';
        echo '<p>Available Quantity: ' . round($row['quantity'] * 0.85) . '</p>';
        echo '<p>Plant Type: ' . implode(', ', $plantTypes) . '</p>';
        echo '</div></div>';
    }
} else {
    echo 'No plants found matching the criteria.';
}

$conn->close();
?>
