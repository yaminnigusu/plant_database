<?php
session_start();

include("../database/config.php"); // Include database configuration

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

// Desired profit margin (adjust as needed)
$profitMargin = 0.4; // 20% profit margin

// Retrieve search term, selected plant types, and price range from URL parameters or session
$searchTerm = $_GET['search'] ?? '';
$selectedPlantType = $_GET['plantType'] ?? '';
$selectedPriceRange = $_GET['priceRange'] ?? '';

// If parameters are empty in URL, reset session variables
if (empty($searchTerm) && empty($selectedPlantType) && empty($selectedPriceRange)) {
    unset($_SESSION['search']);
    unset($_SESSION['plantType']);
    unset($_SESSION['priceRange']);
} else {
    // Store query parameters in session for future reference
    $_SESSION['search'] = $searchTerm;
    $_SESSION['plantType'] = $selectedPlantType;
    $_SESSION['priceRange'] = $selectedPriceRange;
}

// Initialize $availablePlantTypes array
$availablePlantTypes = [];

// Construct SQL query to fetch distinct plant types
$plantTypeSql = "SELECT DISTINCT plant_type FROM plants";
$plantTypeResult = $conn->query($plantTypeSql);

if ($plantTypeResult && $plantTypeResult->num_rows > 0) {
    // Populate $availablePlantTypes array with fetched plant types
    while ($row = $plantTypeResult->fetch_assoc()) {
        $availablePlantTypes[] = $row['plant_type'];
    }
}

// Pagination settings
$itemsPerPage = 15; // Number of items to display per page
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Construct SQL query to fetch total count of plants
$countSql = "SELECT COUNT(*) AS total FROM plants WHERE 1=1";

// Add search conditions based on the search term
if (!empty($searchTerm)) {
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    $countSql .= " AND (plant_name LIKE '%$searchTerm%' OR scientific_name LIKE '%$searchTerm%' OR plant_type LIKE '%$searchTerm%')";
}

// Add plant type filter based on selected type
if (!empty($selectedPlantType)) {
    $selectedPlantType = mysqli_real_escape_string($conn, $selectedPlantType);
    $countSql .= " AND plant_type LIKE '%$selectedPlantType%'";
}

// Execute SQL query to fetch total count of plants
$countResult = $conn->query($countSql);
$totalRecords = $countResult->fetch_assoc()['total'];

// Calculate total number of pages
$totalPages = ceil($totalRecords / $itemsPerPage);

// Construct SQL query to fetch plants with pagination
$sql = "SELECT * FROM plants WHERE 1=1";

// Add search conditions based on the search term
if (!empty($searchTerm)) {
    $sql .= " AND (plant_name LIKE '%$searchTerm%' OR scientific_name LIKE '%$searchTerm%' OR plant_type LIKE '%$searchTerm%')";
}

// Add plant type filter based on selected type
if (!empty($selectedPlantType)) {
    $sql .= " AND plant_type LIKE '%$selectedPlantType%'";
}

// Add price range filter based on selected range
if (!empty($selectedPriceRange)) {
    list($minPrice, $maxPrice) = explode('-', $selectedPriceRange);
    $sql .= " AND (value / quantity) BETWEEN $minPrice AND $maxPrice";
}

// Add pagination to SQL query
$sql .= " LIMIT $itemsPerPage OFFSET $offset";

// Execute SQL query to fetch plants
$result = $conn->query($sql);

// Initialize $plants array
$plants = [];

if ($result && $result->num_rows > 0) {
    // Populate $plants array with fetched plant data
    while ($row = $result->fetch_assoc()) {
        $plantTypes = explode(', ', $row['plant_type']);
        $costPerPlant = $row['value'] / $row['quantity'];

        // Calculate selling price including additional cost and profit
        $sellingPrice = $costPerPlant + $additionalCostPerPlant + ($costPerPlant * $profitMargin);

        // Create plant array with required data
        $plants[] = [
            'plant_name' => htmlspecialchars($row['plant_name']),
            'photo_path' => htmlspecialchars($row['photo_path']),
            'quantity' => $row['quantity'],
            'plant_type' => $plantTypes,
            'sellingPrice' => $sellingPrice, // Adjusted selling price
        ];
    }
}

// Close database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Database</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="../images/logo.png" type="image/jpg">
    <link rel="stylesheet" href="../database/styles.css">
    <link rel="stylesheet" href="../css/styles.css">

    <style>
        /* Custom styles */
        .sidebar {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
        .sidebar a {
            display: block;
            margin-bottom: 10px;
        }
        .sidebar a:hover {
            color: #007bff;
        }
        .sidebar {
            position: sticky;
            top: 60px; /* Adjust top position as needed */
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            overflow-y: auto;
            height: calc(100vh - 100px);
        }
        @media (max-width: 992px) {
            .sidebar {
                position: relative; /* Remove sticky behavior on smaller screens */
                top: auto;
            }
        }
    </style>
</head>

<body class="w3-light-gray">
    <header class="sticky-top">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h1>Le Jardin de Kakoo</h1>
                </div>
                <div class="col-auto">
                    <!-- You can add login/logout functionality here -->
                </div>
            </div>
            <nav>
                <a href="../pages/home.php">Home</a>
                <a href="../pages/shop.php">Shop</a>
                <a href="../pages/about.php">About Us</a>
                <a href="../pages/contactus.php">Contact Us</a>
                <a href="../database/database.php">Database</a>
                
                <div class="col-auto">
                    <!-- Add login/logout button or user profile links here -->
                    <button id="login-icon" onclick="toggleLoginForm()" aria-label="Login" class="btn btn-success">Login</button>
                </div>
            </nav>

            <!-- Search Form -->
            
        </div>
    </header>
    
    

    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar with plant types and price range options -->
            <div class="col-md-3">
                <div class="sidebar">
                    <h4>Category</h4>
                    <hr>
                    <!-- Display available plant types -->
                    <?php foreach ($availablePlantTypes as $plantType) : ?>
                        <a href="shop.php?plantType=<?= urlencode($plantType) ?>"><?= htmlspecialchars($plantType) ?></a>
                    <?php endforeach; ?>
                    <hr>
                    <!-- Price range filter based on value per quantity -->
                    <h4>Price Range</h4>
                    <form action="shop.php" method="get">
                        <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                        <input type="hidden" name="plantType" value="<?= htmlspecialchars($selectedPlantType) ?>">
                        <select name="priceRange" class="form-control">
                            <option value="" selected>Select Price Range</option>
                            <option value="0-50" <?= $selectedPriceRange === '0-50' ? 'selected' : '' ?>>Birr 0 - Birr 50</option>
                            <option value="51-100" <?= $selectedPriceRange === '51-100' ? 'selected' : '' ?>>Birr 51 - Birr 100</option>
                            <option value="101-200" <?= $selectedPriceRange === '101-200' ? 'selected' : '' ?>>Birr 101 - Birr 200</option>
                            <option value="201-500" <?= $selectedPriceRange === '201-500' ? 'selected' : '' ?>>Birr 201 - Birr 500</option>
                        </select>
                        <button type="submit" class="btn btn-primary mt-3">Apply</button>
                    </form>
                </div>
            </div>

            <!-- Product Listing Section -->
            <div class="col-md-9">
            <form class="mt-3 mb-4" action="shop.php" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search..." name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
                <div class="row">
                    <?php if (!empty($plants)) : ?>
                        <?php foreach ($plants as $plant) : ?>
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <img src="../database/uploads/<?= htmlspecialchars($plant['photo_path']) ?>" width="100px" height="200px" alt="<?= htmlspecialchars($plant['plant_name']) ?>" class="card-img-top">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($plant['plant_name']) ?></h5>
                                        <p class="card-text">Price: Birr <b><?= number_format($plant['sellingPrice'], 2) ?></b></p>
                                        <p class="card-text">Available Quantity: <?= round($plant['quantity'] * 0.85) ?></p>
                                        <p class="card-text">Plant Type: <?= implode(', ', $plant['plant_type']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="col-md-12">
                            <p>No plants found matching the criteria.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination Links -->
                <?php if ($totalPages > 1) : ?>
                    <div class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1) : ?>
                                <li class="page-item"><a class="page-link" href="shop.php?page=<?= $currentPage - 1 ?>">Previous</a></li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>"><a class="page-link" href="shop.php?page=<?= $i ?>"><?= $i ?></a></li>
                            <?php endfor; ?>
                            <?php if ($currentPage < $totalPages) : ?>
                                <li class="page-item"><a class="page-link" href="shop.php?page=<?= $currentPage + 1 ?>">Next</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <section id="contact" class="mt-4">
        <div class="container">
            <h3>Contact Us</h3>
            <div class="row">
                <div class="col-md-6">
                    <p><a href="tel:0940384999">+251940384999</a></p>
                    <p><a href="mailto:nigusuyamin@gmail.com">nigusuyamin@gmail.com</a></p>
                    <p>Address: Addis Ababa, Bole Japan</p>
                </div>
                <div class="col-md-6 text-center">
                    <a href="https://www.facebook.com/lejardindekakoo?mibextid=ZbWKwL"><i class="fab fa-facebook fa-2x mr-3"></i></a>
                    <a href="https://www.instagram.com/lejardindekakoo?igsh=MWNzdWhwb3EwNDYwMw=="><i class="fab fa-instagram fa-2x mr-3"></i></a>
                    <a href="https://t.me/LeGiardinDeKakoo"><i class="fab fa-telegram fa-2x mr-3"></i></a>
                    <a href="https://wa.me/message/47J4IQW4KT3SL1"><i class="fab fa-whatsapp fa-2x mr-3"></i></a>
                    <a href="https://www.tiktok.com/@plantparadise25?_t=8imWxp8efJd&_r=1"><i class="fab fa-tiktok fa-2x mr-3"></i></a>
                </div>
            </div>
        </div>
    </section>

    <footer class="mt-4">
        <div class="container">
            <p>&copy; 2024 Le Jardin de kakoo</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
