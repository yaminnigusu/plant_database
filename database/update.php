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

    // Prepare the SQL update query
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
        // Redirect to the page from which the user came or to a default page
        $redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : 'database.php';
        header("Location: $redirect_url");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Handle file upload if a new photo is uploaded
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../database/uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is a valid image
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            // Attempt to upload the file
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                // Update photo path in the database
                $sql_photo_update = "UPDATE plants SET photo_path = '$target_file' WHERE id = '$id'";
                $conn->query($sql_photo_update);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    }
}

$conn->close();
?>
