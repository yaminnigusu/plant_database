<?php
// Establish database connection
include("../config.php");

// Get form data
$title = $_POST['title'];
$completed = $_POST['completed'];

// Prepare SQL statement to update completion status
$sql = "UPDATE  SET completed = '$completed' WHERE plant_name = '$title'";

// Execute SQL statement
if ($conn->query($sql) === TRUE) {
    echo "Completion status updated successfully";
} else {
    echo "Error updating completion status: " . $conn->error;
}

// Close database connection
$conn->close();

