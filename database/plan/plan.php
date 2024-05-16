<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Database</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="icon" href="../../images/logo.png" type="image/jpg">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="style.css">
    <style>
       

        .submenu {
            display: none;
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
                    <button id="navToggleButton" onclick="toggleNavVisibility()" class="btn btn-dark d-block d-lg-none">Menu</button>
                </div>
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
            <br>
            <br>
            
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
                    <li><a href="../sidenav\annuals.php">Annuals</a></li>
                    <li><a href="sidenav/perinnals.php">Perennials</a></li>
                    <li><a href="sidenav/indoorplants.php">Indoor Plants</a></li>
                    <li><a href="sidenav/herbs.php">Herbs</a></li>
                </ul>
            </li>
           <li> <a href="plan.php"><b>Plan</b></a></li>
           <li> <a href="../cost/cost.php"><b>Cost and Analytics</b></a></li>
        </ul>
       
    </aside>

    <div class="main-content">
    <h1>Plant Nursery Planning</h1>

    <!-- Button to toggle form -->
    <button id="toggleFormBtn" class="btn btn-primary mb-4"">Add New Plan</button>
    

    <!-- Form to add new plan (initially hidden) -->
    <form id="plantForm" action="process.php" method="POST" style="display: none;">
        <label for="plantName">Plant Name:</label>
        <input type="text" id="plantName" name="plantName" required><br><br>

        <label for="plantingTime">Planting Time:</label>
        <input type="text" id="plantingTime" name="plantingTime" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <label for="additionalInfo">Additional Information:</label><br>
        <textarea id="additionalInfo" name="additionalInfo" rows="4"></textarea><br><br>

        <button type="submit" class="btn btn-success">Submit</button>
    </form>

    <!-- Display added plans -->
    <div id="plansList">
        <!-- Plans will be dynamically added here -->
    </div>

    <!-- Checkout button for completed plans -->
    <button id="checkoutBtn" class="btn btn-danger mt-3" style="display: none;">Checkout Completed Plans</button>
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
    <script src="script.js"></script>
</body>

</html>
