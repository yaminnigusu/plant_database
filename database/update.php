<?php
include("config.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $plantName = $_POST['plantName'];
    $scientificName = $_POST['scientificName'];
    $quantity = $_POST['quantity'];
    $plasticSize = $_POST['plasticSize'];
    $plantationDate = $_POST['plantationDate'];
    $value = $_POST['value'];
    
    // Handle plantType as an array
    if (isset($_POST['plantType']) && is_array($_POST['plantType'])) {
        $plantType = implode(', ', $_POST['plantType']);
    } else {
        $plantType = ''; // Default value if no plant types are selected
    }

    // Update the plant record in the database
    $sql_update = "UPDATE plants SET plant_name = ?, scientific_name = ?, quantity = ?, plastic_size = ?, plantation_date = ?, value = ?, plant_type = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssissssi", $plantName, $scientificName, $quantity, $plasticSize, $plantationDate, $value, $plantType, $id);
    
    if ($stmt->execute()) {
        // Check if a new photo is uploaded
        if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = "uploads/";
            $uploadFile = $uploadDir . basename($_FILES['photo']['name']);

            // Move uploaded file to the upload directory
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                // Update the photo_path field in the database
                $photoPath = $_FILES['photo']['name'];
                $sql_update_photo = "UPDATE plants SET photo_path = ? WHERE id = ?";
                $stmt_photo = $conn->prepare($sql_update_photo);
                $stmt_photo->bind_param("si", $photoPath, $id);
                $stmt_photo->execute();
                $stmt_photo->close();
            } else {
                echo "Failed to upload photo.";
            }
        }

        // Redirect to database.php after successful update
        header("Location: database.php");
        exit;
    } else {
        echo "Error updating plant record: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
