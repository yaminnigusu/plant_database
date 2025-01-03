<?php
session_start();

// Store the current page in session if not already logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Store the requested URL
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>
<?php
include("config.php");

// Function to fetch available plants
function fetchAvailablePlants($conn) {
    $sql = "SELECT id, plant_name, quantity, plastic_size, plant_type FROM plants WHERE quantity > 0";
    $result = $conn->query($sql);

    $plants = [];
    while ($row = $result->fetch_assoc()) {
        $plants[] = $row;
    }
    return $plants;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plant_id = $_POST['plant_id'];
    $quantity_sold = $_POST['quantity'];
    $sale_date = $_POST['sale_date'];
    $selling_price = $_POST['selling_price'];

    // Fetch the plant details
    $sql = "SELECT * FROM plants WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $plant_id);
    $stmt->execute();
    $plant = $stmt->get_result()->fetch_assoc();

    if ($plant && $plant['quantity'] >= $quantity_sold) {
        // Insert into the sold table
        $sql = "INSERT INTO sold (plant_id, plant_name, quantity_sold, sale_date, cost_per_plant, selling_price) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "isissd",
            $plant_id,
            $plant['plant_name'],
            $quantity_sold,
            $sale_date,
            $plant['cost_per_plant'],
            $selling_price
        );
        $stmt->execute();

        // Update quantity in plants table
        $new_quantity = $plant['quantity'] - $quantity_sold;
        $sql = "UPDATE plants SET quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $new_quantity, $plant_id);
        $stmt->execute();

        if ($new_quantity == 0) {
            // Optionally delete the plant if quantity is zero
            $sql = "DELETE FROM plants WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $plant_id);
            $stmt->execute();
        }

        echo "Sale registered successfully.";
    } else {
        echo "Insufficient quantity or invalid plant.";
    }
}

// Fetch available plants for the form
$plants = fetchAvailablePlants($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Sale</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="../images/logo.png" type="image/jpg"> 
    <style>
        /* Existing styles */
        .submenu {
            display: none;
        }
        .suggestions {
            border: 1px solid #ccc;
            max-height: 200px;
            overflow-y: auto;
            position: absolute;
            z-index: 1000;
            background-color: #fff;
        }
        .suggestion-item {
            padding: 8px;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background-color: #f0f0f0;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        label {
            display: block;
            margin: 8px 0 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        #available_quantity {
            margin-top: 10px;
            color: #555;
        }
    </style>
    <script>
        function fetchSuggestions(query) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "search_plants.php?query=" + encodeURIComponent(query), true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var results = JSON.parse(xhr.responseText);
                    var suggestions = document.getElementById('suggestions');
                    suggestions.innerHTML = '';
                    results.forEach(function (plant) {
                        var div = document.createElement('div');
                        div.classList.add('suggestion-item');
                        div.textContent = plant.plant_name + " - " + plant.plastic_size + " - " + plant.plant_type;
                        div.dataset.id = plant.id;
                        div.dataset.quantity = plant.quantity;
                        div.onclick = function () {
                            document.getElementById('plant_id').value = this.dataset.id;
                            document.getElementById('searchPlant').value = this.textContent;
                            document.getElementById('available_quantity').textContent = "Available Quantity: " + this.dataset.quantity;
                            suggestions.innerHTML = '';
                        };
                        suggestions.appendChild(div);
                    });
                }
            };
            xhr.send();
        }

        function onInput() {
            var input = document.getElementById('searchPlant');
            var query = input.value;
            if (query.length > 0) {
                fetchSuggestions(query);
            } else {
                document.getElementById('suggestions').innerHTML = '';
                document.getElementById('available_quantity').textContent = '';
            }
        }
    </script>
</head>
<body>
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

<div class="container">
    <h1 class="mt-4">Register Plant Sale</h1>
    <form method="post" action="">
        <div class="form-group">
            <label for="searchPlant">Search Plant:</label>
            <input type="text" id="searchPlant" onkeyup="onInput()" placeholder="Search for plants..." autocomplete="off">
            <div id="suggestions" class="suggestions"></div>
            <div id="available_quantity" style="margin-top: 10px; color: #555;"></div>
        </div>
        <div class="form-group">
            <input type="hidden" id="plant_id" name="plant_id">
            <label for="quantity">Quantity Sold:</label>
            <input type="number" id="quantity" name="quantity" required>
        </div>
        <div class="form-group">
            <label for="sale_date">Sale Date:</label>
            <input type="date" id="sale_date" name="sale_date" required>
        </div>
        <div class="form-group">
            <label for="selling_price">Selling Price:</label>
            <input type="number" id="selling_price" name="selling_price" required>
        </div>
        <button type="submit">Register Sale</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
