<?php
session_start();

// Store the current page in session if not already logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Store the requested URL
    header("Location: ../login.php"); // Redirect to login page if not logged in
    exit();
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

        

/* Target the checkbox container */
.checkbox-container {
    display: flex;
    flex-wrap: wrap; /* Allow items to wrap to the next line if needed */
}

.checkbox-item {
    margin-right: 20px;
    display: flex;
    align-items: center;
}

.checkbox-item input[type="checkbox"] {
    margin-right: 5px;
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

    
    </style>
    
    
    
    
</head>
<body>
<header class="sticky-top">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h1>Le Jardin de Kakoo</h1>
                </div>
                <div class="col-auto">
                
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
        <br>
        <br>
        <br>
    <li><a href="../database.php"><b>Home</b></a></li>
        <li><a href="home.php"><b>Search</b></a></li>
        <li class="has-submenu">
            <a href="#" ><b>Plants</b></a>
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
        <li> <a href="../plan/plan.php"><b>Plan</b></a></li>
           <li> <a href="../cost/cost.php"><b>Cost and Analytics</b></a></li>
           <li><a href="../sold.php"><b>Sold Units</b></a></li>
        <li><a href="../manage_users.php"><b>Users</b></a></li>
        <li><a href="../receive_orders.php"><b>Orders</b></a></li>
        <li><a href="../message/view_messages.php"><b>View Messages</b></a></li>
    </ul>
</aside>

<div class="main-content">
    <h1>Plant Database</h1>

    <!-- Button to toggle form display -->
    <button id="formToggleButton" onclick="toggleFormVisibility()">Add New Data</button>

    <!-- Form to add new plant data (initially hidden) -->
    <form id="plantForm" enctype="multipart/form-data" method="post" action="../process_form.php" style="display: none;">
        <div class="form-group">
            <label for="photo">Add Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*" not required>
        </div>
        <div class="form-group">
            <label for="plantName">Common Name:</label>
            <input type="text" id="plantName" name="plantName" required>
        </div>
        <div class="form-group">
            <label for="scientificName">Scientific Name:</label>
            <input type="text" id="scientificName" name="scientificName" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
        </div>
        <div class="form-group">
            <label for="plasticSize">Plastic Size:</label>
            <select id="plasticSize" name="plasticSize" required>
                <option value="xsmall">X-Small</option>
                <option value="small">Small</option>
                <option value="medium">Medium</option>
                <option value="large">Large</option>
                <option value="xlarge">X-Large</option>
            </select>
        </div>
        <label>Plant Type:</label><br>
    <div class="form-group checkbox-container">
    
    
    <div class="checkbox-item">
        <input type="checkbox" id="tree" name="plantType[]" value="tree">
        <label for="tree">Tree</label>
    </div>
    
    <div class="checkbox-item">
        <input type="checkbox" id="shrub" name="plantType[]" value="shrub">
        <label for="shrub">Shrub</label>
    </div>
    
    <div class="checkbox-item">
        <input type="checkbox" id="fern" name="plantType[]" value="fern">
        <label for="fern">Fern</label>
    </div>
    
    <div class="checkbox-item">
        <input type="checkbox" id="climber" name="plantType[]" value="climber">
        <label for="climber">Climber</label>
    </div>
    
    <div class="checkbox-item">
        <input type="checkbox" id="waterPlant" name="plantType[]" value="water_plant">
        <label for="waterPlant">Water Plant</label>
    </div>
    
    <div class="checkbox-item">
        <input type="checkbox" id="palm" name="plantType[]" value="palm">
        <label for="palm">Palm</label>
    </div>
    
    <div class="checkbox-item">
        <input type="checkbox" id="cactus" name="plantType[]" value="cactus">
        <label for="cactus">Cactus</label>
    </div>
    
    <div class="checkbox-item">
        <input type="checkbox" id="succulent" name="plantType[]" value="succulent">
        <label for="succulent">Succulent</label>
    </div>
    
    <div class="checkbox-item">
        <input type="checkbox" id="annual" name="plantType[]" value="annual">
        <label for="annual">Annual</label>
    </div>
    
    <div class="checkbox-item">
        <input type="checkbox" id="perennial" name="plantType[]" value="perennial">
        <label for="perennial">Perennial</label>
    </div>
    
    <div class="checkbox-item">
        <input type="checkbox" id="indoorPlant" name="plantType[]" value="indoorplant">
        <label for="indoorPlant">Indoor Plant</label>
    </div>
    
    <div class="checkbox-item">
        <input type="checkbox" id="herb" name="plantType[]" value="herb">
        <label for="herb">Herb</label>
    </div>
</div>



        <div class="form-group">
            <label for="plantationDate">Plantation Date:</label>
            <input type="date" id="plantationDate" name="plantationDate" required>
        </div>
        <div class="form-group">
            <label for="value">Value:</label>
            <input type="number" id="value" name="value" required>
        </div>
        <button type="submit">Submit</button>
    </form>

    <!-- Display plant database table -->
    <div class="plant-table-section">
    <?php
include("../config.php");

// Fetch plant data where 'perennial' is among the plant types
$sql = "SELECT * FROM plants WHERE FIND_IN_SET('perennial', REPLACE(plant_type, ', ', ',')) > 0";

$result = $conn->query($sql);

if (!$result) {
    // Output any SQL error
    echo "Error executing SQL query: " . $conn->error . "<br>";
    exit; // Exit script if there's an error
}

$totalQuantity = 0;
$totalValue = 0;

if ($result->num_rows > 0) {
    echo '<table id="plantTable">';
    echo '<thead><tr><th>Photo</th><th>Common Name</th><th>Scientific Name</th><th>Quantity</th><th>Plastic Size</th><th>Plantation Date</th><th>Plant Type</th><th>Value</th><th>Actions</th></tr></thead>';
    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td class="photo-cell"><img src="../uploads/' . htmlspecialchars($row['photo_path']) . '" alt="' . htmlspecialchars($row['plant_name']) . '"></td>';
        echo '<td>' . htmlspecialchars($row['plant_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['scientific_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
        echo '<td>' . htmlspecialchars($row['plastic_size']) . '</td>';
        echo '<td>' . htmlspecialchars($row['plantation_date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['plant_type']) . '</td>'; // Display Plant Type
        echo '<td>' . htmlspecialchars($row['value']) . '</td>';
        echo '<td class="action-buttons">';
        echo '<a class="actionButton editButton" href="../edit.php?id=' . $row['id'] . '">Edit</a>';
        echo '<a class="actionButton deleteButton" href="database.php?action=delete&id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this record?\')">Delete</a>';
        echo '</td>';
        echo '</td>';
        echo '</tr>';

        // Update total quantity and total value
        $totalQuantity += intval($row['quantity']);
        $totalValue += intval($row['value']);
    }

    echo '</tbody></table>';
    echo '<div class="total-info">';
    echo '<p>Total Quantity: ' . $totalQuantity . '</p>';
    echo '<p>Total Value: ' . $totalValue . '</p>';
    echo '</div>';
} else {
    echo '<p>No plant records found for perennial</p>';
}

// Close result and database connection
$result->close();
$conn->close();
?>

    </div>

    <script>
        function toggleFormVisibility() {
            const plantForm = document.getElementById('plantForm');
            if (plantForm.style.display === 'none') {
                plantForm.style.display = 'block';
            } else {
                plantForm.style.display = 'none';
            }
        }
    </script>
     <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../../js/script2.js"></script>

    </body>
    </html>
