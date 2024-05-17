<?php
include("../config.php");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check database connection
if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Calculate additional cost per plant
$sql_total_costs = "SELECT SUM(cost_amount) AS totalCosts FROM costs";
$result_total_costs = $conn->query($sql_total_costs);
$totalCosts = $result_total_costs->fetch_assoc()['totalCosts'] ?? 0;

$sql_total_quantity = "SELECT SUM(quantity) AS totalQuantity FROM plants";
$result_total_quantity = $conn->query($sql_total_quantity);
$totalQuantity = $result_total_quantity->fetch_assoc()['totalQuantity'] ?? 0;

$additionalCostPerPlant = ($totalCosts > 0 && $totalQuantity > 0) ? ($totalCosts / $totalQuantity) : 0;

// Retrieve plant info
$sql = "SELECT plant_name, SUM(value) AS totalValue, SUM(quantity) AS totalQuantity FROM plants GROUP BY plant_name";
$result = $conn->query($sql);

$analyticsData = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $plantName = $row['plant_name'];
        $totalValue = $row['totalValue'];
        $totalQuantity = $row['totalQuantity'];
        $costPerPlant = ($totalValue / $totalQuantity);
        $costWithAdditional = $costPerPlant + $additionalCostPerPlant;
        $sellingPrice = $costWithAdditional + ($costWithAdditional * 0.4);
        $profitMargin = $sellingPrice - $costWithAdditional;

        $analyticsData[] = array(
            'plantName' => $plantName,
            'profitMargin' => floatval($profitMargin)
        );
    }
} else {
    $analyticsData[] = array(
        'plantName' => 'No Data',
        'profitMargin' => 0
    );
}

if ($result) {
    $result->close();
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($analyticsData);



