<?php
include("../config.php");

$sql_plant_info = "SELECT plant_name, (selling_price - cost_with_additional) AS profit_margin FROM plants";
$result_plant_info = $conn->query($sql_plant_info);

$analyticsData = array();

if ($result_plant_info->num_rows > 0) {
    while ($row = $result_plant_info->fetch_assoc()) {
        $analyticsData[] = array(
            'plantName' => $row['plant_name'],
            'profitMargin' => floatval($row['profit_margin'])
        );
    }
}

$result_plant_info->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($analyticsData);
?>
