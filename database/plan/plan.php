<?php
// Establish database connection
include("../config.php");
?>
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
                    <li><a href="../sidenav/annuals.php">Annuals</a></li>
                    <li><a href="../sidenav/perinnals.php">Perennials</a></li>
                    <li><a href="../sidenav/indoorplants.php">Indoor Plants</a></li>
                    <li><a href="../sidenav/herbs.php">Herbs</a></li>
                </ul>
            </li>
            <li><a href="../sidenav/cuttings.php"><b>Cuttings</b></a></li>
            <li><a href="../plan/plan.php"><b>Plan</b></a></li>
            <li><a href="cost.php"><b>Cost and Analytics</b></a></li>
            <li><a href="../sold.php"><b>sold units</b></a></li>
            <li><a href="../manage_users.php"><b>users</b></a></li>
            <li><a href="../message/view_messages.php"><b>view messages</b></a></li>
        </ul>
    </aside>
    <div class="main-content">
    <h1>Plant Nursery Planning</h1>

<!-- Button to toggle form -->
<button id="toggleFormBtn" class="btn btn-primary mb-4">Add New Plan</button>

<!-- Form to add new plan (initially hidden) -->
<form id="plantForm" action="process.php" method="POST" style="display: none;">
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" class="form-control" rows="4"></textarea>
    </div>
    <div class="form-group">
        <label for="completionDate">Completion Date:</label>
        <input type="date" id="completionDate" name="completionDate" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Submit</button>
</form>
<div class="container">
    <h2>Ongoing Plans</h2>
    <div id="ongoingPlansList" class="row mb-4">
        <!-- Ongoing plans will be dynamically added here -->
        <?php
        $ongoingPlans = $conn->query("SELECT * FROM ongoing_plans");
        while ($plan = $ongoingPlans->fetch_assoc()) {
            echo "<div class='col-md-4'>
                    <div class='card mb-3' data-id='{$plan['id']}'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$plan['title']}</h5>
                            <p class='card-text'><strong>Subject:</strong> {$plan['subject']}</p>
                            <p class='card-text'><strong>Description:</strong> {$plan['description']}</p>
                            <p class='card-text'><strong>Completion Date:</strong> {$plan['completion_date']}</p>
                            <div class='form-check'>
                                <input type='checkbox' class='form-check-input complete-checkbox'>
                                <label class='form-check-label'>Completed</label>
                            </div>
                            <button class='btn btn-primary btn-edit'>Edit</button>
                            <button class='btn btn-danger btn-delete'>Delete</button>
                        </div>
                    </div>
                  </div>";
        }
        ?>
    </div>

    <h2>Completed Plans</h2>
    <div id="completedPlansList" class="row">
        <!-- Completed plans will be dynamically added here -->
        <?php
        $completedPlans = $conn->query("SELECT * FROM completed_plans");
        while ($plan = $completedPlans->fetch_assoc()) {
            echo "<div class='col-md-4'>
                    <div class='card mb-3' data-id='{$plan['id']}'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$plan['title']}</h5>
                            <p class='card-text'><strong>Subject:</strong> {$plan['subject']}</p>
                            <p class='card-text'><strong>Description:</strong> {$plan['description']}</p>
                            <p class='card-text'><strong>Completion Date:</strong> {$plan['completion_date']}</p>
                            <button class='btn btn-primary btn-edit'>Edit</button>
                            <button class='btn btn-danger btn-delete'>Delete</button>
                        
                            </div>
                    </div>
                  </div>";
        }
        ?>
    </div>
</div>
</div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="script2.js"></script>
</body>

</html>
