<?php
session_start();

// Store the current page in session if not already logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Store the requested URL
    header("Location: ../login.php"); // Redirect to login page if not logged in
    exit();
}
?>
<?php
include("../config.php");

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $costId = mysqli_real_escape_string($conn, $_GET['id']);
    $sql_delete_cost = "DELETE FROM costs WHERE id = '$costId'";
    if ($conn->query($sql_delete_cost) === TRUE) {
        header("Location: cost.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
        exit();
    }
}

// Get total cost sum
$sql_total_costs = "SELECT SUM(cost_amount) AS totalCosts FROM costs";
$result_total_costs = $conn->query($sql_total_costs);
$totalCosts = $result_total_costs->fetch_assoc()['totalCosts'] ?? 0;
$result_total_costs->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cost and Analytics</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="icon" href="../../images/logo.png" type="image/jpg">
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .checkbox-item { margin-right: 20px; }
        .photo-cell img { max-width: 100px; max-height: 100px; }
        .total-info { margin-top: 20px; text-align: center; background-color: #f0f0f0; padding: 15px; }
        .total-info p { font-size: 18px; margin-bottom: 10px; }
        .submenu { display: none; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a { display: inline-block; padding: 5px 10px; margin: 0 5px; border: 1px solid #ccc; border-radius: 3px; text-decoration: none; color: #333; }
        .pagination a:hover { background-color: #f0f0f0; }
    </style>
</head>
<body class="w3-light-gray">
    <header class="sticky-top">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col"><h1>Le Jardin de Kakoo</h1></div>
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
                <button id="login-icon" onclick="window.location.href='../logout.php';" aria-label="Login" class="btn btn-success">Logout</button>
                </div>
            </nav>
        </div>
    </header>
    <aside class="side-nav" id="sideNav">
        <ul>
            <br><br>
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
                    <li><a href="../sidenav/annuals.php">Annuals</a></li>
                    <li><a href="../sidenav/perinnals.php">Perennials</a></li>
                    <li><a href="../sidenav/indoorplants.php">Indoor Plants</a></li>
                    <li><a href="../sidenav/herbs.php">Herbs</a></li>
                </ul>
            </li>
            <li><a href="../sidenav/cuttings.php"><b>Cuttings</b></a></li>
            <li><a href="../plan/plan.php"><b>Plan</b></a></li>
            <li><a href="cost.php"><b>Cost and Analytics</b></a></li>
            <li><a href="../sold.php"><b>sold units</b></a></li>
            <li><a href="../manage_users.php"><b>users</b></a></li>
        </ul>
    </aside>
    <div class="main-content">
        <h1>Costs & Analytics</h1>
        <h2>Register Costs</h2>
        <form id="costForm" action="process_costs.php" method="POST">
            <div class="form-group">
                <label for="costDescription">Cost Description:</label>
                <input type="text" class="form-control" id="costDescription" name="costDescription" required>
            </div>
            <div class="form-group">
                <label for="costAmount">Cost Amount:</label>
                <input type="number" class="form-control" id="costAmount" name="costAmount" required>
            </div>
            <div class="form-group">
                <label for="costCategory">Cost Category:</label>
                <select class="form-control" id="costCategory" name="costCategory" required>
                    <option value="asset">Asset</option>
                    <option value="capital">Capital</option>
                    <option value="expense">Expense</option>
                    <option value="liability">Liability</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <hr>
        
        <?php
        include("../config.php");

        $sql_costs = "SELECT id, description, cost_amount, created_at, category FROM costs";
        $result_costs = $conn->query($sql_costs);

        if ($result_costs->num_rows > 0) {
            echo '<h2>All Costs</h2>';
            echo '<table class="table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Description</th>';
            echo '<th>Amount</th>';
            echo '<th>Date</th>';
            echo '<th>Category</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $result_costs->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                echo '<td>' . number_format($row['cost_amount'], 2) . ' Birr</td>';
                echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                echo '<td>' . htmlspecialchars($row['category']) . '</td>';
                echo '<td>';
                echo '<a href="edit.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm">Edit</a>';
                echo '<a href="cost.php?action=delete&id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this cost?\')">Delete</a>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No costs found.</p>';
        }

        $result_costs->close();
        $conn->close();
        ?>

        <div class="total-info">
            <p><strong>Total Costs:</strong> <?php echo number_format($totalCosts, 2); ?> Birr</p>
        </div>

        <br><br>
        <?php
        include("../config.php");

        $search = $_GET['search'] ?? '';
        $search = $conn->real_escape_string($search);
        $whereClause = '';

        if (!empty($search)) {
            $whereClause = "WHERE plant_name LIKE '%$search%'";
        }

        // Determine total number of records with search condition
        $sql_total_records = "SELECT COUNT(*) AS totalRecords FROM plants $whereClause";
        $result_total_records = $conn->query($sql_total_records);
        $totalRecords = $result_total_records->fetch_assoc()['totalRecords'] ?? 0;

        $limit = 15; // Number of records per page
        $totalPages = ceil($totalRecords / $limit);

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $start = ($page - 1) * $limit;

        $sql_total_costs = "SELECT SUM(cost_amount) AS totalCosts FROM costs";
        $result_total_costs = $conn->query($sql_total_costs);
        $totalCosts = $result_total_costs->fetch_assoc()['totalCosts'] ?? 0;

        $sql_total_quantity = "SELECT SUM(quantity) AS totalQuantity FROM plants";
        $result_total_quantity = $conn->query($sql_total_quantity);
        $totalQuantity = $result_total_quantity->fetch_assoc()['totalQuantity'] ?? 0;

        $additionalCostPerPlant = ($totalCosts > 0 && $totalQuantity > 0) ? ($totalCosts / $totalQuantity) : 0;

        $sql_plant_info = "SELECT plant_name, SUM(value) AS totalValue, SUM(quantity) AS totalQuantity 
                           FROM plants $whereClause 
                           GROUP BY plant_name 
                           LIMIT $start, $limit";
        $result_plant_info = $conn->query($sql_plant_info);

        echo '<h2>Profit Margins for Each Plant</h2>';
        echo '<form method="GET" action="">';
        echo '<input type="text" name="search" id="plantSearchInput" placeholder="Search plant names..." value="' . htmlspecialchars($_GET['search'] ?? '') . '">';
        echo '<input type="submit" value="Search">';
        echo '</form>';

        if ($result_plant_info->num_rows > 0) {
            echo '<table id="plantTable" class="table">';
            echo '<thead>';
            echo '<tr><th>Plant Name</th><th>Cost per Plant</th><th>Additional Cost per Plant</th><th>Selling Price</th><th>Profit</th></tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $result_plant_info->fetch_assoc()) {
                $plantName = $row['plant_name'];
                $totalValue = $row['totalValue'];
                $totalQuantity = $row['totalQuantity'];

                $costPerPlant = ($totalValue / $totalQuantity);
                $costWithAdditional = $costPerPlant + $additionalCostPerPlant;

                $sellingPrice = $costWithAdditional + ($costWithAdditional * 0.4);
                $profit = $sellingPrice - $costWithAdditional;

                echo '<tr>';
                echo '<td>' . htmlspecialchars($plantName) . '</td>';
                echo '<td>' . number_format($costWithAdditional, 2) . ' Birr</td>';
                echo '<td>' . number_format($additionalCostPerPlant, 2) . ' Birr</td>';
                echo '<td>' . number_format($sellingPrice, 2) . ' Birr</td>';
                echo '<td>' . number_format($profit, 2) . ' Birr</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';

            // Pagination links
            echo '<nav>';
            echo '<ul class="pagination">';
            for ($i = 1; $i <= $totalPages; $i++) {
                $active = ($i == $page) ? 'active' : '';
                echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '&search=' . urlencode($search) . '">' . $i . '</a></li>';
            }
            echo '</ul>';
            echo '</nav>';
        } else {
            echo '<p>No plant names found.</p>';
        }

        $result_total_costs->close();
        $result_total_quantity->close();
        $result_plant_info->close();
        $result_total_records->close();

        $conn->close();
        ?>

        <h2>Analytics</h2>
        <canvas id="analyticsChart"></canvas>

        <!-- HTML canvas for the plant types pie chart -->
       

    </div>
    <script src="script.js"></script>
    
    <script src="../../js/script2.js"></script>
</body>
</html>
