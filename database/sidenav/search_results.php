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
// Include database connection config and any necessary files
include("../config.php");

// Clean the output buffer
ob_clean();

// Check if form is submitted to generate the report
if (isset($_GET['report']) && isset($_GET['format']) && $_GET['format'] === 'pdf') {
    // Include TCPDF library
    require_once(__DIR__ . '/vendor/tecnickcom/tcpdf/tcpdf.php');

    // Create new PDF instance
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Le Jardin de Kakoo');

    // Determine the report date range based on selected report type and year
    $reportType = $_GET['report'];
    $year = isset($_GET['year']) ? $_GET['year'] : date('Y'); // Default to current year if not specified

    switch ($reportType) {
        case '3months':
            $startDate = date('Y-m-d', strtotime('-3 months'));
            $endDate = date('Y-m-d'); // Default to today's date
            break;
        case '6months':
            $startDate = date('Y-m-d', strtotime('-6 months'));
            $endDate = date('Y-m-d'); // Default to today's date
            break;
        case 'year':
            $startDate = $year . '-01-01';
            $endDate = $year . '-12-31';
            break;
        case 'all':
            $startDate = '';
            $endDate = '';
            break;
        default:
            // Handle invalid report type
            break;
    }

    // Add a page
    $pdf->AddPage();

    // Set report title
    $reportTitle = ucfirst($reportType) . ' Report (' . $year . ')';
    $html = '<div style="text-align: center; margin-bottom: 20px;">
                <img src="path_to_your_logo.png" alt="Logo" style="max-width: 150px; height: auto; margin-bottom: 10px;">
                <h1 style="font-size: 28px; color: #333; font-weight: bold;">Le Jardin de Kakoo</h1>
                <h2 style="font-size: 24px; color: #555; margin-top: 0;">' . $reportTitle . '</h2>
            </div>';

    // Generate SQL query to fetch detailed plant data for the specified date range
    if ($reportType === 'all') {
        $sql = "SELECT plant_type, plant_name, plastic_size, quantity FROM plants";
        $stmt = $conn->prepare($sql);
    } else {
        $sql = "SELECT plant_type, plant_name, plastic_size, quantity FROM plants WHERE plantation_date BETWEEN ? AND ?";
        $stmt = $conn->prepare($sql);
    }

    if ($stmt) {
        if ($reportType !== 'all') {
            $stmt->bind_param('ss', $startDate, $endDate);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if query was successful
        if ($result->num_rows > 0) {
            // Initialize arrays to store plant data
            $plantsByType = array();
            $totalAllQuantity = 0;

            while ($row = $result->fetch_assoc()) {
                $plantType = htmlspecialchars($row['plant_type']);
                $plantName = htmlspecialchars($row['plant_name']);
                $plasticSize = htmlspecialchars($row['plastic_size']);
                $quantity = htmlspecialchars($row['quantity']);

                // Store plant data into the array by plant type
                if (!isset($plantsByType[$plantType])) {
                    $plantsByType[$plantType] = array('total_quantity' => 0, 'plants' => array());
                }

                // Add plant name, plastic size, and quantity to the corresponding plant type
                $plantsByType[$plantType]['plants'][] = array('name' => $plantName, 'plastic_size' => $plasticSize, 'quantity' => $quantity);
                $plantsByType[$plantType]['total_quantity'] += $quantity;

                // Accumulate total quantity for all plant types
                $totalAllQuantity += $quantity;
            }

            // Start generating HTML for the report
            foreach ($plantsByType as $type => $data) {
                // Add section header for each plant type
                $html .= '<h3 style="font-size: 20px; color: #444; margin-top: 20px;">' . ucfirst($type) . '</h3>';

                // Start table for displaying plant names, plastic size, and quantities
                $html .= '<table style="width: 100%; border-collapse: collapse;">
                            <tr style="background-color: #f2f2f2;">
                                <th style="padding: 10px; text-align: left; color: #333; font-weight: bold; font-size: 16px;">Plant Name</th>
                                <th style="padding: 10px; text-align: center; color: #333; font-weight: bold; font-size: 16px;">Plastic Size</th>
                                <th style="padding: 10px; text-align: center; color: #333; font-weight: bold; font-size: 16px;">Quantity</th>
                            </tr>';

                foreach ($data['plants'] as $plant) {
                    $html .= '<tr>
                                <td style="padding: 10px; border-bottom: 1px solid #ddd; color: #333; font-weight: bold; font-size: 14px;">' . $plant['name'] . '</td>
                                <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center; color: #333; font-weight: bold; font-size: 14px;">' . $plant['plastic_size'] . '</td>
                                <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center; color: #333; font-weight: bold; font-size: 14px;">' . $plant['quantity'] . '</td>
                              </tr>';
                }

                // Display total quantity for this plant type
                $html .= '<tr>
                            <td colspan="3" style="padding: 10px; text-align: right; font-weight: bold; font-size: 16px;">Total: ' . $data['total_quantity'] . '</td>
                          </tr>';

                $html .= '</table>'; // Close table for this plant type
            }

            // Display total quantity for all plant types
            $html .= '<p style="text-align: center; font-weight: bold; margin-top: 20px; font-size: 22px; color: #333;">Total Quantity for All Plant Types: ' . $totalAllQuantity . '</p>';
        } else {
            $html .= '<p style="text-align: center;">No data found for the selected date range.</p>';
        }

        // Close statement
        $stmt->close();
    } else {
        $html .= '<p style="text-align: center; color: red;">Error executing SQL query: ' . $conn->error . '</p>';
    }

    // Output HTML content to PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Close and output PDF document
    $pdf->Output('plant_report.pdf', 'I');
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
        /* Ensure proper layout for main content and side nav */
.container-search-result {
    padding: 20px;
    margin-left: 220px; /* Adjust to match side nav width */
}

.side-nav {
    width: 200px; /* Adjust width as needed */
    background-color: #f8f9fa; /* Optional: Set background color for side nav */
    padding-top: 20px; /* Adjust top padding */
    position: fixed;
    top: 80px; /* Adjust to match header height */
    left: 0;
    height: calc(100vh - 80px); /* Fill remaining viewport height */
    overflow-y: auto; /* Enable vertical scrolling for side nav */
}

.result-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.result-table th,
.result-table td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: center;
}

.result-table th {
    background-color: #f0f0f0;
    font-weight: bold;
}

.result-table tbody tr:nth-child(odd) {
    background-color: #d9f4d2; /* Light green background for odd rows */
}

.result-table tbody tr:nth-child(even) {
    background-color: #ffffff; /* White background for even rows */
}


.result-table img {
    max-width: 200px;
    max-height: 150px;
    
}
/* Styling for total info section */
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
                <a href="database.php">Database</a>
                <div class="col-auto">
                <button id="login-icon" onclick="window.location.href='../logout.php';" aria-label="Login" class="btn btn-success">Logout</button>
                </div>
            </nav>
        </div>
    </header>

    <aside class="side-nav" id="sideNav">
        <ul>
            <li><a href="../database.php"><b>Home</b></a></li>
            <li><a href="home.php"><b>Search</b></a></li>
            <li class="has-submenu">
                <a href="#"><b>Plants</b></a>
                <ul class="submenu">
                    <li><a href="tress.php">Trees</a></li>
                    <li><a href="shrubs.php">Shrubs</a></li>
                    <li><a href="ferns.php">Ferns</a></li>
                    <li><a href="climbers.php">Climbers</a></li>
                    <li><a href="waterplants.php">Water Plants</a></li>
                    <li><a href="palms.php">Palms</a></li>
                    <li><a href="cactus.php">Cactus</a></li>
                    <li><a href="succulent.php">Succulent</a></li>
                    <li><a href="annuals.php">Annuals</a></li>
                    <li><a href="perinnals.php">Perennials</a></li>
                    <li><a href="indoorplants.php">Indoor Plants</a></li>
                    <li><a href="herbs.php">Herbs</a></li>
                </ul>
            </li>
            <li><a href="cuttings.php"><b>Cuttings</b></a></li>
            <li><a href="../plan/plan.php"><b>Plan</b></a></li>
            <li><a href="../cost/cost.php"><b>Cost and Analytics</b></a></li>
            <li><a href="../sold.php"><b>sold units</b></a></li>
            <li><a href="manage_users.php"><b>users</b></a></li>
        </ul>
    </aside>



<div class="container-search-result">
    <h1>Search Results</h1>

    <?php
// Include database connection config
include("../config.php");

// Get search parameters from the form
$plantName = $_GET['plantName'] ?? '';
$scientificName = $_GET['scientificName'] ?? '';
$plasticSize = $_GET['plasticSize'] ?? '';
$plantationMonth = $_GET['plantationMonth'] ?? '';
$plantationYear = $_GET['plantationYear'] ?? '';
$plantTypes = $_GET['plantType'] ?? [];

// Construct SQL query based on search criteria
$sql = "SELECT * FROM plants WHERE 1=1";

$bindParams = [];

if (!empty($plantName)) {
    $sql .= " AND plant_name LIKE ?";
    $bindParams[] = '%' . $plantName . '%';
}

if (!empty($scientificName)) {
    $sql .= " AND scientific_name LIKE ?";
    $bindParams[] = '%' . $scientificName . '%';
}

if (!empty($plasticSize)) {
    $sql .= " AND plastic_size = ?";
    $bindParams[] = $plasticSize;
}

if (!empty($plantationMonth) && !empty($plantationYear)) {
    $sql .= " AND MONTH(plantation_date) = ? AND YEAR(plantation_date) = ?";
    $bindParams[] = $plantationMonth;
    $bindParams[] = $plantationYear;
}

if (!empty($plantTypes)) {
    $placeholders = str_repeat('?,', count($plantTypes) - 1) . '?';
    $sql .= " AND (";

    foreach ($plantTypes as $index => $plantType) {
        if ($index > 0) {
            $sql .= " OR ";
        }
        $sql .= "plant_type LIKE ?";
        $bindParams[] = '%' . $plantType . '%'; // Use LIKE to match plant types containing the selected value
    }

    $sql .= ")";
}

// Prepare the SQL query
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters for prepared statement if there are any bindParams
if (!empty($bindParams)) {
    $bindParamsTypes = str_repeat('s', count($bindParams)); // Generate type string for bind_param
    $stmt->bind_param($bindParamsTypes, ...$bindParams); // Bind parameters to the statement
}

// Execute the SQL query
if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Display search results...
        echo '<table class="result-table">';
        echo '<thead style=""><tr><th>Photo</th><th>Plant Name</th><th>Scientific Name</th><th>Plant Type</th><th>Plastic Size</th><th>Quantity</th><th>Value</th></tr></thead>';
        echo '<tbody>';
        $totalQuantity = 0;
        $totalValue = 0;

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td class="photo-cell"><img src="../uploads/' . htmlspecialchars($row['photo_path']) . '" width="200px" height="150px" alt="' . htmlspecialchars($row['plant_name']) . '"></td>'; // Display ID column
            htmlspecialchars($row['plant_name']) . '"></td>';
            echo '<td>' . htmlspecialchars($row['plant_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['scientific_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['plant_type']) . '</td>';
            echo '<td>' . htmlspecialchars($row['plastic_size']) . '</td>';
            echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
            echo '<td>' . htmlspecialchars($row['value']) . '</td>';
            echo '</tr>';

            // Accumulate total quantity and total value
            $totalQuantity += intval($row['quantity']);
            $totalValue += intval($row['value']);
        }

        echo '</tbody>';
        echo '</table>';

        // Display total quantity and total value
        echo '<div class="total-info">';
        echo '<p>Total Quantity: ' . $totalQuantity . '</p>';
        echo '<p>Total Value: ' . $totalValue . '</p>';
        echo '</div>';
    } else {
        echo '<p>No results found.</p>';
    }
} else {
    echo 'Query execution failed.';
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
<a href="home.php" style="margin-top: 10px; padding: 10px 20px; background-color: #4CAF50; color: #fff; text-decoration: none; border-radius: 5px;">Back to Advanced Search</a>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../../js/script2.js"></script>
</body>
</html>




