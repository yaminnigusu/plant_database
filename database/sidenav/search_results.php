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
    <title>Plant Database-search result</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="icon" href="../../images/logo.png" type="image/jpg">
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

.result-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Add shadow for a modern look */
    border-radius: 10px; /* Rounded corners */
    overflow: hidden; /* To ensure the rounded corners work with table */
}

.result-table th,
.result-table td {
    padding: 15px; /* Consistent padding */
    border-bottom: 1px solid #ddd; /* Subtle bottom border */
    text-align: center; /* Center align the text */
}

.result-table th {
    background-color: #28a745; /* Green header background */
    color: #fff; /* White text */
    font-weight: bold;
    font-size: 16px;
    text-transform: uppercase; /* Uppercase headers for a modern touch */
    letter-spacing: 1px; /* Slight letter spacing */
}

.result-table tbody tr:nth-child(odd) {
    background-color: #f2f2f2; /* Light grey background for odd rows */
}

.result-table tbody tr:nth-child(even) {
    background-color: #fff; /* White background for even rows */
}

.result-table tbody tr:hover {
    background-color: #d9f4d2; /* Light green background on hover */
}

.result-table img {
    max-width: 150px; /* Resize images */
    max-height: 100px;
    border-radius: 5px; /* Rounded image corners */
}

/* Adding specific hover effects to the cells */
.result-table td {
    transition: background-color 0.3s ease; /* Smooth hover transition */
}

.result-table th:first-child,
.result-table td:first-child {
    text-align: left; /* Align first column (plant name) to the left */
    padding-left: 20px;
}

.result-table th:last-child,
.result-table td:last-child {
    padding-right: 20px; /* Ensure spacing for the last column */
}

/* Style total row */
.result-table tfoot tr {
    background-color: #28a745; /* Green background for total row */
    color: #fff; /* White text */
    font-weight: bold;
}

.result-table tfoot td {
    padding: 15px;
    font-size: 16px;
    text-align: right; /* Right-align text in total row */
}

/* Optional: Adding animation when the row is hovered */
.result-table tbody tr {
    transition: all 0.2s ease-in-out;
}

.result-table tbody tr:hover {
    transform: scale(1.02); /* Slight scale up on hover */
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

/* Styling the action buttons */
.action-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.action-buttons button {
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 14px;
}

/* Responsive styles */
@media (max-width: 768px) {
    .result-table {
        font-size: 14px; /* Adjust font size for smaller screens */
    }

    .result-table th, .result-table td {
        padding: 10px; /* Reduce padding for smaller screens */
    }

    .container-search-result {
        margin-left: 0; /* Remove left margin on mobile */
    }

    .photo-cell img {
        max-width: 80px; /* Reduce image size on mobile */
        max-height: 80px;
    }

    /* Ensure action buttons are visible on small screens */
    .action-buttons {
        flex-direction: column; /* Stack buttons vertically */
        align-items: center;
    }

    .action-buttons button {
        width: 100%; /* Make buttons take full width on mobile */
    }

    /* Adjust the table layout for mobile devices */
    .result-table th, .result-table td {
        font-size: 12px; /* Smaller text for smaller screens */
    }
    .container-search-result {
        overflow-x: auto; /* Allow horizontal scrolling for the table */
    }

    .result-table {
        width: 100%; /* Ensure the table width is 100% */
    }
}
     
    </style>
</head>

<body  class="w3-light-gray">
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
        <li><a href="../sold.php"><b>Sold Units</b></a></li>
        <li><a href="../manage_users.php"><b>Users</b></a></li>
        <li><a href="../receive_orders.php"><b>Orders</b></a></li>
        <li><a href="../message/view_messages.php"><b>View Messages</b></a></li>
    </ul>
</aside>

<!-- Mobile Side Navigation Toggle -->

<div class="collapse" id="mobileSideNav">
    <aside class="side-nav">
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
            <li><a href="../sold.php"><b>Sold Units</b></a></li>
            <li><a href="../manage_users.php"><b>Users</b></a></li>
            <li><a href="../receive_orders.php"><b>Orders</b></a></li>
            <li><a href="../message/view_messages.php"><b>View Messages</b></a></li>
        </ul>
    </aside>
</div>



<div class="container-search-result">
    <h1>Search Results</h1>

    <?php
// Include database connection config
include("../config.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_plant_id'])) {
    $plantIdToDelete = $_POST['delete_plant_id'];

    // Prepare the delete query
    $deleteSql = "DELETE FROM plants WHERE id = ?";
    $stmtDelete = $conn->prepare($deleteSql);

    if ($stmtDelete) {
        $stmtDelete->bind_param('i', $plantIdToDelete); // Bind plant ID as an integer
        if ($stmtDelete->execute()) {
            echo '<p class="success-message">Plant deleted successfully.</p>';
        } else {
            echo '<p class="error-message">Error deleting plant: ' . $stmtDelete->error . '</p>';
        }
        $stmtDelete->close();
    } else {
        echo '<p class="error-message">Error preparing statement: ' . $conn->error . '</p>';
    }
}


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
        echo '<thead style=""><tr><th>Photo</th><th>Plant Name</th><th>Scientific Name</th><th>Plant Type</th><th>Plastic Size</th><th>Quantity</th><th>plantation date</th><th>Value</th><th>action</th></tr></thead>';
        echo '<tbody>';
        $totalQuantity = 0;
        $totalValue = 0;

        while ($row = $result->fetch_assoc()) {
            $plantId = $row['id'];
            echo '<tr>';
            echo '<td class="photo-cell">';
        
        // Store images as an array
        $photos = explode(',', $row['photo_path']);
        if (count($photos) > 0) {
            echo '<div class="slider-container">';
            echo '<div class="slides">';
            foreach ($photos as $index => $photo) {
                echo '<img src="../uploads/' . htmlspecialchars(trim($photo)) . '" alt="' . htmlspecialchars($row['plant_name']) . '" class="plant-image" style="display: ' . ($index === 0 ? 'block' : 'none') . ';">';
            }
            echo '</div>'; // End of slides
            echo '<div class="d-flex justify-content-center nav-buttons">';
            echo '    <button class="btn btn-outline-secondary nav-button prev me-2 text-white" onclick="plusSlides(event, -1)">&lt;</button>';
            echo '    <button class="btn btn-outline-secondary nav-button next text-white" onclick="plusSlides(event, 1)">&gt;</button>';
            echo '</div>'; // End of nav-buttons
            // End of nav-buttons
            // End of slider-container
        }
        echo '</td>';// Display ID column
            htmlspecialchars($row['plant_name']) . '"></td>';
            echo '<td>' . htmlspecialchars($row['plant_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['scientific_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['plant_type']) . '</td>';
            echo '<td>' . htmlspecialchars($row['plastic_size']) . '</td>';
            echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
            echo '<td>' . htmlspecialchars($row['plantation_date']) . '</td>';
            echo '<td>' . htmlspecialchars($row['value']) . '</td>';
            echo '<td>';
            echo '<a href="../edit.php?id=' . $plantId . '" class="btn btn-primary">Edit</a> ';
            echo '<form method="POST" onsubmit="return confirm(\'Are you sure you want to delete this plant?\');">';
            echo '<input type="hidden" name="delete_plant_id" value="' . $plantId . '">';
            echo '<button type="submit" class="btn btn-danger">Delete</button>';    
            echo '</td>';
            echo '</tr>';

            // Accumulate total quantity and total value
            $totalQuantity += intval($row['quantity']);
            $totalValue += intval($row['value']);
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
        // Display total quantity and total value
        echo '<div class="total-info">';
        echo '<p>Total Quantity: ' . $totalQuantity . '</p>';
        echo '<p>Total Value: ' . $totalValue . '</p>';
        
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
    <script>
function showSlides(slideContainer, slideIndex) {
    const slides = slideContainer.querySelectorAll('.plant-image');
    slides.forEach((slide, index) => {
        slide.style.display = (index === slideIndex) ? 'block' : 'none';
    });
}

function plusSlides(event, n) {
    const slideContainer = event.target.closest('.slider-container'); // Get the closest slider container
    const slides = slideContainer.querySelectorAll('.plant-image');
    let slideIndex = Array.from(slides).findIndex(slide => slide.style.display === 'block'); // Find current slide index

    slideIndex += n; // Change the index by the value of n
    if (slideIndex >= slides.length) {
        slideIndex = 0; // Loop to the first slide
    } else if (slideIndex < 0) {
        slideIndex = slides.length - 1; // Loop to the last slide
    }

    showSlides(slideContainer, slideIndex); // Show current slide for this container
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




