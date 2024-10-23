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
                .checkbox-container {
                    display: flex;
                    flex-wrap: wrap;
                }

                .checkbox-item {
                    margin-right: 20px;
                }

                .image-preview {
                    width: 100px; /* Adjust as needed */
                    height: auto;
                    margin-bottom: 10px;
                }
            </style>
        </head>
        <body>
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
                    <button id="login-icon" onclick="window.location.href='logout.php';" aria-label="Login" class="btn btn-success">Logout</button>
                </div>
            </nav>
        </div>
    </header>
   <aside class="side-nav" id="sideNav">
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
    
            <div class="main-content">
                <h1>Edit Plant Record</h1>
                <form method="post" action="update.php" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="redirect_url" value="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; ?>">

                    <div class="form-group">
        <label>Current Photos:</label><br>
        <?php 
        $existingPhotos = explode(', ', $row['photo_path']); // Assuming photos are stored as comma-separated values
        if (!empty($row['photo_path'])):
            foreach ($existingPhotos as $photo): ?>
                <div>
                    <img src="../database/uploads/<?php echo htmlspecialchars($photo); ?>" alt="Current Photo" class="image-preview">
                    <div>
                        <input type="checkbox" id="deletePhoto_<?php echo htmlspecialchars($photo); ?>" name="deletePhotos[]" value="<?php echo htmlspecialchars($photo); ?>">
                        <label for="deletePhoto_<?php echo htmlspecialchars($photo); ?>">Delete <?php echo htmlspecialchars($photo); ?></label>
                    </div>
                </div>
            <?php endforeach; 
        else: ?>
            <p>No current photos.</p>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="photos">Update Photos:</label>
        <input type="file" id="photos" name="photos[]" multiple>
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
                    <label>Plant Type:</label>
        <div class="form-group checkbox-container">
            <?php
            $plantTypes = ['tree', 'shrub', 'fern', 'climber', 'water_plant', 'palm', 'cactus', 'succulent', 'annual', 'perennial', 'indoor_plant', 'herb'];
            $existingPlantTypes = explode(', ', $row['plant_type']);
            foreach ($plantTypes as $type) {
                $checked = in_array($type, $existingPlantTypes) ? 'checked' : '';
                echo '<div class="checkbox-item">';
                echo '<input type="checkbox" id="' . $type . '" name="plantType[]" value="' . $type . '" ' . $checked . '>';
                echo '<label for="' . $type . '">' . ucfirst(str_replace('_', ' ', $type)) . '</label>';
                echo '</div>';
            }
            ?>
            <label for="is_featured">Mark as Featured:</label>
            <input type="checkbox" name="is_featured" id="is_featured" value="1" <?php if ($row['is_featured'] == 1) echo 'checked'; ?>></div>
                    <div class="form-group">
                        <label for="plantationDate">Plantation Date:</label>
                        <input type="date" id="plantationDate" name="plantationDate" value="<?php echo $row['plantation_date']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="value">Value:</label>
                        <input type="number" id="value" name="value" value="<?php echo $row['value']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Record</button>
                </form>
            </div>
            <script>
                // Sidebar toggle functionality
                const toggleSidebar = () => {
                    const sideNav = document.getElementById('sideNav');
                    sideNav.style.display = sideNav.style.display === 'block' ? 'none' : 'block';
                };

                document.getElementById('toggleButton').addEventListener('click', toggleSidebar);
            </script>
        </body>
        </html>
<?php
    } else {
        echo "No record found.";
    }
} else {
    echo "Invalid request.";
}
$conn->close();
?>
