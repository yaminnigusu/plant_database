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
    <title>Le Jardin-Plant Database</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="../images/logo.png" type="image/jpg">
    <style>
        .submenu {
            display: none; /* Hide submenu by default */
        }
        .statistics {
            margin-top: 20px;
            text-align: center;
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 10px;
            border: 2px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px; /* Adjusted max width */
            margin-left: auto;
            margin-right: auto;
        }
        .statistics h2 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }
        .stat-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 15px; /* Increased gap between items */
        }
        .stat-item {
            font-size: 20px; /* Larger font size for text */
            padding: 12px; /* Increased padding for larger boxes */
            background-color: #e0e0e0;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            flex: 1 1 calc(33% - 20px); /* Adjusted flex basis for larger boxes */
            aspect-ratio: 1; /* Keeps the item square */
            display: flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
            min-width: 100px; /* Slightly larger minimum width */
            min-height: 100px; /* Slightly larger minimum height */
            max-width: 120px; /* Increased maximum width */
            max-height: 120px; /* Increased maximum height */
            cursor: pointer; /* Show pointer cursor */
        }
        .stat-item p {
            margin: 0;
            color: #555;
            font-size: 20px; /* Consistent text size */
            text-align: center; /* Center the text */
        }
        .plant-block {
    text-align: center;
    border: 1px solid #ddd;
    padding: 15px;
    background-color: #f9f9f9;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    transition: transform 0.3s ease;
    flex: 1 1 calc(33.33% - 20px); /* Adjust width to fit 3 blocks in a row */
    box-sizing: border-box; /* Ensure padding and border are included in the width */
    min-height: 200px; /* Ensure a minimum height for the block */
}

.plant-block img {
    width: 250px; /* Increase width */
    height: 200px; /* Increase height */
    object-fit: cover; /* Ensure the image covers the area */
    display: block;
    margin: 0 auto 10px;
    border-radius: 4px;
}

.plant-block strong {
    display: block;
    margin-bottom: 5px;
    font-size: 18px; /* Increase font size */
    color: #333;
}
.checkbox-container {
    display: flex;
    flex-wrap: wrap;
    margin: -8px; /* Remove outer spacing */
}

.checkbox-item {
    flex: 0 0 calc(20% - 16px); /* Adjust for spacing */
    margin: 8px; /* Inner margin for space between items */
    display: flex;
    align-items: center;
}

.slider {
    position: relative;
    max-width: 300px;
    margin: auto;
}

.slides {
    display: flex;
    overflow: hidden;
    position: relative;
}

.slide {
    display: none;
    min-width: 100%;
}

.slide.active {
    display: block;
}

.slider button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 5px;
    cursor: pointer;
    font-size: 18px;
}

.slider .prev {
    left: 5px;
}

.slider .next {
    right: 5px;
}

    </style>
</head>

<body class="bg-light">
<header class="sticky-top bg-light py-2">
    <div class="container-fluid">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <!-- Logo and Title -->
            <div class="col-auto d-flex align-items-center mb-3 mb-sm-0">
                <img src="../images/logo.png" alt="Logo" width="50">
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
                            <li class="nav-item"><a class="nav-link" href="../pages/home.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="../pages/shop.php">Shop</a></li>
                            <li class="nav-item"><a class="nav-link" href="../pages/about.php">About Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="../pages/contactus.php">Contact Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="database.php">Database</a></li>
                            <button id="login-icon" onclick="window.location.href='logout.php';" aria-label="Logout" class="btn btn-success ms-3">Logout</button>
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
        <li><a href="database.php"><b>Home</b></a></li>
        <li><a href="sidenav/home.php"><b>Search</b></a></li>
        <li class="has-submenu">
            <a href="#"><b>Plants</b></a>
            <ul class="submenu">
                <li><a href="sidenav/tress.php">Trees</a></li>
                <li><a href="sidenav/shrubs.php">Shrubs</a></li>
                <li><a href="sidenav/ferns.php">Ferns</a></li>
                <li><a href="sidenav/climbers.php">Climbers</a></li>
                <li><a href="sidenav/waterplants.php">Water Plants</a></li>
                <li><a href="sidenav/palms.php">Palms</a></li>
                <li><a href="sidenav/cactus.php">Cactus</a></li>
                <li><a href="sidenav/succulent.php">Succulent</a></li>
                <li><a href="sidenav/annuals.php">Annuals</a></li>
                <li><a href="sidenav/perinnals.php">Perennials</a></li>
                <li><a href="sidenav/indoorplants.php">Indoor Plants</a></li>
                <li><a href="sidenav/herbs.php">Herbs</a></li>
            </ul>
        </li>
        <li><a href="sidenav/cuttings.php"><b>Cuttings</b></a></li>
        <li><a href="plan/plan.php"><b>Plan</b></a></li>
        <li><a href="cost/cost.php"><b>Cost and Analytics</b></a></li>
        <li><a href="sold.php"><b>Sold Units</b></a></li>
        <li><a href="manage_users.php"><b>Users</b></a></li>
        <li><a href="receive_orders.php"><b>Orders</b></a></li>
        <li><a href="message/view_messages.php"><b>View Messages</b></a></li>
    </ul>
</aside>

<!-- Mobile Side Navigation Toggle -->

<div class="collapse" id="mobileSideNav">
    <aside class="side-nav">
        <ul>
            <li><a href="database.php"><b>Home</b></a></li>
            <li><a href="sidenav/home.php"><b>Search</b></a></li>
            <li class="has-submenu">
                <a href="#"><b>Plants</b></a>
                <ul class="submenu">
                    <li><a href="sidenav/tress.php">Trees</a></li>
                    <li><a href="sidenav/shrubs.php">Shrubs</a></li>
                    <li><a href="sidenav/ferns.php">Ferns</a></li>
                    <li><a href="sidenav/climbers.php">Climbers</a></li>
                    <li><a href="sidenav/waterplants.php">Water Plants</a></li>
                    <li><a href="sidenav/palms.php">Palms</a></li>
                    <li><a href="sidenav/cactus.php">Cactus</a></li>
                    <li><a href="sidenav/succulent.php">Succulent</a></li>
                    <li><a href="sidenav/annuals.php">Annuals</a></li>
                    <li><a href="sidenav/perinnals.php">Perennials</a></li>
                    <li><a href="sidenav/indoorplants.php">Indoor Plants</a></li>
                    <li><a href="sidenav/herbs.php">Herbs</a></li>
                </ul>
            </li>
            <li><a href="sidenav/cuttings.php"><b>Cuttings</b></a></li>
            <li><a href="plan/plan.php"><b>Plan</b></a></li>
            <li><a href="cost/cost.php"><b>Cost and Analytics</b></a></li>
            <li><a href="sold.php"><b>Sold Units</b></a></li>
            <li><a href="manage_users.php"><b>Users</b></a></li>
            <li><a href="receive_orders.php"><b>Orders</b></a></li>
            <li><a href="message/view_messages.php"><b>View Messages</b></a></li>
        </ul>
    </aside>
</div>

    <div class="main-content">
        <div class="container">
            
            <div class="header-container">
                <h1 class="mt-4">Plant Database</h1>
                <button id="formToggleButton" onclick="toggleFormVisibility()" style="width: 150px; height: auto; padding: 0; font-size: 30px; text-align: center;  background-color: #28a745; color: white;  border: none; cursor: pointer;" aria-label="Add New Data">+</button>
            </div>

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

        </div>

        <div class="stat-container">
    <div class="stat-item" id="totalPlantTypes">
        <p>Total plant types:<br> <?php include 'statistics.php'; echo $totalScientificNames; ?></p>
    </div>
    <div class="stat-item" id="totalPlantVarieties">
        <p>Total Plant Varieties: <?php include 'statistics.php'; echo $totalPlantVarieties; ?></p>
    </div>
    <div class="stat-item" id="totalQuantity">
        <p>Total Quantity: <?php echo $totalQuantity; ?></p>
    </div>
    <div class="stat-item" id="totalCost">
        <p>Total Cost: <?php include 'statistics.php'; echo number_format($totalCost, 2); ?> </p>
    </div>
    <div class="stat-item" id="totalCuttings">
        <p>Total Cuttings: <?php include 'statistics.php'; echo $totalCuttings; ?></p>
    </div>
    <div class="stat-item" id="totalUnitsSold">
    <p>Total Units Sold:<br> <?php echo $totalSoldUnits; ?></p>
</div>

</div>
<br>
<br>

<!-- Plant list container -->
<div class="plant-list" id="plantListContainer" style="display: none;">
    <h3>Plant Types:</h3>
    <br>
    <div id="plantList" class="row">
        <!-- Plant items with sliders will be inserted here -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('totalPlantTypes').addEventListener('click', function () {
        fetchPlantData();
    });
});

function fetchPlantData() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'plant_data.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var plants = JSON.parse(xhr.responseText);
            var plantList = document.getElementById('plantList');
            plantList.innerHTML = ''; // Clear existing content

            // Group plants by both common and scientific names, merging photos for duplicates
            var plantMap = {};

            plants.forEach(function (plant) {
                var key = plant.plant_name + '_' + plant.scientific_name;
                if (!plantMap[key]) {
                    plantMap[key] = {
                        plant_name: plant.plant_name,
                        scientific_name: plant.scientific_name,
                        photos: new Set(), // Use a Set to ensure unique images
                        plastic_sizes: {}
                    };
                }

                // Add photos to the Set to avoid duplicates
                plant.photo_path.split(',').forEach(photo => plantMap[key].photos.add(photo.trim()));

                if (!plantMap[key].plastic_sizes[plant.plastic_size]) {
                    plantMap[key].plastic_sizes[plant.plastic_size] = 0;
                }
                plantMap[key].plastic_sizes[plant.plastic_size] += parseInt(plant.total_quantity); // Use total_quantity from SQL
            });

            // Render each plant with the merged photos
            Object.keys(plantMap).forEach(function (key) {
                var plant = plantMap[key];
                var plantBlock = document.createElement('div');
                plantBlock.classList.add('plant-block');

                // Convert Set back to Array for easier handling
                var photos = Array.from(plant.photos);

                // Image slider HTML for each plant
                var plantHTML = '<div class="slider" id="slider-' + key + '">';
                plantHTML += '<div class="slides">';

                photos.forEach(function (photo, index) {
                    plantHTML += '<div class="slide' + (index === 0 ? ' active' : '') + '">';
                    plantHTML += '<img src="uploads/' + photo + '" alt="Plant Photo">';
                    plantHTML += '</div>';
                });

                plantHTML += '</div>';
                plantHTML += '<button class="prev" onclick="changeSlide(\'' + key + '\', -1)">&#10094;</button>';
                plantHTML += '<button class="next" onclick="changeSlide(\'' + key + '\', 1)">&#10095;</button>';
                plantHTML += '</div>';

                plantHTML += '<strong>' + plant.plant_name + '</strong> (' + plant.scientific_name + ')<br>';

                // Initialize total quantity
                var totalQuantity = 0;

                // Display total quantity for each plastic size
                for (var size in plant.plastic_sizes) {
                    var quantity = plant.plastic_sizes[size] || 0; // Ensure quantity is defined
                    plantHTML += size.charAt(0).toUpperCase() + size.slice(1) + ': ' + quantity + '<br>';
                    totalQuantity += quantity; // Accumulate total quantity
                }

                // Display the total quantity for all plastic sizes
                plantHTML += 'Total Quantity: ' + totalQuantity + '<br>';

                plantBlock.innerHTML = plantHTML;
                plantList.appendChild(plantBlock);
            });

            document.getElementById('plantListContainer').style.display = 'block';
        } else {
            console.error("Failed to fetch plant data:", xhr.statusText); // Debugging line
        }
    };
    xhr.onerror = function () {
        console.error("Request failed."); // Debugging line
    };
    xhr.send();
}

// Function to handle slide changes
function changeSlide(sliderId, direction) {
    var slider = document.getElementById('slider-' + sliderId);
    var slides = slider.querySelectorAll('.slide');
    var activeSlide = slider.querySelector('.slide.active');
    var newIndex = (Array.from(slides).indexOf(activeSlide) + direction + slides.length) % slides.length;

    activeSlide.classList.remove('active');
    slides[newIndex].classList.add('active');
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
    <script>
    function toggleSubmenu(element) {
        const submenu = element.nextElementSibling;
        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
    }

    document.querySelectorAll('.has-submenu > a').forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            toggleSubmenu(event.target);
        });
    });
</script>

      <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
     
</html>
