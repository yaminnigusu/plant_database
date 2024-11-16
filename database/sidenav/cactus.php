<?php
session_start();

// Store the current page in session if not already logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Store the requested URL
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Database-Cactus</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="icon" href="../../images/logo.png" type="image/jpg">
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

    
    </style>
    
    
    
    
</head>
<body>
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
        <li> <a href="../plan/plan.php"><b>Plan</b></a></li>
           <li> <a href="../cost/cost.php"><b>Cost and Analytics</b></a></li>
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

<div class="main-content">
    <h1>Plant Database</h1>

    <!-- Button to toggle form display -->
    <button id="formToggleButton" onclick="toggleFormVisibility()">Add New Data</button>

    <!-- Form to add new plant data (initially hidden) -->
    <form id="plantForm" enctype="multipart/form-data" method="post" action="process_form.php" style="display: none;">
    <div class="form-group">
        <input type="checkbox" id="optionalData" name="optionalData" value="1" onchange="toggleOptionalFields()">
        <label for="optionalData">Add Optional Data</label>
    </div>
    
    <div class="form-group" id="photoGroup">
        <label for="photo">Add Photos (up to 4):</label>
        <input type="file" id="photo" name="photos[]" accept="image/*" class="form-control" multiple onchange="checkFileCount()">
    </div>

    <div class="form-group" id="plantNameGroup">
        <label for="plantName">Common Name:</label>
        <input type="text" id="plantName" name="plantName" class="form-control" required>
    </div>

    <div class="form-group" id="scientificNameGroup">
        <label for="scientificName">Scientific Name:</label>
        <input type="text" id="scientificName" name="scientificName" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="plasticSize">Plastic Size:</label>
        <select id="plasticSize" name="plasticSize" class="form-control">
            <option value="xsmall">X-Small</option>
            <option value="small">Small</option>
            <option value="medium">Medium</option>
            <option value="large">Large</option>
            <option value="xlarge">X-Large</option>
        </select>
    </div>

    <label>Plant Type:</label>
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
            <input type="checkbox" id="indoorPlant" name="plantType[]" value="indoor_plant">
            <label for="indoorPlant">Indoor Plant</label>
        </div>
        <div class="checkbox-item">
            <input type="checkbox" id="herb" name="plantType[]" value="herb">
            <label for="herb">Herb</label>
        </div>
        <label for="is_featured">Mark as Featured:</label>
        <input type="checkbox" name="is_featured" id="is_featured" value="1">
    </div>

    <div class="form-group">
        <label for="plantationDate">Plantation Date:</label>
        <input type="date" id="plantationDate" name="plantationDate" required class="form-control">
    </div>

    <div class="form-group">
        <label for="value">Value:</label>
        <input type="number" id="value" name="value" required class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

    <!-- Display plant database table -->
    <div class="plant-table-section">
    <?php
include("../config.php");

// Fetch plant data where 'succulent' is among the plant types
$sql = "SELECT * FROM plants WHERE FIND_IN_SET('cactus', REPLACE(plant_type, ', ', ',')) > 0";



$result = $conn->query($sql);

if (!$result) {
    // Output any SQL error
    echo "Error executing SQL query: " . $conn->error . "<br>";
    exit; // Exit script if there's an error
}

$totalQuantity = 0;
$totalValue = 0;

if ($result->num_rows > 0) {
    echo '<div class="table-wrapper">';
    echo '<table id="plantTable">';
    echo '<thead><tr><th>Photo</th><th>Common Name</th><th>Scientific Name</th><th>Quantity</th><th>Plastic Size</th><th>Plantation Date</th><th>Plant Type</th><th>Value</th><th>Actions</th></tr></thead>';
    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {
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
        echo '</td>';
        echo '<td>' . htmlspecialchars($row['plant_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['scientific_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
        echo '<td>' . htmlspecialchars($row['plastic_size']) . '</td>';
        echo '<td>' . htmlspecialchars($row['plantation_date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['plant_type']) . '</td>';
        echo '<td>' . htmlspecialchars($row['value']) . '</td>';
        echo '<td class="action-buttons">';
        echo '<a class="actionButton editButton" href="../edit.php?id=' . $row['id'] . '">Edit</a>';
        echo '<a class="actionButton deleteButton" href="?action=delete&id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this record?\')">Delete</a>';  
        echo '</td>';
        echo '</tr>';

        // Update total quantity and total value
        $totalQuantity += intval($row['quantity']);
        $totalValue += intval($row['value']);
    }

    echo '</tbody></table>';
    echo '</div>';
    echo '<div class="total-info">';
    echo '<p>Total Quantity: ' . $totalQuantity . '</p>';
    echo '<p>Total Value: ' . $totalValue . '</p>';
    echo '</div>';
    
} else {
    echo '<p>No plant records found for cactus</p>';
}

// Close result and database connection
$result->close();
$conn->close();
?>

    </div>
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
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
     <script src="../../js/script2.js"></script>

    </body>
    </html>
