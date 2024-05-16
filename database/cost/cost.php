<?php
// Include database connection file
include("../config.php");

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    // Sanitize the ID to prevent SQL injection
    $costId = mysqli_real_escape_string($conn, $_GET['id']);

    // SQL to delete cost from the 'costs' table based on the provided ID
    $sql_delete_cost = "DELETE FROM costs WHERE id = '$costId'";

    // Execute the delete query
    if ($conn->query($sql_delete_cost) === TRUE) {
        // Redirect back to the cost.php page after successful deletion
        header("Location: cost.php");
        exit();
    } else {
        // Display an error message if the deletion fails
        echo "Error deleting record: " . $conn->error;
        exit(); // Terminate script to prevent further output
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Database</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="icon" href="../images/logo.png" type="image/jpg">
    <link rel="stylesheet" href="../styles.css">
    <style>
        /* Custom CSS for specific styling */
        .checkbox-item {
            margin-right: 20px;
        }

        .photo-cell img {
            max-width: 100px;
            max-height: 100px;
        }
        .total-info {
    margin-top: 20px; /* Add space above the total info section */
    text-align: center; /* Center-align the content */
    background-color: #f0f0f0; /* Background color for the total info section */
    padding: 15px; /* Add padding around the content */
}

.total-info p {
    font-size: 18px; /* Increase font size for total quantity and total value */
    margin-bottom: 10px; /* Add space below each paragraph */
}
.submenu {
    display: none;
}


    </style>
</head>

<body  class="w3-light-gray">
    <header class="sticky-top">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h1>Le Jardin de Kakoo</h1>
                </div>
                <div class="col-auto">
                <button id="navToggleButton" onclick="toggleNavVisibility()" class="btn btn-dark d-block d-lg-none">Menu</button>
            </div>
                
            </div>
            <nav>
                <a href="../../pages/home.php">Home</a>
                <a href="../../pages/shop.php">Shop</a>
                <a href="../../pages/about.php">About Us</a>
                <a href="../../pages/contactus.php">Contact Us</a>
                <a href="../database.php">Database</a>
                <div class="col-auto">
                    <button id="login-icon" onclick="toggleLoginForm()" aria-label="Login" class="btn btn-success">Login</button>
                </div>
            </nav>
        </div>
    </header>

    <aside class="side-nav" id="sideNav">
        <ul>
            <br>
            <br>
            
            <li><a href="../database.php"><b>Home</b></a></li>
            <li><a href="../sidenav/home.php"><b>Search</b></a></li>
            <li class="has-submenu">
                <a href="#"><b>Plants</b></a>
                <ul class="submenu">
                    <li><a href="../sidenav/tress.php">Trees</a></li>
                    <li><a href="../sidenav/shrubs.php">Shrubs</a></li>
                    <li><a href="../sidenav/ferns.php">Ferns</a></li>
                    <li><a href="../sidenav/climbers.php">Climbers</a></li>
                    <li><a href="../sidenav/waterplants.php">Water Plants</a></li>
                    <li><a href="../sidenav/palms.php">Palms</a></li>
                    <li><a href="../sidenav/cactus.php">Cactus</a></li>
                    <li><a href="../sidenav/succulent.php">Succulent</a></li>
                    <li><a href="../sidenav\annuals.php">Annuals</a></li>
                    <li><a href="sidenav/perinnals.php">Perennials</a></li>
                    <li><a href="sidenav/indoorplants.php">Indoor Plants</a></li>
                    <li><a href="sidenav/herbs.php">Herbs</a></li>
                </ul>
            </li>
           <li> <a href="../plan/plan.php"><b>Plan</b></a></li>
           <li> <a href="../cost/cost.php"><b>Cost and Analytics</b></a></li>
        </ul>
       
    </aside>
    <div class="main-content">
    <h1>Costs & Analytics</h1>
        <h2>Register Costs</h2>
        <!-- Form to register costs -->
        <form id="costForm" action="process_costs.php" method="POST">
            <div class="form-group">
                <label for="costDescription">Cost Description:</label>
                <input type="text" class="form-control" id="costDescription" name="costDescription" required>
            </div>
            <div class="form-group">
                <label for="costAmount">Cost Amount:</label>
                <input type="number" class="form-control" id="costAmount" name="costAmount" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <hr>
        <?php
// Include database connection file
include("../config.php");


// Retrieve all costs from the 'costs' table
$sql_costs = "SELECT id, description, cost_amount, created_at FROM costs";
$result_costs = $conn->query($sql_costs);

if ($result_costs->num_rows > 0) {
    echo '<h2>All Costs</h2>';
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Description</th>';
    echo '<th>Amount</th>';
    echo '<th>Date</th>';
    echo '<th>Action</th>'; // Add new column for action buttons
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($row = $result_costs->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['description']) . '</td>';
        echo '<td>' . number_format($row['cost_amount'], 2) . ' Birr</td>';
        echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
        echo '<td>';
        
        // Edit button (links to edit.php with cost ID as parameter)
        echo '<a href="edit.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm">Edit</a>';
        
        // Delete button (links to delete.php with cost ID as parameter)
        echo '<a href="cost.php?action=delete&id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this cost?\')">Delete</a>';

        
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p>No costs found.</p>';
}

// Close result set and database connection
$result_costs->close();
$conn->close();
?>


       
<br>
<br>
<?php
include("../config.php");

// Calculate total costs from the 'costs' table
$sql_total_costs = "SELECT SUM(cost_amount) AS totalCosts FROM costs";
$result_total_costs = $conn->query($sql_total_costs);
$totalCosts = $result_total_costs->fetch_assoc()['totalCosts'] ?? 0; // Total costs from 'costs' table

// Calculate total quantity of all plants
$sql_total_quantity = "SELECT SUM(quantity) AS totalQuantity FROM plants";
$result_total_quantity = $conn->query($sql_total_quantity);
$totalQuantity = $result_total_quantity->fetch_assoc()['totalQuantity'] ?? 0; // Total quantity of all plants

// Calculate additional cost per plant by evenly distributing the total costs
$additionalCostPerPlant = ($totalCosts > 0 && $totalQuantity > 0) ? ($totalCosts / $totalQuantity) : 0;

// Retrieve distinct plant names, total value, and total quantity from the 'plants' table
$sql_plant_info = "SELECT plant_name, SUM(value) AS totalValue, SUM(quantity) AS totalQuantity FROM plants GROUP BY plant_name";
$result_plant_info = $conn->query($sql_plant_info);

if ($result_plant_info->num_rows > 0) {
    echo '<h2>Profit Margins for Each Plant</h2>';
    echo '<table id="plantTable"><tr><th>Plant Name</th><th>Cost per Plant</th><th>Additional Cost per Plant</th><th>Selling Price</th><th>Profit</th></tr>';
    echo '<tbody>';

    while ($row = $result_plant_info->fetch_assoc()) {
        $plantName = $row['plant_name'];
        $totalValue = $row['totalValue']; // Total value (total cost) of the plant
        $totalQuantity = $row['totalQuantity']; // Total quantity of the plant

        // Calculate cost per plant including the additional cost
        $costPerPlant = ($totalValue / $totalQuantity);
        $costWithAdditional = $costPerPlant + $additionalCostPerPlant;

        // Calculate selling price for the plant with a 40% profit margin
        $sellingPrice = $costWithAdditional + ($costWithAdditional * 0.4); // Selling price with profit margin
        $profit = $sellingPrice - $costWithAdditional; // Profit per plant
        

        echo '<tr>';
        echo '<td>' . htmlspecialchars($plantName) . '</td>';
        echo '<td>' . number_format($costWithAdditional, 2) . ' Birr</td>';
        echo '<td>' . number_format($additionalCostPerPlant, 2) . ' Birr</td>';
        echo '<td>' . number_format($sellingPrice, 2) . ' Birr</td>';
        echo '<td>' . number_format($profit, 2) . ' Birr</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo '<p>No plant names found.</p>';
}

// Close result sets and database connection
$result_total_costs->close();
$result_total_quantity->close();
$result_plant_info->close();
$conn->close();
?>

<h2>Analytics</h2>
        <!-- Display analytics here (e.g., charts, data tables) -->
        

<canvas id="analyticsChart"></canvas>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
 
    <script src="script.js"></script>
    <script src="../../js/script2.js"></script>
     <!-- Include any custom JavaScript -->
</body>

</html>

