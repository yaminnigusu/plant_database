<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

include("config.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $id = $_POST['id'];
    $plantName = htmlspecialchars($_POST['plantName']);
    $scientificName = htmlspecialchars($_POST['scientificName']);
    $quantity = $_POST['quantity'];
    $plasticSize = $_POST['plasticSize'];
    $plantType = isset($_POST['plantType']) ? implode(', ', $_POST['plantType']) : '';
    $plantationDate = $_POST['plantationDate'];
    $value = $_POST['value'];

    // Prepare the SQL update query for the non-file data
    $sql_update = "UPDATE plants SET 
        plant_name = '$plantName', 
        scientific_name = '$scientificName', 
        quantity = '$quantity', 
        plastic_size = '$plasticSize', 
        plant_type = '$plantType', 
        plantation_date = '$plantationDate', 
        value = '$value' 
        WHERE id = '$id'";

    // Execute the query
    if ($conn->query($sql_update) === TRUE) {
        // Handle multiple file uploads
        $target_dir = "../database/uploads/";
        $photoNames = []; // Array to store photo names

        // Process each uploaded file
        if (isset($_FILES['photos'])) {
            foreach ($_FILES['photos']['name'] as $key => $name) {
                if ($_FILES['photos']['error'][$key] == UPLOAD_ERR_OK) {
                    $target_file = basename($name);
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    // Check if the file is a valid image
                    $check = getimagesize($_FILES['photos']['tmp_name'][$key]);
                    if ($check !== false) {
                        // Attempt to upload the file
                        if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $target_dir . $target_file)) {
                            $photoNames[] = $target_file; // Add only the photo name to the array
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                        }
                    } else {
                        echo "File is not an image.";
                    }
                }
            }
        }

        // Retrieve existing photo paths
        $sql_select = "SELECT photo_path FROM plants WHERE id = '$id'";
        $result = $conn->query($sql_select);
        $existingPhotos = [];
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $existingPhotos = explode(', ', $row['photo_path']);
        }

        // Handle deletion of photos if checkboxes are checked
        if (isset($_POST['deletePhotos'])) {
            foreach ($_POST['deletePhotos'] as $photo) {
                // Delete photo from the server
                $currentPhotoPath = $target_dir . $photo;
                if (file_exists($currentPhotoPath)) {
                    unlink($currentPhotoPath); // Delete the file
                }

                // Remove the photo from the existing photos array
                $existingPhotos = array_diff($existingPhotos, [$photo]);
            }
        }

        // Combine existing photos with newly uploaded photos
        $allPhotos = array_merge($existingPhotos, $photoNames);
        $allPhotosString = implode(', ', $allPhotos); // Prepare the string for the database

        // Update photo paths in the database (only names)
        $sql_photo_update = "UPDATE plants SET photo_path = '$allPhotosString' WHERE id = '$id'";
        $conn->query($sql_photo_update);

        // Redirect to the page from which the user came or to a default page
        $redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : 'database.php';
        header("Location: $redirect_url");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
