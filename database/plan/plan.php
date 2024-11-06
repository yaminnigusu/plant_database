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
    <link rel="stylesheet" href="../styles.css">
    <link rel="icon" href="../../images/logo.png" type="image/jpg">
   
    <style>
        .submenu {
            display: none;
        }
    </style>
</head>

<body class="w3-light-gray">
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
        <li><a href="../cost/cost.php"><b>Cost and Analytics</b></a></li>
        <li><a href="../sold.php"><b>Sold Units</b></a></li>
        <li><a href="../manage_users.php"><b>Users</b></a></li>
        <li><a href="../receive_orders.php"><b>Orders</b></a></li>
        <li><a href="view_messages.php"><b>View Messages</b></a></li>
    </ul>
</aside>

<!-- Mobile Side Navigation Toggle -->

<div class="collapse" id="mobileSideNav">
    <aside class="side-nav">
        <ul>
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
            <li><a href="../sold.php"><b>Sold Units</b></a></li>
            <li><a href="../manage_users.php"><b>Users</b></a></li>
            <li><a href="../receive_orders.php"><b>Orders</b></a></li>
            <li><a href="../message/view_messages.php"><b>View Messages</b></a></li>
        </ul>
    </aside>
</div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="script2.js"></script>
</body>

</html>
