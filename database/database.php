<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Jardin-Plant Database</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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
        .plant-list {
            display: none;
            margin-top: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .plant-list ul {
            list-style-type: none;
            padding: 0;
        }
        .plant-list ul li {
            padding: 5px 0;
        }
    </style>
</head>

<body class="w3-light-gray">
    <header class="sticky-top">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col"></div>
                <div class="col-left">
                    <img src="../images/logo.png" alt="Logo" width="50">
                </div>
                <h1>Le Jardin de Kakoo</h1>
            </div>
            <nav>
                <a href="../pages/home.php">Home</a>
                <a href="../pages/shop.php">Shop</a>
                <a href="../pages/about.php">About Us</a>
                <a href="../pages/contactus.php">Contact Us</a>
                <a href="database.php">Database</a>
                <div class="col-auto">
                    <button id="login-icon" onclick="toggleLoginForm()" aria-label="Login" class="btn btn-success">Login</button>
                </div>
            </nav>
        </div>
    </header>

    <aside class="side-nav" id="sideNav">
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
            <li><a href="sold.php"><b>sold units</b></a></li>
        </ul>
    </aside>

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
                    <label for="photo">Add Photo:</label>
                    <input type="file" id="photo" name="photo" accept="image/*" class="form-control">
                </div>
                <div class="form-group" id="plantNameGroup">
                    <label for="plantName">Common Name:</label>
                    <input type="text" id="plantName" name="plantName" class="form-control">
                </div>
                <div class="form-group" id="scientificNameGroup">
                    <label for="scientificName">Scientific Name:</label>
                    <input type="text" id="scientificName" name="scientificName" class="form-control">
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control">
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
                        <input type="checkbox" id="indoorPlant" name="plantType[]" value="indoor_plant">
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
                <p>Total Units Sold:<br> </p>
            </div>
        </div>

        <!-- Plant lists -->
        <div class="plant-list" id="plantTypesList">
            <h3>Plant Types:</h3>
            <ul>
                <!-- Plant types will be inserted here -->
            </ul>
        </div>
        <div class="plant-list" id="plantVarietiesList">
            <h3>Plant Varieties:</h3>
            <ul>
                <!-- Plant varieties will be inserted here -->
            </ul>
        </div>
    </div>

    <script>
        function toggleFormVisibility() {
            var form = document.getElementById('plantForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        document.querySelectorAll('.has-submenu > a').forEach(function (menuLink) {
            menuLink.addEventListener('click', function (e) {
                e.preventDefault();
                var submenu = menuLink.nextElementSibling;
                submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            });
        });

        function toggleOptionalFields() {
            var optionalChecked = document.getElementById('optionalData').checked;
            document.getElementById('photoGroup').style.display = optionalChecked ? 'block' : 'none';
            document.getElementById('plantNameGroup').style.display = optionalChecked ? 'block' : 'none';
            document.getElementById('scientificNameGroup').style.display = optionalChecked ? 'block' : 'none';
        }

        function showPlantList(type) {
            var plantList = document.getElementById(type + 'List');
            var otherLists = document.querySelectorAll('.plant-list');
            otherLists.forEach(function (list) {
                if (list !== plantList) {
                    list.style.display = 'none';
                }
            });
            plantList.style.display = 'block';
        }

        document.getElementById('totalPlantTypes').addEventListener('click', function () {
            showPlantList('plantTypes');
        });

        document.getElementById('totalPlantVarieties').addEventListener('click', function () {
            showPlantList('plantVarieties');
        });

        // Populate the plant lists dynamically (example):
        document.addEventListener('DOMContentLoaded', function () {
            var plantTypes = ['Tree', 'Shrub', 'Fern', 'Climber', 'Water Plant', 'Palm', 'Cactus', 'Succulent', 'Annual', 'Perennial', 'Indoor Plant', 'Herb'];
            var plantVarieties = ['Rose', 'Tulip', 'Fern', 'Climbing Rose', 'Water Lily', 'Date Palm', 'Saguaro', 'Aloe Vera', 'Sunflower', 'Lavender', 'Pothos', 'Basil'];

            var plantTypesList = document.querySelector('#plantTypesList ul');
            plantTypes.forEach(function (type) {
                var li = document.createElement('li');
                li.textContent = type;
                plantTypesList.appendChild(li);
            });

            var plantVarietiesList = document.querySelector('#plantVarietiesList ul');
            plantVarieties.forEach(function (variety) {
                var li = document.createElement('li');
                li.textContent = variety;
                plantVarietiesList.appendChild(li);
            });
        });
    </script>
</body>

</html>
