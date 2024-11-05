<?php
include("../config.php");

// Step 1: Fetch total quantity of all plants
$sql_total_quantity = "SELECT SUM(quantity) AS total_quantity FROM plants";
$result_quantity = $conn->query($sql_total_quantity);
$total_quantity = 0;

if ($result_quantity && $result_quantity->num_rows > 0) {
    $quantity_row = $result_quantity->fetch_assoc();
    $total_quantity = $quantity_row['total_quantity'];
}

// Step 2: Fetch total cost from the costs table
$sql_cost = "SELECT SUM(cost_amount) AS total_cost_amount FROM costs";
$result_cost = $conn->query($sql_cost);
$total_cost = 0;

if ($result_cost && $result_cost->num_rows > 0) {
    $cost_row = $result_cost->fetch_assoc();
    $total_cost = $cost_row['total_cost_amount'];
}

// Step 3: Calculate average cost per plant
if ($total_quantity > 0) {
    $average_cost_per_plant = $total_cost / $total_quantity; // Average cost for each plant
} else {
    $average_cost_per_plant = 0; // Prevent division by zero
}

// Step 4: Calculate total quantity for sale (65%)
$quantity_for_sale = $total_quantity * 0.65;

// Debugging output
echo "Total Quantity: " . $total_quantity . "<br>";
echo "Total Cost Amount: " . $total_cost . "<br>";
echo "Average Cost Per Plant: " . number_format($average_cost_per_plant, 2) . " Birr<br>";
echo "Quantity for Sale: " . $quantity_for_sale . "<br>";

// Step 5: Fetch plant sales data and calculate profits
$sql_plants = "
    SELECT p.plant_name, 
           p.scientific_name,
           p.value AS plant_value,
           IFNULL(SUM(s.quantity_sold * s.selling_price), 0) AS total_sales
    FROM plants p 
    LEFT JOIN sold s ON p.id = s.plant_id 
    GROUP BY p.plant_name, p.scientific_name, p.value
    ORDER BY p.plant_name"; 

$result_plants = $conn->query($sql_plants);

// Debugging: Output the SQL query for verification
if (!$result_plants) {
    echo "Error executing query: " . $conn->error;
    exit();
}

$total_sales_all = 0;
$total_profit_all = 0;

$output = '<div class="table-responsive">';
$output .= '<table class="table table-bordered table-striped">';
$output .= '<thead><tr><th>Plant Name</th><th>Scientific Name</th><th>Value</th><th>Total Sales</th><th>Total Cost for Sale</th><th>Profit</th></tr></thead><tbody>';

if ($result_plants->num_rows > 0) {
    while ($row = $result_plants->fetch_assoc()) {
        // Calculate profit based on the total cost (not divided)
        $profit = $row['total_sales'] - $total_cost; // Subtract the total cost for all plants

        $output .= '<tr>';
        $output .= '<td>' . htmlspecialchars($row['plant_name']) . '</td>';
        $output .= '<td>' . htmlspecialchars($row['scientific_name']) . '</td>';
        $output .= '<td>' . number_format($row['plant_value'], 2) . ' Birr</td>';  // Include plant value
        $output .= '<td>' . number_format($row['total_sales'], 2) . ' Birr</td>';
        $output .= '<td>' . number_format($total_cost, 2) . ' Birr</td>';  // Total cost for all plants
        $output .= '<td>' . number_format($profit, 2) . ' Birr</td>';
        $output .= '</tr>';
        
        // Accumulate totals
        $total_sales_all += $row['total_sales'];
        $total_profit_all += $profit;
    }
} else {
    $output .= '<tr><td colspan="6">No plant sales data found.</td></tr>';
}

// Totals Row
$output .= '<tfoot><tr>';
$output .= '<td><strong>Total</strong></td>';
$output .= '<td></td>';
$output .= '<td></td>';  // No total for plant value
$output .= '<td><strong>' . number_format($total_sales_all, 2) . ' Birr</strong></td>';
$output .= '<td><strong>' . number_format($total_cost, 2) . ' Birr</strong></td>';  // Total cost for all plants
$output .= '<td><strong>' . number_format($total_profit_all, 2) . ' Birr</strong></td>';
$output .= '</tr></tfoot>';

// Closing the table and outputting the results
$output .= '</tbody></table>';
$output .= '</div>';  // Close the responsive div
echo $output;

// Closing the database connection
$conn->close();
?>
