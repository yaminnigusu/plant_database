    <?php
    include("config.php");

    // Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $plantName = $_POST['plantName'];
        $quantity = $_POST['quantity'];
        $plantationDate = $_POST['plantationDate'];
        $value = $_POST['value'];
        $plasticSize = $_POST['plasticSize'];
        $scientificName = $_POST['scientificName'];
        
        // Check if plantType is set and convert to string
        $plantTypeStr = isset($_POST['plantType']) ? implode(', ', $_POST['plantType']) : '';

        // Retrieve uploaded file information
        $photoPath = basename($_FILES["photo"]["name"]); // Use only the filename for database storage
        $targetDir = __DIR__ . '/uploads/';
        $targetFile = $targetDir . $photoPath;

        // Check if the uploads directory exists and is writable
        if (!file_exists($targetDir) || !is_writable($targetDir)) {
            die("Error: The uploads directory is missing or not writable.");
        }

        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            // Insert data into database
            $sql_insert = "INSERT INTO plants (plant_name, scientific_name, quantity, plastic_size, plantation_date, value, plant_type, photo_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            
            // Bind parameters
            $stmt->bind_param("ssisssss", $plantName, $scientificName, $quantity, $plasticSize, $plantationDate, $value, $plantTypeStr, $photoPath);
            
            // Calculate selling price with a 40% profit margin
$costPerPlant = $value; // Use 'value' as the cost per plant
$profitMargin = 0.4; // 40% profit margin
$sellingPrice = $costPerPlant * (1 + $profitMargin);

// Insert data into database with selling price
$sql_insert = "INSERT INTO plants (plant_name, scientific_name, quantity, plastic_size, plantation_date, cost_per_plant, selling_price, plant_type, photo_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql_insert);
$stmt->bind_param("ssissssss", $plantName, $scientificName, $quantity, $plasticSize, $plantationDate, $costPerPlant, $sellingPrice, $plantTypeStr, $photoPath);

// Execute SQL statement


            
            // Execute SQL statement
            if ($stmt->execute()) {
                // Success message
                echo '<!DOCTYPE html>';
                echo '<html lang="en">';
                echo '<head> ... </head>';
                echo '<body>';
                echo '<div class="modal">';
                echo '<h1>New record created successfully</h1>';
                echo '<p>Redirecting to database...</p>';
                echo '</div>';
                echo '<script>';
                echo 'setTimeout(function() {';
                echo '  window.location.href = "database.php";';
                echo '}, 1000);'; // Redirect after 1 second
                echo '</script>';
                echo '</body>';
                echo '</html>';
            } else {
                // Error message
                echo "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        } else {
            // File upload error
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        // Invalid request method
        echo "Invalid request method.";
    }

    // Close database connection
    $conn->close();
    ?>
