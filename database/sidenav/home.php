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
        .main-content-plant-search {
    margin-left: 280px; /* Adjusted margin to account for side nav width */
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.main-content-plant-search form {
    padding: 20px;
}

.main-content-plant-search label {
    display: block;
    margin-bottom: 10px;
}

.main-content-plant-search input[type="text"],
.main-content-plant-search select {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
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

.main-content-plant-search button {
    padding: 12px 20px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.main-content-plant-search button:hover {
    background-color: #45a049;
}

@media (max-width: 768px) {
    .main-content-plant-search {
        margin-left: 0; /* No margin on smaller screens */
        width: 100%; /* Full width on smaller screens */
        margin-top: 20px; /* Add space between form and side nav on smaller screens */
    }
}
.btn.btn-primary {
            color: #fff;
            background-color: #28a745; /* Green background color */
            border-color: #28a745;     /* Green border color */
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            margin-right: 10px; /* Add margin between buttons */
        }

        /* Hover effect for the green buttons */
        .btn.btn-primary:hover {
            background-color: #218838; /* Darker green on hover */
            border-color: #1e7e34;     /* Darker border on hover */
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
    </ul>
</aside>



    </html>
    <div class="main-content-plant-search">
    
    <h1>Advanced Plant Search</h1>
    <form action="search_results.php" method="get">
        <label for="plantName">Plant Name:</label>
        <input type="text" id="plantName" name="plantName">
        <br><br>
        
        <label for="scientificName">Scientific Name:</label>
        <input type="text" id="scientificName" name="scientificName">
        <br><br>
        
    <!-- Other existing form inputs (e.g., plantName, scientificName, etc.) -->

    <label for="plantType">Plant Type:</label>
    <select name="plantType[]" id="plantType" >
    <option value=""  selected>-- Select Plant Type --</option>
       <option value="tree">Tree</option>
        <option value="shrub">Shrub</option>
        <option value="fern">Fern</option>
        <option value="climber">Climber</option>
        <option value="waterplant">Water Plant</option>
        <option value="palm">Palm</option>
        <option value="cactus">Cactus</option>
        <option value="succulent">Succulent</option>
        <option value="annual">Annual</option>
        <option value="perennial">Perennial</option>
        <option value="indoor">Indoor Plant</option>
        <option value="herb">Herb</option>
    </select>

    


        
        <label for="plantType">Plant Type:</label>
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
        <input type="checkbox" id="waterPlant" name="plantType[]" value="water plant">
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
        <input type="checkbox" id="indoorPlant" name="plantType[]" value="indoor plant">
        <label for="indoorPlant">Indoor Plant</label>
    </div>
    <div class="checkbox-item">
        <input type="checkbox" id="herb" name="plantType[]" value="herb">
        <label for="herb">Herb</label>
    </div>
</div>

        <br><br>
        
        <label for="plasticSize">Plastic Size:</label>
        <select id="plasticSize" name="plasticSize">
            <option value="">-- Select Plastic Size --</option>
            <option value="xsmall">X-Small</option>
            <option value="small">Small</option>
            <option value="medium">Medium</option>
            <option value="large">Large</option>
            <option value="xlarge">X-Large</option>
        </select>
        <br><br>
        
        <label for="plantationMonth">Plantation Month:</label>
            <select id="plantationMonth" name="plantationMonth">
                <option value="">Select Month</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>

            <label for="plantationYear">Plantation Year:</label>
            <select id="plantationYear" name="plantationYear">
                <option value="">Select Year</option>
                <?php
                // Generate year options (from 2000 to current year + 5 years)
                $currentYear = date("Y");
                for ($year = 2000; $year <= $currentYear + 5; $year++) {
                    echo '<option value="' . $year . '">' . $year . '</option>';
                }
                ?>
            </select>
            



        <br>

        <button type="submit">Search</button>
    </form>
    <div>
    <a href="search_results.php?report=3months&format=pdf" class="btn btn-primary">Generate 3-Month Report (PDF)</a>
    <a href="search_results.php?report=6months&format=pdf" class="btn btn-primary">Generate 6-Month Report (PDF)</a>
    <form action="search_results.php" method="get" style="display: inline-block;">
        <input type="hidden" name="format" value="pdf">
        <select name="year" id="year" class="form-control" style="display: inline-block; width: auto;">
            <?php
            // Generate options for the select dropdown based on available years in the database
            $currentYear = date('Y');
            for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
                echo '<option value="' . $year . '">' . $year . '</option>';
            }
            ?>
        </select>
        <button type="submit" name="report" value="year" class="btn btn-primary">Generate Yearly Report (PDF)</button>
    </form>
</div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.has-submenu > a').click(function(e) {
                e.preventDefault(); // Prevent default link behavior

                // Toggle the submenu visibility
                $(this).siblings('.submenu').slideToggle();
            });
        });
    </script>
</body>
</html>