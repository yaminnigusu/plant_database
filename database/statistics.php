    <?php
    // statistics.php
    include 'config.php';



    // Total scientific names
    $sqlScientificNames = "SELECT COUNT(DISTINCT scientific_name) AS total_scientific_names FROM plants";
    $resultScientificNames = $conn->query($sqlScientificNames);
    $rowScientificNames = $resultScientificNames->fetch_assoc();
    $totalScientificNames = $rowScientificNames['total_scientific_names'];

    // Total plant varieties
    $sqlPlantVarieties = "SELECT COUNT(DISTINCT plant_name) AS total_plant_varieties FROM plants";
    $resultPlantVarieties = $conn->query($sqlPlantVarieties);
    $rowPlantVarieties = $resultPlantVarieties->fetch_assoc();
    $totalPlantVarieties = $rowPlantVarieties['total_plant_varieties'];
    // totla cost 
    $sqlTotalCost = "SELECT SUM(cost_amount) AS total_cost FROM costs";
    $resultTotalCost = $conn->query($sqlTotalCost);
    $rowTotalCost = $resultTotalCost->fetch_assoc();
    $totalCost = $rowTotalCost['total_cost'];

    // Total cuttings
    $sqlTotalCuttings = "SELECT SUM(quantity) AS total_cuttings FROM optional_plants";
    $resultTotalCuttings = $conn->query($sqlTotalCuttings);
    $rowTotalCuttings = $resultTotalCuttings->fetch_assoc();
    $totalCuttings = $rowTotalCuttings['total_cuttings'];

    // Total quantity from plants
    $sqlTotalQuantity = "SELECT SUM(quantity) AS total_quantity FROM plants";
    $resultTotalQuantity = $conn->query($sqlTotalQuantity);
    $rowTotalQuantity = $resultTotalQuantity->fetch_assoc();
    $totalQuantity = $rowTotalQuantity['total_quantity'];
// total sold units 
    $sqlTotalSoldUnits = "SELECT SUM(quantity_sold) AS total_sold_units FROM sold";
$resultTotalSoldUnits = $conn->query($sqlTotalSoldUnits);
$rowTotalSoldUnits = $resultTotalSoldUnits->fetch_assoc();
$totalSoldUnits = $rowTotalSoldUnits['total_sold_units'];

    // Close connection
    $conn->close();
    ?>
