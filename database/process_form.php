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
    $scientificName = isset($_POST['scientificName']) ? $_POST['scientificName'] : null;
    $optionalData = isset($_POST['optionalData']) ? 1 : 0;
    
    // Check if plantType is set and convert to string
    $plantTypeStr = isset($_POST['plantType']) ? implode(', ', $_POST['plantType']) : '';

    // Determine if the plant is featured
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;

    // Handle optional data separately
    if ($optionalData) {
        // Insert into the optional_plants table
        $sql_insert = "INSERT INTO optional_plants (plant_name, quantity, plastic_size, plant_type, plantation_date, value)
                       VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("sisssi", $plantName, $quantity, $plasticSize, $plantTypeStr, $plantationDate, $value);
    } else {
        // Retrieve uploaded file information for multiple images
        $uploadedFilePaths = []; // Array to hold uploaded file paths
        $targetDir = __DIR__ . '/uploads/';
        
        // Check if the uploads directory exists and is writable
        if (!file_exists($targetDir) || !is_writable($targetDir)) {
            die("Error: The uploads directory is missing or not writable.");
        }

        // Loop through each uploaded file
        foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
            $photoPath = basename($_FILES['photos']['name'][$key]); // Get only the filename
            $targetFile = $targetDir . $photoPath;

            // Move uploaded file to target directory
            if (move_uploaded_file($tmpName, $targetFile)) {
                // Append to the array of uploaded file paths
                $uploadedFilePaths[] = $photoPath;
            } else {
                // Handle file upload error
                die("Sorry, there was an error uploading file: " . $_FILES['photos']['name'][$key]);
            }
        }

        // Convert the array of file paths into a comma-separated string
        $photoPathsString = implode(',', $uploadedFilePaths);

        // Calculate selling price with a 40% profit margin
        $costPerPlant = $value; // Use 'value' as the cost per plant
        $profitMargin = 0.4; // 40% profit margin
        $sellingPrice = $costPerPlant * (1 + $profitMargin);

        // Insert data into the database with selling price and photo paths
        $sql_insert = "INSERT INTO plants (plant_name, scientific_name, quantity, plastic_size, plantation_date, cost_per_plant, selling_price, plant_type, photo_path, is_featured)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("ssissssssi", $plantName, $scientificName, $quantity, $plasticSize, $plantationDate, $costPerPlant, $sellingPrice, $plantTypeStr, $photoPathsString, $isFeatured);
    }

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

    // Close statement
    $stmt->close();
} else {
    // Invalid request method
    echo "Invalid request method.";
}

// Close database connection
$conn->close();
?>
