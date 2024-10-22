<?php
session_start();

// Store the current page in session if not already logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Store the requested URL
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

include("config.php");

// Check if ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch record based on ID
    $sql_select = "SELECT * FROM plants WHERE id = '$id'";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Display form with pre-filled values for editing
?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Plant Record</title>
            <link rel="stylesheet" href="editstyle.css">
            <link rel="stylesheet" href="styles.css">
            <style>
                /* CSS to style the checkbox container */
                .checkbox-container {
                    display: flex;
                    flex-wrap: wrap; /* Allow wrapping of checkboxes */
                }

                /* Style for individual checkbox items */
                .checkbox-item {
                    margin-right: 20px; /* Adjust margin between checkbox items */
                }
            </style>
        </head>
        <body>
        <header>
            <h1>Le Jardin de Kakoo</h1>
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
            <aside class="side-nav">
                <ul>
                    <li><a href="sidenav/home.php">Home</a></li>
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
                    <br><br>
                </ul>
            </aside>
        </header>
            <div class="container">
                <h1>Edit Plant Record</h1>
                <form method="post" action="update.php" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="redirect_url" value="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; ?>">

                    <div class="form-group">
                        <label for="photo">Update Photo:</label>
                        <input type="file" id="photo" name="photo">
                    </div>
                    <div class="form-group">
                        <label for="plantName">Common Name:</label>
                        <input type="text" id="plantName" name="plantName" value="<?php echo htmlspecialchars($row['plant_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="scientificName">Scientific Name:</label>
                        <input type="text" id="scientificName" name="scientificName" value="<?php echo htmlspecialchars($row['scientific_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="<?php echo $row['quantity']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="plasticSize">Plastic Size:</label>
                        <select id="plasticSize" name="plasticSize" required>
                            <option value="xsmall" <?php if ($row['plastic_size'] === 'xsmall') echo 'selected'; ?>>X-Small</option>
                            <option value="small" <?php if ($row['plastic_size'] === 'small') echo 'selected'; ?>>Small</option>
                            <option value="medium" <?php if ($row['plastic_size'] === 'medium') echo 'selected'; ?>>Medium</option>
                            <option value="large" <?php if ($row['plastic_size'] === 'large') echo 'selected'; ?>>Large</option>
                            <option value="xlarge" <?php if ($row['plastic_size'] === 'xlarge') echo 'selected'; ?>>X-Large</option>
                        </select>
                    </div>
                    <label>Plant Type:</label><br>
                    <div class="form-group checkbox-container">
                        <div class="checkbox-item">
                            <input type="checkbox" id="tree" name="plantType[]" value="tree" <?php if (in_array('tree', explode(', ', $row['plant_type']))) echo 'checked'; ?>>
                            <label for="tree">Tree</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="shrub" name="plantType[]" value="shrub" <?php if (in_array('shrub', explode(', ', $row['plant_type']))) echo 'checked'; ?>>
                            <label for="shrub">Shrub</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="fern" name="plantType[]" value="fern" <?php if (in_array('fern', explode(', ', $row['plant_type']))) echo 'checked'; ?>>
                            <label for="fern">Fern</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="climber" name="plantType[]" value="climber" <?php if (in_array('climber', explode(', ', $row['plant_type']))) echo 'checked'; ?>>
                            <label for="climber">Climber</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="waterPlant" name="plantType[]" value="water_plant" <?php if (in_array('water_plant', explode(', ', $row['plant_type']))) echo 'checked'; ?>>
                            <label for="waterPlant">Water Plant</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="palm" name="plantType[]" value="palm" <?php if (in_array('palm', explode(', ', $row['plant_type']))) echo 'checked'; ?>>
                            <label for="palm">Palm</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="cactus" name="plantType[]" value="cactus" <?php if (in_array('cactus', explode(', ', $row['plant_type']))) echo 'checked'; ?>>
                            <label for="cactus">Cactus</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="succulent" name="plantType[]" value="succulent" <?php if (in_array('succulent', explode(', ', $row['plant_type']))) echo 'checked'; ?>>
                            <label for="succulent">Succulent</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="annual" name="plantType[]" value="annual" <?php if (in_array('annual', explode(', ', $row['plant_type']))) echo 'checked'; ?>>
                            <label for="annual">Annual</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="perennial" name="plantType[]" value="perennial" <?php if (in_array('perennial', explode(', ', $row['plant_type']))) echo 'checked'; ?>>
                            <label for="perennial">Perennial</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="indoor" name="plantType[]" value="indoorplant" <?php if (in_array('indoor', explode(', ', $row['plant_type']))) echo 'checked'; ?>>
                            <label for="indoor">Indoor</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="herb" name="plantType[]" value="herb" <?php if (in_array('herb', explode(', ', $row['plant_type']))) echo 'checked'; ?>>
                            <label for="herb">Herb</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="plantationDate">Plantation Date:</label>
                        <input type="date" id="plantationDate" name="plantationDate" value="<?php echo htmlspecialchars($row['plantation_date']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="value">Value:</label>
                        <input type="number" id="value" name="value" value="<?php echo $row['value']; ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Update Plant">
                    </div>
                </form>
            </div>
        </body>
        </html>
<?php
    } else {
        echo "No record found.";
    }
} else {
    echo "No ID provided.";
}

$conn->close();
?>
