<?php
session_start();

// Store the current page in session if not already logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Store the requested URL
    header("Location: ../login.php"); // Redirect to login page if not logged in
    exit();
}

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

// Get total costs, sales, and profits
$sql_total_costs = "SELECT SUM(cost_amount) AS totalCosts FROM costs";
$result_total_costs = $conn->query($sql_total_costs);
$totalCosts = $result_total_costs->fetch_assoc()['totalCosts'] ?? 0;

$sql_total_sales = "SELECT SUM(selling_price * quantity_sold) AS totalSales FROM sold";
$result_total_sales = $conn->query($sql_total_sales);
$totalSales = $result_total_sales->fetch_assoc()['totalSales'] ?? 0;

// Get total plants
$sql_total_plants = "SELECT COUNT(*) AS totalPlants FROM plants";
$result_total_plants = $conn->query($sql_total_plants);
$totalPlants = $result_total_plants->fetch_assoc()['totalPlants'] ?? 0;

// Calculate plants for sale (65% of total plants)
$plantsForSale = $totalPlants * 0.65;

// Calculate profit
$totalProfit = $totalSales - $totalCosts; // Assuming profit is total sales minus total costs

$result_total_costs->close();
$result_total_sales->close();
$result_total_plants->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cost and Analytics</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
    <link rel="icon" href="../../images/logo.png" type="image/jpg">
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

<header class="sticky-top bg-light py-2">
    <div class="container-fluid">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <!-- Logo and Title -->
            <div class="col-auto d-flex align-items-center mb-3 mb-sm-0">
                <img src="../../images/logo.png" alt="Logo" width="50">
                <h1 class="h4 mb-0 ms-2">Le Jardin de Kakoo</h1>
            </div>

            <!-- Navigation and Logout Button -->
            <div class="col-auto d-flex align-items-center">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <!-- Navbar toggler for smaller screens -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!-- Navbar Links -->
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-3">
                            <li class="nav-item"><a class="nav-link" href="../../pages/home.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="../../pages/shop.php">Shop</a></li>
                            <li class="nav-item"><a class="nav-link" href="../../pages/about.php">About Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="../../pages/contactus.php">Contact Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="../database.php">Database</a></li>
                            <button id="login-icon" onclick="window.location.href='../logout.php';" aria-label="Logout" class="btn btn-success ms-3">Logout</button>
                        </ul>
                    </div>
                </nav>
                <!-- Logout Button -->
                <div class="d-lg-none text-end">
    <button class="btn btn-primary mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSideNav" aria-expanded="false" aria-controls="mobileSideNav">
        Menu
    </button>
</div>
            </div>
        </div>
    </div>
</header>


<aside class="side-nav d-lg-block d-none" id="sideNav">
    <ul>
        <br><br><br>
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
        <li><a href="../cost/cost.php"><b>Cost and Analytics</b></a></li>
        <li><a href="../sold.php"><b>Sold Units</b></a></li>
        <li><a href="../manage_users.php"><b>Users</b></a></li>
        <li><a href="../receive_orders.php"><b>Orders</b></a></li>
        <li><a href="view_messages.php"><b>View Messages</b></a></li>
    </ul>
</aside>

<!-- Mobile Side Navigation Toggle -->


<div class="collapse" id="mobileSideNav">
    <aside class="side-nav">
        <ul>
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
            <li><a href="../sold.php"><b>Sold Units</b></a></li>
            <li><a href="../manage_users.php"><b>Users</b></a></li>
            <li><a href="../receive_orders.php"><b>Orders</b></a></li>
            <li><a href="../message/view_messages.php"><b>View Messages</b></a></li>
        </ul>
    </aside>
</div>


<body>
    <div class="main-content">
    <div class="container mt-4">
        <h1>Costs & Analytics</h1>
        <h2>Archived Costs</h2>
<div class="form-group">
    <label for="yearSelect">Select Archive Year:</label>
    <select id="yearSelect" class="form-control" onchange="redirectToArchive(this.value)">
        <option value="">-- Select Year --</option>
        <?php
        $archivePath = __DIR__ . '/archives/';
        if (is_dir($archivePath)) {
            $files = scandir($archivePath);
            foreach ($files as $file) {
                if (preg_match('/^costs_(\d{4})\.csv$/', $file, $matches)) {
                    echo '<option value="' . $file . '">Year ' . $matches[1] . '</option>';
                }
            }
        }
        ?>
    </select>
        
</div>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Costs</h5>
                        <p class="card-text"><?php echo number_format($totalCosts, 2); ?> Birr</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Sales</h5>
                        <p class="card-text"><?php echo number_format($totalSales, 2); ?> Birr</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Total Profit</h5>
                        <p class="card-text"><?php echo number_format($totalProfit, 2); ?> Birr</p>
                    </div>
                </div>
            </div>
        </div>

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

        <h2>All Costs</h2>
        <div class="form-group">
            <label for="search">Search Costs:</label>
            <input type="text" id="search" class="form-control" placeholder="Search by description...">
        </div>
        
        <div class="form-group">
            <label for="categoryFilter">Filter by Category:</label>
            <select id="categoryFilter" class="form-control">
                <option value="">All Categories</option>
                <option value="asset">Asset</option>
                <option value="capital">Capital</option>
                <option value="expense">Expense</option>
                <option value="liability">Liability</option>
            </select>
        </div>

        <button id="filterButton" class="btn btn-secondary">Filter</button>
        <div class="table-wrapper">
        <table class="table" id="costTable">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
</div>
                <?php
                $sql_costs = "SELECT id, description, cost_amount, created_at, category FROM costs";
                $result_costs = $conn->query($sql_costs);

                if ($result_costs->num_rows > 0) {
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
                } else {
                    echo '<tr><td colspan="5">No costs found.</td></tr>';
                }

                $result_costs->close();
                ?>
            </tbody>
        </table>

        <h2>Profit Margins for Each Plant</h2>
        <form method="GET" action="">
            <input type="text" name="search" id="plantSearchInput" placeholder="Search plant names..." class="form-control mb-3" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            <input type="submit" value="Search" class="btn btn-primary">
        </form>

        <div id="plantTableContainer">
            <!-- Plant table will be filled via AJAX -->
        </div>
    </div>
    <script>
    function redirectToArchive(selectedFile) {
        if (selectedFile) {
            window.location.href = 'archives/' + selectedFile;
        }
    }
</script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load the plant data with profit margins
            loadPlantData();

            // Filter and search functionality
            $('#filterButton').click(function() {
                const searchValue = $('#search').val();
                const categoryValue = $('#categoryFilter').val();
                loadCostData(searchValue, categoryValue);
            });

            function loadCostData(search = '', category = '') {
                $.ajax({
                    url: 'fetch_cost_data.php',
                    method: 'GET',
                    data: { search: search, category: category },
                    success: function(data) {
                        $('#costTable tbody').html(data);
                    }
                });
            }

            function loadPlantData() {
                $.ajax({
                    url: 'fetch_plant_data.php',
                    method: 'GET',
                    success: function(data) {
                        $('#plantTableContainer').html(data);
                    }
                });
            }
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>
</html>
