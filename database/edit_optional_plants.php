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

// Check if ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch record based on ID
    $sql_select = "SELECT * FROM optional_plants WHERE id = '$id'";
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
            <title>Edit Cutting Record</title>
            <link rel="stylesheet" href="editstyle.css" >
            <link rel="stylesheet" href="styles.css">
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
                <a href="cuttings.php">Cuttings Data</a>
                <div class="col-auto">
                <button id="login-icon" onclick="window.location.href='logout.php';" aria-label="Login" class="btn btn-success">Logout</button>
                </div>
            </nav>
        </header>
        <div class="container">
            <h1>Edit Cutting Record</h1>
            <form method="post" action="update_optional_plants.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <div class="form-group">
                    <label for="plantName">Common Name:</label>
                    <input type="text" id="plantName" name="plantName" value="<?php echo htmlspecialchars($row['plant_name']); ?>" required>
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
                <div class="form-group">
                    <label for="plantationDate">Plantation Date:</label>
                    <input type="date" id="plantationDate" name="plantationDate" value="<?php echo $row['plantation_date']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="value">Value:</label>
                    <input type="number" id="value" name="value" value="<?php echo $row['value']; ?>" required>
                </div>
                
                <button type="submit">Update</button>
            </form>
        </div>
        </body>
        </html>
<?php
    } else {
        echo "Record not found";
    }

    // Close result set
    $result->close();
}

// Close database connection
$conn->close();
?>
