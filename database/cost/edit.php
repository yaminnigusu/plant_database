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
    <title>Edit Cost</title>
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
                <button id="login-icon" onclick="window.location.href='logout.php';" aria-label="Login" class="btn btn-success">Logout</button>
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
                    <li><a href="sidenav/perinnals.php">Perennials</a></li>
                    <li><a href="sidenav/indoorplants.php">Indoor Plants</a></li>
                    <li><a href="sidenav/herbs.php">Herbs</a></li>
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

    <div class="main-content">
        <h2>Edit Cost</h2>

        <?php
        // Check if cost ID is provided in the URL
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            // Include config file
            include("../config.php");

            // Prepare SQL query to fetch cost details by ID
            $costId = $_GET['id'];
            $sql = "SELECT * FROM costs WHERE id = $costId";
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $costDescription = $row['description'];
                $costAmount = $row['cost_amount'];
                $costCategory = $row['category'];
            } else {
                echo '<p class="text-danger">Cost not found.</p>';
                exit;
            }

            // Close result set
            $result->close();

            // Process update on form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $newCostDescription = $_POST['costDescription'];
                $newCostAmount = $_POST['costAmount'];
                $newCostCategory = $_POST['costCategory'];

                // Update cost in database
                $updateSql = "UPDATE costs SET description = '$newCostDescription', cost_amount = $newCostAmount, category = '$newCostCategory' WHERE id = $costId";

                if ($conn->query($updateSql) === TRUE) {
                    echo '<p class="text-success">Cost updated successfully.</p>';
                } else {
                    echo '<p class="text-danger">Error updating cost: ' . $conn->error . '</p>';
                }
            }

            // Close database connection
            $conn->close();
        } else {
            echo '<p class="text-danger">Invalid request.</p>';
            exit;
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $costId; ?>" method="POST">
            <div class="form-group">
                <label for="costDescription">Cost Description:</label>
                <input type="text" class="form-control" id="costDescription" name="costDescription" value="<?php echo htmlspecialchars($costDescription); ?>" required>
            </div>
            <div class="form-group">
                <label for="costAmount">Cost Amount:</label>
                <input type="number" class="form-control" id="costAmount" name="costAmount" value="<?php echo $costAmount; ?>" required>
            </div>
            <div class="form-group">
                <label for="costCategory">Cost Category:</label>
                <select class="form-control" id="costCategory" name="costCategory" required>
                    <option value="asset" <?php echo $costCategory == 'asset' ? 'selected' : ''; ?>>Asset</option>
                    <option value="capital" <?php echo $costCategory == 'capital' ? 'selected' : ''; ?>>Capital</option>
                    <option value="expense" <?php echo $costCategory == 'expense' ? 'selected' : ''; ?>>Expense</option>
                    <option value="liability" <?php echo $costCategory == 'liability' ? 'selected' : ''; ?>>Liability</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="../pages/database.php" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>

</html>
